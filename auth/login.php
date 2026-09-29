<?php
session_start();

// Jika admin sudah dalam kondisi login, langsung alihkan ke dashboard admin
if (isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - Toko Kue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdfaf6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .card-auth {
            max-width: 420px;
            width: 100%;
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        }
        .btn-warm {
            background-color: #c97a3e;
            border-color: #c97a3e;
            color: #ffffff;
        }
        .btn-warm:hover {
            background-color: #b26830;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="container p-3">
    <div class="card card-auth mx-auto p-4 p-md-5 bg-white">
        <div class="text-center mb-4">
            <div class="bg-light text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                <i class="bi bi-shield-lock-fill fs-2" style="color: #c97a3e;"></i>
            </div>
            <h4 class="fw-bold mb-1">Login Admin</h4>
            <p class="text-muted small">Kelola katalog dan menu toko kue</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 small" role="alert">
                <?= htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="admin" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-key"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-warm w-100 fw-bold py-2 shadow-sm mb-3">Masuk</button>
            
            <div class="text-center">
                <a href="../index.php" class="text-muted text-decoration-none small">&larr; Kembali ke Beranda Toko</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>