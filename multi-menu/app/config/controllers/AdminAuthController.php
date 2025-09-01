<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Company.php';

class AdminAuthController extends Controller {
  public function loginForm($params) {
    Auth::start();
    $slug = $params['slug'];
    $company = Company::findBySlug($slug);
    if (!$company) { echo "Empresa inválida"; return; }
    return $this->view('admin/auth/login', compact('company'));
  }

  public function login($params) {
    Auth::start();
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';
    $slug  = $params['slug'];

    $company = Company::findBySlug($slug);
    if (!$company) { echo "Empresa inválida"; return; }

    $user = User::findByEmail($email);
    if ($user && password_verify($pass, $user['password_hash']) &&
        ($user['company_id'] == $company['id'] || $user['role'] === 'root')) {
      Auth::login($user);
      header('Location: ' . base_url("admin/$slug/dashboard"));
      exit;
    }
    $error = "Credenciais inválidas";
    return $this->view('admin/auth/login', compact('company','error'));
  }

  public function logout($params) {
    Auth::start();
    Auth::logout();
    $slug = $params['slug'];
    header('Location: ' . base_url("admin/$slug/login"));
  }
}
