<?php
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

// Ambil ID produk dari parameter URL
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    setFlash('danger', 'ID produk tidak valid!');
    header("Location: produk.php");
    exit;
}

// Ambil data produk dari database berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id LIMIT 1");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    setFlash('danger', 'Produk tidak ditemukan!');
    header("Location: produk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Sweet Delights</title>
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
        .card-form {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            max-width: 780px;
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
            <a href="logout.php" class="btn btn-sm btn-outline-danger" title="Logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4 p-md-5">
        <div class="mb-4">
            <a href="produk.php" class="text-decoration-none text-muted small">&larr; Kembali ke Kelola Produk</a>
            <h3 class="fw-bold mt-2 mb-1">Edit Menu Kue</h3>
            <p class="text-muted small">Perbarui data produk untuk disimpan ke katalog toko.</p>
        </div>

        <?= getFlash(); ?>

        <div class="card card-form bg-white p-4 p-md-5">
            <!-- Form Edit dengan target action=edit -->
            <form action="proses_produk.php?action=edit" method="POST" enctype="multipart/form-data">
                
                <!-- PENTING: ID produk & Foto Lama tersimpan di hidden input -->
                <input type="hidden" name="id" value="<?= $produk['id']; ?>">
                <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($produk['gambar']); ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Produk Kue <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($produk['nama']); ?>" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori Menu <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Cake" <?= $produk['kategori'] === 'Cake' ? 'selected' : ''; ?>>Cake & Tart</option>
                            <option value="Pastry" <?= $produk['kategori'] === 'Pastry' ? 'selected' : ''; ?>>Pastry & Croissant</option>
                            <option value="Bread" <?= $produk['kategori'] === 'Bread' ? 'selected' : ''; ?>>Roti & Bolu</option>
                            <option value="Cookies" <?= $produk['kategori'] === 'Cookies' ? 'selected' : ''; ?>>Cookies & Kue Kering</option>
                            <option value="Dessert" <?= $produk['kategori'] === 'Dessert' ? 'selected' : ''; ?>>Dessert Box</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="harga" class="form-control" value="<?= $produk['harga']; ?>" min="500" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Produk</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img id="imgPreview" src="../uploads/produk/<?= htmlspecialchars($produk['gambar']); ?>" 
                             class="rounded-3 border shadow-sm" width="80" height="80" style="object-fit: cover;"
                             onerror="this.src='https://images.unsplash.com/photo-1535141192574-5d4897c13136?auto=format&fit=crop&w=150&q=80'">
                        <small class="text-muted">Biarkan input di bawah kosong jika tidak ingin mengganti foto saat ini.</small>
                    </div>
                    <input type="file" name="gambar" id="inputFoto" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage(event)">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Deskripsi Kue</label>
                    <textarea name="deskripsi" rows="4" class="form-control"><?= htmlspecialchars($produk['deskripsi']); ?></textarea>
                </div>

                <div class="d-flex align-items-center gap-2 pt-2 border-top">
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Perbarui Produk
                    </button>
                    <a href="produk.php" class="btn btn-light border px-4 py-2">Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const imgPreview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgPreview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>