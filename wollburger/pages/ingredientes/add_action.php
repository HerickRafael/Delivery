<?php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$descricao      = $conn->real_escape_string($_POST['descricao']);
$custo_unitario = floatval($_POST['custo_unitario']);

$sql = "
  INSERT INTO ingredientes
    (descricao,custo_unitario)
  VALUES
    ('$descricao',$custo_unitario)
";
$conn->query($sql);
header('Location: list.php');
exit;
