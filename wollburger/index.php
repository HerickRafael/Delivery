<?php
// pagina.php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>InstaDelivery – Layout Completo</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <!-- Container principal (tamanho máximo para dispositivos móveis/tablet) -->
  <div class="max-w-md mx-auto bg-white shadow-lg">
    
    <!-- ================================
         SEÇÃO 1: Cabeçalho com imagem, logo, status e primeiros produtos
         ================================ -->
    <div class="relative">
      <!-- Imagem de fundo (ex: pizza) -->
      <img
        src="pizza.jpg"
        alt="Pizza de Fundo"
        class="w-full h-48 object-cover"
      />
      <!-- Botão de compartilhar no canto superior direito -->
      <button
        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 p-2 rounded-full"
      >
        <!-- Ícone de “share” (Heroicon) -->
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5 text-white"
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path
            d="M15 8a3 3 0 0 0-2.995 2.824L12 11v1.586L6.707 9.293a1 1 0 0 0-1.414 1.414l5.293 3.293V17a1 1 0 1 0 2 0v-2.586l5.293-3.293A1 1 0 0 0 18.293 9.293L12 5.707V7a3 3 0 0 0 3 3z"
          />
        </svg>
      </button>
      <!-- Logo circular sobreposto na parte inferior esquerda -->
      <div class="absolute bottom-0 left-4 transform translate-y-1/2">
        <img
          src="logo.png"
          alt="Logo InstaDelivery"
          class="w-16 h-16 rounded-full border-2 border-white object-cover"
        />
      </div>
    </div>

    <!-- Espaçamento para compensar a sobreposição da logo -->
    <div class="h-8"></div>

    <div class="px-4 pb-6">
      <!-- Status “Aberto!”, horário, info e botão Login -->
      <div class="flex justify-between items-center">
        <div class="flex items-center space-x-2">
          <span
            class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded"
            >Aberto!</span
          >
          <span class="text-gray-700 text-xs">00:00 - 23:59</span>
          <button class="text-gray-400 hover:text-gray-600">
            <!-- Ícone de informação -->
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <circle
                cx="12"
                cy="12"
                r="10"
                stroke-width="2"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 16v-4m0-4h.01"
              />
            </svg>
          </button>
        </div>
        <button
          class="bg-blue-900 hover:bg-blue-800 text-white text-sm px-4 py-1 rounded"
        >
          Login
        </button>
      </div>

      <!-- Frete grátis, pedido mínimo e cashback + avaliação -->
      <div class="mt-2 flex justify-between items-center">
        <div class="space-y-1">
          <p class="text-gray-800 text-sm">
            Entrega grátis acima de
            <span class="font-bold text-green-600">R$ 30,00</span>
          </p>
          <p class="text-gray-800 text-sm">
            Pedido mínimo:
            <span class="font-bold text-green-600">R$ 5,00</span>
          </p>
          <p class="text-gray-800 text-sm">5% de cashback!</p>
        </div>
        <div class="flex items-center">
          <!-- Ícone da estrela -->
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-yellow-400"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              d="M9.049 2.927A1 1 0 0110 2a1 1 0 01.951.927l.717 3.326 3.47.505a1 1 0 01.562 1.705l-2.508 2.444.592 3.454a1 1 0 01-1.451 1.054L10 13.187l-3.095 1.626a1 1 0 01-1.451-1.054l.592-3.454L3.538 7.458a1 1 0 01.562-1.705l3.47-.505.717-3.326z"
            />
          </svg>
          <span class="text-gray-700 ml-1 text-sm">(4.8)</span>
        </div>
      </div>

      <!-- Banner de boas-vindas -->
      <div
        class="mt-4 bg-gray-800 text-white text-sm px-4 py-3 rounded-lg text-center"
      >
        Bem-vindo ao InstaDelivery, a plataforma que mais cresce no Brasil! Veja
        como é fácil e prático!
      </div>

      <!-- Menu de abas (tabs) -->
      <div class="mt-4">
        <nav class="flex space-x-2 overflow-x-auto">
          <!-- Aba ativa: “Topo” -->
          <button
            class="px-3 py-1 bg-gray-900 text-white text-xs font-medium rounded"
          >
            Topo
          </button>
          <!-- Abas inativas -->
          <button
            class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded"
          >
            Burgers &amp; Lanches
          </button>
          <button
            class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded"
          >
            Marmitas
          </button>
          <button
            class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded"
          >
            Pizzas Salgadas
          </button>
        </nav>
      </div>

      <!-- Campo de busca com ícone de lupa -->
      <div class="mt-4 relative">
        <span class="absolute inset-y-0 left-3 flex items-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-gray-400"
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
        <input
          type="text"
          placeholder="Digite para buscar um item"
          class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <!-- Botão “Mais Pedidos” -->
      <div class="mt-4">
        <button
          class="w-full bg-gray-900 hover:bg-gray-800 text-orange-500 font-bold py-2 rounded text-sm"
        >
          Mais Pedidos &rarr;
        </button>
      </div>

      <!-- Cards iniciais (grid 2 colunas) -->
      <div class="mt-4 grid grid-cols-2 gap-4">
        <!-- CARD EXEMPLO 1 -->
        <div class="relative bg-white rounded-lg shadow overflow-hidden">
          <span
            class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded"
            >Explosão</span
          >
          <span
            class="absolute top-2 right-2 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded"
            >+ Vendido</span
          >
          <img
            src="burger.jpg"
            alt="Burger Explosão"
            class="w-full h-32 object-cover"
          />
          <div class="p-2">
            <p class="text-gray-800 text-sm font-semibold">Burger Explosão</p>
            <p class="text-gray-600 text-xs">R$ 25,00</p>
          </div>
        </div>
        <!-- CARD EXEMPLO 2 -->
        <div class="relative bg-white rounded-lg shadow overflow-hidden">
          <span
            class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded"
            >Promoção!</span
          >
          <img
            src="sandwich.jpg"
            alt="Super Sanduíche"
            class="w-full h-32 object-cover"
          />
          <div class="p-2">
            <p class="text-gray-800 text-sm font-semibold">Super Sanduíche</p>
            <p class="text-gray-600 text-xs line-through">R$ 20,00</p>
            <p class="text-red-600 text-sm font-bold">R$ 18,00</p>
          </div>
        </div>
        <!-- Você pode duplicar esses blocos para inserir mais produtos -->
      </div>
    </div>

    <!-- ================================
         SEÇÃO 2: Burgers & Lanches (aba ativa)
         ================================ -->
    <div class="px-4 pb-8">
      <!-- Título da seção em fundo preto -->
      <div class="bg-gray-900 text-white text-lg font-bold px-4 py-2 rounded-t">
        Burgers &amp; Lanches
      </div>
      <!-- Imagem/banner de destaque (ex: dois burgers com fogo atrás) -->
      <img
        src="burger-banner.jpg"
        alt="Banner Burgers & Lanches"
        class="w-full h-48 object-cover"
      />

      <!-- Lista de cards (uma coluna, espaçamento vertical) -->
      <div class="space-y-4 mt-4">

        <!-- CARD: X-Tudo -->
        <div class="bg-white shadow rounded-lg p-4 flex space-x-4">
          <!-- Imagem pequena à esquerda -->
          <div class="relative w-24 h-24 flex-shrink-0">
            <img
              src="xtudo.jpg"
              alt="X-Tudo"
              class="w-full h-full object-cover rounded"
            />
            <!-- Rótulos sobrepostos -->
            <span
              class="absolute top-0 left-0 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-br"
              >Explosão</span
            >
            <span
              class="absolute top-0 right-0 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded-bl"
              >+ Vendido</span
            >
          </div>
          <!-- Conteúdo textual -->
          <div class="flex flex-col justify-between flex-1">
            <div>
              <p class="text-gray-800 font-semibold">X-Tudo</p>
              <p class="text-gray-600 text-sm mt-1">
                Um delicioso hambúrguer X-Tudo com carne suculenta, queijo
                derretido, bacon crocante, sala... 
                <a href="#" class="text-blue-500 hover:underline">Ver mais</a>
              </p>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-800 font-bold">R$ 40,00</span>
              <button
                class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-3 py-1 rounded"
              >
                Pedir
              </button>
            </div>
          </div>
        </div>

        <!-- CARD: Misto Quente -->
        <div class="bg-white shadow rounded-lg p-4 flex space-x-4">
          <div class="relative w-24 h-24 flex-shrink-0">
            <img
              src="misto.jpg"
              alt="Misto Quente"
              class="w-full h-full object-cover rounded"
            />
            <span
              class="absolute top-0 left-0 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-br"
              >Promoção!</span
            >
          </div>
          <div class="flex flex-col justify-between flex-1">
            <div>
              <p class="text-gray-800 font-semibold">Misto Quente</p>
              <p class="text-gray-600 text-sm mt-1">
                Pão de forma, queijo mussarela e presunto
              </p>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-800 font-bold">R$ 29,90</span>
              <button
                class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-3 py-1 rounded"
              >
                Pedir
              </button>
            </div>
          </div>
        </div>

        <!-- CARD: X-Salada (Esgotado) -->
        <div class="bg-white shadow rounded-lg p-4 flex space-x-4">
          <div class="relative w-24 h-24 flex-shrink-0">
            <img
              src="xsalada.jpg"
              alt="X-Salada"
              class="w-full h-full object-cover rounded"
            />
            <span
              class="absolute top-0 left-0 bg-gray-400 text-white text-xs font-bold px-2 py-1 rounded-br"
              >Esgotado</span
            >
          </div>
          <div class="flex flex-col justify-between flex-1">
            <div>
              <p class="text-gray-800 font-semibold">X-Salada</p>
              <p class="text-gray-600 text-sm mt-1">
                Delicioso lanche com hambúrguer suculento, queijo derretido,
                salada fresca, maionese e ket...
              </p>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-800 font-bold">R$ 29,90</span>
              <button
                class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded opacity-50 cursor-not-allowed"
                disabled
              >
                Pedir
              </button>
            </div>
          </div>
        </div>

        <!-- -> Você pode duplicar esse bloco para adicionar novos produtos -> -->

      </div>
    </div>
  </div>
</body>
</html>
