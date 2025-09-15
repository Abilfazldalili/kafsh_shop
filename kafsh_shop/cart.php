<?php
require_once "loader.php";
$conn = new mysqli("localhost", "root", "", "shop_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM cart_itrms WHERE status = 'in_cart'";
$result = $conn->query($sql);

$grand_total = 0;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سبد خرید</title>
    <link rel="stylesheet" href="./style2.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
    <?php
    if (isset($_SESSION['form_error'])) {
        echo '<p class="alert alert-danger" style="text-align:center; color:red; margin-bottom: 20px;">' . htmlspecialchars($_SESSION['form_error']) . '</p>';
        unset($_SESSION['form_error']);
    }
    ?>
<body>
    <nav class="flex">
        <div class="column flex-center">
            <div class="flex-center logo">
                <h1>کفش <span class="gradient-text">شیک</span></h1>
                <i class="fa-solid fa-cart-plus"></i>
            </div>
        </div>
        <input type="checkbox" id="sidebar-toggle">
        <label for="sidebar-toggle" class="overlay"></label>
        <div class="column flex-center tabs sidebar">
            <label for="sidebar-toggle" class="flex-center icon-wrapper cancel-btn">
                <i class="fa-solid fa-xmark"></i>
            </label>
            <a href="./index1.php" class="tab">
                <h4>خانه</h4>
            </a>
            <a href="#about" class="tab">
                <h4>درباره ما</h4>
            </a>
            <a href="#products" class="tab">
                <h4>محصولات</h4>
            </a>
            <a href="#sec" class="tab">
                <h4>نظرات</h4>
            </a>
            <a href="#" class="tab">
                <h4>تماس با ما</h4>
            </a>
            <?php if(isset($_SESSION['role'])){ ?>
            <li>
                <?php if($_SESSION['role'] == "admin"){ ?>
                <a href="./adminpanel.php" class="tab">داشبورد ادمین</a>
                <?php } ?>
                <?php if($_SESSION['role'] == "user"){ ?>
                <a href="#" class="tab">کاربر جدید</a>
                <?php } ?>
                <?php if($_SESSION['role'] == "writer"){ ?>
                <a href="./writerpanel.php" class="tab">داشبورد نویسنده</a>
                <?php } ?>
            </li>
            <?php } ?>
        </div>
        <div class="column flex-center buttons-wrapper">
            <button class="flex-center btn search-btn">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <button class="flex-center btn search-btn">
                <i class="fa-solid fa-cart-shopping"></i>
            </button>
            <li>
                <?php if(isset($_SESSION['login'])){ ?>
            <a href="./logout.php" class="btn primary contact-btn">خارج شوید</a>
            <?php }else{ ?>
                <a href="./login.php" class="btn primary contact-btn">ثبت نام کنید</a>
                <?php } ?>
                </li>
            <label for="sidebar-toggle" class="flex-center btn menu-btn">
                <i class="fa-solid fa-bars"></i>
            </label>
        </div>
    </nav>
    <main class="main">
        <section class="container">
    <h2 class="h2">سبد خرید</h2>
    <table border="1" cellpadding="10" class="jadval">
        <tr class="tr">
            <th class="th">نام محصول</th>
            <th class="th">تعداد</th>
            <th class="th">قیمت واحد</th>
            <th class="th">مجموع</th>
            <th class="th">وضعیت</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr class="tr1">
                    <td class="th1"><?= $row['product_name'] ?></td>
                    <td class="th1"><?= $row['quantity'] ?></td>
                    <td class="th1"><?= $row['price'] ?></td>
                    <td class="th1"><?= $row['total'] ?></td>
                    <td class="thbut">
                    <form method="post" action="remove_from_cart.php" onsubmit="return confirm('آیا مطمئن هستید؟')">
                    <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                    <button class="btn" type="submit">حذف</button>
            </form>
        </td>
    </tr>
                <?php $grand_total += $row['total']; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="4" class="h2">سبد خرید خالی است</td></tr>
    <?php endif; ?>
      </table>
      <p class="h2">جمع کل: <?= $grand_total ?> تومان</p>
      <?php $_SESSION['grand_total'] = $grand_total; ?>
     </section>
     <section class="container">
    <h3 class="h3">اطلاعات سفارش دهنده</h3>
            <form action="submit_order.php" method="post" class="form" onsubmit="console.log('Form submitted!'); return true;">
        <label for="full_name" class="lab">نام و نام خانوادگی</label><br>
        <input type="text" name="full_name" id="myTextInput" required><br><br>
        <label for="phone" class="lab">شماره تماس:</label><br>
        <input type="tel" name="phone" id="myTextInput" required><br><br>
        <label for="address" class="lab">آدرس:</label><br>
        <textarea class="address" name="address" id="myTextInput" required></textarea><br><br>
        <button type="submit" class="btn1">ثبت سفارش</button>
    </form>
    </section>
    </main>
    <footer>
    <div class="container">
        <div class="flex-center logo">
            <h1>کفش <span class="gradient-text">شیک</span></h1>
            <i class="fa-solid fa-cart-plus"></i>
        </div>
        <div class="flex-center footer-tabs">
            <a href="#" class="tab active"><h4>خانه</h4></a>
            <a href="#" class="tab "><h4>درباره ما</h4></a>
            <a href="#" class="tab "><h4>محصولات</h4></a>
            <a href="#" class="tab "><h4>نظرات</h4></a>
            <a href="#" class="tab "><h4>تماس با ما</h4></a>
        </div>
        <aside class="flex-center footer-handle-container">
        <a href="#" class="flex-center icon-wrapper">
            <i class="fa-brands fa-youtube"></i>
        </a>
        <a href="#" class="flex-center icon-wrapper">
            <i class="fa-brands fa-telegram"></i>
        </a>
        <a href="#" class="flex-center icon-wrapper">
            <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="#" class="flex-center icon-wrapper">
            <i class="fa-brands fa-tiktok"></i>
        </a>
     </aside>
    </div>
    <div class="flex-center copyright">
        <p class="muted">
            ساخته شده توسط:ابوالفضل دلیلی
        </p>
        <p>site &copy; all right reserved | 2025</p>
    </div>
  </footer>
</body>
</html>
