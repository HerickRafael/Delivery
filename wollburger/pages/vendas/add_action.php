<?php
// pages/vendas/add_action.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

// Sanitização básica
$data          = $conn->real_escape_string($_POST['data'] ?? '');
$produto_combo = $conn->real_escape_string($_POST['produto_combo'] ?? '');
$tipo          = $conn->real_escape_string($_POST['tipo'] ?? '');
$qtd           = intval($_POST['qtd'] ?? 1);
$valor_unit    = floatval($_POST['valor_unit'] ?? 0);
$custo         = floatval($_POST['custo'] ?? 0);
$observacoes   = $conn->real_escape_string($_POST['observacoes'] ?? '');

// Calcular faturamento e lucro
$faturamento = $qtd * $valor_unit;
$lucro       = $faturamento - $custo;

$sql = "
  INSERT INTO vendas 
    (data, produto_combo, tipo, qtd, valor_unit, faturamento, custo, lucro, observacoes)
  VALUES
    ('$data', '$produto_combo', '$tipo', $qtd, $valor_unit, $faturamento, $custo, $lucro, '$observacoes')
";

if (!$conn->query($sql)) {
    die("Erro ao inserir venda: " . $conn->error);
}

// Redirecionar de volta à listagem
header('Location: list.php');
exit;
