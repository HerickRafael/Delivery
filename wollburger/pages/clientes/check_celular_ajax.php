<?php
// pages/clientes/check_celular_ajax.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit;
}
header('Content-Type: application/json; charset=UTF-8');

$celular = $conn->real_escape_string($_GET['celular'] ?? '');
$idAtual = intval($_GET['id'] ?? 0);

if (empty($celular)) {
    echo json_encode(['exists' => false]);
    exit;
}

$sql = "SELECT id, nome FROM clientes WHERE celular = '$celular' AND id != $idAtual LIMIT 1";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo json_encode([
        'exists' => true,
        'nome'   => $row['nome'],
        'id'     => $row['id']
    ]);
} else {
    echo json_encode(['exists' => false]);
}
