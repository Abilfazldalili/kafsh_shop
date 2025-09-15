<?php
require_once "loader.php";
require_once "database.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Register</title>
  <link rel="stylesheet" href="style1.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <?php require_once "styleLoad.php"; ?>
</head>
<body>
<div class="wrapper">
<div class="card login-size">
<form class="login-form" method="post" action="./sign-up.php"> 
    <h2>ثبت نام</h2>
    <div class="input-group">
      
      <input type="text" name="username" placeholder="نام" required>
    </div>
    <div class="input-group">
      <i class="fas fa-lock"></i>
      <input type="password" name="password" placeholder="پسورد" required>
    </div>
    <div class="input-group">
      <i class="fas fa-envelope"></i>
      <input type="email" name="email" placeholder="ایمیل" required>
    </div>
    <div class="input-group">
      <i class="fas fa-lock"></i>
      <input type="tel" name="mobile" placeholder="موبایل" required>
    </div>

    <button type="submit" name="signup" class="btn btn-login">ثبت نام</button>
    <p class="toggle-text">اکانت دارید؟ <a href="login.php" class="toggle">login Now</a></p>
</form>
</div>
</div>
<?php require_once "errorLoaded.php"; ?>
</body>
</html>