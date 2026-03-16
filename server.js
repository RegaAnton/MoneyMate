const express = require("express");
const fs = require("fs").promises;
const path = require("path");
const bcrypt = require("bcryptjs");

const app = express();

app.use(express.json());
app.use(express.static(path.join(__dirname, "public")));

const USERS_FILE = path.join(__dirname, "users.json");
const CATEGORIES_FILE = path.join(__dirname, "categories.json");
const EXPENSES_FILE = path.join(__dirname, "expenses.json");

const readJson = async (file) => {
  try {
    const data = await fs.readFile(file, "utf8");
    return JSON.parse(data);
  } catch (error) {
    if (error.code === "ENOENT") return [];
    console.error(`Gagal membaca ${file}:`, error);
    return [];
  }
};

const writeJson = async (file, data) => {
  try {
    await fs.writeFile(file, JSON.stringify(data, null, 2), "utf8");
  } catch (error) {
    console.error(`Gagal menulis ke ${file}:`, error);
  }
};

const initDB = async () => {
  const files = [USERS_FILE, CATEGORIES_FILE, EXPENSES_FILE];
  for (const file of files) {
    try {
      await fs.access(file);
    } catch {
      await writeJson(file, []);
    }
  }
};
initDB();

// ==========================================
// ROUTES API AUTHENTICATION
// ==========================================
app.post("/api/login", async (req, res) => {
  try {
    const { username, password } = req.body;
    const users = await readJson(USERS_FILE);
    const userIndex = users.findIndex((u) => u.username === username);

    if (userIndex === -1) {
      return res
        .status(401)
        .json({ success: false, message: "Username atau password salah" });
    }

    const user = users[userIndex];
    let isMatch = false;
    const isHashed =
      user.password &&
      user.password.startsWith("$2") &&
      user.password.length === 60;

    if (isHashed) {
      isMatch = await bcrypt.compare(password, user.password);
    } else {
      isMatch = password === user.password;
      if (isMatch) {
        const salt = await bcrypt.genSalt(10);
        user.password = await bcrypt.hash(password, salt);
        users[userIndex] = user;
        await writeJson(USERS_FILE, users);
      }
    }

    if (isMatch) {
      res.json({
        success: true,
        userId: user.id,
        username: user.username,
        fullName: user.fullName || user.username,
      });
    } else {
      res
        .status(401)
        .json({ success: false, message: "Username atau password salah" });
    }
  } catch (error) {
    res
      .status(500)
      .json({ success: false, message: "Terjadi kesalahan server." });
  }
});

app.post("/api/register", async (req, res) => {
  try {
    const { fullName, username, email, password } = req.body; // fullName ditambahkan
    const users = await readJson(USERS_FILE);

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return res
        .status(400)
        .json({ success: false, message: "Format email tidak valid!" });
    }
    if (users.find((u) => u.username === username)) {
      return res
        .status(400)
        .json({ success: false, message: "Username sudah terdaftar!" });
    }
    if (users.find((u) => u.email === email)) {
      return res
        .status(400)
        .json({ success: false, message: "Email sudah terdaftar!" });
    }

    const salt = await bcrypt.genSalt(10);
    const hashedPassword = await bcrypt.hash(password, salt);

    const newUser = {
      id: Date.now(),
      fullName: fullName || username, // Simpan Nama Lengkap
      username,
      email,
      password: hashedPassword,
    };

    users.push(newUser);
    await writeJson(USERS_FILE, users);

    res.json({ success: true, message: "Registrasi berhasil, silakan masuk." });
  } catch (error) {
    res
      .status(500)
      .json({ success: false, message: "Terjadi kesalahan server." });
  }
});

// ==========================================
// ROUTES API USER (PENGATURAN)
// ==========================================
app.get("/api/user/:id", async (req, res) => {
  try {
    const users = await readJson(USERS_FILE);
    const user = users.find((u) => u.id == req.params.id); // Pake == karena id di json bisa number/string
    if (!user)
      return res
        .status(404)
        .json({ success: false, message: "User tidak ditemukan" });

    // Jangan kirim password ke frontend
    res.json({
      success: true,
      fullName: user.fullName || "",
      username: user.username,
      email: user.email,
    });
  } catch (error) {
    res.status(500).json({ success: false, message: "Error mengambil data" });
  }
});

app.put("/api/user/:id", async (req, res) => {
  try {
    const { fullName, username, email, oldPassword, newPassword } = req.body;
    const users = await readJson(USERS_FILE);
    const userIndex = users.findIndex((u) => u.id == req.params.id);

    if (userIndex === -1)
      return res
        .status(404)
        .json({ success: false, message: "User tidak ditemukan" });

    const user = users[userIndex];

    // Cek apakah username/email dipakai orang lain (selain dirinya sendiri)
    if (users.find((u) => u.username === username && u.id != req.params.id)) {
      return res.status(400).json({
        success: false,
        message: "Username sudah dipakai orang lain!",
      });
    }
    if (users.find((u) => u.email === email && u.id != req.params.id)) {
      return res
        .status(400)
        .json({ success: false, message: "Email sudah dipakai orang lain!" });
    }

    // Logika ubah password
    if (newPassword) {
      if (!oldPassword)
        return res.status(400).json({
          success: false,
          message: "Masukkan password lama untuk mengubah password!",
        });

      const isMatch = await bcrypt.compare(oldPassword, user.password);
      if (!isMatch)
        return res
          .status(400)
          .json({ success: false, message: "Password lama salah!" });

      const salt = await bcrypt.genSalt(10);
      user.password = await bcrypt.hash(newPassword, salt);
    }

    // Update data lainnya
    user.fullName = fullName;
    user.username = username;
    user.email = email;

    users[userIndex] = user;
    await writeJson(USERS_FILE, users);

    res.json({
      success: true,
      message: "Profil berhasil diperbarui!",
      username: user.username,
    });
  } catch (error) {
    res
      .status(500)
      .json({ success: false, message: "Error menyimpan pengaturan" });
  }
});

// ==========================================
// ROUTES API TRANSAKSI & KATEGORI
// ==========================================
app.get("/api/categories", async (req, res) => {
  try {
    const categories = await readJson(CATEGORIES_FILE);
    res.json(categories);
  } catch (error) {
    res.status(500).json({ message: "Gagal mengambil kategori" });
  }
});

app.get("/api/expenses/:userId", async (req, res) => {
  try {
    const expenses = await readJson(EXPENSES_FILE);
    res.json(expenses.filter((e) => e.userId == req.params.userId));
  } catch (error) {
    res.status(500).json({ message: "Gagal mengambil transaksi" });
  }
});

app.post("/api/expenses", async (req, res) => {
  try {
    const expenses = await readJson(EXPENSES_FILE);
    const newExp = {
      id: Date.now(),
      userId: req.body.userId,
      categoryId: parseInt(req.body.categoryId),
      amount: parseInt(req.body.amount),
      date: req.body.date,
      note: req.body.note,
    };
    expenses.push(newExp);
    await writeJson(EXPENSES_FILE, expenses);
    res.json(newExp);
  } catch (error) {
    res.status(500).json({ message: "Gagal menyimpan transaksi" });
  }
});

app.delete("/api/expenses/:id", async (req, res) => {
  try {
    let expenses = await readJson(EXPENSES_FILE);
    expenses = expenses.filter((e) => e.id != req.params.id);
    await writeJson(EXPENSES_FILE, expenses);
    res.json({ success: true });
  } catch (error) {
    res.status(500).json({ message: "Gagal menghapus transaksi" });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Server berjalan di port ${PORT}`));
