<?php
require_once "loader.php";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
    $conn = new mysqli("localhost", "root", "", "shop_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("DELETE FROM cart_itrms WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: cart.php");
    exit();
}
?>