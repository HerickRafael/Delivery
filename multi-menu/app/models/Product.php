<?php
require_once __DIR__ . '/../config/db.php';

class Product {
  public static function listByCompany(int $companyId, ?string $q=null): array {
    $sql = "SELECT * FROM products WHERE company_id = ? AND active = 1";
    $args = [$companyId];
    if ($q) {
      $sql .= " AND (name LIKE ? OR description LIKE ?)";
      $args[] = "%$q%"; $args[] = "%$q%";
    }
    $sql .= " ORDER BY sort_order, name";
    $st = db()->prepare($sql);
    $st->execute($args);
    return $st->fetchAll();
  }

  public static function listByCategory(int $companyId, int $categoryId, ?string $q=null): array {
    $sql = "SELECT * FROM products WHERE company_id = ? AND category_id = ? AND active = 1";
    $args = [$companyId, $categoryId];
    if ($q) {
      $sql .= " AND (name LIKE ? OR description LIKE ?)";
      $args[] = "%$q%"; $args[] = "%$q%";
    }
    $sql .= " ORDER BY sort_order, name";
    $st = db()->prepare($sql);
    $st->execute($args);
    return $st->fetchAll();
  }

  public static function find(int $id): ?array {
    $st = db()->prepare("SELECT * FROM products WHERE id = ?");
    $st->execute([$id]);
    return $st->fetch() ?: null;
  }

  public static function create(array $data): int {
    $st = db()->prepare("INSERT INTO products (company_id, category_id, name, description, price, promo_price, sku, image, active, sort_order) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $st->execute([
      $data['company_id'],
      $data['category_id'] ?: null,
      $data['name'],
      $data['description'] ?? null,
      $data['price'],
      $data['promo_price'] ?: null,
      $data['sku'] ?: null,
      $data['image'] ?: null,
      isset($data['active']) ? (int)$data['active'] : 1,
      (int)($data['sort_order'] ?? 0),
    ]);
    return (int)db()->lastInsertId();
  }

  public static function update(int $id, array $data): void {
    $st = db()->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, promo_price=?, sku=?, image=?, active=?, sort_order=? WHERE id=?");
    $st->execute([
      $data['category_id'] ?: null,
      $data['name'],
      $data['description'] ?? null,
      $data['price'],
      $data['promo_price'] ?: null,
      $data['sku'] ?: null,
      $data['image'] ?: null,
      isset($data['active']) ? (int)$data['active'] : 1,
      (int)($data['sort_order'] ?? 0),
      $id
    ]);
  }

  public static function delete(int $id): void {
    $st = db()->prepare("DELETE FROM products WHERE id=?");
    $st->execute([$id]);
  }
}
