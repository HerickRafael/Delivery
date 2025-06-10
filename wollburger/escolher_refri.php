<?php
// escolher_refri.php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Escolha Seu Refrigerante</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <!-- Container centralizado, largura máxima para mobile/tablet -->
  <div class="max-w-md mx-auto mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
    
    <!-- ================================
         HEADER: Imagem do combo + título + botão fechar
         ================================ -->
    <div class="flex items-center justify-between px-4 py-3 border-b">
      <!-- Bloco da esquerda: imagem e nome do combo -->
      <div class="flex items-center space-x-3">
        <!-- Miniatura da Pizza Grande + Refrigerante -->
        <div class="relative">
          <img
            src="pizza_grande_refri.jpg"
            alt="Pizza Grande + Refrigerante"
            class="w-16 h-16 object-cover rounded"
          />
          <!-- Ícone de lupa sobreposto no canto inferior direito (zoom) -->
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
        <!-- Título do produto -->
        <h1 class="text-gray-800 font-semibold text-lg leading-tight">
          PIZZA GRANDE +<br />REFRIGERANTE
        </h1>
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
         BOTÕES “Voltar” e “Avançar”
         ================================ -->
    <div class="px-4 py-3 border-b flex space-x-2">
      <!-- Botão Voltar (histórico) -->
      <button
        onclick="history.back()"
        class="flex-1 flex items-center justify-center space-x-1 bg-gray-700 hover:bg-gray-800 text-white font-medium py-2 rounded"
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

      <!-- Botão Avançar (submete o form para confirmar seleção) -->
      <!-- O atributo form="form-refri" associa este botão ao formulário abaixo -->
      <button
        form="form-refri"
        class="flex-1 flex items-center justify-center space-x-1 bg-teal-500 hover:bg-teal-600 text-white font-medium py-2 rounded"
      >
        <span>Avançar</span>
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
      </button>
    </div>

    <!-- ================================
         SUBTÍTULO “ESCOLHA SEU REFRIGERANTE (Obrigatório)”
         ================================ -->
    <div class="px-4 py-2 bg-gray-100">
      <p class="text-gray-800 text-sm">
        ESCOLHA SEU REFRIGERANTE 
        <span class="text-red-500">(Obrigatório)</span>
      </p>
    </div>

    <!-- ================================
         FORMULÁRIO DE SELEÇÃO DE REFRI
         ================================ -->
    <form id="form-refri" action="confirmacao_refri.php" method="post" class="px-4">
      <!-- Cada opção é um label clicável que engloba o input radio e o texto -->
      <label
        class="flex items-center py-3 border-b border-gray-200 cursor-pointer hover:bg-gray-50"
      >
        <input
          type="radio"
          name="refrigerante"
          value="COCA-COLA"
          class="form-radio h-4 w-4 text-teal-500"
          required
        />
        <span class="ml-3 text-gray-800 font-medium">COCA-COLA</span>
      </label>

      <label
        class="flex items-center py-3 border-b border-gray-200 cursor-pointer hover:bg-gray-50"
      >
        <input
          type="radio"
          name="refrigerante"
          value="COCA-COLA ZERO"
          class="form-radio h-4 w-4 text-teal-500"
        />
        <span class="ml-3 text-gray-800 font-medium">COCA-COLA ZERO</span>
      </label>

      <label
        class="flex items-center py-3 border-b border-gray-200 cursor-pointer hover:bg-gray-50"
      >
        <input
          type="radio"
          name="refrigerante"
          value="FRUKI GUARANÁ"
          class="form-radio h-4 w-4 text-teal-500"
        />
        <span class="ml-3 text-gray-800 font-medium">FRUKI GUARANÁ</span>
      </label>

      <label
        class="flex items-center py-3 border-b border-gray-200 cursor-pointer hover:bg-gray-50"
      >
        <input
          type="radio"
          name="refrigerante"
          value="SEM REFRIGERANTE"
          class="form-radio h-4 w-4 text-teal-500"
        />
        <span class="ml-3 text-gray-800 font-medium">SEM REFRIGERANTE</span>
      </label>
    </form>

  </div>
</body>
</html>
