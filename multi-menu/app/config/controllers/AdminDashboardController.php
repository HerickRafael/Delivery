<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';

class AdminDashboardController extends Controller {
  private function guard($slug) {
    Auth::start();
    $u = Auth::user();
    if (!$u) { header('Location: ' . base_url("admin/$slug/login")); exit; }
    $company = Company::findBySlug($slug);
    if (!$company) { echo "Empresa inválida"; exit; }
    if ($u['role'] !== 'root' && (int)$u['company_id'] !== (int)$company['id']) {
      echo "Acesso negado"; exit;
    }
    return [$u, $company];
  }

  public function index($params) {
    $slug = $params['slug'];
    [$u, $company] = $this->guard($slug);
    $categories = Category::listByCompany((int)$company['id']);
    $products   = Product::listByCompany((int)$company['id']);
    return $this->view('admin/dashboard/index', compact('company','u','categories','products'));
  }
}
