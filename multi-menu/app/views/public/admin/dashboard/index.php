<?php
$title = "Dashboard - " . ($company['name'] ?? 'Empresa');
ob_start(); ?>
<header class="flex items-center gap-3 mb-6">
  <img src="<?= base_url($company['logo'] ?: 'assets/logo-placeholder.png') ?>" class="w-12 h-12 rounded-xl">
  <div>
    <h1 class="text-xl font-bold"><?= e($company['name'] ?? '') ?></h1>
    <p class="text-sm text-gray-600">Categorias: <?= count($categories) ?> • Produtos: <?= count($products) ?></p>
  </div>
  <a class="ml-auto px-3 py-2 rounded-xl border" href="<?= base_url('admin/' . $company['slug'] . '/logout') ?>">Sair</a>
</header>

<div class="grid md:grid-cols-2 gap-4">
  <div class="rounded-2xl bg-white border p-4">
    <h2 class="font-semibold mb-2">Categorias</h2>
    <ul class="list-disc ml-5">
      <?php foreach ($categories as $c): ?>
        <li><?= e($c['name']) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="rounded-2xl bg-white border p-4">
    <h2 class="font-semibold mb-2">Produtos</h2>
    <ul class="list-disc ml-5">
      <?php foreach ($products as $p): ?>
        <li><?= e($p['name']) ?> — R$ <?= number_format($p['price'],2,',','.') ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
