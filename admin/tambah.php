<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi sesi admin: jika belum login alihkan ke login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";
require_once "../includes/flash.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Menu - Sweet Delights</title>
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
            <li><a href="produk.php" class="nav-link-custom"><i class="bi bi-box-seam-fill"></i> Kelola Produk</a></li>
            <li><a href="tambah.php" class="nav-link-custom active"><i class="bi bi-plus-circle-fill"></i> Tambah Menu</a></li>
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
        <!-- Topbar Mobile -->
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
            <h5 class="fw-bold mb-0">Admin Panel</h5>
            <div class="d-flex gap-2">
                <a href="produk.php" class="btn btn-sm btn-outline-dark">Produk</a>
                <a href="logout.php" class="btn btn-sm btn-danger" onclick="return confirm('Keluar dari panel admin?');"><i class="bi bi-power"></i></a>
            </div>
        </div>

        <div class="mb-4">
            <a href="produk.php" class="text-decoration-none text-muted small">&larr; Kembali ke Kelola Produk</a>
            <h3 class="fw-bold mt-2 mb-1">Tambah Menu Kue Baru</h3>
            <p class="text-muted small">Lengkapi data informasi produk untuk ditampilkan di etalase web toko.</p>
        </div>

        <?= getFlash(); ?>

        <!-- Form Tambah Produk -->
        <div class="card card-form bg-white p-4 p-md-5">
            <form action="proses_produk.php?action=tambah" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Produk Kue <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Triple Chocolate Cake" required autofocus>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori Menu <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Cake">Cake & Tart</option>
                            <option value="Pastry">Pastry & Croissant</option>
                            <option value="Bread">Roti & Bolu</option>
                            <option value="Cookies">Cookies & Kue Kering</option>
                            <option value="Dessert">Dessert Box</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="harga" class="form-control" placeholder="Contoh: 45000" min="500" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Produk</label>
                    <input type="file" name="gambar" id="inputFoto" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage(event)">
                    <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, WEBP. Maksimal ukuran gambar yang disarankan 2MB.</small>
                    
                    <!-- Preview Foto -->
                    <div class="mt-3 d-none" id="previewContainer">
                        <small class="text-muted d-block mb-1 fw-semibold">Pratinjau Gambar:</small>
                        <img id="imgPreview" src="" class="rounded-3 border shadow-sm" style="max-height: 160px; object-fit: cover;">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Deskripsi Kue</label>
                    <textarea name="deskripsi" rows="4" class="form-control" placeholder="Jelaskan rasa, tekstur, atau bahan spesial dari kue ini..."></textarea>
                </div>

                <div class="d-flex align-items-center gap-2 pt-2 border-top">
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2 shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Produk
                    </button>
                    <a href="produk.php" class="btn btn-light border px-4 py-2">Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
// Fungsi JavaScript untuk preview foto langsung saat dipilih
function previewImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById('previewContainer');
    const imgPreview = document.getElementById('imgPreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgPreview.src = e.target.result;
            previewContainer.classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.classList.add('d-none');
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>