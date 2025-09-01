<?php
class Auth {
  public static function start(): void {
    $cfg = config();
    date_default_timezone_set($cfg['timezone'] ?? 'America/Sao_Paulo');
    session_name($cfg['session_name'] ?? 'mm_session');
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
  }
  public static function login(array $user): void {
    $_SESSION['user'] = [
      'id' => $user['id'],
      'role' => $user['role'],
      'company_id' => $user['company_id'],
      'name' => $user['name'],
      'email' => $user['email'],
    ];
  }
  public static function user(): ?array { return $_SESSION['user'] ?? null; }
  public static function logout(): void { $_SESSION = []; session_destroy(); }
}
