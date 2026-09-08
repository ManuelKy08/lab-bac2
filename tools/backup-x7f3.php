<?php
// "Alat cadangan" rahasia — hardening by obscurity.
// Nama file 'aneh' diharapkan tak akan tertebak. Ternyata bisa di-forced-browse.
if (($_COOKIE['login_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo "<h1>403</h1><p>Tidak berwenang.</p>";
    exit;
}
$pdo = new PDO('mysql:host=127.0.0.1;dbname=lab_bac2;charset=utf8mb4', 'root', '');
echo "<h2>Backup Pengguna (rahasia)</h2><pre>";
foreach ($pdo->query('SELECT id,username,password,nama,role FROM users') as $u) {
    echo htmlspecialchars(implode(' | ', $u)), "\n";
}
echo "</pre>";
