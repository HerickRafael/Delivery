<?php
// pages/clientes/add_action.php
session_start();
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add.php');
    exit;
}

// Sanitização e trim dos campos
$nome            = trim($conn->real_escape_string($_POST['nome'] ?? ''));
$cpf             = trim($conn->real_escape_string($_POST['cpf'] ?? ''));
$data_nascimento = trim($conn->real_escape_string($_POST['data_nascimento'] ?? ''));
$celular         = trim($conn->real_escape_string($_POST['celular'] ?? ''));
$cep             = trim($conn->real_escape_string($_POST['cep'] ?? ''));
$uf              = trim($conn->real_escape_string($_POST['uf'] ?? ''));
$cidade          = trim($conn->real_escape_string($_POST['cidade'] ?? ''));
$bairro          = trim($conn->real_escape_string($_POST['bairro'] ?? ''));
$endereco        = trim($conn->real_escape_string($_POST['endereco'] ?? ''));
$numero          = trim($conn->real_escape_string($_POST['numero'] ?? ''));
$complemento     = trim($conn->real_escape_string($_POST['complemento'] ?? ''));

// Validação básica de campos obrigatórios
if (empty($nome) || empty($cpf) || empty($data_nascimento) || empty($celular) || empty($cep) || empty($uf) || empty($cidade) || empty($bairro) || empty($endereco) || empty($numero)) {
    $_SESSION['erro_msg'] = 'Preencha todos os campos obrigatórios.';
    header('Location: add.php');
    exit;
}

// 1) Verifica duplicidade de CPF
$sql_check_cpf = "SELECT id FROM clientes WHERE cpf = '$cpf' LIMIT 1";
$res_cpf = $conn->query($sql_check_cpf);
if ($res_cpf && $res_cpf->num_rows > 0) {
    $_SESSION['erro_msg'] = 'CPF já cadastrado. Informe outro.';
    header('Location: add.php');
    exit;
}

// 2) Verifica duplicidade de Celular
$sql_check_cel = "SELECT id FROM clientes WHERE celular = '$celular' LIMIT 1";
$res_cel = $conn->query($sql_check_cel);
if ($res_cel && $res_cel->num_rows > 0) {
    $_SESSION['erro_msg'] = 'Celular já cadastrado. Informe outro.';
    header('Location: add.php');
    exit;
}

// 3) Se não encontrou duplicados, prossegue com INSERT
$sql = "
  INSERT INTO clientes (
    nome, cpf, data_nascimento, celular, cep, uf,
    cidade, bairro, endereco, numero, complemento
  ) VALUES (
    '$nome', '$cpf', '$data_nascimento', '$celular',
    '$cep', '$uf', '$cidade', '$bairro', '$endereco',
    '$numero', '$complemento'
  )
";

if (!$conn->query($sql)) {
    // Se der erro no INSERT, redireciona de volta com mensagem
    $_SESSION['erro_msg'] = 'Erro ao inserir cliente: ' . $conn->error;
    header('Location: add.php');
    exit;
}

// Sucesso: redireciona para a lista
header('Location: list.php');
exit;
