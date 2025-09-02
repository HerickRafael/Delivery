<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';

class PublicHomeController extends Controller {
  private function loadHours(int $companyId): array {
    $st = db()->prepare("SELECT * FROM company_hours WHERE company_id=? ORDER BY weekday");
    $st->execute([$companyId]);
    $rows = $st->fetchAll();
    $by=[]; foreach($rows as $r){ $by[(int)$r['weekday']]=$r; }
    return $by;
  }

  /** Verifica se está aberto agora e gera rótulo do dia (suporta virada de dia). */
  private function openNow(array $todayRow): array {
    // use o timezone da config (padrão SP)
    date_default_timezone_set(config('timezone') ?? 'America/Sao_Paulo');

    $today = new DateTime('today 00:00:00');
    $tomorrow = (clone $today)->modify('+1 day');
    $now = new DateTime();

    $ranges = [];
    $mkRange = function(?string $o, ?string $c) use ($today, $tomorrow) {
      if (!$o || !$c) return null;
      $open  = DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' ' . $o);
      $close = DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' ' . $c);
      if (!$open || !$close) return null;
      if ($close < $open) { // atravessa meia-noite
        $close = DateTime::createFromFormat('Y-m-d H:i:s', $tomorrow->format('Y-m-d') . ' ' . $c);
      }
      return [$open, $close];
    };

    if (!empty($todayRow['is_open'])) {
      if ($r = $mkRange($todayRow['open1'] ?? null, $todayRow['close1'] ?? null)) $ranges[] = $r;
      if ($r = $mkRange($todayRow['open2'] ?? null, $todayRow['close2'] ?? null)) $ranges[] = $r;
    }

    $open = false;
    foreach ($ranges as [$a,$b]) {
      if ($now >= $a && $now <= $b) { $open = true; break; }
    }

    // label amigável do dia
    $label = 'Fechado hoje';
    if (!empty($todayRow['is_open']) && !empty($todayRow['open1']) && !empty($todayRow['close1'])) {
      $label = substr($todayRow['open1'],0,5) . ' - ' . substr($todayRow['close1'],0,5);
      if (!empty($todayRow['open2']) && !empty($todayRow['close2'])) {
        $label .= ' / ' . substr($todayRow['open2'],0,5) . ' - ' . substr($todayRow['close2'],0,5);
      }
    }

    return [$open, $label];
  }

  public function index($params) {
    $slug = $params['slug'] ?? null;
    $q = isset($_GET['q']) ? trim($_GET['q']) : null;

    $company = Company::findBySlug($slug);
    if (!$company || !$company['active']) {
      http_response_code(404);
      echo "Empresa não encontrada";
      return;
    }

    // ⚠️ Garanta o timezone ANTES de calcular o dia atual
    date_default_timezone_set(config('timezone') ?? 'America/Sao_Paulo');

    $categories = Category::listByCompany((int)$company['id']);
    $products   = Product::listByCompany((int)$company['id'], $q);

    // horários
    $hours = $this->loadHours((int)$company['id']);
    $w = (int)date('N'); // 1=Seg .. 7=Dom (agora no fuso correto)
    $today = $hours[$w] ?? ['is_open'=>0];
    [$isOpenNow, $todayLabel] = $this->openNow($today);

    return $this->view('public/home', compact(
      'company',
      'categories',
      'products',
      'q',
      'hours',
      'isOpenNow',
      'todayLabel'
    ));
  }
}
