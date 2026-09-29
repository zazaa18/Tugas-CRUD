<?php
// Cegah error session duplicate & cegah whitespace output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi sesi admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";
require_once "../includes/flash.php";

$action = $_GET['action'] ?? '';

/* ==========================================
   1. AKSI: TAMBAH PRODUK BARU
   ========================================== */
if ($action === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $harga     = intval($_POST['harga'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($nama === '' || $kategori === '' || $harga <= 0) {
        setFlash('danger', 'Semua kolom wajib diisi dengan benar!');
        header("Location: tambah.php");
        exit;
    }

    // Handle Upload Gambar
    $nama_file = 'default.jpg';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($file_ext, $allowed)) {
            $nama_file = time() . '_' . uniqid() . '.' . $file_ext;
            $target_dir = "../uploads/produk/";

            // Buat folder jika belum ada
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            move_uploaded_file($file_tmp, $target_dir . $nama_file);
        }
    }

    $nama_safe      = mysqli_real_escape_string($conn, $nama);
    $kategori_safe  = mysqli_real_escape_string($conn, $kategori);
    $deskripsi_safe = mysqli_real_escape_string($conn, $deskripsi);

    $sql = "INSERT INTO produk (nama, kategori, harga, deskripsi, gambar) 
            VALUES ('$nama_safe', '$kategori_safe', $harga, '$deskripsi_safe', '$nama_file')";

    if (mysqli_query($conn, $sql)) {
        setFlash('success', 'Produk berhasil ditambahkan!');
    } else {
        setFlash('danger', 'Gagal menambahkan produk: ' . mysqli_error($conn));
    }

    header("Location: produk.php");
    exit;
}

/* ==========================================
   2. AKSI: EDIT PRODUK
   ========================================== */
elseif ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = intval($_POST['id'] ?? 0);
    $nama      = trim($_POST['nama'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $harga     = intval($_POST['harga'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $foto_lama = $_POST['foto_lama'] ?? '';

    if ($id <= 0 || $nama === '' || $kategori === '' || $harga <= 0) {
        setFlash('danger', 'Data edit tidak valid!');
        header("Location: produk.php");
        exit;
    }

    $nama_file = $foto_lama;

    // Jika user mengunggah foto pengganti baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($file_ext, $allowed)) {
            $nama_file = time() . '_' . uniqid() . '.' . $file_ext;
            $target_dir = "../uploads/produk/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $target_dir . $nama_file)) {
                // Hapus file lama jika ada dan bukan gambar default
                if ($foto_lama && $foto_lama !== 'default.jpg' && file_exists($target_dir . $foto_lama)) {
                    unlink($target_dir . $foto_lama);
                }
            }
        }
    }

    $nama_safe      = mysqli_real_escape_string($conn, $nama);
    $kategori_safe  = mysqli_real_escape_string($conn, $kategori);
    $deskripsi_safe = mysqli_real_escape_string($conn, $deskripsi);

    $sql = "UPDATE produk SET 
            nama = '$nama_safe', 
            kategori = '$kategori_safe', 
            harga = $harga, 
            deskripsi = '$deskripsi_safe', 
            gambar = '$nama_file' 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        setFlash('success', 'Produk berhasil diperbarui!');
    } else {
        setFlash('danger', 'Gagal memperbarui produk: ' . mysqli_error($conn));
    }

    header("Location: produk.php");
    exit;
}

/* ==========================================
   DEFAULT FALLBACK JIKA AKSES LANGSUNG
   ========================================== */
else {
    header("Location: produk.php");
    exit;
}