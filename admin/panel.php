<?php
// ============================================================
// LAB BAC #2 - Panel Admin #1 (Tugas Kuliah) — kikikokok
// KERENTANAN: otorisasi hanya cek header "Referer" — header
// bisa dipalsukan total oleh penyerang.
// Bonus: bila kamu mengubah cookie login_role=admin pun,
// kebebasan akses alternatif ikut terbuka (lihat cek di bawah).
// ============================================================
$base = '../';
$judul = 'LAB BAC #2 · Panel Admin';

// Evaluasi otorisasi SEBELUM output apa pun agar kode 403 benar-benar terkirim.
$ref        = (string)($_SERVER['HTTP_REFERER'] ?? '');
$refererOk  = str_starts_with($ref, 'https://lantai1.admin');
$cookieRole = (string)($_COOKIE['login_role'] ?? '');
$tertolak   = !$refererOk && $cookieRole !== 'admin';

// Kode status dikirim SEBELUM header/HTML apa pun.
if ($tertolak) {
    http_response_code(403);
}

require_once __DIR__ . '/../includes/header.php';

if ($tertolak) {
    ?>
    <div class="card" style="max-width:540px">
      <h2>⛔ 403 — Panel Admin Ditolak</h2>
      <div class="danger">
        <b>Referer kamu:</b> <code><?= e($ref ?: '(kosong)') ?></code> (harus diawali <code>https://lantai1.admin</code>)
        <br><b>Cookie role:</b> <code><?= e($cookieRole ?: '(kosong)') ?></code>
      </div>
      <p class="muted">Cara lolos (coba 😈):<br>
        1) kirim header <code>Referer: https://lantai1.admin</code> saat membuka halaman ini (lihat cara curl di bawah),<br>
        2) atau set cookie <code>login_role=admin</code> dari halaman <a href="<?= $base ?>index.php">beranda</a>.
      </p>
      <pre class="muted">curl "http://127.0.0.1:8093/admin/panel.php" -e "https://lantai1.admin"</pre>
    </div>
    <?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$users = $pdo->query('SELECT id,username,password,nama,role,telepon FROM users')->fetchAll();
?>
<div class="card">
  <h2>🛠️ Panel Admin #1 — Daftar Pengguna</h2>
  <div class="ok-box">✅ Otorisasi lolos. Referer dianggap valid → data rahasia ditampilkan.</div>
  <table class="tbl">
    <thead><tr><th>ID</th><th>Username</th><th>Password</th><th>Nama</th><th>Role</th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= e($u['username']) ?></td>
        <td><code><?= e($u['password']) ?></code></td>
        <td><?= e($u['nama']) ?></td>
        <td><span class="badge <?= e($u['role']) ?>"><?= e($u['role']) ?></span></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>