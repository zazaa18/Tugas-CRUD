<?php
require_once "config/database.php";
require_once "includes/header.php";

// Ambil input kata kunci pencarian
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $q_safe = mysqli_real_escape_string($conn, $q);
    $query = "SELECT * FROM produk WHERE nama LIKE '%$q_safe%' OR kategori LIKE '%$q_safe%' OR deskripsi LIKE '%$q_safe%' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM produk ORDER BY id DESC";
}

$result = mysqli_query($conn, $query);
$total_ditemukan = mysqli_num_rows($result);
?>

<div class="mb-4">
    <!-- Judul Halaman -->
    <h2 class="fw-bold mb-3" style="color: #2b1d14;">Daftar Produk</h2>

    <!-- Kotak Pencarian Sesuai Desain -->
    <div class="card p-3 p-md-4 shadow-sm border-0 rounded-3 bg-white mb-4">
        <form action="produk.php" method="GET">
            <div class="mb-3">
                <input 
                    type="text" 
                    name="q" 
                    class="form-control py-2 px-3" 
                    placeholder="Cari produk..." 
                    value="<?= htmlspecialchars($q); ?>"
                >
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="submit" class="btn px-4 text-white fw-medium" style="background-color: #7b3f00; border-color: #7b3f00;">
                    Cari
                </button>
                <?php if ($q !== ''): ?>
                    <a href="produk.php" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Info hasil jika sedang mencari -->
    <?php if ($q !== ''): ?>
        <p class="text-muted small">
            Menampilkan hasil untuk: <strong>"<?= htmlspecialchars($q); ?>"</strong> (<?= $total_ditemukan; ?> produk ditemukan)
        </p>
    <?php endif; ?>
</div>

<!-- Daftar Produk -->
<div class="row g-4">
    <?php if ($total_ditemukan > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    <img 
                        src="uploads/produk/<?= htmlspecialchars($row['gambar']); ?>" 
                        class="card-img-top" 
                        style="height: 220px; object-fit: cover;"
                        onerror="this.src='https://images.unsplash.com/photo-1535141192574-5d4897c13136?auto=format&fit=crop&w=600&q=80'"
                    >
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary align-self-start mb-2"><?= htmlspecialchars($row['kategori']); ?></span>
                        <h5 class="card-title fw-bold mb-2"><?= htmlspecialchars($row['nama']); ?></h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= substr(htmlspecialchars($row['deskripsi']), 0, 95); ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <span class="fw-bold text-danger fs-5">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                            <a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-sm text-white fw-semibold px-3" style="background-color: #7b3f00;">
                                Detail & Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
            <h5 class="fw-bold text-secondary">Produk tidak ditemukan</h5>
            <p class="text-muted small">Coba cari dengan kata kunci nama kue atau kategori lainnya.</p>
            <a href="produk.php" class="btn btn-sm btn-dark mt-2">Tampilkan Semua Produk</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once "includes/footer.php"; ?>