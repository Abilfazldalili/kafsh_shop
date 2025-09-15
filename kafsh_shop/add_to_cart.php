<?php
require_once "loader.php";
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM post WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        $quantity = 1;
        $price = $product['price'];
        $total = $quantity * $price;
        $product_name = $product['title'];
        $stmt = $conn->prepare("INSERT INTO cart_itrms (product_name, quantity, price, total)
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([$product_name, $quantity, $price, $total]);
        header("Location: cart.php");
         exit;
    } else {
        $_SESSION['error'] = "محصول یافت نشد";
        header("Location: index1.php");
         exit;
    }
} else {
    $_SESSION['error'] = "شناسه محصول ارسال نشد";
    header("Location: index1.php");
     exit;
}
?>