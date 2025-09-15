<?php
require_once "loader.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id'])) {
    echo "شناسه مشتری نامعتبر است.";
    exit;
}
$customer_id = $_GET['id'];
$stmt_user = $conn->prepare("SELECT username, mobile, email FROM users WHERE id = ?");
$stmt_user->execute([$customer_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo "مشتری یافت نشد.";
    exit;
}
$stmt_orders = $conn->prepare("SELECT * FROM orders WHERE customer_phone = ? ORDER BY order_date DESC");
$stmt_orders->execute([$user['mobile']]);
$orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سفارشات مشتری: <?= htmlspecialchars($user['username']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            font-family: Vazir, Tahoma, sans-serif;
        }
        .invoice-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 30px;
        }
        .invoice-header {
            border-bottom: 2px solid #eee;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .invoice-header h5 {
            margin: 0;
            color: #0d6efd;
            font-weight: bold;
        }
        .invoice-info p {
            margin: 4px 0;
        }
        .invoice-table th {
            background: #0d6efd;
            color: #fff;
            text-align: center;
        }
        .invoice-table td {
            text-align: center;
            vertical-align: middle;
        }
        .total-row {
            font-weight: bold;
            background: #f1f1f1;
        }
        .badge-status {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="container py-4">
    <h2 class="mb-4">فاکتورهای مشتری: <?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['mobile']) ?>)</h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">هیچ سفارشی برای این مشتری یافت نشد.</div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="invoice-card">
                <div class="invoice-header d-flex justify-content-between align-items-center">
                    <h5>سفارش #<?= htmlspecialchars($order['id']) ?></h5>
                    <span class="badge bg-success badge-status"><?= htmlspecialchars($order['status']) ?></span>
                </div>
                <div class="invoice-info">
                    <p><strong>تاریخ سفارش:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
                    <p><strong>نام مشتری:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                    <p><strong>آدرس:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
                    <p><strong>موبایل:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                </div>

                <h6 class="mt-4">جزئیات محصولات</h6>
                <table class="table table-bordered table-striped invoice-table">
                    <thead>
                        <tr>
                            <th>نام محصول</th>
                            <th>تعداد</th>
                            <th>قیمت واحد (تومان)</th>
                            <th>جمع آیتم (تومان)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt_items = $conn->prepare("SELECT product_name, quantity, price, total FROM order_items WHERE order_id = ?");
                        $stmt_items->execute([$order['id']]);
                        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
                        if (empty($items)):
                        ?>
                            <tr>
                                <td colspan="4">محصولی یافت نشد.</td>
                            </tr>
                        <?php else:
                            foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td><?= (int)$item['quantity'] ?></td>
                                    <td><?= number_format($item['price']) ?></td>
                                    <td><?= number_format($item['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td colspan="3">جمع کل سفارش</td>
                                <td><?= number_format($order['total_amount']) ?> تومان</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>