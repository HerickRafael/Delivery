<?php
// pages/clientes/edit.php
session_start();
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');

  $id = intval($_POST['id'] ?? 0);
  $nome = trim($_POST['nome'] ?? '');
  $cpf = trim($_POST['cpf'] ?? '');
  $celular = trim($_POST['celular'] ?? '');
  $cep = trim($_POST['cep'] ?? '');
  $uf = trim($_POST['uf'] ?? '');
  $cidade = trim($_POST['cidade'] ?? '');
  $bairro = trim($_POST['bairro'] ?? '');
  $endereco = trim($_POST['endereco'] ?? '');
  $numero = trim($_POST['numero'] ?? '');
  $complemento = trim($_POST['complemento'] ?? '');

  if (!$id || !$nome || !$cpf || !$celular || !$cep || !$uf || !$cidade || !$bairro || !$endereco || !$numero) {
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.']);
    exit;
  }

  function duplicado($script, $param, $valor, $id) {
    $url = $script . "?$param=" . urlencode($valor) . "&id=$id";
    $res = @file_get_contents($url);
    return json_decode($res, true);
  }

  $cpfCheck = duplicado('check_cpf_ajax.php', 'cpf', $cpf, $id);
  if ($cpfCheck['exists'] ?? false) {
    echo json_encode(['success' => false, 'message' => 'CPF já cadastrado.']);
    exit;
  }

  $celCheck = duplicado('check_celular_ajax.php', 'celular', $celular, $id);
  if ($celCheck['exists'] ?? false) {
    echo json_encode(['success' => false, 'message' => 'Celular já cadastrado.']);
    exit;
  }

  $sql = "UPDATE clientes SET nome=?, cpf=?, celular=?, cep=?, uf=?, cidade=?, bairro=?, endereco=?, numero=?, complemento=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssssssssi", $nome, $cpf, $celular, $cep, $uf, $cidade, $bairro, $endereco, $numero, $complemento, $id);

  if ($stmt->execute()) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar cliente.']);
  }
  exit;
}

$id = intval($_GET['id'] ?? 0);
$sql = "SELECT * FROM clientes WHERE id = $id";
$res = $conn->query($sql);
$cliente = $res->fetch_assoc();
?>

<form id="editForm" method="post" class="space-y-6">
  <input type="hidden" name="id" value="<?= $cliente['id'] ?>" />
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block mb-1">Nome Completo</label>
      <input name="nome" id="nomeEdit" type="text" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['nome']) ?>" required />
    </div>
    <div>
      <label class="block mb-1">CPF</label>
      <input name="cpf" id="cpfEdit" type="text" maxlength="14" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['cpf']) ?>" required />
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block mb-1">Data de Nascimento</label>
      <input name="data_nascimento" id="dataNascimentoEdit" type="date" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['data_nascimento']) ?>" required />
    </div>
    <div>
      <label class="block mb-1">Celular</label>
      <input name="celular" id="celularEdit" type="text" maxlength="15" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['celular']) ?>" required />
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block mb-1">CEP</label>
      <input name="cep" id="cepEdit" type="text" maxlength="9" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['cep']) ?>" required />
    </div>
    <div>
      <label class="block mb-1">UF</label>
      <select name="uf" id="ufEdit" class="w-full p-2 border rounded" required>
        <option value="">Selecione a UF</option>
        <?php
          $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
          foreach ($ufs as $ufOpc) {
            $sel = $cliente['uf'] === $ufOpc ? 'selected' : '';
            echo "<option value=\"$ufOpc\" $sel>$ufOpc</option>";
          }
        ?>
      </select>
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block mb-1">Cidade</label>
      <input name="cidade" id="cidadeEdit" type="text" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['cidade']) ?>" required />
    </div>
    <div>
      <label class="block mb-1">Bairro</label>
      <input name="bairro" id="bairroEdit" type="text" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['bairro']) ?>" required />
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-2">
      <label class="block mb-1">Endereço</label>
      <input name="endereco" id="enderecoEdit" type="text" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['endereco']) ?>" required />
    </div>
    <div>
      <label class="block mb-1">Número</label>
      <input name="numero" id="numeroEdit" type="text" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['numero']) ?>" required />
    </div>
  </div>
  <div>
    <label class="block mb-1">Complemento</label>
    <input name="complemento" id="complementoEdit" type="text" class="w-full p-2 border rounded" value="<?= htmlspecialchars($cliente['complemento']) ?>" />
  </div>
  <div class="text-right">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Salvar</button>
  </div>
</form>

<script>
function maskCPF(value) {
  return value.replace(/\D/g, "").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2");
}
function maskCelular(value) {
  return value.replace(/\D/g, "").replace(/(\d{2})(\d)/, "($1) $2").replace(/(\d{5})(\d{4})$/, "$1-$2");
}
function maskCEP(value) {
  return value.replace(/\D/g, "").replace(/(\d{5})(\d{3})$/, "$1-$2");
}

document.addEventListener('DOMContentLoaded', () => {
  const cpf = document.getElementById('cpfEdit');
  const celular = document.getElementById('celularEdit');
  const cep = document.getElementById('cepEdit');

  if (cpf) cpf.addEventListener('input', e => e.target.value = maskCPF(e.target.value));
  if (celular) celular.addEventListener('input', e => e.target.value = maskCelular(e.target.value));
  if (cep) cep.addEventListener('input', e => e.target.value = maskCEP(e.target.value));
});
</script>
