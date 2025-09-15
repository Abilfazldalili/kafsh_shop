<?php
require_once "loader.php";
if(!isset($_GET['user_id'])) header('Location: index1.php');
$post_id = $_GET['user_id'];

$query = "SELECT * FROM `post` WHERE id=? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindValue(1,$post_id);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post->title ?></title>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <?php require_once "styleLoad.php"; ?>
</head>
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


    
     <aside class="flex-center social-handle-container">
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


     <section id="about">
        <div class="container">
            <div class="column about-image">
                <img src="uploads/<?= $post->image ?>" alt="">
            </div>
            <div class="column ttt">
                <h2 class="sub-title"><?= $post->id ?></h2>
                <h1 class="heading-1 gradient-text"><?= $post->title ?></h1>
                <h3 class="rating price"><?= $post->price ?></h3>
                <div class="content">
                    <p class="muted">
                    <?= $post->description ?>
                </p>
                </div>
        <div class="buttons-wrapper">
    <?php if(isset($_SESSION['login'])): ?>
    <a href="add_to_cart.php?product_id=<?= $post->id ?>" class="btn primary">
            ادامه فرایند خرید
    </a>
    <?php else: ?>
    <a href="login.php?redirect=product&id=<?= $post->id ?>" class="btn primary">
            برای خرید وارد شوید
     </a>
     <?php endif; ?>
     </div>

     </div>
        </div>
     </section>


  <section id="contact">
    <div class="container">
        <div class="contact-form">
            <div class="top">
                <h1 class="title">
                    <span class="gradient-text">سریع به ما اضافه شوید!!</span>
                </h1>
                <p class="muted">نیاز به کمک دارید؟ ما اینجاییم تا به شما کمک کنیم</p>
            </div>
            <div class="middle">
                <div class="flex row">
                    <input type="text" name="name" class="control" placeholder="نام">
                    <input type="text" name="lastName" class="control" placeholder="نام خانوتدگی">
                    </div>
                    <div class="flex row">
                    <input type="text" name="email" class="control" placeholder="ایمیل">
                    <input type="tel" name="phone" class="control" placeholder="+98">
                </div>
                <textarea name="message" class="control" cols="30" rows="10" placeholder="پیام"></textarea>
            </div>
            <div class="bottom flex-center">
                <button class="btn primary">ارسال کنید</button>
            </div>
        </div>
    </div>
  </section>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="script.js"></script>
    <?php require_once "errorLoaded.php"; ?>
</body>
</html>