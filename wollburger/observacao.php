<?php
// observacao.php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Observação do Pedido – X-Tudo</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <!-- Container principal (max-width_mobile) -->
  <div class="max-w-md mx-auto mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
    
    <!-- ================================
         HEADER DO PRODUTO
         ================================ -->
    <div class="flex items-center justify-between px-4 py-3 border-b">
      <!-- Miniatura + Título -->
      <div class="flex items-center space-x-3">
        <div class="relative">
          <img
            src="xtudo.jpg"
            alt="X-Tudo"
            class="w-16 h-16 object-cover rounded"
          />
          <!-- Ícone de lupa para zoom -->
          <span class="absolute bottom-0 right-0 bg-white p-1 rounded-full shadow">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 text-gray-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-4.35-4.35M5 11a6 6 0 1112 0 6 6 0 01-12 0"
              />
            </svg>
          </span>
        </div>
        <h2 class="text-gray-800 font-semibold text-lg">X-Tudo</h2>
      </div>
      <!-- Botão de fechar -->
      <button class="text-gray-500 hover:text-gray-700">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    </div>

    <!-- ================================
         DESCRIÇÃO DO PRODUTO
         ================================ -->
    <div class="px-4 py-3 border-b">
      <p class="text-gray-700 text-sm leading-relaxed">
        Um delicioso hambúrguer X-Tudo com carne suculenta, queijo derretido,
        bacon crocante, salada fresca e molho especial.
      </p>
    </div>

    <!-- ================================
         CAMPO DE OBSERVAÇÃO
         ================================ -->
    <div class="px-4 py-4 border-b">
      <input
        type="text"
        placeholder="Existe alguma observação?"
        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
      />
    </div>

    <!-- ================================
         CONTROLE DE QUANTIDADE
         ================================ -->
    <div class="px-4 py-4 border-b flex items-center justify-center space-x-4">
      <!-- Botão de “menos” -->
      <button
        id="btn-minus"
        class="w-10 h-10 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 text-gray-700"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M20 12H4"
          />
        </svg>
      </button>

      <!-- Campo de quantidade (somente texto, sem possibilidade de digitar livremente) -->
      <input
        id="input-qty"
        type="text"
        value="1"
        readonly
        class="w-16 text-center border border-gray-300 rounded py-2 text-gray-800 font-medium"
      />

      <!-- Botão de “mais” -->
      <button
        id="btn-plus"
        class="w-10 h-10 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 text-gray-700"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          />
        </svg>
      </button>
    </div>

    <!-- ================================
         BOTÕES “VOLTAR” E “ADICIONAR”
         ================================ -->
    <div class="px-4 py-4 flex space-x-3">
      <!-- Botão Voltar -->
      <button
        id="btn-back"
        class="flex-1 flex items-center justify-center space-x-1 bg-gray-700 hover:bg-gray-800 text-white font-medium py-3 rounded"
      >
        <!-- Ícone de seta para esquerda -->
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18"
          />
        </svg>
        <span>Voltar</span>
      </button>

      <!-- Botão Adicionar (será atualizado com o valor total) -->
      <button
        id="btn-add"
        class="flex-1 flex items-center justify-center bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 rounded"
      >
        Adicionar – R$ 40,00
      </button>
    </div>
  </div>

  <!-- ================================
       Script para controle de quantidade e atualização do botão
       ================================ -->
  <script>
    const btnPlus = document.getElementById('btn-plus');
    const btnMinus = document.getElementById('btn-minus');
    const inputQty = document.getElementById('input-qty');
    const btnAdd = document.getElementById('btn-add');

    // Preço unitário do X-Tudo (em reais)
    const precoUnitario = 40.0;

    // Função para formatar número (pt-BR) com duas casas decimais
    function formatarBR(valor) {
      return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    }

    // Atualiza o texto do botão “Adicionar” com base na quantidade
    function atualizarBotao() {
      const qtd = parseInt(inputQty.value, 10);
      const total = precoUnitario * qtd;
      btnAdd.innerText = `Adicionar – R$ ${formatarBR(total)}`;
    }

    // Evento “+”
    btnPlus.addEventListener('click', () => {
      let qtd = parseInt(inputQty.value, 10);
      qtd++;
      // Se quiser limitar a quantidade, por exemplo, a 99, altere aqui.
      inputQty.value = qtd;
      atualizarBotao();
    });

    // Evento “–”
    btnMinus.addEventListener('click', () => {
      let qtd = parseInt(inputQty.value, 10);
      if (qtd > 1) {
        qtd--;
        inputQty.value = qtd;
        atualizarBotao();
      }
    });

    // Inicializa o botão com o valor correto
    atualizarBotao();

    // Botão “Voltar” pode simplesmente dar um history.back()
    document.getElementById('btn-back').addEventListener('click', () => {
      history.back();
    });
  </script>
</body>
</html>
