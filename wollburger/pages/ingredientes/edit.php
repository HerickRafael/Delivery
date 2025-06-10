<?php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo "Objeto não encontrado!";
    exit;
}
$res = $conn->query("SELECT * FROM ingredientes WHERE id = $id");
if (!$res || $res->num_rows === 0) {
    echo "Objeto não encontrado!";
    exit;
}
$row = $res->fetch_assoc();
?>
<form method="post" action="update_action.php" class="space-y-4">
  <input type="hidden" name="id" value="<?= $row['id'] ?>"/>
  <div>
    <label class="block mb-1"><span class="text-gray-700">Descrição</span>
      <input name="descricao" type="text" 
             value="<?= htmlspecialchars($row['descricao']) ?>"
             class="w-full p-2 border rounded" required/>
    </label>
  </div>
  <div>
    <label class="block mb-1"><span class="text-gray-700">Custo Unitário (R$)</span>
      <input name="custo_unitario" type="number" step="0.01"
             value="<?= number_format($row['custo_unitario'],2,'.','') ?>"
             class="w-full p-2 border rounded" required/>
    </label>
  </div>
  <div class="flex justify-end space-x-2">
    <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
      Salvar
    </button>
    <button type="button" id="cancelEdit" class="text-gray-600 hover:underline">
      Cancelar
    </button>
  </div>
</form>
<script>
  document.getElementById('cancelEdit').onclick = () => {
    document.getElementById('drawerOverlay').click();
  };
</script>
