<?php
require_once "config/database.php";
require_once "includes/header.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM produk WHERE id = $id";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    echo "<div class='alert alert-danger'>Produk tidak ditemukan! <a href='produk.php'>Kembali</a></div>";
    require_once "includes/footer.php";
    exit;
}
?>

<div class="card shadow border-0 overflow-hidden my-3">
    <div class="row g-0">
        <div class="col-md-6">
            <img src="uploads/produk/<?= htmlspecialchars($item['gambar']); ?>" class="img-fluid w-100 h-100 object-fit-cover" onerror="this.src='https://images.unsplash.com/photo-1535141192574-5d4897c13136?auto=format&fit=crop&w=600&q=80'">
        </div>
        <div class="col-md-6 d-flex flex-column p-4 p-lg-5">
            <span class="badge bg-warning text-dark align-self-start mb-2"><?= htmlspecialchars($item['kategori']); ?></span>
            <h2 class="fw-bold mb-3"><?= htmlspecialchars($item['nama']); ?></h2>
            <h3 class="text-danger fw-bold mb-4">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></h3>
            <h6 class="fw-semibold text-secondary">Deskripsi Produk:</h6>
            <p class="text-muted leading-relaxed"><?= nl2br(htmlspecialchars($item['deskripsi'])); ?></p>
            <div class="mt-auto pt-4 border-top d-flex gap-2">
                <a href="https://wa.me/6281234567890?text=Halo%20saya%20mau%20pesan%20<?= urlencode($item['nama']); ?>" target="_blank" class="btn btn-success flex-grow-1"><i class="bi bi-whatsapp me-2"></i>Pesan via WhatsApp</a>
                <a href="produk.php" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>