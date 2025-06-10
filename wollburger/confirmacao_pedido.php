<?php
// confirmacao_pedido.php

// Captura variáveis do POST (ou usa valores padrão caso não haja POST)
$produtoNome    = $_POST['produto_nome']    ?? 'PRECINHO PROMO';
$variante       = $_POST['variante']        ?? 'ITALIANINHA';
$refrigerante   = $_POST['refrigerante']    ?? 'COCA-COLA';
$quantidade     = intval($_POST['quantidade'] ?? 1);
$precoUnitario  = floatval($_POST['preco_unitario'] ?? 54.99);
$observacao     = $_POST['observacao']      ?? '';

// Calcula valor total (pizza + extras, neste exemplo não há extras que alterem o preço base)
$precoTotal     = $precoUnitario * $quantidade;

// Função para formatar em “pt-BR” com duas casas decimais
function formataPrecoBR($valor) {
    return number_format($valor, 2, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Confirmação de Pedido</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <!-- Container principal -->
  <div class="max-w-md mx-auto mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
    
    <!-- ================================
         HEADER: Imagem do combo + título + botão fechar
         ================================ -->
    <div class="flex items-center justify-between px-4 py-3 border-b">
      <div class="flex items-center space-x-3">
        <!-- Miniatura (pizza + refri) -->
        <div class="relative">
          <img
            src="pizza_grande_refri.jpg"
            alt="<?= htmlspecialchars($produtoNome) ?>"
            class="w-16 h-16 object-cover rounded"
          />
          <!-- Ícone de lupa (zoom) -->
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
        <!-- Nome do produto (título) -->
        <h1 class="text-gray-800 font-semibold text-lg leading-tight">
          <?= htmlspecialchars($produtoNome) ?>
        </h1>
      </div>
      <!-- Botão “X” para fechar (você pode adicionar onclick se for modal) -->
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
         CAMPO DE OBSERVAÇÃO
         ================================ -->
    <div class="px-4 py-4 border-b">
      <input
        type="text"
        name="observacao"
        value="<?= htmlspecialchars($observacao) ?>"
        placeholder="Existe alguma observação?"
        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
      />
    </div>

    <!-- ================================
         CONTROLE DE QUANTIDADE E BOTÕES
         ================================ -->
    <div class="px-4 py-4 border-b">
      <div class="flex items-center justify-center space-x-4 mb-4">
        <!-- Botão “–” -->
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

        <!-- Campo de quantidade (readonly) -->
        <input
          id="input-qty"
          type="text"
          value="<?= $quantidade ?>"
          readonly
          class="w-16 text-center border border-gray-300 rounded py-2 text-gray-800 font-medium"
        />

        <!-- Botão “+” -->
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

      <div class="flex space-x-3">
        <!-- Botão “Voltar” -->
        <button
          id="btn-back"
          class="flex-1 flex items-center justify-center space-x-1 bg-gray-700 hover:bg-gray-800 text-white font-medium py-3 rounded"
        >
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

        <!-- Botão “Adicionar – R$ XX,XX” -->
        <button
          id="btn-add"
          class="flex-1 flex items-center justify-center bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 rounded"
        >
          Adicionar – R$ <?= formataPrecoBR($precoTotal) ?>
        </button>
      </div>
    </div>

    <!-- ================================
         RESUMO DO PEDIDO
         ================================ -->
    <div class="px-4 py-4">
      <h2 class="text-teal-500 font-semibold text-lg mb-2">Resumo:</h2>

      <!-- Linha da promoção / pizza (exibe header e sabor) -->
      <div class="mb-3">
        <!-- Cabeçalho da seção (cinza-claro) -->
        <div class="bg-gray-200 px-3 py-1">
          <span class="text-gray-800 font-medium text-sm"><?= htmlspecialchars($produtoNome) ?></span>
        </div>
        <!-- Variante escolhida (ex.: ITALIANINHA) e preço adicional (neste exemplo, 0) -->
        <div class="flex justify-between items-center px-3 py-2 border border-gray-200">
          <span class="text-gray-800 text-sm">
            <?= $quantidade ?> – <?= htmlspecialchars($variante) ?>
          </span>
          <span class="text-gray-800 text-sm">+ R$ 0,00</span>
        </div>
      </div>

      <!-- Linha do refrigerante -->
      <div class="mb-3">
        <!-- Cabeçalho da seção -->
        <div class="bg-gray-200 px-3 py-1">
          <span class="text-gray-800 font-medium text-sm">ESCOLHA SEU REFRIGERANTE</span>
        </div>
        <div class="flex justify-between items-center px-3 py-2 border border-gray-200">
          <span class="text-gray-800 text-sm">
            <?= $quantidade ?> – <?= htmlspecialchars($refrigerante) ?>
          </span>
          <span class="text-gray-800 text-sm">+ R$ 0,00</span>
        </div>
      </div>
    </div>

  </div>

  <!-- ================================
       SCRIPT PARA CONTROLE DE QUANTIDADE E AÇÃO DOS BOTÕES
       ================================ -->
  <script>
    const btnPlus  = document.getElementById('btn-plus');
    const btnMinus = document.getElementById('btn-minus');
    const inputQty = document.getElementById('input-qty');
    const btnAdd   = document.getElementById('btn-add');
    const btnBack  = document.getElementById('btn-back');

    // Preço unitário (do POST) convertido em float
    const precoUnitario = <?= $precoUnitario ?>;

    // Função para formatar em “pt-BR” com duas casas decimais
    function formataBR(valor) {
      return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    }

    // Função que atualiza o texto do botão “Adicionar”
    function atualizarBotao() {
      let qtd = parseInt(inputQty.value, 10);
      let total = precoUnitario * qtd;
      btnAdd.innerText = `Adicionar – R$ ${formataBR(total)}`;
    }

    // Ao clicar em “+”, incrementa até o limite que você desejar (ex.: 99)
    btnPlus.addEventListener('click', () => {
      let qtd = parseInt(inputQty.value, 10);
      qtd++;
      inputQty.value = qtd;
      atualizarBotao();
    });

    // Ao clicar em “–”, decremente mas nunca deixe abaixo de 1
    btnMinus.addEventListener('click', () => {
      let qtd = parseInt(inputQty.value, 10);
      if (qtd > 1) {
        qtd--;
        inputQty.value = qtd;
        atualizarBotao();
      }
    });

    // Botão “Voltar”: retorna ao passo anterior
    btnBack.addEventListener('click', () => {
      history.back();
    });

    // Inicializa o botão “Adicionar” com o valor correto
    atualizarBotao();
  </script>
</body>
</html>
