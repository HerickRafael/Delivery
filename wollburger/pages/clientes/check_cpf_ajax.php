<?php
// pages/clientes/check_cpf_ajax.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit;
}
header('Content-Type: application/json; charset=UTF-8');

$cpf = $conn->real_escape_string($_GET['cpf'] ?? '');
$idAtual = intval($_GET['id'] ?? 0);

if (empty($cpf)) {
    echo json_encode(['exists' => false]);
    exit;
}

$sql = "SELECT id, nome FROM clientes WHERE cpf = '$cpf' AND id != $idAtual LIMIT 1";
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
