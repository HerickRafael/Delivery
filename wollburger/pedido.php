<?php
// pedido.php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pedido – X-Tudo</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <!-- Container centralizado -->
  <div class="max-w-md mx-auto mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
    
    <!-- ================================
         HEADER DO PRODUTO
         ================================ -->
    <div class="flex items-center justify-between px-4 py-3 border-b">
      <!-- Bloco com imagem pequena + nome do produto -->
      <div class="flex items-center space-x-3">
        <!-- Miniatura do X-Tudo -->
        <div class="relative">
          <img
            src="xtudo.jpg"
            alt="X-Tudo"
            class="w-16 h-16 object-cover rounded"
          />
          <!-- ícone de lupa (zoom), sobreposto no canto inferior direito -->
          <span
            class="absolute bottom-0 right-0 bg-white p-1 rounded-full shadow"
          >
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
        <!-- Título do produto -->
        <h2 class="text-gray-800 font-semibold text-lg">X-Tudo</h2>
      </div>
      <!-- Botão de fechar (X) -->
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
         BOTÃO AVANÇAR
         ================================ -->
         <div class="px-4 py-4 border-b">
  <a
    href="observacao.php"
    class="w-full block bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 rounded flex items-center justify-center space-x-2 text-center"
  >
    <span>Avançar</span>
    <!-- seta para a direita -->
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
        d="M13 7l5 5m0 0l-5 5m5-5H6"
      />
    </svg>
  </a>
</div>


    <!-- ================================
         SUBTÍTULO “Turbine seu Lanche!”
         ================================ -->
    <div class="px-4 pt-4 pb-2 border-b">
      <p class="text-gray-800 text-sm">
        Turbine seu Lanche! 
        <span class="text-red-500">(Opcional, max 9)</span>
      </p>
    </div>

    <!-- ================================
         LISTA DE EXTRAS
         ================================ -->
    <div class="px-4 pb-6">
      <!-- LINHA: Dobro de Queijo -->
      <div class="flex items-center justify-between py-3 border-b">
        <!-- controles de quantidade + nome -->
        <div class="flex items-center space-x-3">
          <!-- botão “-” -->
          <button
            class="minus-btn w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-700"
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
          <!-- quantidade atual -->
          <span class="qty w-5 text-center text-gray-800">0</span>
          <!-- botão “+” -->
          <button
            class="plus-btn w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-700"
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
          <!-- nome do extra -->
          <span class="text-gray-800 text-sm">Dobro de Queijo</span>
        </div>
        <!-- preço à direita -->
        <span class="text-gray-800 text-sm">+ R$ 10,00</span>
      </div>

      <!-- LINHA: Hambúrguer Adicional -->
      <div class="flex items-center justify-between py-3 border-b">
        <div class="flex items-center space-x-3">
          <button
            class="minus-btn w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-700"
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
          <span class="qty w-5 text-center text-gray-800">0</span>
          <button
            class="plus-btn w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-700"
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
          <span class="text-gray-800 text-sm">Hambúrguer Adicional</span>
        </div>
        <span class="text-gray-800 text-sm">+ R$ 5,00</span>
      </div>

      <!-- LINHA: Bacon -->
      <div class="flex items-center justify-between py-3 border-b">
        <div class="flex items-center space-x-3">
          <button
            class="minus-btn w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-700"
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
          <span class="qty w-5 text-center text-gray-800">0</span>
          <button
            class="plus-btn w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-700"
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
          <span class="text-gray-800 text-sm">Bacon</span>
        </div>
        <span class="text-gray-800 text-sm">+ R$ 3,00</span>
      </div>

      <!-- LINHA: Turbinado -->
      <div class="flex items-center justify-between py-3">
        <div class="flex items-center space-x-3">
          <button
            class="minus-btn w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-700"
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
          <span class="qty w-5 text-center text-gray-800">0</span>
          <button
            class="plus-btn w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-gray-700"
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
          <span class="text-gray-800 text-sm">Turbinado</span>
        </div>
        <span class="text-gray-800 text-sm">+ R$ 25,00</span>
      </div>
    </div>
  </div>

  <!-- ================================
       JavaScript mínimo para incrementar/decrementar quantidades
       ================================ -->
  <script>
    // Seleciona todos os botões “+”
    document.querySelectorAll('.plus-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const qtySpan = btn.parentElement.querySelector('.qty');
        let quantidade = parseInt(qtySpan.innerText);
        if (quantidade < 9) {
          qtySpan.innerText = quantidade + 1;
        }
      });
    });

    // Seleciona todos os botões “-”
    document.querySelectorAll('.minus-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const qtySpan = btn.parentElement.querySelector('.qty');
        let quantidade = parseInt(qtySpan.innerText);
        if (quantidade > 0) {
          qtySpan.innerText = quantidade - 1;
        }
      });
    });
  </script>
</body>
</html>
