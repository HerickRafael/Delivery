<?php
// pages/vendas/view.php
require_once(__DIR__ . "/../../includes/init.php");
require_once(__DIR__ . "/../../includes/conexao.php");

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "Venda inválida.";
    exit;
}

// 1) Primeiro, buscar dados da venda + cliente
$sqlVenda = "
  SELECT
    v.id,
    v.cliente_id,
    v.data,
    v.mes,
    v.ano,
    v.custo,
    v.faturamento,
    v.lucro,
    v.observacoes,
    c.nome       AS cliente_nome,
    c.cpf        AS cliente_cpf,
    c.celular    AS cliente_celular,
    c.endereco   AS cliente_endereco
  FROM vendas v
  LEFT JOIN clientes c ON (v.cliente_id = c.id)
  WHERE v.id = $id
  LIMIT 1
";
$resVenda = $conn->query($sqlVenda);
if (!$resVenda || $resVenda->num_rows !== 1) {
    echo "Venda não encontrada.";
    exit;
}
$venda = $resVenda->fetch_assoc();

// 2) Buscar itens dessa venda
$sqlItens = "
  SELECT
    vi.id           AS venda_item_id,
    vi.tipo         AS tipo,         -- 'produto' ou 'combo'
    vi.item_id      AS item_id,
    vi.quantidade   AS quantidade,
    vi.valor_unit   AS valor_unit,
    vi.custo_unit   AS custo_unit,
    -- Para mostrar descrição do produto/combo, fazemos LEFT JOIN condicional:
    p.descricao      AS produto_desc,
    c.descricao      AS combo_desc
  FROM venda_itens vi
  LEFT JOIN produtos p 
    ON (vi.tipo = 'produto' AND vi.item_id = p.id)
  LEFT JOIN combos c 
    ON (vi.tipo = 'combo' AND vi.item_id = c.id)
  WHERE vi.venda_id = $id
  ORDER BY vi.id ASC
";
$resItens = $conn->query($sqlItens);
if (!$resItens) {
    die("Erro ao buscar itens da venda: " . $conn->error);
}

$itens = [];
while ($row = $resItens->fetch_assoc()) {
    // Escolher a descrição correta:
    $descricao = ($row['tipo'] === 'produto')
                 ? $row['produto_desc']
                 : $row['combo_desc'];
    $itens[] = [
      'id'           => $row['venda_item_id'],
      'tipo'         => $row['tipo'],
      'item_id'      => $row['item_id'],
      'descricao'    => $descricao,
      'quantidade'   => $row['quantidade'],
      'valor_unit'   => $row['valor_unit'],
      'custo_unit'   => $row['custo_unit'],
      // podemos calcular subtotal e custo_total:
      'subtotal'     => $row['quantidade'] * $row['valor_unit'],
      'custo_total'  => $row['quantidade'] * $row['custo_unit'],
    ];
}

// 3) Exibir HTML com Tailwind
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Detalhes da Venda #<?= $venda['id'] ?> | Sistema</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <?php include(__DIR__ . "/../../includes/menu.php"); ?>

  <main class="ml-56 p-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Venda #<?= $venda['id'] ?></h1>
      <a href="list.php"
         class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
        &larr; Voltar
      </a>
    </div>

    <!-- Seção 1: Dados do Cliente -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
      <h2 class="text-xl font-semibold mb-4">Dados do Cliente</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p><span class="font-semibold">Nome:</span>
            <?= htmlspecialchars($venda['cliente_nome']) ?>
          </p>
        </div>
        <div>
          <p><span class="font-semibold">CPF:</span>
            <?= htmlspecialchars($venda['cliente_cpf']) ?>
          </p>
        </div>
        <div>
          <p><span class="font-semibold">Celular:</span>
            <?= htmlspecialchars($venda['cliente_celular']) ?>
          </p>
        </div>
        <div>
          <p><span class="font-semibold">Endereço:</span>
            <?= htmlspecialchars($venda['cliente_endereco']) ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Seção 2: Resumo da Venda -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
      <h2 class="text-xl font-semibold mb-4">Resumo da Venda</h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <p><span class="font-semibold">Data:</span>
            <?= date('d/m/Y', strtotime($venda['data'])) ?>
          </p>
        </div>
        <div>
          <p><span class="font-semibold">Mês:</span>
            <?= $venda['mes'] ?>
          </p>
        </div>
        <div>
          <p><span class="font-semibold">Ano:</span>
            <?= $venda['ano'] ?>
          </p>
        </div>
        <div>
          <p><span class="font-semibold">Observações:</span>
            <?= nl2br(htmlspecialchars($venda['observacoes'])) ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Seção 3: Itens da Venda -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-semibold mb-4">Itens Comprados</h2>
      <?php if (empty($itens)): ?>
        <p class="text-gray-600">Nenhum item registrado para esta venda.</p>
      <?php else: ?>
        <div class="overflow-auto">
          <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left">#</th>
                <th class="px-4 py-3 text-left">Descrição</th>
                <th class="px-4 py-3 text-right">Tipo</th>
                <th class="px-4 py-3 text-right">Qtd</th>
                <th class="px-4 py-3 text-right">Valor Unit. (R$)</th>
                <th class="px-4 py-3 text-right">Subtotal (R$)</th>
                <th class="px-4 py-3 text-right">Custo Unit. (R$)</th>
                <th class="px-4 py-3 text-right">Custo Total (R$)</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y">
              <?php foreach ($itens as $idx => $item): ?>
                <tr>
                  <td class="px-4 py-2"><?= $idx + 1 ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($item['descricao']) ?></td>
                  <td class="px-4 py-2 text-right"><?= ucfirst($item['tipo']) ?></td>
                  <td class="px-4 py-2 text-right"><?= $item['quantidade'] ?></td>
                  <td class="px-4 py-2 text-right">
                    <?= number_format($item['valor_unit'], 2, ',', '.') ?>
                  </td>
                  <td class="px-4 py-2 text-right">
                    <?= number_format($item['subtotal'], 2, ',', '.') ?>
                  </td>
                  <td class="px-4 py-2 text-right">
                    <?= number_format($item['custo_unit'], 2, ',', '.') ?>
                  </td>
                  <td class="px-4 py-2 text-right">
                    <?= number_format($item['custo_total'], 2, ',', '.') ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
