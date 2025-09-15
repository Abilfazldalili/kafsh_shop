<?php
require_once "loader.php";
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("کد سفارش نامعتبر است.");
}
$order_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();
$stmt2 = $conn->prepare("SELECT * FROM cart_itrms WHERE order_id = ?");
$stmt2->execute([$order_id]);
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>جزئیات سفارش #<?= $order_id ?></h2>
<p><strong>نام:</strong> <?= htmlspecialchars($order['full_name']) ?></p>
<p><strong>آدرس:</strong> <?= htmlspecialchars($order['address']) ?></p>
<p><strong>شماره تماس:</strong> <?= htmlspecialchars($order['phone']) ?></p>
<p><strong>تاریخ:</strong> <?= $order['created_at'] ?></p>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>نام محصول</th>
        <th>تعداد</th>
        <th>قیمت واحد</th>
        <th>جمع</th>
    </tr>
    <?php 
    $total_price = 0;
    foreach ($items as $item): 
        $total_price += $item['total'];
    ?>
    <tr>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($item['price']) ?> تومان</td>
        <td><?= number_format($item['total']) ?> تومان</td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>جمع کل:</strong></td>
        <td><strong><?= number_format($total_price) ?> تومان</strong></td>
    </tr>
</table>
