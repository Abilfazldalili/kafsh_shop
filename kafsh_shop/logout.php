<?php
require_once('./loader.php');
try {
    $hasLogin = $_SESSION['login'] ?? false;
    if ($hasLogin) {
        unset($_SESSION['username']);
        unset($_SESSION['mobile']);
        unset($_SESSION['email']);
        unset($_SESSION['role']);
        unset($_SESSION['login']);

        header('Location: ./index1.php?logout=ok');
        exit;
    } else {
        header('Location: ./index1.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Your error message is: " . $e->getMessage();
}
?>