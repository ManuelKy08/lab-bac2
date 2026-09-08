<?php
// Logout — LAB BAC #2 (kikikokok)
require_once __DIR__ . '/includes/header.php';
setcookie('login_user', '', 0, '/');
setcookie('login_role', '', 0, '/');
unset($_COOKIE['login_user'], $_COOKIE['login_role']);
header('Location: index.php');
exit;