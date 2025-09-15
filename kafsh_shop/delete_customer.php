<?php
require_once "loader.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("شناسه کاربر نامعتبر است.");
}
$customer_id = (int) $_GET['id'];
try {
    $conn->beginTransaction();
    $stmt = $conn->prepare("SELECT mobile FROM users WHERE id = ?");
    $stmt->execute([$customer_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new Exception("کاربر یافت نشد.");
    }
    $mobile = $user['mobile'];
    $stmtOrders = $conn->prepare("SELECT id FROM orders WHERE customer_phone = ?");
    $stmtOrders->execute([$mobile]);
    $orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($orders)) {
        foreach ($orders as $order) {
            $order_id = $order['id'];
            $stmtItems = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmtItems->execute([$order_id]);
        }
        $stmtDelOrders = $conn->prepare("DELETE FROM orders WHERE customer_phone = ?");
        $stmtDelOrders->execute([$mobile]);
    }
    $stmtDelUser = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmtDelUser->execute([$customer_id]);
    $conn->commit();
    header("Location: adminpanel.php?msg=deleted");
    exit;
} catch (Exception $e) {
    $conn->rollBack();
    die("خطا در حذف کاربر: " . $e->getMessage());
}
?>