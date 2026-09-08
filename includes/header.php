<?php
// ============================================================
// LAB BAC #2 - Header bersama (Tugas Kuliah) — kikikokok
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/koneksi.php';
$me = cek_login();
$judul = $judul ?? 'LAB BAC #2 - Broken Access Control';
$base  = $base ?? '';   // '../' bila halaman dalam subfolder
$roleCookie = (string)($_COOKIE['role_pk'] ?? '');  // dipakai beberapa latihan
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?></title>
<link rel="stylesheet" href="<?= $base ?>assets/style.css">
</head>
<body>
<div class="topbar">
  <div class="wrap">
    <div class="logo">🧩 <b>LAB BAC&nbsp;#2</b> <span class="muted">— Broken Access Control</span></div>
    <nav>
      <a href="<?= $base ?>index.php">Beranda</a>
      <a href="<?= $base ?>profil.php">Profil</a>
      <?php if ($me): ?>
      <a href="<?= $base ?>logout.php">Keluar (<?= e($me['username']) ?>)</a>
      <?php else: ?>
      <a href="<?= $base ?>login.php">Masuk</a>
      <?php endif; ?>
    </nav>
  </div>
</div>
<div class="wrap">