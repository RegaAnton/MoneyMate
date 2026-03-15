const express = require("express");
const fs = require("fs");
const path = require("path");
const bcrypt = require("bcryptjs"); // Tambahan library untuk enkripsi password

const app = express();

app.use(express.json());
app.use(express.static(path.join(__dirname, "public")));

// --- Definisi File Database ---
const USERS_FILE = "./users.json";
const CATEGORIES_FILE = "./categories.json";
const EXPENSES_FILE = "./expenses.json";

// --- Fungsi Helper ---
// Membaca file JSON, mengembalikan array kosong jika file belum ada
const readJson = (file) => {
  if (!fs.existsSync(file)) return [];
  return JSON.parse(fs.readFileSync(file, "utf8"));
};

// Menulis data ke file JSON
const writeJson = (file, data) => {
  fs.writeFileSync(file, JSON.stringify(data, null, 2));
};

// --- Inisialisasi Database Otomatis ---
// Jika file belum ada saat server berjalan, otomatis buatkan dengan data default
if (!fs.existsSync(USERS_FILE)) writeJson(USERS_FILE, []);
if (!fs.existsSync(EXPENSES_FILE)) writeJson(EXPENSES_FILE, []);
if (!fs.existsSync(CATEGORIES_FILE)) {
  writeJson(CATEGORIES_FILE, []);
}

// ==========================================
// ROUTES API
// ==========================================

// --- Auth (Login & Register) ---
app.post("/api/login", (req, res) => {
  const { username, password } = req.body;
  const users = readJson(USERS_FILE);

  // Cari index user agar bisa meng-update datanya untuk migrasi
  const userIndex = users.findIndex((u) => u.username === username);

  if (userIndex === -1) {
    return res
      .status(401)
      .json({ success: false, message: "Username atau password salah" });
  }

  const user = users[userIndex];
  let isMatch = false;

  // Cek apakah password sudah di-hash (bcrypt biasanya diawali dengan "$2a$" atau "$2b$" dan panjang 60)
  const isHashed =
    user.password &&
    user.password.startsWith("$2") &&
    user.password.length === 60;

  if (isHashed) {
    // Jika sudah aman (hash), bandingkan menggunakan bcrypt
    isMatch = bcrypt.compareSync(password, user.password);
  } else {
    // [LAZY MIGRATION] Jika masih plain text (akun lama yang dibuat sebelumnya)
    isMatch = password === user.password;

    if (isMatch) {
      // Karena password benar, kita langsung amankan password lamanya menjadi hash
      const salt = bcrypt.genSaltSync(10);
      user.password = bcrypt.hashSync(password, salt);

      // Simpan perubahan ke users.json secara diam-diam
      users[userIndex] = user;
      writeJson(USERS_FILE, users);
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
});

app.post("/api/register", (req, res) => {
  const { username, email, password } = req.body;
  const users = readJson(USERS_FILE);

  // Validasi format email menggunakan Regex
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    return res
      .status(400)
      .json({ success: false, message: "Format email tidak valid!" });
  }

  // Cek apakah username sudah ada
  if (users.find((u) => u.username === username)) {
    return res
      .status(400)
      .json({ success: false, message: "Username sudah terdaftar!" });
  }

  // Cek apakah email sudah ada
  if (users.find((u) => u.email === email)) {
    return res
      .status(400)
      .json({ success: false, message: "Email sudah terdaftar!" });
  }

  // Enkripsi password untuk pendaftar baru
  const salt = bcrypt.genSaltSync(10);
  const hashedPassword = bcrypt.hashSync(password, salt);

  const newUser = {
    id: Date.now(),
    username,
    email,
    password: hashedPassword,
  };

  users.push(newUser);
  writeJson(USERS_FILE, users);

  res.json({ success: true, message: "Registrasi berhasil, silakan masuk." });
});

// --- Categories (Read-Only) ---
app.get("/api/categories", (req, res) => {
  const categories = readJson(CATEGORIES_FILE);
  res.json(categories);
});

// --- Expenses CRUD ---
app.get("/api/expenses/:userId", (req, res) => {
  const expenses = readJson(EXPENSES_FILE);
  res.json(expenses.filter((e) => e.userId == req.params.userId));
});

app.post("/api/expenses", (req, res) => {
  const expenses = readJson(EXPENSES_FILE);

  const newExp = {
    id: Date.now(),
    userId: req.body.userId,
    categoryId: parseInt(req.body.categoryId),
    amount: parseInt(req.body.amount),
    date: req.body.date,
    note: req.body.note,
  };

  expenses.push(newExp);
  writeJson(EXPENSES_FILE, expenses);
  res.json(newExp);
});

app.delete("/api/expenses/:id", (req, res) => {
  let expenses = readJson(EXPENSES_FILE);
  expenses = expenses.filter((e) => e.id != req.params.id);
  writeJson(EXPENSES_FILE, expenses);
  res.json({ success: true });
});

// --- Start Server ---
app.listen(3000, () => console.log("Server berjalan di http://localhost:3000"));
