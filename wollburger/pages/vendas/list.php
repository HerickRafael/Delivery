<?php
// pages/vendas/list.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

// Carregar lista de clientes para possível filtro (opcional, aqui mantemos o array só para JS se precisar)
$clientes = [];
$resC = $conn->query("SELECT id, nome FROM clientes ORDER BY nome ASC");
while ($c = $resC->fetch_assoc()) {
    $clientes[] = [
        'id'   => $c['id'],
        'nome' => $c['nome']
    ];
}

// Colunas permitidas para filtro (AJAX)
$allowed = [
    'id',
    'cliente_id',
    'data',
    'mes',
    'ano',
    'custo',
    'faturamento',
    'lucro'
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Vendas | Sistema</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <?php include(__DIR__ . "/../../includes/menu.php"); ?>

  <main class="ml-56 p-8">
    <!-- Cabeçalho -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Listagem de Vendas</h1>
      <button id="openAdd"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
        + Adicionar Venda
      </button>
    </div>

    <!-- Filtro AJAX -->
    <div class="flex mb-6 space-x-2">
      <input id="searchInput"
             type="text"
             placeholder="Buscar..."
             class="flex-1 border p-2 rounded"/>
      <select id="fieldSelect" class="border p-2 rounded">
        <?php foreach ($allowed as $col): ?>
          <option value="<?= $col ?>">
            <?= match($col) {
                 'id'          => 'ID',
                 'cliente_id'  => 'Cliente',
                 'data'        => 'Data',
                 'mes'         => 'Mês',
                 'ano'         => 'Ano',
                 'custo'       => 'Custo',
                 'faturamento' => 'Faturamento',
                 'lucro'       => 'Lucro',
                 default       => ucfirst($col),
               } ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button id="filterBtn"
              type="button"
              class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
        Filtrar
      </button>
    </div>

    <!-- Tabela de Vendas -->
    <div class="overflow-auto bg-white rounded-lg shadow">
      <table class="min-w-full divide-y">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left">#</th>
            <th class="px-4 py-3 text-left">Cliente</th>
            <th class="px-4 py-3 text-left">Data</th>
            <th class="px-4 py-3 text-left">Mês</th>
            <th class="px-4 py-3 text-left">Ano</th>
            <th class="px-4 py-3 text-right">Custo</th>
            <th class="px-4 py-3 text-right">Faturamento</th>
            <th class="px-4 py-3 text-right">Lucro</th>
            <th class="px-4 py-3 text-center">Obs.</th>
            <th class="px-4 py-3 text-center">Ações</th>
            <th class="px-4 py-3 text-center">Detalhes</th>
          </tr>
        </thead>
        <tbody id="vendasTableBody" class="bg-white divide-y">
          <!-- As linhas serão injetadas por AJAX (via data.php) -->
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal de Adicionar Venda (mesma estrutura que antes) -->
  <div id="addOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>
  <div id="addModal"
       class="fixed inset-0 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl mx-4 overflow-auto">
      <div class="flex justify-between items-center p-4 border-b">
        <h2 class="text-xl font-semibold">Adicionar Venda</h2>
        <button id="closeAdd" class="text-gray-600 hover:text-gray-900">&times;</button>
      </div>
      <div class="p-6">
        <form method="post" action="add_action.php" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Cliente (select) -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Cliente</span>
                <select id="clienteSelect" name="cliente_id"
                        class="w-full p-2 border rounded" required>
                  <option value="">-- selecione cliente --</option>
                  <?php foreach ($clientes as $c): ?>
                    <option value="<?= $c['id'] ?>">
                      <?= htmlspecialchars($c['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </label>
            </div>

            <!-- Data (default = hoje) -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Data</span>
                <input id="dataInput" name="data" type="date"
                       class="w-full p-2 border rounded"
                       value="<?= date('Y-m-d') ?>"
                       required/>
              </label>
            </div>

            <!-- Mês (auto a partir de data) -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Mês</span>
                <input id="mesInput" name="mes" type="number"
                       class="w-full p-2 border rounded bg-gray-100"
                       value="<?= date('n') ?>" readonly/>
              </label>
            </div>

            <!-- Ano (auto a partir de data) -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Ano</span>
                <input id="anoInput" name="ano" type="number"
                       class="w-full p-2 border rounded bg-gray-100"
                       value="<?= date('Y') ?>" readonly/>
              </label>
            </div>

            <!-- Custo -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Custo</span>
                <input id="custoInput" name="custo" type="number" step="0.01"
                       class="w-full p-2 border rounded"
                       value="0.00" required/>
              </label>
            </div>

            <!-- Faturamento -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Faturamento</span>
                <input id="faturamentoInput" name="faturamento" type="number" step="0.01"
                       class="w-full p-2 border rounded"
                       value="0.00" required/>
              </label>
            </div>

            <!-- Lucro (auto = faturamento – custo; readonly) -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Lucro</span>
                <input id="lucroInput" name="lucro" type="number" step="0.01"
                       class="w-full p-2 border rounded bg-gray-100"
                       value="0.00" readonly/>
              </label>
            </div>
          </div>

          <!-- Observações 100% -->
          <div>
            <label class="block mb-1">
              <span class="text-gray-700">Observações</span>
              <textarea name="observacoes"
                        class="w-full p-2 border rounded h-24"
                        placeholder="Digite aqui observações..."></textarea>
            </label>
          </div>

          <!-- Botões Adicionar / Cancelar -->
          <div class="flex justify-end space-x-2">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
              Adicionar
            </button>
            <button type="button" id="cancelAdd"
                    class="text-gray-600 hover:underline">
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Drawer de Edição (igual ao antes, sem mudança) -->
  <div id="drawerOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>
  <div id="editDrawer"
       class="fixed inset-y-0 right-0 w-96 bg-white shadow-lg transform translate-x-full transition-transform duration-300 overflow-auto">
    <div class="flex justify-between items-center p-4 border-b">
      <h2 class="text-lg font-semibold">Editar Venda</h2>
      <button id="closeDrawer" class="text-gray-600 hover:text-gray-900">&times;</button>
    </div>
    <div id="drawerContent" class="p-4">
      <!-- edit.php será carregado aqui via AJAX -->
    </div>
  </div>

  <script>
    // Dados de JS
    const clientes        = <?= json_encode($clientes) ?>;
    const overlayAdd      = document.getElementById('addOverlay');
    const modalAdd        = document.getElementById('addModal');
    const openAddBtn      = document.getElementById('openAdd');
    const closeAddBtn     = document.getElementById('closeAdd');
    const dataInput       = document.getElementById('dataInput');
    const mesInput        = document.getElementById('mesInput');
    const anoInput        = document.getElementById('anoInput');
    const custoInput      = document.getElementById('custoInput');
    const faturamentoInput= document.getElementById('faturamentoInput');
    const lucroInput      = document.getElementById('lucroInput');

    const overlayEdit     = document.getElementById('drawerOverlay');
    const drawerEdit      = document.getElementById('editDrawer');
    const closeEditBtn    = document.getElementById('closeDrawer');
    const contentEdit     = document.getElementById('drawerContent');

    const tbody           = document.getElementById('vendasTableBody');
    const searchInput     = document.getElementById('searchInput');
    const fieldSelect     = document.getElementById('fieldSelect');
    const filterBtn       = document.getElementById('filterBtn');
    let debounceTimer;

    // Função para recalcular o lucro (faturamento – custo)
    function recalcularLucro() {
      const c = parseFloat(custoInput.value) || 0;
      const f = parseFloat(faturamentoInput.value) || 0;
      lucroInput.value = (f - c).toFixed(2);
    }

    // 1) Abrir modal “Adicionar”
    openAddBtn.addEventListener('click', () => {
      overlayAdd.classList.remove('hidden');
      modalAdd.classList.remove('hidden');
      // Resetar campos
      dataInput.value        = "<?= date('Y-m-d') ?>";
      mesInput.value         = "<?= date('n') ?>";
      anoInput.value         = "<?= date('Y') ?>";
      custoInput.value       = "0.00";
      faturamentoInput.value = "0.00";
      lucroInput.value       = "0.00";
    });

    // 2) Fechar modal “Adicionar”
    [overlayAdd, closeAddBtn].forEach(el =>
      el.addEventListener('click', () => {
        overlayAdd.classList.add('hidden');
        modalAdd.classList.add('hidden');
      })
    );

    // 3) Atualizar mês e ano quando a data mudar
    dataInput.addEventListener('change', () => {
      const d = new Date(dataInput.value);
      if (!isNaN(d)) {
        mesInput.value = d.getMonth() + 1;
        anoInput.value = d.getFullYear();
      }
    });

    // 4) Recalcular lucro quando custo ou faturamento mudarem
    [custoInput, faturamentoInput].forEach(el =>
      el.addEventListener('input', recalcularLucro)
    );

    // 5) Carregar vendas via AJAX
    function loadVendas() {
      const params = new URLSearchParams({
        search: searchInput.value,
        field:  fieldSelect.value
      });
      fetch('data.php?' + params.toString())
        .then(res => res.text())
        .then(html => {
          tbody.innerHTML = html;
          bindButtons();
        });
    }

    // 6) Atachar botões “Editar” e “Detalhes”
    function bindButtons() {
      // Botões Editar
      document.querySelectorAll('.editBtn').forEach(btn => {
        btn.onclick = () => {
          fetch(`edit.php?id=${btn.dataset.id}`)
            .then(res => res.text())
            .then(html => {
              contentEdit.innerHTML = html;
              document.getElementById('cancelEdit')?.addEventListener('click', () => {
                closeEditBtn.click();
              });
              overlayEdit.classList.remove('hidden');
              drawerEdit.classList.remove('translate-x-full');
            });
        };
      });
      // Botões Detalhes (link simples, não AJAX)
      document.querySelectorAll('.detailBtn').forEach(btn => {
        btn.onclick = () => {
          const vid = btn.dataset.id;
          window.location.href = `view.php?id=${vid}`;
        };
      });
    }

    // 7) Fechar drawer de edição
    [overlayEdit, closeEditBtn].forEach(el =>
      el.addEventListener('click', () => {
        overlayEdit.classList.add('hidden');
        drawerEdit.classList.add('translate-x-full');
      })
    );

    // 8) Filtros AJAX com debounce
    searchInput.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(loadVendas, 300);
    });
    fieldSelect.addEventListener('change', loadVendas);
    filterBtn.addEventListener('click', loadVendas);

    // 9) Carrega tudo ao iniciar
    document.addEventListener('DOMContentLoaded', loadVendas);
  </script>
</body>
</html>
