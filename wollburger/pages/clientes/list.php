<?php
// pages/clientes/list.php
session_start();
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

// Colunas permitidas para filtro em clientes (ajustadas para os campos existentes)
$allowed = ['nome','cpf','celular','cidade'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Clientes | Sistema</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .modal-bg {
      background-color: rgba(0, 0, 0, 0.5);
    }
    /* Removido aviso de borda vermelha para input inválido */
    /* input:invalid { border-color: #dc2626; } */
  </style>
</head>
<body class="bg-gray-100">

  <?php include(__DIR__ . "/../../includes/menu.php"); ?>

  <main class="ml-56 p-8">
    <!-- Cabeçalho -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Listagem de Clientes</h1>
      <button id="openAdd" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
        + Adicionar Cliente
      </button>
    </div>

    <!-- Filtro AJAX -->
    <div class="flex mb-6 space-x-2">
      <input id="searchInput" type="text" placeholder="Buscar..."
             class="flex-1 border p-2 rounded"/>
      <select id="fieldSelect" class="border p-2 rounded">
        <?php foreach ($allowed as $col): ?>
          <option value="<?= $col ?>"><?= ucfirst($col) ?></option>
        <?php endforeach; ?>
      </select>
      <button id="filterBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
        Filtrar
      </button>
    </div>

    <!-- Tabela de Clientes -->
    <div class="overflow-auto bg-white rounded-lg shadow">
      <table class="min-w-full divide-y">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left">#</th>
            <th class="px-4 py-3 text-left">Nome</th>
            <th class="px-4 py-3 text-left">CPF</th>
            <th class="px-4 py-3 text-left">Celular</th>
            <th class="px-4 py-3 text-left">Data Nasc.</th>
            <th class="px-4 py-3 text-left">Endereço Completo</th>
            <th class="px-4 py-3 text-center">Ações</th>
          </tr>
        </thead>
        <tbody id="clientesTableBody" class="bg-white divide-y">
          <!-- As linhas serão injetadas por data.php via AJAX -->
        </tbody>
      </table>
    </div>
  </main>

  <!-- ========================================================= -->
  <!-- Modal de Adicionar Cliente (MÁSCARAS, VIACEP, VALIDAÇÃO CPF e CELULAR) -->
  <!-- ========================================================= -->
  <div id="addOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>
  <div id="addModal" class="fixed inset-0 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 overflow-auto">
      <!-- Cabeçalho do Popup -->
      <div class="flex justify-between items-center p-4 border-b">
        <h2 class="text-xl font-semibold">Adicionar Cliente</h2>
        <button id="closeAdd" class="text-gray-600 hover:text-gray-900">&times;</button>
      </div>
      <!-- Conteúdo do Popup -->
      <div class="p-6">
        <form id="addForm" method="post" action="add_action.php" class="space-y-6" novalidate>
          <!-- Linha 1: Nome e CPF -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nome (agora obrigatório) -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Nome Completo</span>
                <input
                  id="nomeInput"
                  name="nome"
                  type="text"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Nome completo"
                  required
                />
              </label>
            </div>
            <!-- CPF -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">CPF</span>
                <input
                  id="cpfInput"
                  name="cpf"
                  type="text"
                  maxlength="14"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="000.000.000-00"
                  required
                />
              </label>
            </div>
          </div>

          <!-- Linha 2: Data de Nascimento e Celular -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Data de Nascimento -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Data de Nascimento</span>
                <input
                  id="dataNascimentoInput"
                  name="data_nascimento"
                  type="date"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                />
              </label>
            </div>
            <!-- Celular -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Celular</span>
                <input
                  id="celularInput"
                  name="celular"
                  type="text"
                  maxlength="15"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="(00) 90000-0000"
                  required
                />
              </label>
            </div>
          </div>

          <!-- Linha 3: CEP e UF -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- CEP -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">CEP</span>
                <input
                  id="cepInput"
                  name="cep"
                  type="text"
                  maxlength="9"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="00000-000"
                  required
                />
              </label>
            </div>
            <!-- UF -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">UF</span>
                <select
                  id="ufInput"
                  name="uf"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                >
                  <option value="">Selecione a UF</option>
                  <option value="AC">AC</option><option value="AL">AL</option>
                  <option value="AP">AP</option><option value="AM">AM</option>
                  <option value="BA">BA</option><option value="CE">CE</option>
                  <option value="DF">DF</option><option value="ES">ES</option>
                  <option value="GO">GO</option><option value="MA">MA</option>
                  <option value="MT">MT</option><option value="MS">MS</option>
                  <option value="MG">MG</option><option value="PA">PA</option>
                  <option value="PB">PB</option><option value="PR">PR</option>
                  <option value="PE">PE</option><option value="PI">PI</option>
                  <option value="RJ">RJ</option><option value="RN">RN</option>
                  <option value="RS">RS</option><option value="RO">RO</option>
                  <option value="RR">RR</option><option value="SC">SC</option>
                  <option value="SP">SP</option><option value="SE">SE</option>
                  <option value="TO">TO</option>
                </select>
              </label>
            </div>
          </div>

          <!-- Linha 4: Cidade e Bairro -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Cidade -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Cidade</span>
                <input
                  id="cidadeInput"
                  name="cidade"
                  type="text"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Cidade"
                  required
                />
              </label>
            </div>
            <!-- Bairro -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Bairro</span>
                <input
                  id="bairroInput"
                  name="bairro"
                  type="text"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Bairro"
                  required
                />
              </label>
            </div>
          </div>

          <!-- Linha 5: Endereço, Número e Complemento -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Endereço -->
            <div class="md:col-span-2">
              <label class="block mb-1">
                <span class="text-gray-700">Endereço</span>
                <input
                  id="enderecoInput"
                  name="endereco"
                  type="text"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Rua, Avenida, etc."
                  required
                />
              </label>
            </div>
            <!-- Número -->
            <div>
              <label class="block mb-1">
                <span class="text-gray-700">Número</span>
                <input
                  id="numeroInput"
                  name="numero"
                  type="text"
                  class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Número"
                  required
                />
              </label>
            </div>
          </div>

          <!-- Linha 6: Complemento (agora obrigatório) -->
          <div>
            <label class="block mb-1">
              <span class="text-gray-700">Complemento</span>
              <input
                id="complementoInput"
                name="complemento"
                type="text"
                class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Ex: Apt, Bloco, Condomínio"
              />
            </label>
          </div>

          <!-- Botões Adicionar / Cancelar -->
          <div class="flex justify-end space-x-2">
            <button
              type="submit"
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
            >
              Adicionar
            </button>
            <button type="button" id="cancelAdd" class="text-gray-600 hover:underline">
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Drawer de Edição de Cliente (sem alterações aqui; espelhe máscaras em edit.php) -->
  <div id="drawerOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>
  <div id="editDrawer"
       class="fixed inset-y-0 right-0 w-96 bg-white shadow-lg transform translate-x-full transition-transform duration-300 overflow-auto">
    <div class="flex justify-between items-center p-4 border-b">
      <h2 class="text-lg font-semibold">Editar Cliente</h2>
      <button id="closeDrawer" class="text-gray-600 hover:text-gray-900">&times;</button>
    </div>
    <div id="drawerContent" class="p-4">
      <!-- Em edit.php, utilize os mesmos IDs e máscaras para CPF, CEP, Celular, Número etc. -->
    </div>
  </div>

  <script>
    // ============================
    // Funções de Máscara (JavaScript Puro)
    // ============================
    function maskCPF(value) {
      let digits = value.replace(/\D/g, '').substring(0, 11);
      if (digits.length <= 3) return digits;
      if (digits.length <= 6) return digits.replace(/(\d{3})(\d+)/, '$1.$2');
      if (digits.length <= 9) return digits.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
      return digits.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
    }

    function maskCEP(value) {
      let digits = value.replace(/\D/g, '').substring(0, 8);
      if (digits.length <= 5) return digits;
      return digits.replace(/(\d{5})(\d+)/, '$1-$2');
    }

    function maskCelular(value) {
      let digits = value.replace(/\D/g, '').substring(0, 11);
      if (digits.length <= 2) return digits;
      if (digits.length <= 6) return digits.replace(/(\d{2})(\d+)/, '($1) $2');
      if (digits.length <= 10) return digits.replace(/(\d{2})(\d{4})(\d+)/, '($1) $2-$3');
      return digits.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }

    function maskNumero(value) {
      return value.replace(/\D/g, '');
    }

    // ============================
    // Referências aos Inputs
    // ============================
    const nomeInput       = document.getElementById('nomeInput');
    const cpfInput        = document.getElementById('cpfInput');
    const dataNascimentoInput = document.getElementById('dataNascimentoInput');
    const celularInput    = document.getElementById('celularInput');
    const cepInput        = document.getElementById('cepInput');
    const ufInput         = document.getElementById('ufInput');
    const cidadeInput     = document.getElementById('cidadeInput');
    const bairroInput     = document.getElementById('bairroInput');
    const enderecoInput   = document.getElementById('enderecoInput');
    const numeroInput     = document.getElementById('numeroInput');
    const complementoInput= document.getElementById('complementoInput');

    function setupEditForm() {
      const form       = document.getElementById('editForm');
      if (!form) return;
      const id         = form.querySelector('input[name="id"]').value;

      const nomeEdit   = document.getElementById('nomeEdit');
      const cpfEdit    = document.getElementById('cpfEdit');
      const dataEdit   = document.getElementById('dataNascimentoEdit');
      const celularEdit= document.getElementById('celularEdit');
      const cepEdit    = document.getElementById('cepEdit');
      const ufEdit     = document.getElementById('ufEdit');
      const cidadeEdit = document.getElementById('cidadeEdit');
      const bairroEdit = document.getElementById('bairroEdit');
      const enderecoEdit = document.getElementById('enderecoEdit');
      const numeroEdit = document.getElementById('numeroEdit');
      const complEdit  = document.getElementById('complementoEdit');

      nomeEdit?.addEventListener('input', () => nomeEdit.setCustomValidity(''));
      cpfEdit?.addEventListener('input', e => { e.target.value = maskCPF(e.target.value); cpfEdit.setCustomValidity(''); });
      dataEdit?.addEventListener('input', () => dataEdit.setCustomValidity(''));
      celularEdit?.addEventListener('input', e => { e.target.value = maskCelular(e.target.value); celularEdit.setCustomValidity(''); });
      cepEdit?.addEventListener('input', e => { e.target.value = maskCEP(e.target.value); });
      cepEdit?.addEventListener('blur', e => {
        const c = e.target.value.replace(/\D/g, '');
        if (c.length !== 8) return;
        fetch(`https://viacep.com.br/ws/${c}/json/`)
          .then(res => res.json())
          .then(data => {
            if (data.erro) return;
            ufEdit.value      = data.uf;
            cidadeEdit.value  = data.localidade;
            bairroEdit.value  = data.bairro;
            enderecoEdit.value= data.logradouro;
            complEdit.value   = data.complemento || '';
          });
      });
      numeroEdit?.addEventListener('input', e => { e.target.value = maskNumero(e.target.value); });
      complEdit?.addEventListener('input', () => complEdit.setCustomValidity(''));

      cpfEdit?.addEventListener('blur', () => {
        const valor = cpfEdit.value.trim();
        if (!valor) { cpfEdit.setCustomValidity(''); return; }
        fetch(`check_cpf_ajax.php?cpf=${encodeURIComponent(valor)}&id=${id}`)
          .then(r => r.json())
          .then(d => {
            if (d.exists) {
              const msg = `CPF já cadastrado no contato: ${d.nome} (ID: ${d.id}).`;
              cpfEdit.setCustomValidity(msg); cpfEdit.reportValidity();
            } else {
              cpfEdit.setCustomValidity('');
            }
          })
          .catch(() => cpfEdit.setCustomValidity(''));
      });

      celularEdit?.addEventListener('blur', () => {
        const valor = celularEdit.value.trim();
        if (!valor) { celularEdit.setCustomValidity(''); return; }
        fetch(`check_celular_ajax.php?celular=${encodeURIComponent(valor)}&id=${id}`)
          .then(r => r.json())
          .then(d => {
            if (d.exists) {
              const msg = `Celular já cadastrado no contato: ${d.nome} (ID: ${d.id}).`;
              celularEdit.setCustomValidity(msg); celularEdit.reportValidity();
            } else {
              celularEdit.setCustomValidity('');
            }
          })
          .catch(() => celularEdit.setCustomValidity(''));
      });

      form.addEventListener('submit', e => {
        if (!form.checkValidity()) {
          e.preventDefault();
          form.reportValidity();
          return;
        }
        const campos = [nomeEdit, cpfEdit, dataEdit, celularEdit, cepEdit, ufEdit, cidadeEdit, bairroEdit, enderecoEdit, numeroEdit, complEdit];
        for (let campo of campos) {
          if (campo && !campo.checkValidity()) {
            e.preventDefault();
            campo.reportValidity();
            return;
          }
        }
      });
    }

    // ============================
    // Attach de eventos de input (máscaras)
    // ============================
    nomeInput?.addEventListener('input', () => {
      nomeInput.setCustomValidity('');
    });

    cpfInput?.addEventListener('input', e => {
      e.target.value = maskCPF(e.target.value);
      e.target.setCustomValidity('');
    });

    dataNascimentoInput?.addEventListener('input', () => {
      dataNascimentoInput.setCustomValidity('');
    });

    celularInput?.addEventListener('input', e => {
      e.target.value = maskCelular(e.target.value);
      e.target.setCustomValidity('');
    });

    cepInput?.addEventListener('input', e => {
      e.target.value = maskCEP(e.target.value);
    });

    cepInput?.addEventListener('blur', function(e) {
      const cep = e.target.value.replace(/\D/g, '');
      if (cep.length !== 8) return;
      fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {
          if (data.erro) {
            alert('CEP não encontrado.');
            return;
          }
          ufInput.value         = data.uf;
          cidadeInput.value     = data.localidade;
          bairroInput.value     = data.bairro;
          enderecoInput.value   = data.logradouro;
          complementoInput.value= data.complemento || '';
        })
        .catch(() => {
          alert('Erro ao consultar o CEP.');
        });
    });

    numeroInput?.addEventListener('input', e => {
      e.target.value = maskNumero(e.target.value);
    });

    complementoInput?.addEventListener('input', () => {
      complementoInput.setCustomValidity('');
    });

    // ============================
    // Validação AJAX de CPF (duplicidade)
    // ============================
    cpfInput?.addEventListener('blur', () => {
      const valor = cpfInput.value.trim();
      if (!valor) {
        cpfInput.setCustomValidity('');
        return;
      }
      fetch(`check_cpf_ajax.php?cpf=${encodeURIComponent(valor)}`)
        .then(response => response.json())
        .then(data => {
          if (data.exists) {
            const msg = `CPF já cadastrado no contato: ${data.nome} (ID: ${data.id}).`;
            cpfInput.setCustomValidity(msg);
            cpfInput.reportValidity();
          } else {
            cpfInput.setCustomValidity('');
          }
        })
        .catch(err => {
          console.error('Erro na requisição AJAX de CPF:', err);
          cpfInput.setCustomValidity('');
        });
    });

    // ============================
    // Validação AJAX de Celular (duplicidade)
    // ============================
    celularInput?.addEventListener('blur', () => {
      const valor = celularInput.value.trim();
      if (!valor) {
        celularInput.setCustomValidity('');
        return;
      }
      fetch(`check_celular_ajax.php?celular=${encodeURIComponent(valor)}`)
        .then(response => response.json())
        .then(data => {
          if (data.exists) {
            const msg = `Celular já cadastrado no contato: ${data.nome} (ID: ${data.id}).`;
            celularInput.setCustomValidity(msg);
            celularInput.reportValidity();
          } else {
            celularInput.setCustomValidity('');
          }
        })
        .catch(err => {
          console.error('Erro na requisição AJAX de Celular:', err);
          celularInput.setCustomValidity('');
        });
    });

    // ============================
    // Evita submissão do form se houver alguma mensagem de validação
    // ============================
    document.getElementById('addForm').addEventListener('submit', function(event) {
      // dispara validação nativa de campos obrigatórios
      if (!this.checkValidity()) {
        event.preventDefault();
        this.reportValidity();
        return;
      }
      // checa custom validity de cada campo
      const campos = [nomeInput, cpfInput, dataNascimentoInput, celularInput, cepInput, ufInput, cidadeInput, bairroInput, enderecoInput, numeroInput, complementoInput];
      for (let campo of campos) {
        if (campo && !campo.checkValidity()) {
          event.preventDefault();
          campo.reportValidity();
          return;
        }
      }
      // se chegar aqui, não há mensagens, o form segue normalmente
    });

    // ============================
    // Lógica do modal e AJAX para listagem
    // ============================
    const overlayAdd   = document.getElementById('addOverlay');
    const modalAdd     = document.getElementById('addModal');
    const openAddBtn   = document.getElementById('openAdd');
    const closeAddBtn  = document.getElementById('closeAdd');
    const cancelAddBtn = document.getElementById('cancelAdd');

    const overlayEdit  = document.getElementById('drawerOverlay');
    const drawerEdit   = document.getElementById('editDrawer');
    const closeEditBtn = document.getElementById('closeDrawer');
    const contentEdit  = document.getElementById('drawerContent');

    const tbody        = document.getElementById('clientesTableBody');
    const searchInput  = document.getElementById('searchInput');
    const fieldSelect  = document.getElementById('fieldSelect');
    const filterBtn    = document.getElementById('filterBtn');
    let debounceTimer;

    function loadClientes() {
      const params = new URLSearchParams({
        search: searchInput.value,
        field:  fieldSelect.value
      });
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
              setupEditForm();
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
      modalAdd.querySelector('form').reset();
      // limpa mensagens de duplicidade antes de abrir
      cpfInput?.setCustomValidity('');
      celularInput?.setCustomValidity('');
      complementoInput?.setCustomValidity('');
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

    searchInput.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(loadClientes, 300);
    });
    fieldSelect.addEventListener('change', loadClientes);
    filterBtn.addEventListener('click', loadClientes);

    document.addEventListener('DOMContentLoaded', loadClientes);
  </script>
</body>
</html>
