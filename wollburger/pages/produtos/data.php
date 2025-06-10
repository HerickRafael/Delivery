<?php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

$search = $_GET['search'] ?? '';
$field  = $_GET['field']  ?? '';
$allowed = ['descricao'];

$where = '';
if ($search !== '' && in_array($field, $allowed)) {
    $s = $conn->real_escape_string($search);
    $where = " WHERE `$field` LIKE '%$s%'";
}

$sql = "SELECT * FROM produtos $where ORDER BY descricao ASC";
$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {
    // Opcionalmente você pode exibir custo total calculado somando ingredientes
    // mas aqui mostraremos apenas o que está na própria tabela produtos.
    echo '<tr>';
    echo "<td class=\"px-4 py-2\">{$row['id']}</td>";
    echo "<td class=\"px-4 py-2\">".htmlspecialchars($row['descricao'])."</td>";
    echo "<td class=\"px-4 py-2 text-right\">R\$ ".number_format($row['preco_sugerido'],2,',','.')."</td>";
    echo "<td class=\"px-4 py-2 text-right\">R\$ ".number_format($row['preco_ifood'],2,',','.')."</td>";
    echo "<td class=\"px-4 py-2 text-right\">R\$ ".number_format($row['preco_praticado'],2,',','.')."</td>";
    echo "<td class=\"px-4 py-2 text-right\">R\$ ".number_format($row['custo_seguranca'],2,',','.')."</td>";
    echo '<td class="px-4 py-2 text-center space-x-2">';
    echo "  <button class=\"editBtn text-indigo-600 hover:text-indigo-900\" data-id=\"{$row['id']}\">Editar</button>";
    echo "  <a href=\"delete.php?id={$row['id']}\" onclick=\"return confirm('Excluir este produto?')\" class=\"text-red-600 hover:text-red-900\">Excluir</a>";
    echo '</td>';
    echo '</tr>';
}
