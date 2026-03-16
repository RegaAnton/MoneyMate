let categorySelectInstance = null;
let currentUserId = localStorage.getItem("userId");
let currentUsername = localStorage.getItem("username");
let currentFullName = localStorage.getItem("fullName") || currentUsername;
let categories = [];
let expenses = [];
let dashboardFilter = "bulan";
let tableFilter = "bulan";
let expenseChartInstance = null;
let currentTheme = localStorage.getItem("theme") || "light";
let currentPage = 1;
const itemsPerPage = 10;
let sortColumn = "date";
let sortDirection = "desc";

const themeToggleBtn = document.getElementById("themeToggle");
const themeIcon = themeToggleBtn.querySelector("i");
applyTheme(currentTheme);

themeToggleBtn.addEventListener("click", () => {
  currentTheme = currentTheme === "light" ? "dark" : "light";
  applyTheme(currentTheme);
  if (!document.getElementById("page-dashboard").classList.contains("hidden"))
    processDashboard();
});

function applyTheme(theme) {
  document.documentElement.setAttribute("data-bs-theme", theme);
  localStorage.setItem("theme", theme);
  themeIcon.className =
    theme === "dark" ? "fa-solid fa-sun" : "fa-solid fa-moon";
  Chart.defaults.color = theme === "dark" ? "#adb5bd" : "#666666";
}

function getLocalYYYYMMDD(dateObj = new Date()) {
  const y = dateObj.getFullYear();
  const m = String(dateObj.getMonth() + 1).padStart(2, "0");
  const d = String(dateObj.getDate()).padStart(2, "0");
  return `${y}-${m}-${d}`;
}

function isDateInFilter(dateString, filterType, viewContext) {
  if (filterType === "semua") return true;
  const [y, m, d] = dateString.split("-").map(Number);
  const expDate = new Date(y, m - 1, d);
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  if (filterType === "custom") {
    const startVal = document.getElementById(
      viewContext === "dashboard" ? "dashStartDate" : "tblStartDate",
    ).value;
    const endVal = document.getElementById(
      viewContext === "dashboard" ? "dashEndDate" : "tblEndDate",
    ).value;
    if (startVal && expDate < new Date(startVal)) return false;
    if (endVal && expDate > new Date(endVal)) return false;
    return true;
  }

  if (filterType === "hari") return expDate.getTime() === today.getTime();
  if (filterType === "minggu") {
    const diff = today.getDay() === 0 ? 6 : today.getDay() - 1;
    const mon = new Date(today);
    mon.setDate(today.getDate() - diff);
    const sun = new Date(mon);
    sun.setDate(mon.getDate() + 6);
    return expDate >= mon && expDate <= sun;
  }
  if (filterType === "bulan")
    return y === today.getFullYear() && m - 1 === today.getMonth();
  if (filterType === "ytd") return y === today.getFullYear();
  return true;
}

if (currentUserId) {
  document.getElementById("displayUsername").innerText = currentFullName;
  showApp();
} else {
  document.getElementById("loginView").classList.remove("hidden");
}

function toggleAuthView(view) {
  document
    .getElementById("loginView")
    .classList.toggle("hidden", view === "register");
  document
    .getElementById("registerView")
    .classList.toggle("hidden", view === "login");
}

function showApp() {
  document.getElementById("loginView").classList.add("hidden");
  document.getElementById("registerView").classList.add("hidden");
  document.getElementById("mainApp").classList.remove("hidden");
  document.getElementById("expDate").value = getLocalYYYYMMDD();
  loadData();
}

function logout() {
  localStorage.clear();
  location.reload();
}

function navigate(page) {
  document
    .querySelectorAll(".page-view")
    .forEach((el) => el.classList.add("hidden"));
  document
    .querySelectorAll(".nav-link")
    .forEach((el) => el.classList.remove("active"));
  document.getElementById(`page-${page}`).classList.remove("hidden");
  document.getElementById(`nav-${page}`).classList.add("active");

  if (page === "dashboard") processDashboard();
  if (page === "expenses") renderExpensesTable();
  if (page === "settings") loadSettingsData(); // Panggil fungsi saat buka menu setting
}

async function loadData() {
  const [catRes, expRes] = await Promise.all([
    fetch("/api/categories"),
    fetch(`/api/expenses/${currentUserId}`),
  ]);
  categories = (await catRes.json()).sort((a, b) =>
    a.name.localeCompare(b.name),
  );
  expenses = await expRes.json();
  renderCategories();
  renderExpensesTable();
  processDashboard();
}

function renderCategories() {
  const select = document.getElementById("expCategory");
  if (categorySelectInstance) categorySelectInstance.destroy();
  select.innerHTML =
    '<option value="" disabled selected>Pilih atau ketik kategori...</option>';
  categories.forEach(
    (cat) =>
      (select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`),
  );
  categorySelectInstance = new TomSelect("#expCategory", {
    create: false,
    placeholder: "Ketik untuk mencari kategori...",
    sortField: { field: "text", direction: "asc" },
  });
}

function handleSort(column) {
  if (sortColumn === column) {
    sortDirection = sortDirection === "asc" ? "desc" : "asc";
  } else {
    sortColumn = column;
    sortDirection = "asc";
  }
  renderExpensesTable();
}

function renderExpensesTable() {
  const tbody = document.getElementById("expenseTableBody");
  tbody.innerHTML = "";
  let filtered = expenses.filter((exp) =>
    isDateInFilter(exp.date, tableFilter, "table"),
  );

  filtered.sort((a, b) => {
    let valA = sortColumn === "amount" ? a.amount : a.date;
    let valB = sortColumn === "amount" ? b.amount : b.date;
    if (sortColumn === "date") {
      valA = new Date(valA);
      valB = new Date(valB);
    }
    return sortDirection === "asc" ? valA - valB : valB - valA;
  });

  document
    .querySelectorAll(".sort-icon")
    .forEach((icon) => (icon.className = "fa-solid fa-sort sort-icon"));
  const activeIcon = document.getElementById(`sort-${sortColumn}`);
  activeIcon.classList.add("sort-active");
  activeIcon.classList.replace(
    "fa-sort",
    sortDirection === "asc" ? "fa-sort-up" : "fa-sort-down",
  );

  const totalItems = filtered.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
  if (currentPage > totalPages) currentPage = totalPages;
  const startIndex = (currentPage - 1) * itemsPerPage;
  const paginatedItems = filtered.slice(startIndex, startIndex + itemsPerPage);

  if (paginatedItems.length === 0) {
    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-5">Belum ada transaksi.</td></tr>`;
  } else {
    paginatedItems.forEach((exp) => {
      const cat =
        categories.find((c) => c.id == exp.categoryId)?.name || "Lainnya";
      tbody.innerHTML += `<tr><td class="ps-4">${exp.date}</td><td><span class="badge bg-secondary-subtle text-secondary-emphasis border">${cat}</span></td><td class="text-truncate" style="max-width:150px">${exp.note || "-"}</td><td class="fw-bold text-danger">Rp ${exp.amount.toLocaleString("id-ID")}</td><td class="text-end pe-4"><button class="btn btn-sm btn-outline-danger border-0" onclick="deleteExpense(${exp.id})"><i class="fa-solid fa-trash"></i></button></td></tr>`;
    });
  }
  document.getElementById("tableInfo").innerText =
    `Menampilkan ${totalItems ? startIndex + 1 : 0}-${Math.min(startIndex + itemsPerPage, totalItems)} dari ${totalItems}`;
  renderPagination(totalPages);
}

function renderPagination(totalPages) {
  const list = document.getElementById("paginationList");
  list.innerHTML = "";
  if (totalPages <= 1) return;
  const addPage = (p, label, active = false, disabled = false) => {
    list.innerHTML += `<li class="page-item ${active ? "active" : ""} ${disabled ? "disabled" : ""}"><a class="page-link shadow-none" href="javascript:void(0)" onclick="if(!${disabled}) changePage(${p})">${label}</a></li>`;
  };
  addPage(
    currentPage - 1,
    '<i class="fa-solid fa-chevron-left"></i>',
    false,
    currentPage === 1,
  );
  for (let i = 1; i <= totalPages; i++) {
    if (
      i === 1 ||
      i === totalPages ||
      (i >= currentPage - 1 && i <= currentPage + 1)
    ) {
      addPage(i, i, i === currentPage);
    } else if (i === currentPage - 2 || i === currentPage + 2) {
      addPage(0, "...", false, true);
    }
  }
  addPage(
    currentPage + 1,
    '<i class="fa-solid fa-chevron-right"></i>',
    false,
    currentPage === totalPages,
  );
}

function changePage(p) {
  currentPage = p;
  renderExpensesTable();
}

// --- LOGIKA AUTHENTICATION ---
document.getElementById("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const res = await fetch("/api/login", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      username: document.getElementById("loginUsername").value,
      password: document.getElementById("loginPassword").value,
    }),
  });
  const data = await res.json();
  if (data.success) {
    localStorage.setItem("userId", data.userId);
    localStorage.setItem("username", data.username);
    localStorage.setItem("fullName", data.fullName);
    currentUserId = data.userId;
    currentUsername = data.username;
    currentFullName = data.fullName;
    document.getElementById("displayUsername").innerText = currentFullName;
    showApp();
  } else alert(data.message);
});

document
  .getElementById("registerForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault();
    const res = await fetch("/api/register", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        fullName: document.getElementById("regFullName").value, // Kirim fullName
        username: document.getElementById("regUsername").value,
        email: document.getElementById("regEmail").value,
        password: document.getElementById("regPassword").value,
      }),
    });
    const data = await res.json();
    if (data.success) {
      alert("Berhasil! Silakan masuk.");
      toggleAuthView("login");
      document.getElementById("registerForm").reset();
    } else alert(data.message);
  });

// --- LOGIKA PENGATURAN AKUN ---
async function loadSettingsData() {
  const res = await fetch(`/api/user/${currentUserId}`);
  const data = await res.json();
  if (data.success) {
    document.getElementById("setFullName").value = data.fullName;
    document.getElementById("setUsername").value = data.username;
    document.getElementById("setEmail").value = data.email;
    document.getElementById("setOldPassword").value = "";
    document.getElementById("setNewPassword").value = "";
  }
}

document
  .getElementById("settingsForm")
  .addEventListener("submit", async (e) => {
    e.preventDefault();

    const bodyData = {
      fullName: document.getElementById("setFullName").value,
      username: document.getElementById("setUsername").value,
      email: document.getElementById("setEmail").value,
      oldPassword: document.getElementById("setOldPassword").value,
      newPassword: document.getElementById("setNewPassword").value,
    };

    const res = await fetch(`/api/user/${currentUserId}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(bodyData),
    });

    const data = await res.json();
    alert(data.message); // Notifikasi berhasil atau error

    if (data.success) {
      currentUsername = data.username;
      currentFullName = document.getElementById("setFullName").value;

      localStorage.setItem("username", currentUsername);
      localStorage.setItem("fullName", currentFullName);

      document.getElementById("displayUsername").innerText = currentFullName;

      // Kosongkan kolom password setelah sukses
      document.getElementById("setOldPassword").value = "";
      document.getElementById("setNewPassword").value = "";
    }
  });

// --- LOGIKA TRANSAKSI ---
document.getElementById("expenseForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  await fetch("/api/expenses", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      userId: currentUserId,
      date: document.getElementById("expDate").value,
      categoryId: document.getElementById("expCategory").value,
      amount: document.getElementById("expAmount").value,
      note: document.getElementById("expNote").value,
    }),
  });
  document.getElementById("expenseForm").reset();
  document.getElementById("expDate").value = getLocalYYYYMMDD();
  if (categorySelectInstance) categorySelectInstance.clear();
  loadData();
});

async function deleteExpense(id) {
  if (confirm("Hapus data?")) {
    await fetch(`/api/expenses/${id}`, { method: "DELETE" });
    loadData();
  }
}

function processDashboard() {
  let total = 0,
    categoryTotals = {};
  const filtered = expenses.filter((exp) =>
    isDateInFilter(exp.date, dashboardFilter, "dashboard"),
  );
  filtered.forEach((exp) => {
    total += exp.amount;
    const cat =
      categories.find((c) => c.id == exp.categoryId)?.name || "Lainnya";
    categoryTotals[cat] = (categoryTotals[cat] || 0) + exp.amount;
  });
  document.getElementById("totalAmount").innerText =
    `Rp ${total.toLocaleString("id-ID")}`;
  renderChart(categoryTotals);
}

function renderChart(dataObj) {
  const ctx = document.getElementById("expenseChart").getContext("2d");
  if (expenseChartInstance) expenseChartInstance.destroy();
  const labels = Object.keys(dataObj),
    data = Object.values(dataObj);
  const isDark = currentTheme === "dark";
  expenseChartInstance = new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: labels.length ? labels : ["Belum ada data"],
      datasets: [
        {
          data: data.length ? data : [1],
          backgroundColor: data.length
            ? [
                "#0d6efd",
                "#20c997",
                "#ffc107",
                "#dc3545",
                "#0dcaf0",
                "#6f42c1",
                "#fd7e14",
                "#198754",
              ]
            : isDark
              ? ["#333"]
              : ["#e9ecef"],
          borderWidth: 2,
          borderColor: isDark ? "#1e1e1e" : "#fff",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: window.innerWidth < 768 ? "bottom" : "right" },
      },
      cutout: "75%",
    },
  });
}

function exportToCSV() {
  const filtered = expenses.filter((exp) =>
    isDateInFilter(exp.date, tableFilter, "table"),
  );
  if (!filtered.length) return alert("Data kosong.");
  let csv = "\uFEFFTanggal;Kategori;Catatan;Jumlah (Rp)\n";
  filtered.forEach((exp) => {
    const cat =
      categories.find((c) => c.id == exp.categoryId)?.name || "Lainnya";
    csv += `${exp.date};"${cat}";"${(exp.note || "").replace(/"/g, '""')}";${exp.amount}\n`;
  });
  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob),
    link = document.createElement("a");
  link.href = url;
  link.download = `MoneyMate_${getLocalYYYYMMDD()}.csv`;
  link.click();
  URL.revokeObjectURL(url);
}

document.getElementById("tableFilterSelect").addEventListener("change", (e) => {
  tableFilter = e.target.value;
  document
    .getElementById("tableCustomDate")
    .classList.toggle("d-none", tableFilter !== "custom");
  currentPage = 1;
  renderExpensesTable();
});

document.querySelectorAll(".filter-btn").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    document.querySelectorAll(".filter-btn").forEach((b) => {
      b.classList.remove("btn-primary", "active");
      b.classList.add("btn-outline-primary");
    });
    e.target.classList.replace("btn-outline-primary", "btn-primary");
    e.target.classList.add("active");
    dashboardFilter = e.target.dataset.filter;
    document
      .getElementById("dashboardCustomDate")
      .classList.toggle("d-none", dashboardFilter !== "custom");
    document.getElementById("filterLabel").innerText =
      dashboardFilter === "custom"
        ? "Rentang Waktu"
        : {
            hari: "Hari Ini",
            minggu: "Minggu Ini",
            bulan: "Bulan Ini",
            ytd: "Tahun Ini",
          }[dashboardFilter];
    processDashboard();
  });
});
