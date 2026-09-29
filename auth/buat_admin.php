<?php
require_once "../config/database.php";

$nama     = "Administrator Toko";
$username = "admin";
$password = "admin123"; // Password yang akan digunakan untuk login

// Enkripsi password dengan algoritma standar PHP
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Pastikan tabel admin sudah ada
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Cek apakah username sudah pernah dibuat
$cek = mysqli_query($conn, "SELECT id FROM admin WHERE username = '$username'");

if (mysqli_num_rows($cek) > 0) {
    // Update password jika username sudah ada
    mysqli_query($conn, "UPDATE admin SET password = '$hashed_password', nama = '$nama' WHERE username = '$username'");
    echo "<h3 style='color:green;'>Password admin berhasil diperbarui!</h3>";
} else {
    // Insert admin baru jika belum ada
    mysqli_query($conn, "INSERT INTO admin (nama, username, password) VALUES ('$nama', '$username', '$hashed_password')");
    echo "<h3 style='color:green;'>Akun admin berhasil dibuat!</h3>";
}

echo "<p>Username: <b>{$username}</b><br>Password: <b>{$password}</b></p>";
echo "<a href='login.php'>Menuju Halaman Login &rarr;</a>";