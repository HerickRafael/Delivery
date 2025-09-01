<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../core/Helpers.php';

class PublicHomeController extends Controller {
  public function index($params) {
    $slug = $params['slug'] ?? null;
    $company = Company::findBySlug($slug);
    if (!$company || !$company['active']) {
      http_response_code(404);
      echo "Empresa não encontrada";
      return;
    }
    $categories = Category::listByCompany((int)$company['id']);
    $products   = Product::listByCompany((int)$company['id']);
    return $this->view('public/home', compact('company','categories','products'));
  }
}
