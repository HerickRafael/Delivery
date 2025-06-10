<?php
date_default_timezone_set('America/Sao_Paulo');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentFile = basename($_SERVER['PHP_SELF']);
$allowList   = [
    'login.php',
    'logout.php'
];

if (!isset($_SESSION['admin_id'])) {
    if (!in_array($currentFile, $allowList, true)) {
        // Redireciona sempre para /wollburger/login.php, independentemente de onde você esteja
        header('Location: /wollburger/login.php');
        exit;
    }
}
