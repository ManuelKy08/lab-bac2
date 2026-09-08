<?php
// ============================================================
// LAB BAC #2 - Beranda (Tugas Kuliah) — kikikokok
// ============================================================
require_once __DIR__ . '/includes/header.php';
?>
<div class="card">
  <h2>🧩 Latihan Praktikum — Broken Access Control (Bagian 2)</h2>
  <p>Berbeda dari Lab #1 yang fokus IDOR & panel terbuka.
  Latihan ini mengeksplorasi <b>pola baru</b> penolakan akses yang <b>salah implementasi</b>:</p>

  <table class="tbl">
    <thead><tr><th>#</th><th>Latihan</th><th>Pola Kerentanan</th></tr></thead>
    <tbody>
      <tr><td>1</td>
          <td>Login → buka <a href="admin/panel.php">admin/panel.php</a> tanpa autentikasi, tambah header <code>Referer: https://lantai1.admin</code></td>
          <td>Authorization berbasis Referer (spoofable header)</td></tr>
      <tr><td>2</td>
          <td>Ubah cookie <code>login_role=admin</code> lewat DevTools → akses panel</td>
          <td>Authorization berbasis cookie (client-side trust)</td></tr>
      <tr><td>3</td>
          <td>POST ke <code>admin/aksi.php</code> tanpa login → aksi tetap jalan</td>
          <td>Method-based access control — POST tanpa otorisasi</td></tr>
      <tr><td>4</td>
          <td><a href="profil.php">profil.php</a> POST + sisipkan <code>role=admin</code></td>
          <td>Mass assignment / object attribute injection</td></tr>
      <tr><td>5</td>
          <td><a href="export.php?ids[]=3&ids[]=4">export.php?ids[]=</a> → data pengguna lain</td>
          <td>IDOR batch — request massal mengabaikan hak akses</td></tr>
      <tr><td>6</td>
          <td><a href="faktur.php?faktur=2&alamat=HACKED">faktur.php?faktur=2&alamat=…</a></td>
          <td>IDOR write — ubah milik orang lain</td></tr>
      <tr><td>7</td>
          <td>Buka <a href="files/data-pelanggan.txt">files/data-pelanggan.txt</a> tanpa login</td>
          <td>Static file disclosure</td></tr>
      <tr><td>8</td>
          <td>Buka <a href="tools/backup-x7f3.php">tools/backup-x7f3.php</a></td>
          <td>Security by obscurity / forced browsing</td></tr>
    </tbody>
  </table>

  <div class="notice" style="margin-top:12px">
    Status cookie kamu saat ini:<br>
    <code>login_user = <?= e($_COOKIE['login_user'] ?? '(kosong)') ?></code> ·
    <code>login_role = <?= e($_COOKIE['login_role'] ?? '(kosong)') ?></code> ·
    <b>Role server</b> = <?= $me ? e($me['role']) : 'tidak login' ?>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>