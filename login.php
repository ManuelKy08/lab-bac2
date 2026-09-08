<?php
// ============================================================
// LAB BAC #2 - Login (Tugas Kuliah) — kikikokok
// KERENTANAN baru (beda dari lab #1):
//  - otentikasi memakai MUDAH-TERUJI cookie pengguna (trust cookie),
//    bukan sesi server yang aman. Cookie bisa dimodifikasi user.
// ============================================================
require_once __DIR__ . '/includes/koneksi.php';

$galat = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = (string)($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $st = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $st->execute([$username]);
    $user = $st->fetch();

    if ($user && $user['password'] === $password) {
        // BUG: login dibuktikan oleh COOKIE yang mudah dipalsukan, bukan SESSION.
        setcookie('login_user', (string)$user['id'], 0, '/');
        setcookie('login_role', $user['role'], 0, '/');
        header('Location: index.php');
        exit;
    }
    $galat = 'Username atau password salah.';
}

$judul = 'LAB BAC #2 · Log Masuk';
require_once __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:400px">
  <h2>🔐 Log Masuk</h2>
  <?php if ($galat): ?><div class="danger"><?= e($galat) ?></div><?php endif; ?>
  <form method="post">
    <label>Username</label>
    <input name="username" required>
    <label>Password</label>
    <input name="password" type="password" required>
    <button class="btn">Masuk</button>
  </form>
  <p class="muted">Akun demo: <code>admin/admin123</code> · <code>staff/staff123</code> · <code>budi/budi123</code>·
  Pakai <b>DevTools</b> untuk melihat cookie <code>login_user</code> & <code>login_role</code> 😉</p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>