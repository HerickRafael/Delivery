<?php
// pages/clientes/data.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

$search = $_GET['search'] ?? '';
$field  = $_GET['field']  ?? '';

// Colunas permitidas para filtro
$allowed = ['nome','cpf','celular','cidade']; // você pode ajustar quais colunas filtrar

$where = '';
if ($search !== '' && in_array($field, $allowed, true)) {
    $s = $conn->real_escape_string($search);
    $where = " WHERE `$field` LIKE '%$s%'";
}

// Agora pegamos também data_nascimento, cep, uf, etc., mas na listagem só exibiremos alguns
$sql = "
  SELECT
    id,
    nome,
    cpf,
    celular,
    data_nascimento,
    cep,
    uf,
    cidade,
    bairro,
    endereco,
    numero,
    complemento
  FROM clientes
  $where
  ORDER BY nome ASC
";

$res = $conn->query($sql);
if (!$res) {
    die("Erro ao buscar clientes: " . $conn->error);
}

while ($row = $res->fetch_assoc()) {
    // Formata data_nascimento como dd/mm/aaaa (se existir)
    $dtNasc = '';
    if (!empty($row['data_nascimento']) && $row['data_nascimento'] !== '0000-00-00') {
        $dtNasc = date('d/m/Y', strtotime($row['data_nascimento']));
    }

    // Monta a string resumida do endereço completo
    $enderecoCompleto = '';
    if (!empty($row['logradouro'] ?? '')) {
        // se houver logradouro separado, mas no nosso caso usamos “endereco” diretamente
    }
    $enderecoCompleto = 
        htmlspecialchars($row['cep']) . ' - ' .
        htmlspecialchars($row['logradouro'] ?? $row['endereco']) . ', ' .
        htmlspecialchars($row['numero']);
    if (!empty($row['complemento'])) {
        $enderecoCompleto .= ' (' . htmlspecialchars($row['complemento']) . ')';
    }
    $enderecoCompleto .= ' - ' . htmlspecialchars($row['bairro']) . ' - ' .
                         htmlspecialchars($row['cidade']) . '/' . htmlspecialchars($row['uf']);

    echo '<tr>';
    echo "<td class=\"px-4 py-2\">{$row['id']}</td>";
    echo "<td class=\"px-4 py-2\">".htmlspecialchars($row['nome'])."</td>";
    echo "<td class=\"px-4 py-2\">".htmlspecialchars($row['cpf'])."</td>";
    echo "<td class=\"px-4 py-2\">".htmlspecialchars($row['celular'])."</td>";
    echo "<td class=\"px-4 py-2\">$dtNasc</td>";
    echo "<td class=\"px-4 py-2\">$enderecoCompleto</td>";
    echo '<td class="px-4 py-2 text-center space-x-2">';
    echo "  <button class=\"editBtn text-indigo-600 hover:text-indigo-900\" data-id=\"{$row['id']}\">Editar</button>";
    echo "  <a href=\"delete.php?id={$row['id']}\" onclick=\"return confirm('Excluir este cliente?')\" class=\"text-red-600 hover:text-red-900\">Excluir</a>";
    echo '</td>';
    echo '</tr>';
}
