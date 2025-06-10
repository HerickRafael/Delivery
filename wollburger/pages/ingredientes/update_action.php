<?php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id             = intval($_POST['id']);
$descricao      = $conn->real_escape_string($_POST['descricao']);
$custo_unitario = floatval($_POST['custo_unitario']);

$sql = "
  UPDATE ingredientes SET
    descricao = '$descricao',
    custo_unitario = $custo_unitario
  WHERE id = $id
";
$conn->query($sql);
header('Location: list.php');
exit;
