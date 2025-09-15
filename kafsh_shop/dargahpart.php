<?php
require_once "loader.php";
if (!session_id()) session_start();

if (!isset($_SESSION['order_details']) || !isset($_SESSION['grand_total'])) {
    $_SESSION['form_error'] = "لطفاً ابتدا سبد خرید خود را تکمیل کنید.";
    header("Location: cart.php");
    exit();
}
$order_details = $_SESSION['order_details'];
$grand_total = $_SESSION['grand_total'];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>درگاه پرداخت</title>
  <link rel="stylesheet" href="style3.css">
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <h3>اطلاعات پذیرنده</h3>
      <p><b>نام پذیرنده:</b> فروشگاه کفش شیک</p>
      <p><b>شماره پذیرنده:</b> 123456</p>
      <p><b>آدرس وب‌سایت:</b> www.kafshshop.com</p>
      <div class="amount-box">
        <span>مبلغ قابل پرداخت:</span>
        <strong><?= number_format($grand_total) ?> ریال</strong>
      </div>
    </div>
    <div class="right-panel">
      <h3>اطلاعات کارت</h3>
      <form action="payment_process.php" method="post">
        <label>شماره کارت</label>
        <input type="text" name="card_number" maxlength="16" required placeholder="**** **** **** ****">

        <label>CVV2</label>
        <input type="text" name="card_cvv2" maxlength="4" required placeholder="***">

        <label>تاریخ انقضا</label>
        <div class="expiry">
          <input type="text" name="expiry_month" maxlength="2" required placeholder="ماه">
          <input type="text" name="expiry_year" maxlength="2" required placeholder="سال">
        </div>
        <label>کد امنیتی</label>
        <div class="captcha">
          <input type="text" name="card_imni" maxlength="6" required placeholder="کد را وارد کنید">
          <img id="captcha-img" src="captcha.php?rand=<?= time() ?>" alt="captcha">
          <button type="button" id="refresh-captcha">↻</button>
        </div>
        <label>رمز اینترنتی کارت</label>
        <input type="password" name="card_rams" maxlength="12" required placeholder="********">
        <label>ایمیل (اختیاری)</label>
        <input type="email" name="card_email" placeholder="example@email.com">
        <input type="hidden" name="full_name" value="<?= htmlspecialchars($order_details['full_name']) ?>">
        <input type="hidden" name="phone" value="<?= htmlspecialchars($order_details['phone']) ?>">
        <input type="hidden" name="address" value="<?= htmlspecialchars($order_details['address']) ?>">
        <input type="hidden" name="grand_total" value="<?= htmlspecialchars($grand_total) ?>">

        <div class="buttons">
          <button type="submit" class="btn-pay">پرداخت</button>
          <a href="payment_failure.php" class="btn-cancel">انصراف</a>
        </div>
      </form>
    </div>
  </div>

<script>
document.getElementById('refresh-captcha').addEventListener('click', function(){
  var img = document.getElementById('captcha-img');
  img.src = 'captcha.php?rand=' + Date.now();
});
</script>
</body>
</html>
