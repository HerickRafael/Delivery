<?php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Produtos | Sistema</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <?php include(__DIR__ . "/../../includes/menu.php"); ?>

  <main class="ml-56 p-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Listagem de Produtos</h1>
      <button id="openAdd" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
        + Adicionar Produto
      </button>
    </div>

    <form id="searchForm" class="flex mb-6 space-x-2">
      <input type="text" name="search" placeholder="Buscar..." class="flex-1 border p-2 rounded"/>
      <select name="field" class="border p-2 rounded">
        <option value="descricao">Descrição</option>
      </select>
      <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
        Filtrar
      </button>
    </form>

    <div class="overflow-auto bg-white rounded-lg shadow">
      <table class="min-w-full divide-y">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left">ID</th>
            <th class="px-4 py-3 text-left">Descrição</th>
            <th class="px-4 py-3 text-right">Preço Sug.</th>
            <th class="px-4 py-3 text-right">Preço iFood</th>
            <th class="px-4 py-3 text-right">Preço Pratic.</th>
            <th class="px-4 py-3 text-right">Custo Seg.</th>
            <th class="px-4 py-3 text-center">Ações</th>
          </tr>
        </thead>
        <tbody id="tableBody" class="bg-white divide-y"></tbody>
      </table>
    </div>
  </main>

  <!-- Modal Adicionar -->
  <div id="addOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>
  <div id="addModal" class="fixed inset-0 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 overflow-auto">
      <div class="flex justify-between items-center p-4 border-b">
        <h2 class="text-xl font-semibold">Adicionar Produto</h2>
        <button id="closeAdd" class="text-gray-600 hover:text-gray-900">&times;</button>
      </div>
      <div class="p-6">
        <form method="post" action="add_action.php" class="space-y-4">
          <div>
            <label class="block mb-1"><span class="text-gray-700">Descrição</span>
              <input name="descricao" type="text" class="w-full p-2 border rounded" required/>
            </label>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block mb-1"><span class="text-gray-700">Preço Sugerido (R$)</span>
                <input name="preco_sugerido" type="number" step="0.01"
                       class="w-full p-2 border rounded" required/>
              </label>
            </div>
            <div>
              <label class="block mb-1"><span class="text-gray-700">Preço iFood (R$)</span>
                <input name="preco_ifood" type="number" step="0.01"
                       class="w-full p-2 border rounded" required/>
              </label>
            </div>
            <div>
              <label class="block mb-1"><span class="text-gray-700">Preço Praticado (R$)</span>
                <input name="preco_praticado" type="number" step="0.01"
                       class="w-full p-2 border rounded" required/>
              </label>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1"><span class="text-gray-700">Custo Segurança (R$)</span>
                <input name="custo_seguranca" type="number" step="0.01"
                       class="w-full p-2 border rounded" required/>
              </label>
            </div>
            <div>
              <label class="block mb-1"><span class="text-gray-700">Número de Ingredientes</span>
                <input id="numIngredientes" type="number" min="1" value="1"
                       class="w-full p-2 border rounded" required/>
                <p class="text-sm text-gray-500 mt-1">Selecione quantas linhas de ingredientes aparecerão abaixo.</p>
              </label>
            </div>
          </div>

          <!-- Montagem de Ingredientes -->
          <div>
            <span class="text-gray-700 font-semibold">Ingredientes do Produto</span>
            <table id="ingredientesTable" class="w-full mt-2 border">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-2 py-1 border">Ingrediente</th>
                  <th class="px-2 py-1 border">Quantidade</th>
                  <th class="px-2 py-1 border">▶️ Ações</th>
                </tr>
              </thead>
              <tbody id="tbodyIngredientes"></tbody>
            </table>
            <button type="button" id="addLineBtn"
                    class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1 rounded">
              + Linha de Ingrediente
            </button>
          </div>

          <div class="flex justify-end space-x-2">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
              Salvar
            </button>
            <button type="button" id="cancelAdd" class="text-gray-600 hover:underline">
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Overlay e Drawer Editar -->
  <div id="drawerOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>
  <div id="editDrawer"
       class="fixed inset-y-0 right-0 w-96 bg-white shadow-lg transform translate-x-full transition-transform duration-300 overflow-auto">
    <div class="flex justify-between items-center p-4 border-b">
      <h2 class="text-lg font-semibold">Editar Produto</h2>
      <button id="closeDrawer" class="text-gray-600 hover:text-gray-900">&times;</button>
    </div>
    <div id="drawerContent" class="p-4"></div>
  </div>

  <script>
    const overlayAdd    = document.getElementById('addOverlay');
    const modalAdd      = document.getElementById('addModal');
    const openAddBtn    = document.getElementById('openAdd');
    const closeAddBtn   = document.getElementById('closeAdd');
    const cancelAddBtn  = document.getElementById('cancelAdd');
    const tbody         = document.getElementById('tableBody');
    const form          = document.getElementById('searchForm');

    const overlayEdit   = document.getElementById('drawerOverlay');
    const drawerEdit    = document.getElementById('editDrawer');
    const closeEditBtn  = document.getElementById('closeDrawer');
    const contentEdit   = document.getElementById('drawerContent');

    let ingredientesData = []; 
    fetch('../../includes/conexao.php'); 
    // Podemos antecipar os ingredientes via AJAX ou diretamente buscar no PHP para JS
    // Para simplificar, vamos buscar via endpoint “/pages/ingredientes/data.php” como JSON:
    fetch('../ingredientes/data.php?json=1')
      .then(res => res.json())
      .then(json => {
        ingredientesData = json; // array {id,descricao,custo_unitario}
      });

    function loadData() {
      const params = new URLSearchParams(new FormData(form));
      fetch('data.php?' + params.toString())
        .then(res => res.text())
        .then(html => {
          tbody.innerHTML = html;
          bindEditButtons();
        });
    }

    function bindEditButtons() {
      document.querySelectorAll('.editBtn').forEach(btn => {
        btn.onclick = () => {
          fetch(`edit.php?id=${btn.dataset.id}`)
            .then(res => res.text())
            .then(html => {
              contentEdit.innerHTML = html;
              // carregar a lógica de montar linhas de ingredientes no edit.php
              document.getElementById('cancelEdit')?.addEventListener('click', () => {
                closeEditBtn.click();
              });
              overlayEdit.classList.remove('hidden');
              drawerEdit.classList.remove('translate-x-full');
            });
        };
      });
    }

    openAddBtn.addEventListener('click', () => {
      overlayAdd.classList.remove('hidden');
      modalAdd.classList.remove('hidden');
      document.getElementById('tbodyIngredientes').innerHTML = '';
    });
    [overlayAdd, closeAddBtn, cancelAddBtn].forEach(el =>
      el.addEventListener('click', () => {
        overlayAdd.classList.add('hidden');
        modalAdd.classList.add('hidden');
      })
    );
    [overlayEdit, closeEditBtn].forEach(el =>
      el.addEventListener('click', () => {
        overlayEdit.classList.add('hidden');
        drawerEdit.classList.add('translate-x-full');
      })
    );
    form.addEventListener('submit', e => {
      e.preventDefault();
      loadData();
    });
    document.addEventListener('DOMContentLoaded', loadData);

    // Montagem dinâmica das linhas de ingredientes
    const addLineBtn = document.getElementById('addLineBtn');
    const tbodyIngr  = document.getElementById('tbodyIngredientes');

    function addIngredientRow(tipoSel = '', ingrIdSel = '', qtd = '') {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td class="border px-2 py-1">
          <select name="prod_ingr_ingrediente_id[]" class="w-full p-1 border rounded" required>
            <option value="">-- selecione ingrediente --</option>
            ${ingredientesData.map(i => `
              <option value="${i.id}" ${i.id==ingrIdSel?'selected':''}>${i.descricao}</option>
            `).join('')}
          </select>
        </td>
        <td class="border px-2 py-1">
          <input name="prod_ingr_qtd[]" type="number" step="0.01" value="${qtd||''}" 
                 class="w-full p-1 border rounded" required/>
        </td>
        <td class="border px-2 py-1 text-center">
          <button type="button" class="removeItemBtn text-red-600">✖️</button>
        </td>
      `;
      tbodyIngr.appendChild(row);
      row.querySelector('.removeItemBtn').addEventListener('click', () => row.remove());
    }

    addLineBtn.addEventListener('click', () => {
      addIngredientRow();
    });
  </script>
</body>
</html>
