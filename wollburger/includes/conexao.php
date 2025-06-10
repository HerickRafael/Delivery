<?php
$dbHost    = 'localhost';
$dbUsuario = 'root';
$dbSenha   = '';
$dbNome    = 'sistema_hamburgueria';

$conn = new mysqli($dbHost, $dbUsuario, $dbSenha, $dbNome);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
