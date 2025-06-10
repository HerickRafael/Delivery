<?php
require_once("includes/conexao.php");

$username = 'admin';
$plainpw  = 'seu_password'; 
$hash     = password_hash($plainpw, PASSWORD_DEFAULT);

$sql = "
  INSERT INTO admins (username,password) 
  VALUES ('$username','$hash')
  ON DUPLICATE KEY UPDATE password='$hash'
";
$conn->query($sql);
echo "Administrador criado/atualizado. Use usuário '{$username}' e senha '{$plainpw}'.";
