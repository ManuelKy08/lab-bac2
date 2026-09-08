<?php
// ============================================================
// LAB BAC #2 - Aksi Admin #2 (Tugas Kuliah) — kikikokok
// KERENTANAN: pemikiran "aman karena yang menghapus adalah
// endpoint POST" — padahal tanpa otorisasi SAMA SEKALI.
// (Method-based access control salah kaprah.)
// ============================================================
$base = '../';
$judul = 'LAB BAC #2 · Laporan Tahunan';
require_once __DIR__ . '/../includes/header.php';

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = (string)($_POST['akun'] ?? '');
    // BUG: tidak ada cek login/role — siapa pun yang POST, dihapus!
    $hapus = $pdo->prepare('DELETE FROM faktur WHERE kode=?');
    $hapus->execute([$kode]);
    $pesan = "Faktur $kode DIHAPUS lewat POST — tanpa verifikasi pengguna.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // "Keamanan" ala kadarnya: konon DELETE lebih 'sulit' — ternyata salah.
    $in = file_get_contents('php://input');
    parse_str((string)$in, $parsed);
    $kode = (string)($parsed['akun'] ?? '');
    $pdo->prepare('DELETE FROM faktur WHERE kode=?')->execute([$kode]);
    $pesan = "DELETE request mengeksekusi penghapusan faktur $kode!";
}

$daftar = $pdo->query('SELECT kode,user_id,total,status FROM faktur')->fetchAll();
?>
<div class="card">
  <h2>📊 Laporan Tahunan (Panel #2)</h2>
  <?php if ($pesan): ?><div class="danger"><?= e($pesan) ?></div><?php endif; ?>
  <table class="tbl">
    <thead><tr><th>Kode</th><th>Pemilik (user_id)</th><th>Total</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($daftar as $f): ?>
      <tr><td><?= e($f['kode']) ?></td><td><?= (int)$f['user_id'] ?></td>
          <td>Rp <?= number_format((int)$f['total'],0,',','.') ?></td>
          <td><?= e($f['status']) ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <form method="post" style="margin-top:10px">
    <label>Hapus faktur (kode)</label>
    <input name="akun" value="FK-1005" required>
    <button class="btn merah">Hapus via POST</button>
  </form>
  <p class="muted">🧨 Coba juga metode lain —<br>
  <code>curl -X POST -d "akun=FK-1004" http://127.0.0.1:8093/admin/aksi.php</code><br>
  <code>curl -X DELETE -d "akun=FK-1003" http://127.0.0.1:8093/admin/aksi.php</code></p>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>