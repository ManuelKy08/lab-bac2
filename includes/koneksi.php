<?php
// ============================================================
// LAB BAC #2 - Koneksi Database (Tugas Kuliah)
// Nama: kikikokok
// ============================================================

declare(strict_types=1);

$dsn = 'mysql:host=127.0.0.1;dbname=lab_bac2;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

function e(mixed $v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function cek_login(): ?array
{
    // BUG: otorisasi mengandalkan cookie yang mudah dipalsukan.
    global $pdo;
    $uid = (int)($_COOKIE['login_user'] ?? 0);
    if ($uid <= 0) {
        return null;
    }
    $stmt = $pdo->prepare('SELECT id,username,nama,role FROM users WHERE id=?');
    $stmt->execute([$uid]);
    return $stmt->fetch() ?: null;
}