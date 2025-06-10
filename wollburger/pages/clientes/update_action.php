<?php
// pages/clientes/update_action.php
session_start();
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id              = intval($_POST['id'] ?? 0);
$nome            = $conn->real_escape_string($_POST['nome'] ?? '');
$cpf             = $conn->real_escape_string($_POST['cpf'] ?? '');
$data_nascimento = $conn->real_escape_string($_POST['data_nascimento'] ?? '');
$celular         = $conn->real_escape_string($_POST['celular'] ?? '');
$cep             = $conn->real_escape_string($_POST['cep'] ?? '');
$uf              = $conn->real_escape_string($_POST['uf'] ?? '');
$cidade          = $conn->real_escape_string($_POST['cidade'] ?? '');
$bairro          = $conn->real_escape_string($_POST['bairro'] ?? '');
$endereco        = $conn->real_escape_string($_POST['endereco'] ?? '');
$numero          = $conn->real_escape_string($_POST['numero'] ?? '');
$complemento     = $conn->real_escape_string($_POST['complemento'] ?? '');

// 1) Verifica duplicidade de CPF (ignorando o próprio registro)
$sql_check_cpf = "
  SELECT id 
  FROM clientes 
  WHERE cpf = '$cpf' 
    AND id <> $id 
  LIMIT 1
";
$res_cpf = $conn->query($sql_check_cpf);
if ($res_cpf && $res_cpf->num_rows > 0) {
    $_SESSION['erro_msg'] = 'Outro cliente já possui esse CPF.';
    header("Location: edit.php?id={$id}");
    exit;
}

// 2) Verifica duplicidade de Celular (ignorando o próprio registro)
$sql_check_cel = "
  SELECT id 
  FROM clientes 
  WHERE celular = '$celular' 
    AND id <> $id 
  LIMIT 1
";
$res_cel = $conn->query($sql_check_cel);
if ($res_cel && $res_cel->num_rows > 0) {
    $_SESSION['erro_msg'] = 'Outro cliente já possui esse Celular.';
    header("Location: edit.php?id={$id}");
    exit;
}

// 3) Se não houver duplicatas, faz o UPDATE
$sql = "
  UPDATE clientes SET
    nome            = '$nome',
    cpf             = '$cpf',
    data_nascimento = '$data_nascimento',
    celular         = '$celular',
    cep             = '$cep',
    uf              = '$uf',
    cidade          = '$cidade',
    bairro          = '$bairro',
    endereco        = '$endereco',
    numero          = '$numero',
    complemento     = '$complemento'
  WHERE id = $id
";

if (!$conn->query($sql)) {
    die("Erro ao atualizar cliente: " . $conn->error);
}

header('Location: list.php');
exit;
