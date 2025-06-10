<?php
// pages/vendas/data.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

// Colunas permitidas para filtro (vendas)
$allowed = [
  'id',
  'cliente_id',
  'data',
  'mes',
  'ano',
  'custo',
  'faturamento',
  'lucro'
];

$search = $_GET['search'] ?? '';
$field  = $_GET['field']  ?? '';

$where = '';
if ($search !== '' && in_array($field, $allowed, true)) {
    $s = $conn->real_escape_string($search);
    if ($field === 'cliente_id') {
        // filtrar pelo nome do cliente (JOIN)
        $where = " WHERE c.nome LIKE '%$s%'";
    } else {
        $where = " WHERE v.`$field` LIKE '%$s%'";
    }
}

// Montar SQL com JOIN em clientes
$sql = "
  SELECT
    v.id,
    v.cliente_id,
    c.nome AS cliente_nome,
    v.data,
    v.mes,
    v.ano,
    v.custo,
    v.faturamento,
    v.lucro,
    v.observacoes
  FROM vendas v
  LEFT JOIN clientes c ON (v.cliente_id = c.id)
  $where
  ORDER BY v.data DESC, v.id DESC
";

$res = $conn->query($sql);
if (!$res) {
    die("Erro ao buscar vendas: " . $conn->error);
}

while ($row = $res->fetch_assoc()) {
    // Ícone de observação
    $obsIcon = '';
    if (trim($row['observacoes']) !== '') {
        $textoObs = htmlspecialchars($row['observacoes']);
        $obsIcon  = "<span title=\"$textoObs\">⚠️</span>";
    }

    echo '<tr>';
    echo "<td class=\"px-4 py-2\">{$row['id']}</td>";
    echo "<td class=\"px-4 py-2\">".htmlspecialchars($row['cliente_nome'])."</td>";
    echo "<td class=\"px-4 py-2\">".date('d/m/Y', strtotime($row['data']))."</td>";
    echo "<td class=\"px-4 py-2\">{$row['mes']}</td>";
    echo "<td class=\"px-4 py-2\">{$row['ano']}</td>";
    echo "<td class=\"px-4 py-2 text-right\">R$ ".number_format($row['custo'], 2, ',', '.')."</td>";
    echo "<td class=\"px-4 py-2 text-right\">R$ ".number_format($row['faturamento'], 2, ',', '.')."</td>";
    echo "<td class=\"px-4 py-2 text-right\">R$ ".number_format($row['lucro'], 2, ',', '.')."</td>";
    echo "<td class=\"px-4 py-2 text-center\">$obsIcon</td>";

    // Coluna “Ações” (Editar / Excluir)
    echo '<td class="px-4 py-2 text-center space-x-2">';
    echo "  <button class=\"editBtn text-indigo-600 hover:text-indigo-900\" data-id=\"{$row['id']}\">Editar</button>";
    echo "  <a href=\"delete.php?id={$row['id']}\" onclick=\"return confirm('Excluir esta venda?')\" class=\"text-red-600 hover:text-red-900\">Excluir</a>";
    echo '</td>';

    // Coluna “Detalhes” (leva a view.php?id=…)
    echo '<td class="px-4 py-2 text-center">';
    echo "  <button class=\"detailBtn text-green-600 hover:text-green-900\" data-id=\"{$row['id']}\">Ver</button>";
    echo '</td>';

    echo '</tr>';
}
