<?php
// Remova (ou comente) temporariamente estas linhas:
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . "/includes/init.php";
require_once __DIR__ . "/includes/conexao.php";

// Se já estiver logado, redireciona
if (isset($_SESSION['admin_id'])) {
    header('Location: /wollburger/pages/vendas/list.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $erro = 'Preencha usuário e senha.';
    } else {
        $res = $conn->query("
          SELECT id,password 
          FROM admins 
          WHERE username='$username' 
          LIMIT 1
        ");

        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id']   = $row['id'];
                $_SESSION['admin_user'] = $username;
                header('Location: /wollburger/pages/vendas/list.php');
                exit;
            } else {
                $erro = 'Usuário ou senha inválidos.';
            }
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login | WollBurger</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
    <h1 class="text-2xl font-bold mb-6 text-center">WollBurger – Faça Login</h1>
    <?php if ($erro): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>
    <form method="post" action="login.php" class="space-y-4">
      <div>
        <label class="block text-gray-700">Usuário</label>
        <input type="text" name="username"
               class="w-full border p-2 rounded"
               placeholder="Digite seu usuário" required>
      </div>
      <div>
        <label class="block text-gray-700">Senha</label>
        <input type="password" name="password"
               class="w-full border p-2 rounded"
               placeholder="Digite sua senha" required>
      </div>
      <div class="flex justify-end">
        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
          Entrar
        </button>
      </div>
    </form>
  </div>
</body>
</html>
