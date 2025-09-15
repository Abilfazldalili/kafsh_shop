<?php
require_once "loader.php";
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index1.php");
    exit;
}
$error = '';
$success = '';
if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    if (empty($username) || empty($password) || empty($mobile) || empty($email)) {
        $error = "لطفاً تمام فیلدها را پر کنید.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "فرمت ایمیل نامعتبر است.";
    } elseif (!preg_match('/^09[0-9]{9}$/', $mobile)) {
        $error = "شماره موبایل باید 11 رقمی و با 09 شروع شود.";
    } else {
        try {
            $check_query = "SELECT id FROM users WHERE username = :username OR email = :email OR mobile = :mobile LIMIT 1";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bindValue(':username', $username);
            $check_stmt->bindValue(':email', $email);
            $check_stmt->bindValue(':mobile', $mobile);
            $check_stmt->execute();

            if ($check_stmt->rowCount() > 0) {
                $error = "نام کاربری، ایمیل یا شماره موبایل قبلاً ثبت شده است.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_query = "INSERT INTO users (username, password, mobile, email, role) 
                               VALUES (:username, :password, :mobile, :email, 'user')";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bindValue(':username', $username);
                $insert_stmt->bindValue(':password', $hashed_password);
                $insert_stmt->bindValue(':mobile', $mobile);
                $insert_stmt->bindValue(':email', $email);
                $insert_stmt->execute();
                $_SESSION['login'] = true;
                $_SESSION['user_id'] = $conn->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'user';
                header("Location: index1.php?signup_success=true");
                exit; 
            }
        } catch (PDOException $e) {
            $error = "خطا در ثبت نام: " . $e->getMessage();
        }
    }

    }
