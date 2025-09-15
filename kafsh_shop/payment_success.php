<?php
require_once "loader.php";
$conn = new mysqli("localhost", "root", "", "shop_db");
if ($conn->connect_error) {
    error_log("Database Connection Error: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_status']) && $_POST['payment_status'] === 'success') {
    $customer_name = $_SESSION['user_full_name'] ?? $_POST['customer_name'] ?? null;
    $customer_phone = $_SESSION['user_phone'] ?? $_POST['customer_phone'] ?? null;
    $customer_address = $_SESSION['user_address'] ?? $_POST['customer_address'] ?? null;
    $customer_email = $_SESSION['user_email'] ?? $_POST['customer_email'] ?? null;
    if (empty($customer_name) || empty($customer_phone) || empty($customer_address)) {
        error_log("Missing essential customer information.");
        echo "<p class='alert alert-danger'>خطا: اطلاعات ضروری مشتری ناقص است. لطفاً دوباره تلاش کنید.</p>";
        exit;
    }
    $cart_session_data = $_SESSION['cart_itrms'] ?? [];
    $order_items_to_insert = [];
    $total_amount = 0;
    if (empty($cart_session_data)) {
        echo "<p class='alert alert-danger'>خطا: سبد خرید شما خالی است. امکان ثبت سفارش وجود ندارد.</p>";
        exit;
    }
    try {
        $product_stmt = $conn->prepare("SELECT id, price, title FROM post WHERE id = ?");
        if ($product_stmt === false) {
            throw new Exception("Failed to prepare product statement: " . $conn->error);
        }
        foreach ($cart_session_data as $cart_item) {
            if (isset($cart_item['product_id']) && isset($cart_item['quantity']) && $cart_item['quantity'] > 0) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];
                $product_stmt->bind_param("i", $product_id);
                $product_stmt->execute();
                $result_product = $product_stmt->get_result();
                $product = $result_product->fetch_assoc();
                if ($product) {
                    $unit_price = $product['price'];
                    $item_total = $quantity * $unit_price;
                    $total_amount += $item_total;
                    $order_items_to_insert[] = [
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                        'price' => $unit_price
                    ];
                } else {
                    error_log("Post item with ID $product_id not found in 'post' table for order processing.");
                    echo "<p class='alert alert-warning'>هشدار: یکی از اقلام سبد خرید شما پیدا نشد. لطفاً سبد خرید خود را بررسی کنید.</p>";
                }
            } else {
                error_log("Invalid cart item structure or quantity in session: " . print_r($cart_item, true));
                echo "<p class='alert alert-warning'>هشدار: از یک قلم با فرمت نادرست در سبد خرید صرف نظر شد.</p>";
            }
        }
        $product_stmt->close();
    } catch (Exception $e) {
        error_log("Database error fetching post prices: " . $e->getMessage());
        echo "<p class='alert alert-danger'>خطای پایگاه داده هنگام دریافت جزئیات اقلام: " . $e->getMessage() . "</p>";
        exit;
    }
    if (empty($order_items_to_insert) || $total_amount <= 0) {
        echo "<p class='alert alert-danger'>خطا: هیچ قلم معتبری پردازش نشد یا مجموع مبلغ نامعتبر است. لطفاً سبد خرید خود را بررسی کنید.</p>";
        exit;
    }
    $order_id = null;
    try {
        $order_stmt = $conn->prepare("INSERT INTO `orders` (user_id, full_name, phone, address, email, total_amount, order_date, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'processing')");
        if ($order_stmt === false) {
            throw new Exception("Failed to prepare order statement: " . $conn->error);
        }
        $user_id_for_order = $_SESSION['user_id'] ?? null; 
        $order_stmt->bind_param("issssd", $user_id_for_order, $customer_name, $customer_phone, $customer_address, $customer_email, $total_amount);
        
        if ($order_stmt->execute()) {
            $order_id = $conn->insert_id;
        } else {
            throw new Exception("Order insertion failed: " . $order_stmt->error);
        }
        $order_stmt->close();
    } catch (Exception $e) {
        error_log("Database error during order creation: " . $e->getMessage());
        echo "<p class='alert alert-danger'>خطای پایگاه داده هنگام ایجاد سفارش. لطفاً با پشتیبانی تماس بگیرید.</p>";
        exit;
    }
    if ($order_id) {
        try {
            $item_insert_sql = "INSERT INTO `order_itrms` (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $item_stmt = $conn->prepare($item_insert_sql);
            if ($item_stmt === false) {
                throw new Exception("Failed to prepare order items statement: " . $conn->error);
            }
            foreach ($order_items_to_insert as $item_data) {
                $item_stmt->bind_param("iiid", $order_id, $item_data['product_id'], $item_data['quantity'], $item_data['price']);
                $item_stmt->execute();
            }
            $item_stmt->close();
        } catch (Exception $e) {
            error_log("Database error during order_items insertion: " . $e->getMessage());
            echo "<p class='alert alert-danger'>خطای پایگاه داده هنگام ذخیره جزئیات سفارش. لطفاً با پشتیبانی تماس بگیرید.</p>";
            exit;
        }
    } else {
         error_log("Order ID was not generated after insertion into orders table. Cannot insert order items.");
         echo "<p class='alert alert-danger'>ثبت سفارش اصلی ناموفق بود. اقلام سفارش ذخیره نشدند.</p>";
         exit;
    }
    echo "<p class='alert alert-success'>سفارش شما با موفقیت ثبت شد! از خرید شما سپاسگزاریم.</p>";

} else {
    echo "<p class='alert alert-success'>سفارش شما با موفقیت ثبت شد! از خرید شما سپاسگزاریم.</p>";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>وضعیت پرداخت</title>
    <link rel="stylesheet" href="./style2.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .alert { 
            padding: 15px; 
            margin-bottom: 20px; 
            border: 1px solid transparent; 
            border-radius: 4px; 
        }
        .alert-success { 
            color: #3c763d; 
            background-color: #dff0d8; 
            border-color: #d6e9c6; 
        }
        .alert-warning { 
            color: #8a6d3b; 
            background-color: #fcf8e3; 
            border-color: #faebcc; 
        }
        .alert-danger { 
            color: #a94442; 
            background-color: #f2dede; 
            border-color: #ebccd1; 
        }
        .payment-result { 
            text-align: center; 
            padding: 50px 20px; 
        }
        .payment-result h2 { 
            color: #28a745; 
        }
        .payment-result a { 
            display: inline-block; 
            margin-top: 20px; 
            padding: 10px 20px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        .payment-result a:hover { 
            background-color: #0056b3; 
        }
    </style>
</head>
<body>
    <div class="container">
        <section class="payment-result">
            <h2>وضعیت سفارش شما</h2>
            <p>جزئیات سفارش شما در بالا نمایش داده شده است.</p>
            <a href="index1.php">بازگشت به صفحه اصلی</a>
        </section>
    </div>
</body>
</html>
