<?php
// pages/vendas/delete.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$sql = "DELETE FROM vendas WHERE id = $id";
if (!$conn->query($sql)) {
    die("Erro ao excluir venda: " . $conn->error);
}

header('Location: list.php');
exit;
