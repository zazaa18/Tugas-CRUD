<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setFlash($tipe, $pesan) {
    $_SESSION['flash'] = [
        'tipe' => $tipe,
        'pesan' => $pesan
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $tipe = $_SESSION['flash']['tipe'];
        $pesan = $_SESSION['flash']['pesan'];
        unset($_SESSION['flash']);
        return "<div class='alert alert-{$tipe} alert-dismissible fade show' role='alert'>
                    {$pesan}
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
    }
    return "";
}