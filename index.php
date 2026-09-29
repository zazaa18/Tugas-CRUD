<?php
require_once "config/database.php";
require_once "includes/header.php";

// Ambil 3 produk unggulan/terbaru untuk highlight di Beranda
$query = "SELECT * FROM produk ORDER BY id DESC LIMIT 3";
$result = mysqli_query($conn, $query);
?>

<!-- Section: Hero Selamat Datang -->
<section class="p-4 p-md-5 mb-5 rounded-4 shadow-sm border-0 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="badge px-3 py-2 rounded-pill mb-3 text-uppercase fw-semibold" 
                  style="background-color: #fed7aa; color: #9a3412; letter-spacing: 1px; font-size: 0.8rem;">
                <i class="bi bi-stars me-1"></i> Fresh Baked Every Day
            </span>
            <h1 class="display-5 fw-bold mb-3" style="color: #431407; line-height: 1.2;">
                Selamat Datang di <span style="color: #c2410c;">Sweet Delights</span>
            </h1>
            <p class="lead mb-4 text-secondary" style="font-size: 1.1rem;">
                Hadirkan kehangatan dan kebahagiaan di setiap gigitan. Kami memanggang aneka kue tart, bolu lembut, brownies, dan pastry istimewa menggunakan bahan premium pilihan tanpa bahan pengawet.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="produk.php" class="btn btn-lg px-4 text-white fw-semibold shadow-sm" style="background-color: #7b3f00; border-color: #7b3f00;">
                    <i class="bi bi-grid-fill me-2"></i>Lihat Menu Kue
                </a>
                <a href="tentang.php" class="btn btn-lg px-4 btn-outline-dark fw-semibold">
                    Tentang Kami
                </a>
            </div>
        </div>
        <div class="col-lg-5 text-center">
            <div class="position-relative d-inline-block">
                <img src="uploads/produk/home.jpg" 
                     class="img-fluid rounded-4 shadow-lg" 
                     style="max-height: 340px; width: 100%; object-fit: cover;" 
                     alt="Kue Spesial Sweet Delights">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section: Keunggulan Toko -->
<section class="row g-3 mb-5 text-center">
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border-0 h-100 d-flex align-items-center gap-3">
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #fff7ed; color: #c2410c; width: 55px; height: 55px;">
                <i class="bi bi-clock-history fs-4"></i>
            </div>
            <div class="text-start">
                <h6 class="fw-bold mb-0">Dipanggang Segar</h6>
                <small class="text-muted">Fresh from the oven setiap pagi</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border-0 h-100 d-flex align-items-center gap-3">
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #fef2f2; color: #dc2626; width: 55px; height: 55px;">
                <i class="bi bi-heart-pulse-fill fs-4"></i>
            </div>
            <div class="text-start">
                <h6 class="fw-bold mb-0">Bahan Premium</h6>
                <small class="text-muted">Butter pilihan & cokelat murni</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 shadow-sm border-0 h-100 d-flex align-items-center gap-3">
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #f0fdf4; color: #16a34a; width: 55px; height: 55px;">
                <i class="bi bi-check-circle-fill fs-4"></i>
            </div>
            <div class="text-start">
                <h6 class="fw-bold mb-0">100% Halal & Higienis</h6>
                <small class="text-muted">Bersih, higienis, dan terpercaya</small>
            </div>
        </div>
    </div>
</section>

<!-- Section: BEST SELLER -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-uppercase small fw-bold" style="color: #7b3f00;">BEST SELLER</span>
            <h3 class="fw-bold mb-0" style="color: #2b1d14;">Kue Terlaris Kami</h3>
        </div>
        <a href="produk.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            Lihat Semua Produk <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden bg-white">
                    <img src="uploads/produk/<?= htmlspecialchars($row['gambar']); ?>" 
                         class="card-img-top" 
                         style="height: 220px; object-fit: cover;"
                         onerror="this.src='https://images.unsplash.com/photo-1535141192574-5d4897c13136?auto=format&fit=crop&w=600&q=80'"
                         alt="<?= htmlspecialchars($row['nama']); ?>">
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge bg-light text-dark border align-self-start mb-2 px-2 py-1">
                            <?= htmlspecialchars($row['kategori']); ?>
                        </span>
                        <h5 class="card-title fw-bold text-dark mb-2"><?= htmlspecialchars($row['nama']); ?></h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= substr(htmlspecialchars($row['deskripsi']), 0, 90); ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Harga</small>
                                <span class="fw-bold text-danger fs-5">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                            </div>
                            <a href="detail.php?id=<?= $row['id']; ?>" class="btn text-white btn-sm px-3 fw-medium rounded-pill" style="background-color: #7b3f00;">
                                Detail & Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Section: Banner Pemesanan / Kontak Cepat -->
<section class="p-4 p-md-5 rounded-4 text-center text-white mb-4 shadow-sm" 
         style="background: linear-gradient(rgba(43, 29, 20, 0.85), rgba(43, 29, 20, 0.85)), url('https://images.unsplash.com/photo-1517433670267-08bbd4be890f?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;">
    <h3 class="fw-bold mb-2">Butuh Kue Khusus untuk Acara Anda?</h3>
    <p class="text-light opacity-75 mb-4" style="max-width: 600px; margin: 0 auto;">
        Kami menerima pesanan custom cake ulang tahun, hampers hari raya, kue pernikahan, dan katering kantor.
    </p>
    <a href="https://wa.me/6281234567890?text=Halo%20Sweet%20Delights,%20saya%20ingin%20tanya%20pesanan%20kue" 
       target="_blank" 
       class="btn btn-success btn-lg px-4 fw-semibold rounded-pill shadow">
        <i class="bi bi-whatsapp me-2"></i>Konsultasi via WhatsApp
    </a>
</section>

<?php require_once "includes/footer.php"; ?>