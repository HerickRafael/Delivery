<?php
// Inclua este arquivo logo após <body> em cada list.php/add.php/edit.php...
?>
<nav class="fixed inset-y-0 left-0 w-56 bg-gray-800 text-gray-100">
  <div class="p-4 text-center text-2xl font-bold">Hamburgueria</div>
  <?php if (isset($_SESSION['admin_user'])): ?>
    <div class="px-4 py-2 text-sm">
      Olá, <span class="font-semibold"><?= htmlspecialchars($_SESSION['admin_user']) ?></span>
    </div>
  <?php endif; ?>
  <ul class="mt-4">
    <li class="px-4 py-2 hover:bg-gray-700">
      <a href="/wollburger/pages/clientes/list.php" class="flex items-center space-x-2">
        <span>👤</span><span>Clientes</span>
      </a>
    </li>
    <li class="px-4 py-2 hover:bg-gray-700">
      <a href="/wollburger/pages/ingredientes/list.php" class="flex items-center space-x-2">
        <span>🌿</span><span>Ingredientes</span>
      </a>
    </li>
    <li class="px-4 py-2 hover:bg-gray-700">
      <a href="/wollburger/pages/produtos/list.php" class="flex items-center space-x-2">
        <span>🍔</span><span>Produtos</span>
      </a>
    </li>
    <li class="px-4 py-2 hover:bg-gray-700">
      <a href="/wollburger/pages/combos/list.php" class="flex items-center space-x-2">
        <span>🎁</span><span>Combos</span>
      </a>
    </li>
    <li class="px-4 py-2 hover:bg-gray-700">
      <a href="/wollburger/pages/vendas/list.php" class="flex items-center space-x-2">
        <span>💰</span><span>Vendas</span>
      </a>
    </li>
    <li class="px-4 py-2 hover:bg-gray-700">
      <a href="/wollburger/pages/despesas/list.php" class="flex items-center space-x-2">
        <span>📊</span><span>Despesas</span>
      </a>
    </li>
  </ul>
  <?php if (isset($_SESSION['admin_user'])): ?>
    <div class="absolute bottom-0 w-full">
      <a href="/wollburger/logout.php"
         class="block px-4 py-2 text-red-400 hover:bg-gray-700 hover:text-red-200">
        Logout
      </a>
    </div>
  <?php endif; ?>
</nav>
