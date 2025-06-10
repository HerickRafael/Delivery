<?php
// pages/vendas/edit.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

// Precisamos dos mesmos arrays de produtos e combos para o select
$produtos = [];
$resP = $conn->query("SELECT id, descricao, preco_praticado, custo_receita FROM produtos");
while ($row = $resP->fetch_assoc()) {
    $produtos[] = [
        'id'             => $row['id'],
        'descricao'      => $row['descricao'],
        'preco_praticado'=> $row['preco_praticado'],
        'custo_receita'  => $row['custo_receita']
    ];
}

$combos = [];
$resC = $conn->query("SELECT id, descricao, preco_praticado, custo_receita FROM combos");
while ($row = $resC->fetch_assoc()) {
    $combos[] = [
        'id'             => $row['id'],
        'descricao'      => $row['descricao'],
        'preco_praticado'=> $row['preco_praticado'],
        'custo_receita'  => $row['custo_receita']
    ];
}

// Busca o ID e carrega o registro
$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM vendas WHERE id = $id LIMIT 1");
if (!$res || $res->num_rows !== 1) {
    echo "Venda não encontrada!";
    exit;
}

$row = $res->fetch_assoc();

// Parsers para selecionar a opção correta (produto_X ou combo_Y)
$selectedProdCombo = $row['produto_combo']; // ex: "produto_3" ou "combo_5"
?>
<form method="post" action="update_action.php" class="space-y-4">
  <input type="hidden" name="id" value="<?= $row['id'] ?>"/>

  <label class="block">
    <span class="text-gray-700">Data</span>
    <input name="data" type="date" value="<?= $row['data'] ?>"
           class="w-full p-2 border rounded" required/>
  </label>

  <label class="block">
    <span class="text-gray-700">Produto/Combo</span>
    <select id="editProdutoSelect" name="produto_combo"
            class="w-full p-2 border rounded" required>
      <option value="">-- selecione --</option>
      <?php foreach ($produtos as $p): 
        $val = "produto_{$p['id']}";
      ?>
        <option value="<?= $val ?>"
          <?= $selectedProdCombo === $val ? 'selected' : '' ?>>
          <?= htmlspecialchars($p['descricao']) ?> (Produto)
        </option>
      <?php endforeach; ?>

      <?php foreach ($combos as $c):
        $val = "combo_{$c['id']}";
      ?>
        <option value="<?= $val ?>"
          <?= $selectedProdCombo === $val ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['descricao']) ?> (Combo)
        </option>
      <?php endforeach; ?>
    </select>
  </label>

  <label class="block">
    <span class="text-gray-700">Tipo</span>
    <input id="editTipoInput" name="tipo" type="text"
           value="<?= htmlspecialchars($row['tipo']) ?>"
           class="w-full p-2 border rounded bg-gray-100" readonly/>
  </label>

  <label class="block">
    <span class="text-gray-700">Quantidade</span>
    <input id="editQtdInput" name="qtd" type="number"
           value="<?= $row['qtd'] ?>"
           class="w-full p-2 border rounded" required/>
  </label>

  <label class="block">
    <span class="text-gray-700">Valor Unitário</span>
    <input id="editValorUnitInput" name="valor_unit" type="number" step="0.01"
           value="<?= $row['valor_unit'] ?>"
           class="w-full p-2 border rounded bg-gray-100" readonly/>
  </label>

  <label class="block">
    <span class="text-gray-700">Custo</span>
    <input id="editCustoInput" name="custo" type="number" step="0.01"
           value="<?= $row['custo'] ?>"
           class="w-full p-2 border rounded bg-gray-100" readonly/>
  </label>

  <label class="block">
    <span class="text-gray-700">Observações</span>
    <textarea name="observacoes"
              class="w-full p-2 border rounded h-24"><?= htmlspecialchars($row['observacoes']) ?></textarea>
  </label>

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
  // JavaScript para auto‐preencher Tipo, Valor Unitário e Custo no formulário de edição
  const produtos = <?= json_encode($produtos) ?>;
  const combos   = <?= json_encode($combos)   ?>;

  const editProdutoSelect = document.getElementById('editProdutoSelect');
  const editTipoInput     = document.getElementById('editTipoInput');
  const editValorUnitInput= document.getElementById('editValorUnitInput');
  const editCustoInput    = document.getElementById('editCustoInput');
  const editQtdInput      = document.getElementById('editQtdInput');

  editProdutoSelect.addEventListener('change', () => {
    const val = editProdutoSelect.value; // ex: "produto_3" ou "combo_5"
    if (!val) {
      editTipoInput.value = "";
      editValorUnitInput.value = "";
      editCustoInput.value = "";
      return;
    }
    const [tipo, id] = val.split('_');
    let item;
    if (tipo === 'produto') {
      item = produtos.find(p => p.id.toString() === id);
    } else if (tipo === 'combo') {
      item = combos.find(c => c.id.toString() === id);
    }
    if (item) {
      editTipoInput.value       = tipo.charAt(0).toUpperCase() + tipo.slice(1);
      editValorUnitInput.value  = parseFloat(item.preco_praticado).toFixed(2);
      editCustoInput.value      = parseFloat(item.custo_receita).toFixed(2);
      editQtdInput.value        = 1;
    }
  });

  // Se quiser que o botão “Cancelar” também feche o drawer:
  document.getElementById('cancelEdit').addEventListener('click', () => {
    document.getElementById('closeDrawer').click();
  });
</script>
