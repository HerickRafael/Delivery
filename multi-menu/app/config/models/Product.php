<?php
require_once __DIR__ . '/../config/db.php';
class Product {
  public static function listByCompany(int $companyId): array {
    $st = db()->prepare("SELECT * FROM products WHERE company_id = ? AND active = 1 ORDER BY sort_order, name");
    $st->execute([$companyId]);
    return $st->fetchAll();
  }
}
