const express = require("express");
const fs = require("fs");
const path = require("path");
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
  const users = readJson(USERS_FILE); // Baca dari users.json

  const user = users.find(
    (u) => u.username === username && u.password === password,
  );
  if (user)
    res.json({ success: true, userId: user.id, username: user.username });
  else
    res
      .status(401)
      .json({ success: false, message: "Username atau password salah" });
});

app.post("/api/register", (req, res) => {
  const { username, email, password } = req.body; // Ambil email dari req.body
  const users = readJson(USERS_FILE);

  // --- VALIDASI EMAIL ---
  // Regex untuk memastikan format email benar (contoh: nama@domain.com)
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

  // Simpan user baru lengkap dengan email
  const newUser = { id: Date.now(), username, email, password };
  users.push(newUser);
  writeJson(USERS_FILE, users);

  res.json({ success: true, message: "Registrasi berhasil, silakan masuk." });
});

// --- Categories (Read-Only) ---
app.get("/api/categories", (req, res) => {
  const categories = readJson(CATEGORIES_FILE); // Baca dari categories.json
  res.json(categories);
});

// --- Expenses CRUD ---
app.get("/api/expenses/:userId", (req, res) => {
  const expenses = readJson(EXPENSES_FILE); // Baca dari expenses.json
  res.json(expenses.filter((e) => e.userId == req.params.userId));
});

app.post("/api/expenses", (req, res) => {
  const expenses = readJson(EXPENSES_FILE); // Baca dari expenses.json

  const newExp = {
    id: Date.now(),
    userId: req.body.userId,
    categoryId: parseInt(req.body.categoryId),
    amount: parseInt(req.body.amount),
    date: req.body.date,
    note: req.body.note,
  };

  expenses.push(newExp);
  writeJson(EXPENSES_FILE, expenses); // Tulis ke expenses.json
  res.json(newExp);
});

app.delete("/api/expenses/:id", (req, res) => {
  let expenses = readJson(EXPENSES_FILE); // Baca dari expenses.json
  expenses = expenses.filter((e) => e.id != req.params.id);
  writeJson(EXPENSES_FILE, expenses); // Tulis ulang ke expenses.json
  res.json({ success: true });
});

// --- Start Server ---
app.listen(3000, () => console.log("Server berjalan di http://localhost:3000"));
