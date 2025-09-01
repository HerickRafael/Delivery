<?php
$title = ($company['name'] ?? 'Cardápio') . ' - Cardápio';
ob_start(); ?>
<header class="flex items-center gap-3 mb-6">
  <img src="<?= base_url($company['logo'] ?: 'assets/logo-placeholder.png') ?>" class="w-14 h-14 rounded-xl object-cover" alt="<?= e($company['name']) ?>">
  <div>
    <h1 class="text-2xl font-bold"><?= e($company['name']) ?></h1>
    <p class="text-sm text-gray-600"><?= e($company['address'] ?? '') ?></p>
  </div>
  <?php if (!empty($company['whatsapp'])): ?>
    <a class="ml-auto px-3 py-2 rounded-xl border" href="https://wa.me/<?= e($company['whatsapp']) ?>" target="_blank">WhatsApp</a>
  <?php endif; ?>
</header>

<section class="grid md:grid-cols-3 gap-4">
  <?php foreach ($products as $p): ?>
    <div class="rounded-2xl shadow p-4 bg-white border">
      <img src="<?= base_url($p['image'] ?: 'assets/logo-placeholder.png') ?>" alt="<?= e($p['name']) ?>" class="w-full h-40 object-cover rounded-xl mb-3">
      <h3 class="font-semibold text-lg"><?= e($p['name']) ?></h3>
      <?php if (!empty($p['description'])): ?>
        <p class="text-sm text-gray-600 line-clamp-2"><?= e($p['description']) ?></p>
      <?php endif; ?>
      <div class="mt-2">
        <?php if (!empty($p['promo_price'])): ?>
          <span class="text-sm text-gray-400 line-through">R$ <?= number_format($p['price'],2,',','.') ?></span>
          <span class="ml-2 text-xl font-bold">R$ <?= number_format($p['promo_price'],2,',','.') ?></span>
        <?php else: ?>
          <span class="text-xl font-bold">R$ <?= number_format($p['price'],2,',','.') ?></span>
        <?php endif; ?>
      </div>
      <?php if (!empty($company['whatsapp'])): ?>
      <a href="https://wa.me/<?= e($company['whatsapp']) ?>?text=Quero%20o%20<?= urlencode($p['name']) ?>"
         class="mt-3 inline-block w-full text-center rounded-xl py-2 border font-medium">
         Pedir no WhatsApp
      </a>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
