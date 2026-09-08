<?php
// ============================================================
// LAB BAC #2 - Export (IDOR batch) (Tugas Kuliah) — kikikokok
// KERENTANAN: menerima ARRAY id (ids[]) lalu mengekstrak data
// tanpa cek "apakah id itu milik pengirim". Request massal.
// ============================================================
require_once __DIR__ . '/includes/header.php';

$ids = $_GET['ids'] ?? [];

// BUG: tidak ada pengecekan kepemilikan — satu request banyak baris.
$baris = [];
if (is_array($ids)) {
    foreach ($ids as $id) {
        $id = (int)$id;
        if ($id > 0) {
            $st = $pdo->prepare('SELECT id,username,nama,role,telepon FROM users WHERE id=?');
            $st->execute([$id]);
            $r = $st->fetch();
            if ($r) {
                $baris[] = $r;
            }
        }
    }
}
?>
<div class="card">
  <h2>📦 Ekspor Daftar Pelanggan</h2>
  <?php if ($baris): ?>
    <div class="ok-box">Berhasil mengekstrak <?= count($baris) ?> baris — siapa pun bisa minta milik siapa pun.</div>
    <table class="tbl">
      <thead><tr><th>ID</th><th>Username</th><th>Nama</th><th>Role</th><th>Telepon</th></tr></thead>
      <tbody>
        <?php foreach ($baris as $r): ?>
        <tr><td><?= (int)$r['id'] ?></td><td><?= e($r['username']) ?></td><td><?= e($r['nama']) ?></td>
            <td><?= e($r['role']) ?></td><td><?= e($r['telepon']) ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="muted">Belum ada id dipilih. Coba: <code>export.php?ids[]=3</code></p>
  <?php endif; ?>
  <p class="muted" style="margin-top:10px">
    Batch IDOR: <code>export.php?ids[]=1&ids[]=2&ids[]=3&ids[]=4&ids[]=5</code> — sekali permintaan, seluruh akun bocor.
  </p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>