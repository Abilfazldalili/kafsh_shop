<?php
require_once "loader.php";
if (isset($_SESSION['signin']) && $_SESSION['signin'] === true) {
    header("Location: index1.php");
    exit;
}
$error = '';

if (isset($_POST['signin'])) {
    $key = trim($_POST['key']); 
    $password = trim($_POST['password']);
    if (empty($key) || empty($password)) {
        $error = "لطفاً نام کاربری/موبایل/ایمیل و رمز عبور را وارد کنید.";
    } else {
        try {
            $query = "SELECT * FROM `users` WHERE (username = :key OR mobile = :key OR email = :key) LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":key", $key);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['login'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $redirect = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index1.php';
                unset($_SESSION['redirect_url']);
                header("Location: " . $redirect);
                exit;
            } else {
                $error = "نام کاربری/موبایل/ایمیل یا رمز عبور نادرست است.";
            }
        } catch (PDOException $e) {
            $error = "خطا در ورود به سیستم: " . $e->getMessage();
        }
    }
}
?>