<?php
require_once "loader.php";
if (!session_id()) session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: cart.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "shop_db");
if ($conn->connect_error) {
    $_SESSION['form_error'] = "خطا در اتصال به پایگاه داده: " . $conn->connect_error;
    header("Location: cart.php");
    exit();
}

$full_name = trim($_POST["full_name"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$address = trim($_POST["address"] ?? "");

if ($full_name === "" || $phone === "" || $address === "") {
    $_SESSION['form_error'] = "لطفاً تمام فیلدهای اطلاعات سفارش‌دهنده را تکمیل کنید.";
    header("Location: cart.php");
    exit();
}
if (!isset($_SESSION['grand_total']) || empty($_SESSION['grand_total'])) {
    $check_cart_sql = "SELECT SUM(total) as total_sum FROM `cart_itrms`";
    $result_cart_sum = $conn->query($check_cart_sql);
    if ($result_cart_sum && $row_sum = $result_cart_sum->fetch_assoc()) {
        if ($row_sum['total_sum'] > 0) {
            $_SESSION['grand_total'] = $row_sum['total_sum'];
        } else {
            $_SESSION['form_error'] = "سبد خرید شما خالی است یا مشکلی در محاسبه مبلغ کل وجود دارد.";
            header("Location: cart.php");
            exit();
        }
    } else {
        $_SESSION['form_error'] = "خطا در خواندن سبد خرید.";
        header("Location: cart.php");
        exit();
    }
}

$grand_total = $_SESSION['grand_total'];
$_SESSION['order_details'] = [
    'full_name' => $full_name,
    'phone' => $phone,
    'address' => $address,
];
$_SESSION['grand_total'] = $grand_total;
header("Location: dargahpart.php");
exit();
?>
