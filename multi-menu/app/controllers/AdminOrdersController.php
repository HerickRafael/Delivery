<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Order.php';

class AdminOrdersController extends Controller
{
  public function index(array $params)
  {
    $this->requireAdmin();
    $db   = $this->db();
    $slug = trim((string)($params['slug'] ?? ''));

    $company = Company::findBySlug($slug);
    if (!$company) { http_response_code(404); echo "Empresa inválida"; return; }
    $this->ensureCompanyContext((int)$company['id'], $slug);

    $companyId = (int)$company['id'];
    $status    = $_GET['status'] ?? null;

    $orders = Order::listByCompany($db, $companyId, $status, 50, 0);

    $activeSlug = $this->currentCompanySlug() ?? $slug;
    return $this->view('admin/orders/index', compact('orders','status','activeSlug','company'));
  }

  public function show(array $params)
  {
    $this->requireAdmin();
    $db   = $this->db();
    $slug = trim((string)($params['slug'] ?? ''));

    $company = Company::findBySlug($slug);
    if (!$company) { http_response_code(404); echo "Empresa inválida"; return; }
    $this->ensureCompanyContext((int)$company['id'], $slug);

    $companyId = (int)$company['id'];
    $orderId   = (int)($_GET['id'] ?? 0);

    $order = Order::findWithItems($db, $orderId, $companyId);
    if (!$order) { http_response_code(404); echo "Pedido não encontrado"; return; }

    $activeSlug = $this->currentCompanySlug() ?? $slug;
    return $this->view('admin/orders/show', compact('order','activeSlug','company'));
  }

  public function setStatus(array $params)
  {
    $this->requireAdmin();
    $db   = $this->db();
    $slug = trim((string)($params['slug'] ?? ''));

    $company = Company::findBySlug($slug);
    if (!$company) { http_response_code(404); echo "Empresa inválida"; return; }
    $this->ensureCompanyContext((int)$company['id'], $slug);

    $companyId = (int)$company['id'];
    $orderId   = (int)($_POST['id'] ?? 0);
    $status    = (string)($_POST['status'] ?? '');

    if (Order::updateStatus($db, $orderId, $companyId, $status)) {
      header('Location: ' . base_url("admin/{$slug}/orders/show?id={$orderId}"));
      exit;
    }

    http_response_code(400);
    echo "Não foi possível atualizar o status";
  }
}
