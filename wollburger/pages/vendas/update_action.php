<?php
// pages/vendas/update_action.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id            = intval($_POST['id'] ?? 0);
$data          = $conn->real_escape_string($_POST['data'] ?? '');
$produto_combo = $conn->real_escape_string($_POST['produto_combo'] ?? '');
$tipo          = $conn->real_escape_string($_POST['tipo'] ?? '');
$qtd           = intval($_POST['qtd'] ?? 1);
$valor_unit    = floatval($_POST['valor_unit'] ?? 0);
$custo         = floatval($_POST['custo'] ?? 0);
$observacoes   = $conn->real_escape_string($_POST['observacoes'] ?? '');

// Recalcular faturamento e lucro
$faturamento = $qtd * $valor_unit;
$lucro       = $faturamento - $custo;

$sql = "
  UPDATE vendas SET
    data           = '$data',
    produto_combo  = '$produto_combo',
    tipo           = '$tipo',
    qtd            = $qtd,
    valor_unit     = $valor_unit,
    faturamento    = $faturamento,
    custo          = $custo,
    lucro          = $lucro,
    observacoes    = '$observacoes'
  WHERE id = $id
";

if (!$conn->query($sql)) {
    die("Erro ao atualizar venda: " . $conn->error);
}

// Voltar para a lista
header('Location: list.php');
exit;
