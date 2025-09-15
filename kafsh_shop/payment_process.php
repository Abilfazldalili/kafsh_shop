<?php
require_once "loader.php";
if (!session_id()) session_start();
$conn = new mysqli("localhost", "root", "", "shop_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$full_name   = trim($_POST['full_name'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$address     = trim($_POST['address'] ?? '');
$grand_total = floatval($_POST['grand_total'] ?? 0);
if ($full_name === '' || $phone === '' || $address === '' || $grand_total <= 0) {
    $_SESSION['form_error'] = "اطلاعات سفارش ناقص است.";
    header("Location: dargahpart.php");
    exit();
}
$payment_successful = true; 

if ($payment_successful) {
    $conn->begin_transaction();
    try {
        $sqlOrder = "INSERT INTO orders (customer_name, customer_phone, customer_address, total_amount, order_date, status)
                     VALUES (?, ?, ?, ?, NOW(), 'paid')";
        $stmt = $conn->prepare($sqlOrder);
        if (!$stmt) {
            throw new Exception("Prepare failed (orders): " . $conn->error);
        }
        $stmt->bind_param("sssd", $full_name, $phone, $address, $grand_total);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed (orders): " . $stmt->error);
        }
        $order_id = $conn->insert_id;
        $stmt->close();
        $sqlCart = "SELECT product_name, quantity, price, total FROM cart_itrms WHERE status = 'in_cart'";
        $resCart = $conn->query($sqlCart);
        if ($resCart && $resCart->num_rows > 0) {
            $stmtItems = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price, total)
                                         VALUES (?, ?, ?, ?, ?)");
            if (!$stmtItems) {
                throw new Exception("Prepare failed (order_items): " . $conn->error);
            }
            while ($row = $resCart->fetch_assoc()) {
                $pname = $row['product_name'];
                $qty   = (int)$row['quantity'];
                $price = (float)$row['price'];
                $total = (float)$row['total'];
                $stmtItems->bind_param("isidd", $order_id, $pname, $qty, $price, $total);
                if (!$stmtItems->execute()) {
                    throw new Exception("Execute failed (order_items): " . $stmtItems->error);
                }
            }
            $stmtItems->close();
        }
        $sqlUpdate = "UPDATE cart_itrms SET status = 'ordered' WHERE status = 'in_cart'";
        if (!$conn->query($sqlUpdate)) {
            throw new Exception("Update cart_itrms failed: " . $conn->error);
        }
        $conn->commit();
        unset($_SESSION['grand_total'], $_SESSION['order_details']);
        header("Location: payment_success.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Payment error: " . $e->getMessage());
        $_SESSION['form_error'] = "خطا در ثبت سفارش.";
        header("Location: dargahpart.php");
        exit();
    }
} else {
    $_SESSION['form_error'] = "پرداخت ناموفق بود.";
    header("Location: payment_failure.php");
    exit();
}
?>