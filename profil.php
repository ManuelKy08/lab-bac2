<?php
// ============================================================
// LAB BAC #2 - Profil & Mass Assignment (Tugas Kuliah) — kikikokok
// KERENTANAN mass assignment: update kolom diambil langsung
// dari seluruh array $_POST (.e. role), tanpa allowlist.
// ============================================================
require_once __DIR__ . '/includes/koneksi.php';
$me = cek_login();
if (!$me) {
    header('Location: index.php');
    exit;
}
$judul = 'LAB BAC #2 · Profil';
require_once __DIR__ . '/includes/header.php';

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // BUG: seluruh POST dipakai membangun update — termasuk 'role'!
    $set  = [];
    $args = [];
    foreach (['nama', 'telepon', 'alamat'] as $k) {
        $set[]  = "$k = ?";
        $args[] = trim((string)($_POST[$k] ?? ''));
    }
    $set[]  = 'role = ?';
    $args[] = (string)($_POST['role'] ?? 'pelanggan'); // tidak semestinya dari user
    $args[] = (int)$me['id'];

    $sql = 'UPDATE users SET ' . implode(', ', $set) . ' WHERE id = ?';
    $pdo->prepare($sql)->execute($args);
    $pesan = 'Profil diperbarui (termasuk role dari form!).';
}

$st = $pdo->prepare('SELECT * FROM users WHERE id=?');
$st->execute([(int)$me['id']]);
$u = $st->fetch();
?>
<div class="card" style="max-width:520px">
  <h2>👤 Profil Saya</h2>
  <?php if ($pesan): ?><div class="notice"><?= e($pesan) ?></div><?php endif; ?>
  <form method="post">
    <label>Nama</label>
    <input name="nama" value="<?= e($u['nama']) ?>" required>
    <div class="grid2">
      <div><label>Telepon</label><input name="telepon" value="<?= e($u['telepon']) ?>"></div>
      <div><label>Role (di aplikasi asli tersembunyi; di sini terlihat 😈)</label>
        <select name="role">
          <option><?= e($u['role']) ?></option>
          <option>admin</option><option>staff</option><option>pelanggan</option>
        </select></div>
    </div>
    <label>Alamat</label>
    <textarea name="alamat" rows="2"><?= e($u['alamat']) ?></textarea>
    <button class="btn">Simpan</button>
  </form>
  <p class="muted" style="margin-top:8px">
    Trik: kolom tersembunyi di form bukanlah keharusan — kamu boleh <b>menambahkan sendiri</b>
    parameter <code>role=admin</code> di permintaan POST; server memercayainya bulat-bulat.
  </p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>