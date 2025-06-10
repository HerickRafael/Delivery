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

$sql = "SELECT * FROM ingredientes $where ORDER BY descricao ASC";
$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {
    echo '<tr>';
    echo "<td class=\"px-4 py-2\">{$row['id']}</td>";
    echo "<td class=\"px-4 py-2\">".htmlspecialchars($row['descricao'])."</td>";
    echo "<td class=\"px-4 py-2 text-right\">R\$ ".number_format($row['custo_unitario'],2,',','.')."</td>";
    echo '<td class="px-4 py-2 text-center space-x-2">';
    echo "  <button class=\"editBtn text-indigo-600 hover:text-indigo-900\" data-id=\"{$row['id']}\">Editar</button>";
    echo "  <a href=\"delete.php?id={$row['id']}\" onclick=\"return confirm('Excluir este ingrediente?')\" class=\"text-red-600 hover:text-red-900\">Excluir</a>";
    echo '</td>';
    echo '</tr>';
}
