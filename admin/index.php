<?php
session_start();

// Proteksi sesi admin: jika belum login, alihkan ke auth/login.php
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

// Ambil statistik data
$q_total = mysqli_query($conn, "SELECT COUNT(*) as total, COALESCE(AVG(harga), 0) as rata_harga FROM produk");
$stat = mysqli_fetch_assoc($q_total);
$total_produk = $stat['total'] ?? 0;
$rata_harga = round($stat['rata_harga'] ?? 0);

$q_kat = mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) as total FROM produk");
$kategori_unik = mysqli_fetch_assoc($q_kat)['total'] ?? 0;

// Ambil 4 produk terbaru
$q_terbaru = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sweet Delights</title>
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
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar Navigasi -->
    <div class="sidebar p-3 text-white d-none d-lg-flex flex-column flex-shrink-0">
        <div class="d-flex align-items-center gap-2 mb-4 px-2 pt-2">
            <span class="fs-4 text-warning"><i class="bi bi-cake2-fill"></i></span>
            <span class="fs-5 fw-bold text-white tracking-wide">Sweet Delights</span>
        </div>
        <hr class="border-secondary mb-4 opacity-25">
        <ul class="nav flex-column gap-2 mb-auto">
            <li>
                <a href="index.php" class="nav-link-custom active"><i class="bi bi-grid-fill"></i> Dashboard</a>
            </li>
            <li>
                <a href="produk.php" class="nav-link-custom"><i class="bi bi-box-seam-fill"></i> Kelola Produk</a>
            </li>
            <li>
                <a href="tambah.php" class="nav-link-custom"><i class="bi bi-plus-circle-fill"></i> Tambah Menu</a>
            </li>
            <li>
                <a href="../index.php" target="_blank" class="nav-link-custom"><i class="bi bi-eye"></i> Lihat Web Toko</a>
            </li>
        </ul>
        <hr class="border-secondary opacity-25">
        <div class="d-flex align-items-center justify-content-between px-2">
            <div class="small">
                <div class="fw-bold"><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin'); ?></div>
                <div class="text-secondary small">Online</div>
            </div>
            <!-- Tombol Logout Mengarah ke logout.php -->
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
                <a href="produk.php" class="btn btn-sm btn-outline-dark">Produk</a>
                <a href="logout.php" class="btn btn-sm btn-danger" onclick="return confirm('Keluar dari panel admin?');"><i class="bi bi-power"></i></a>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold mb-1">Halo, <?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin'); ?> 👋</h3>
                <p class="text-muted mb-0">Ringkasan operasional dan katalog toko kue hari ini.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="tambah.php" class="btn btn-warning fw-semibold shadow-sm px-3"><i class="bi bi-plus-lg me-1"></i> Tambah Produk</a>
                <a href="../index.php" target="_blank" class="btn btn-light border fw-semibold"><i class="bi bi-box-arrow-up-right me-1"></i> Buka Toko</a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="row g-4 mb-5">
            <div class="col-sm-6 col-xl-4">
                <div class="card stat-card bg-white p-3 p-xl-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">TOTAL MENU AKTIF</span>
                            <h2 class="fw-bold mt-2 mb-0"><?= $total_produk; ?></h2>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-basket-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card stat-card bg-white p-3 p-xl-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">KATEGORI KUE</span>
                            <h2 class="fw-bold mt-2 mb-0"><?= $kategori_unik; ?></h2>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-tag-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xl-4">
                <div class="card stat-card bg-white p-3 p-xl-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">RATA-RATA HARGA</span>
                            <h2 class="fw-bold mt-2 mb-0">Rp <?= number_format($rata_harga, 0, ',', '.'); ?></h2>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Items Table Preview -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="fw-bold mb-0">Produk Terbaru</h5>
                <a href="produk.php" class="text-decoration-none fw-semibold small text-warning">Lihat Semua Data &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                        <tr>
                            <th class="ps-4">PRODUK</th>
                            <th>KATEGORI</th>
                            <th>HARGA</th>
                            <th class="text-end pe-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($q_terbaru && mysqli_num_rows($q_terbaru) > 0): ?>
                            <?php while ($item = mysqli_fetch_assoc($q_terbaru)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="../uploads/produk/<?= htmlspecialchars($item['gambar']); ?>" class="rounded-3" width="46" height="46" style="object-fit:cover;" onerror="this.src='https://images.unsplash.com/photo-1535141192574-5d4897c13136?auto=format&fit=crop&w=100&q=80'">
                                            <div class="fw-bold"><?= htmlspecialchars($item['nama']); ?></div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['kategori']); ?></span></td>
                                    <td class="fw-semibold text-danger">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                                    <td class="text-end pe-4">
                                        <a href="edit.php?id=<?= $item['id']; ?>" class="btn btn-sm btn-light border me-1"><i class="bi bi-pencil"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada produk.</td></tr>
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