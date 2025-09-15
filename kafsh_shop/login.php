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
  <title>Login</title>
  <link rel="stylesheet" href="style1.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <?php require_once "styleLoad.php"; ?>
</head>
<body>
<div class="wrapper">
<div class="card login-size">
  <form class="login-form" method="post" action="./sig-in.php"> 
    <h2>ورود</h2>
    <div class="input-group">
      <i class="fas fa-envelope"></i>
      <input type="text" name="key" placeholder="شماره موبایل" required>
    </div>
    <div class="input-group">
      <i class="fas fa-lock"></i>
      <input type="password" name="password" placeholder="پسورد" required>
    </div>
    <a href="#" class="forgot-password">پسوردت و فراموش کردی؟</a>
    <button type="submit" name="signin" class="btn btn-login">ورود</button>
    <p class="toggle-text">اکانت ندارید؟ <a href="register.php" class="toggle">Register Now</a></p>
</form>
</div>
</div>
<?php require_once "errorLoaded.php"; ?>
</body>
</html>