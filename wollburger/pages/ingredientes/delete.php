<?php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $conn->query("DELETE FROM ingredientes WHERE id = $id");
}
header('Location: list.php');
exit;
