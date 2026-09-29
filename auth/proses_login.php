<?php
session_start();

require_once "../config/database.php";

// 1. Cek apakah form dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// 2. Ambil dan bersihkan data input
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// 3. Validasi input kosong
if ($username === '' || $password === '') {
    $_SESSION['error'] = "Username dan password wajib diisi!";
    header("Location: login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| AUTO-FIX DATABASE: Membuat tabel admin & akun default jika belum tersedia
|--------------------------------------------------------------------------
*/
$createTableQuery = "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $createTableQuery);

// Cek apakah tabel admin masih kosong, jika kosong buat akun default
$checkEmpty = mysqli_query($conn, "SELECT id FROM admin LIMIT 1");
if ($checkEmpty && mysqli_num_rows($checkEmpty) === 0) {
    $defaultPassHash = password_hash("admin123", PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO admin (nama, username, password) VALUES ('Administrator', 'admin', '$defaultPassHash')");
}

/*
|--------------------------------------------------------------------------
| CARI DATA ADMIN BERDASARKAN USERNAME
|--------------------------------------------------------------------------
*/
$username_safe = mysqli_real_escape_string($conn, $username);
$sql = "SELECT * FROM admin WHERE username = '$username_safe' LIMIT 1";
$query = mysqli_query($conn, $sql);

// Pengecekan agar terhindar dari Fatal Error bool given jika query bermasalah
if (!$query) {
    $_SESSION['error'] = "Terjadi kendala pada database: " . mysqli_error($conn);
    header("Location: login.php");
    exit;
}

$admin = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| CEK COCOK ATAU TIDAKNYA USERNAME & PASSWORD
|--------------------------------------------------------------------------
*/
if ($admin && password_verify($password, $admin['password'])) {
    // Login Berhasil -> Buat session aman
    session_regenerate_id(true);

    $_SESSION['admin_id']       = $admin['id'];
    $_SESSION['admin_nama']     = $admin['nama'];
    $_SESSION['admin_username'] = $admin['username'];

    // Alihkan ke Dashboard Admin
    header("Location: ../admin/index.php");
    exit;
} else {
    // Login Gagal
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: login.php");
    exit;
}