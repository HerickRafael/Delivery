<?php
class Order
{
    public static function listByCompany(PDO $db, int $companyId, ?string $status = null, int $limit = 50, int $offset = 0): array {
        $sql = "SELECT * FROM orders WHERE company_id = :cid";
        $params = [':cid' => $companyId];

        if ($status) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $st = $db->prepare($sql);
        foreach ($params as $k => $v) $st->bindValue($k, $v);
        $st->bindValue(':limit', $limit, PDO::PARAM_INT);
        $st->bindValue(':offset', $offset, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findWithItems(PDO $db, int $orderId, int $companyId): ?array {
        $st = $db->prepare("SELECT * FROM orders WHERE id = :id AND company_id = :cid LIMIT 1");
        $st->execute([':id' => $orderId, ':cid' => $companyId]);
        $order = $st->fetch(PDO::FETCH_ASSOC);
        if (!$order) return null;

        $it = $db->prepare("SELECT * FROM order_items WHERE order_id = :oid ORDER BY id ASC");
        $it->execute([':oid' => $orderId]);
        $order['items'] = $it->fetchAll(PDO::FETCH_ASSOC);
        return $order;
    }

    public static function updateStatus(PDO $db, int $orderId, int $companyId, string $newStatus): bool {
        $allowed = ['pending','paid','completed','canceled'];
        if (!in_array($newStatus, $allowed, true)) return false;

        $st = $db->prepare("UPDATE orders SET status = :s WHERE id = :id AND company_id = :cid");
        return $st->execute([':s' => $newStatus, ':id' => $orderId, ':cid' => $companyId]);
    }
}
