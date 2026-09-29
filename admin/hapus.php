<?php
require_once "../config/database.php";
require_once "../includes/flash.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$get = mysqli_query($conn, "SELECT gambar FROM produk WHERE id = $id");
$data = mysqli_fetch_assoc($get);

if ($data) {
    if ($data['gambar'] && file_exists("../uploads/produk/" . $data['gambar'])) {
        unlink("../uploads/produk/" . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM produk WHERE id = $id");
    setFlash("success", "Produk berhasil dihapus!");
} else {
    setFlash("danger", "Produk gagal ditemukan!");
}

header("Location: produk.php");
exit;