<?php
// ============================================================
// LAB BAC #2 - Faktur (IDOR write) (Tugas Kuliah) — kikikokok
// KERENTANAN: mengubah alamat kirim faktur milik PENGUNJUNG LAIN.
// (IDOR dengan operasi tulis — insecure direct object reference)
// ============================================================
require_once __DIR__ . '/includes/koneksi.php';
$me = cek_login();
if (!$me) {
    header('Location: index.php');
    exit;
}
$judul = 'LAB BAC #2 · Faktur';
require_once __DIR__ . '/includes/header.php';

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faktur = (int)($_POST['faktur'] ?? 0);
    $alamat = trim((string)($_POST['alamat'] ?? ''));
    // BUG: TIDAK dicek apakah faktur ini milik $me — langsung update!
    $up = $pdo->prepare('UPDATE faktur SET alamat_kirim=? WHERE id=?');
    $up->execute([$alamat, $faktur]);
    $pesan = "Alamat kirim faktur #$faktur DIUBAH menjadi: " . htmlspecialchars($alamat);
}

$daftar = $pdo->prepare('SELECT f.*, u.nama AS pemilik FROM faktur f JOIN users u ON u.id=f.user_id ORDER BY f.id');
$daftar->execute();
$semua = $daftar->fetchAll();
?>
<div class="card">
  <h2>🚚 Kelola Alamat Pengiriman</h2>
  <?php if ($pesan): ?><div class="danger"><?= e($pesan) ?></div><?php endif; ?>
  <table class="tbl">
    <thead><tr><th>#</th><th>Kode</th><th>Pemilik</th><th>Alamat kirim</th></tr></thead>
    <tbody>
      <?php foreach ($semua as $f): ?>
      <tr>
        <td><?= (int)$f['id'] ?></td>
        <td><?= e($f['kode']) ?></td>
        <td><?= e($f['pemilik']) ?></td>
        <td><?= e($f['alamat_kirim']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <form method="post" style="margin-top:10px;max-width:480px">
    <div class="grid2">
      <div><label>ID faktur</label><input name="faktur" type="number" required></div>
      <div><label>Alamat kirim baru</label><input name="alamat" required></div>
    </div>
    <button class="btn">Ubah Alamat</button>
  </form>
  <p class="muted" style="margin-top:8px">
    Eksploit: ubah alamat <b>faktur milik orang lain</b> — misal
    <code>POST faktur=4 (punya riko) alamat=Rumah Penyerang</code>.
    Server tak pernah menanyakan "ini fakturmu bukan?"
  </p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>