<?php
// cart.php
session_start();

// ─────────────────────────────────────────────────────────────────────────────
// [1] – LÓGICA DE SESSÃO, ADIÇÃO/REMOÇÃO/ATUALIZAÇÃO DE ITENS, CUPOM, CÁLCULO DE SUBTOTAL ETC.
//      (exatamente como mostrado anteriormente). Vamos supor que, ao chegar aqui,
//      já temos os valores de $subtotal, $_SESSION['carrinho'], $qtdItens, $msgCupom.
// ─────────────────────────────────────────────────────────────────────────────

// Função de formatação de preço
function formataPrecoBR(float $valor): string {
    return number_format($valor, 2, ',', '.');
}

// Exemplo simplificado de variáveis já calculadas:
$qtdItensDiferentes = isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0;
$subtotal = 0.0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $subtotal += $item['preco_unitario'] * $item['quantidade'];
    }
}
$taxaEntrega = null; // ainda não calculada
$total = $subtotal;  // neste exemplo, sem frete
$msgCupom = '';      // mensagem de cupom (preenchida via POST, se houver)

// … caso haja lógica de cupom, remoção, plus/minus, etc., ela viria aqui …
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Carrinho – InstaDelivery</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-600">
  <!-- ───────────────────────────────────────────────────────────────────────
       CONTAINER BRANCO PRINCIPAL (todos os campos e seções dentro dele)
       ─────────────────────────────────────────────────────────────────────── -->
  <div class="max-w-md mx-auto mt-6 bg-white rounded-t-lg shadow-lg overflow-hidden">

    <!-- [HEADER] Carrinho + Adicionar Itens -->
    <div class="flex items-center justify-between px-4 py-3 border-b">
      <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-6 w-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6m0 0a1 1 0 
                001 1h12a1 1 0 001-1l-1.2-6M7 13h10M17 21a1 1 0 
                11-2 0 1 1 0 012 0m-8 0a1 1 0 11-2 0 1 1 0 012 0" />
        </svg>
        <h1 class="text-gray-800 font-semibold text-lg">Carrinho</h1>
      </div>
      <a href="index.php"
         class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-3 py-1 rounded">
        + ADICIONAR ITENS
      </a>
    </div>

    <!-- [ITENS DO CARRINHO] -->
    <?php if ($qtdItensDiferentes === 0): ?>
      <div class="p-4 text-center text-gray-700">
        Seu carrinho está vazio.
      </div>
    <?php else: ?>
      <div class="divide-y divide-gray-200">
        <?php foreach ($_SESSION['carrinho'] as $idx => $item): ?>
          <?php $totalItem = $item['preco_unitario'] * $item['quantidade']; ?>
          <div class="px-4 py-3 flex flex-col space-y-2">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <a href="cart.php?action=minus&idx=<?= $idx ?>"
                   class="w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded">
                  <svg xmlns="http://www.w3.org/2000/svg"
                       class="h-5 w-5 text-gray-700"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M20 12H4" />
                  </svg>
                </a>
                <span class="text-red-600 font-semibold"><?= $item['quantidade'] ?>x</span>
                <a href="cart.php?action=plus&idx=<?= $idx ?>"
                   class="w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded">
                  <svg xmlns="http://www.w3.org/2000/svg"
                       class="h-5 w-5 text-gray-700"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </a>
                <span class="text-gray-800 font-medium">
                  <?= htmlspecialchars($item['produto']) ?>
                </span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="text-gray-800 font-semibold">
                  R$ <?= formataPrecoBR($totalItem) ?>
                </span>
                <a href="cart.php?remove=<?= $idx ?>"
                   class="text-gray-500 hover:text-gray-700">
                  <svg xmlns="http://www.w3.org/2000/svg"
                       class="h-5 w-5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 
                          21H7.862a2 2 0 01-1.995-1.858L5 7m5 
                          4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v2H9V4a1 1 
                          0 011-1z" />
                  </svg>
                </a>
              </div>
            </div>
            <div class="pl-14 text-gray-500 text-sm flex flex-col space-y-0.5">
              <?php if (!empty($item['variante'])): ?>
                <span><?= $item['quantidade'] ?>x <?= htmlspecialchars($item['variante']) ?></span>
              <?php endif; ?>
              <?php if (!empty($item['refrigerante'])): ?>
                <span><?= $item['quantidade'] ?>x <?= htmlspecialchars($item['refrigerante']) ?></span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- [CUPOM + SUBTOTAL / TAXA / TOTAL] -->
    <div class="px-4 py-4 border-t border-gray-200">
      <?php if ($msgCupom !== ''): ?>
        <div class="mb-2 text-sm text-red-600">
          <?= htmlspecialchars($msgCupom) ?>
        </div>
      <?php endif; ?>
      <form action="cart.php" method="post" class="flex space-x-2">
        <input type="text"
               name="cupom"
               placeholder="Cupom de desconto"
               class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
        />
        <button type="submit"
                class="bg-teal-500 hover:bg-teal-600 text-white font-medium px-4 py-2 rounded text-sm">
          Aplicar
        </button>
      </form>
      <div class="mt-4 space-y-1 text-gray-800 text-sm">
        <div class="flex justify-between">
          <span>Subtotal do pedido:</span>
          <span>R$ <?= formataPrecoBR($subtotal) ?></span>
        </div>
        <div class="flex justify-between">
          <span>Taxa de entrega:</span>
          <span class="text-gray-500 italic">
            <?= $taxaEntrega === null ? 'Escolha o bairro' : 'R$ ' . formataPrecoBR($taxaEntrega) ?>
          </span>
        </div>
        <div class="flex justify-between font-semibold">
          <span>Total:</span>
          <span>R$ <?= formataPrecoBR($total) ?></span>
        </div>
      </div>
    </div>

    <!-- [FORMA DE PAGAMENTO] -->
    <div class="px-4 py-4 border-t border-gray-200">
      <div class="flex items-center space-x-2 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5 text-gray-800"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2"
                d="M3 10h18M3 6h18M5 14h.01M9 14h.01M13 
                14h.01M17 14h.01M5 18h.01M9 18h.01M13 
                18h.01M17 18h.01M3 20h18" />
        </svg>
        <h2 class="text-gray-800 font-semibold text-lg">Forma de pagamento</h2>
      </div>
      <form id="form_pagamento" action="process_payment.php" method="post" class="space-y-2">
        <?php
          $metodos = ['ROOMCARD', 'Dinheiro', 'STYVEPG', 'LECARD', 'STYVEF', 'AMEX'];
          foreach ($metodos as $metodo):
        ?>
          <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 px-2 py-1 rounded">
            <input type="radio"
                   name="forma_pagamento"
                   value="<?= $metodo ?>"
                   class="form-radio h-4 w-4 text-teal-500"
                   required
            />
            <span class="text-gray-800"><?= $metodo ?></span>
          </label>
        <?php endforeach; ?>
      </form>
    </div>

    <!-- [TIPO DE PEDIDO] -->
    <div class="px-4 py-4 border-t border-gray-200">
      <h2 class="text-gray-800 font-semibold mb-2">Tipo de pedido</h2>
      <div class="flex items-center space-x-4">
        <label class="flex items-center space-x-1 cursor-pointer">
          <input type="radio" name="tipo_pedido" value="delivery"
                 class="form-radio h-4 w-4 text-teal-500" checked />
          <svg xmlns="http://www.w3.org/2000/svg"
               class="h-5 w-5 text-gray-600"
               fill="none" stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6m0 0a1 1 0 
                  001 1h12a1 1 0 001-1l-1.2-6M7 13h10M17 21a1 1 0 
                  11-2 0 1 1 0 012 0m-8 0a1 1 0 11-2 0 1 1 0 012 0" />
          </svg>
          <span class="text-gray-800 font-medium">Delivery</span>
        </label>
        <label class="flex items-center space-x-1 cursor-pointer">
          <input type="radio" name="tipo_pedido" value="retirada"
                 class="form-radio h-4 w-4 text-teal-500" />
          <svg xmlns="http://www.w3.org/2000/svg"
               class="h-5 w-5 text-gray-600"
               fill="none" stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 9l9-7 9 7v11a2 2 0 
                  01-2 2H5a2 2 0 01-2-2zM9 22V12h6v10" />
          </svg>
          <span class="text-gray-800 font-medium">Retirada</span>
        </label>
      </div>
    </div>

    <!-- [DADOS DO CLIENTE] -->
    <form id="form_dados" action="finalizar_pedido.php" method="post" class="space-y-4">

      <!-- Nome (apenas 1ª vez) -->
      <div class="px-4">
        <label for="nome_cliente"
               class="block text-gray-800 font-medium mb-1">
          Nome (apenas 1ª vez)
        </label>
        <input
          type="text"
          id="nome_cliente"
          name="nome_cliente"
          placeholder="Digite seu nome"
          class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
          required
        />
      </div>

      <!-- WhatsApp (somente dígitos) -->
      <div class="px-4">
        <label for="whatsapp_cliente"
               class="block text-gray-800 font-medium mb-1">
          Seu WhatsApp (somente dígitos)
        </label>
        <input
          type="tel"
          id="whatsapp_cliente"
          name="whatsapp_cliente"
          placeholder="(xx) xxxxx-xxxx"
          pattern="[0-9]{10,11}"
          class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
          required
        />
      </div>

      <!-- Aniversário (opcional, para brindes) -->
      <div class="px-4">
        <label for="aniversario_cliente"
               class="block text-gray-800 font-medium mb-1">
          Aniversário (opcional, para brindes)
        </label>
        <input
          type="text"
          id="aniversario_cliente"
          name="aniversario_cliente"
          placeholder="dd/mm"
          maxlength="5"
          class="w-32 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
        />
      </div>

      <!-- Cidade -->
      <div class="px-4">
        <label for="cidade"
               class="block text-gray-800 font-medium mb-1">
          Cidade
        </label>
        <select
          id="cidade"
          name="cidade"
          class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-teal-400"
          required
        >
          <option value="" disabled selected>Escolha a cidade</option>
          <option value="Tramandaí">Tramandaí</option>
          <option value="Imbé">Imbé</option>
          <option value="Capão da Canoa">Capão da Canoa</option>
          <option value="Torres">Torres</option>
          <!-- … outras cidades … -->
        </select>
      </div>

      <!-- Bairro / Condomínio -->
      <div class="px-4">
        <label for="bairro"
               class="block text-gray-800 font-medium mb-1">
          Escolha seu bairro/condomínio
        </label>
        <select
          id="bairro"
          name="bairro"
          class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-teal-400"
          required
        >
          <option value="" disabled selected>Selecione o bairro</option>
          <option value="Centro">Centro</option>
          <option value="Zona Sul">Zona Sul</option>
          <option value="Bairro das Nações">Bairro das Nações</option>
          <option value="Condomínio X">Condomínio X</option>
          <!-- … outros bairros … -->
        </select>
      </div>

      <!-- Endereço -->
      <div class="px-4">
        <label for="endereco"
               class="block text-gray-800 font-medium mb-1">
          Endereço
        </label>
        <div class="relative flex items-center">
          <!-- Ícone de localização -->
          <svg xmlns="http://www.w3.org/2000/svg"
               class="absolute left-3 h-5 w-5 text-gray-400"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 
                  1.343-3 3 1.343 3 3 3zm0 0v8m0 4h.01" />
          </svg>
          <input
            type="text"
            id="endereco"
            name="endereco"
            placeholder="Digite seu endereço"
            class="w-full pl-10 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
            required
          />
        </div>
      </div>

      <!-- Número -->
      <div class="px-4">
        <label for="numero"
               class="block text-gray-800 font-medium mb-1">
          Número
        </label>
        <div class="relative flex items-center">
          <!-- Ícone de etiqueta -->
          <svg xmlns="http://www.w3.org/2000/svg"
               class="absolute left-3 h-5 w-5 text-gray-400"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 14L9 2 3 8l12 12 6-6zM8 8l6 6" />
          </svg>
          <input
            type="text"
            id="numero"
            name="numero"
            placeholder="Nº"
            class="w-32 pl-10 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
            required
          />
        </div>
      </div>

      <!-- APTO / Referência / Complemento -->
      <div class="px-4 pb-8">
        <label for="complemento"
               class="block text-gray-800 font-medium mb-1">
          APTO / Referência / Complemento
        </label>
        <div class="relative flex items-center">
          <!-- Ícone de informação -->
          <svg xmlns="http://www.w3.org/2000/svg"
               class="absolute left-3 h-5 w-5 text-gray-400"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M12 
                  21C7.029 21 3 16.971 3 12S7.029 3 12 
                  3s9 4.029 9 9-4.029 9-9 9z" />
          </svg>
          <input
            type="text"
            id="complemento"
            name="complemento"
            placeholder="Apto / Referência / Complemento"
            class="w-full pl-10 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
          />
        </div>
      </div>
    </form>

  </div> <!-- Fim do container branco -->

  <!-- [BOTÃO “ENVIAR PEDIDO →” FIXO NO RODAPÉ] -->
  <?php if ($qtdItensDiferentes > 0): ?>
    <div class="fixed bottom-0 inset-x-0 z-50">
      <!-- Fundo semitransparente escuro -->
      <div class="bg-black bg-opacity-30 h-full w-full absolute"></div>
      <!-- Caixa branca contendo o botão -->
      <div class="relative max-w-md mx-auto bg-white rounded-t-lg overflow-hidden shadow-lg">
        <button
          type="submit"
          form="form_dados"
          class="block w-full bg-orange-500 hover:bg-orange-600 text-white font-bold text-center py-3"
          onclick="document.getElementById('form_dados').submit();"
        >
          ENVIAR PEDIDO &rarr;
        </button>
      </div>
    </div>
  <?php endif; ?>

</body>
</html>
