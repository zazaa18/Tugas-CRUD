<?php
session_start();

// Proteksi sesi admin: jika belum login, alihkan ke auth/login.php
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";
require_once "../includes/flash.php";

// Filter pencarian opsional
$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $query = "SELECT * FROM produk WHERE nama LIKE '%$search_safe%' OR kategori LIKE '%$search_safe%' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM produk ORDER BY id DESC";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Sweet Delights</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .sidebar {
            width: 260px;
            background: #1e1b18;
            min-height: 100vh;
        }
        .nav-link-custom {
            color: #cbd5e1;
            padding: 12px 18px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            background-color: #c97a3e;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar Desktop -->
    <div class="sidebar p-3 text-white d-none d-lg-flex flex-column flex-shrink-0">
        <div class="d-flex align-items-center gap-2 mb-4 px-2 pt-2">
            <span class="fs-4 text-warning"><i class="bi bi-cake2-fill"></i></span>
            <span class="fs-5 fw-bold text-white tracking-wide">Sweet Delights</span>
        </div>
        <hr class="border-secondary mb-4 opacity-25">
        <ul class="nav flex-column gap-2 mb-auto">
            <li><a href="index.php" class="nav-link-custom"><i class="bi bi-grid-fill"></i> Dashboard</a></li>
            <li><a href="produk.php" class="nav-link-custom active"><i class="bi bi-box-seam-fill"></i> Kelola Produk</a></li>
            <li><a href="tambah.php" class="nav-link-custom"><i class="bi bi-plus-circle-fill"></i> Tambah Menu</a></li>
            <li><a href="../index.php" target="_blank" class="nav-link-custom"><i class="bi bi-eye"></i> Lihat Web Toko</a></li>
        </ul>
        <hr class="border-secondary opacity-25">
        <div class="d-flex align-items-center justify-content-between px-2">
            <div class="small">
                <div class="fw-bold"><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin'); ?></div>
                <div class="text-secondary small">Online</div>
            </div>
            <!-- Tombol Logout Langsung ke logout.php -->
            <a href="logout.php" class="btn btn-sm btn-outline-danger" title="Logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4 p-md-5">
        <!-- Topbar Mobile -->
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
            <h5 class="fw-bold mb-0">Admin Panel</h5>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-sm btn-outline-dark">Dashboard</a>
                <a href="logout.php" class="btn btn-sm btn-danger" onclick="return confirm('Keluar dari panel admin?');"><i class="bi bi-power"></i></a>
            </div>
        </div>

        <?= getFlash(); ?>

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold mb-1">Daftar Produk Kue</h3>
                <p class="text-muted mb-0">Kelola informasi harga, deskripsi, dan foto menu toko.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="tambah.php" class="btn btn-warning fw-semibold shadow-sm px-3">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Menu Baru
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <!-- Filter Bar -->
            <div class="card-header bg-white p-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <form class="d-flex gap-2" method="GET" action="produk.php" style="max-width: 350px;">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" placeholder="Cari kue atau kategori..." value="<?= htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-dark">Cari</button>
                    </div>
                </form>
                <?php if ($search !== ''): ?>
                    <a href="produk.php" class="btn btn-sm btn-outline-secondary">Reset Pencarian</a>
                <?php endif; ?>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                        <tr>
                            <th class="ps-4" width="80">GAMBAR</th>
                            <th>NAMA PRODUK</th>
                            <th>KATEGORI</th>
                            <th>HARGA</th>
                            <th>DESKRIPSI</th>
                            <th class="text-center pe-4" width="130">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td class="ps-4">
                                        <img src="../uploads/produk/<?= htmlspecialchars($row['gambar']); ?>" class="rounded-3 border" width="55" height="55" style="object-fit:cover;" onerror="this.src='https://images.unsplash.com/photo-1535141192574-5d4897c13136?auto=format&fit=crop&w=100&q=80'">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1"><?= htmlspecialchars($row['kategori']); ?></span>
                                    </td>
                                    <td class="fw-bold text-danger">
                                        Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="text-muted small" style="max-width: 250px;">
                                        <?= substr(htmlspecialchars($row['deskripsi']), 0, 75); ?>...
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-inline-flex gap-1">
                                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-light border text-primary" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" title="Hapus"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    Tidak ada data produk yang ditemukan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>