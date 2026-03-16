const express = require("express");
const fs = require("fs").promises;
const path = require("path");
const bcrypt = require("bcryptjs");

const app = express();

app.use(express.json());
app.use(express.static(path.join(__dirname, "public")));

// --- Definisi File Database ---
const USERS_FILE = path.join(__dirname, "users.json");
const CATEGORIES_FILE = path.join(__dirname, "categories.json");
const EXPENSES_FILE = path.join(__dirname, "expenses.json");

// ==========================================
// FUNGSI HELPER (ASYNCHRONOUS)
// ==========================================

// Membaca file JSON secara Asynchronous (Non-blocking)
const readJson = async (file) => {
  try {
    const data = await fs.readFile(file, "utf8");
    return JSON.parse(data);
  } catch (error) {
    // Jika file belum ada (error ENOENT) atau kosong, kembalikan array kosong
    if (error.code === "ENOENT") return [];
    console.error(`Gagal membaca ${file}:`, error);
    return [];
  }
};

// Menulis data ke file JSON secara Asynchronous (Non-blocking)
const writeJson = async (file, data) => {
  try {
    await fs.writeFile(file, JSON.stringify(data, null, 2), "utf8");
  } catch (error) {
    console.error(`Gagal menulis ke ${file}:`, error);
  }
};

// Inisialisasi Database Otomatis saat server berjalan
const initDB = async () => {
  const files = [USERS_FILE, CATEGORIES_FILE, EXPENSES_FILE];
  for (const file of files) {
    try {
      await fs.access(file);
    } catch {
      await writeJson(file, []);
      console.log(`[Init] File ${path.basename(file)} berhasil dibuat.`);
    }
  }
};

// Jalankan inisialisasi
initDB();

// ==========================================
// ROUTES API (Semua diubah menjadi async/await)
// ==========================================

// --- Auth (Login & Register) ---
app.post("/api/login", async (req, res) => {
  try {
    const { username, password } = req.body;
    const users = await readJson(USERS_FILE); // Pakai 'await'

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
      isMatch = await bcrypt.compare(password, user.password); // bcrypt versi async lebih efisien
    } else {
      // [LAZY MIGRATION]
      isMatch = password === user.password;

      if (isMatch) {
        const salt = await bcrypt.genSalt(10);
        user.password = await bcrypt.hash(password, salt);
        users[userIndex] = user;

        await writeJson(USERS_FILE, users); // Pakai 'await'
        console.log(
          `[Keamanan] Password akun '${username}' berhasil dienkripsi!`,
        );
      }
    }

    if (isMatch) {
      res.json({ success: true, userId: user.id, username: user.username });
    } else {
      res
        .status(401)
        .json({ success: false, message: "Username atau password salah" });
    }
  } catch (error) {
    res
      .status(500)
      .json({ success: false, message: "Terjadi kesalahan pada server." });
  }
});

app.post("/api/register", async (req, res) => {
  try {
    const { username, email, password } = req.body;
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
      .json({ success: false, message: "Terjadi kesalahan pada server." });
  }
});

// --- Categories (Read-Only) ---
app.get("/api/categories", async (req, res) => {
  try {
    const categories = await readJson(CATEGORIES_FILE);
    res.json(categories);
  } catch (error) {
    res.status(500).json({ message: "Gagal mengambil kategori" });
  }
});

// --- Expenses CRUD ---
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

// --- Start Server ---
const PORT = process.env.PORT || 3000;
app.listen(PORT, () =>
  console.log(`Server berjalan di port ${PORT} (Asynchronous Mode)`),
);
