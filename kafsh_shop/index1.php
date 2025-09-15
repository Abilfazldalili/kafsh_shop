<?php
require_once "loader.php"; 

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}
$query = "SELECT * FROM `post`";
$stmt = $conn->query($query);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>katoni</title>
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
            <a href="#" class="tab">
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
            <a href="./cart.php" class="flex-center btn search-btn">
                <i class="fa-solid fa-cart-shopping"></i>
                </a>
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


    <section id="hero" class="flex">
        <div class="container">
            <div class="column">
                <h1 class="heading-1">
                    کفش های <span class="stroke-text">بی نظیر <span class="gradient-text">برای همه</span></span>
                </h1>
                <p class="muted">باهرقدم برتری و خاص بودن خود را با کفش های ما به نمایش بگذارید
                    کفش هایی که فراتر از مد روزه استایلی خاص و چشم نواز به شما هدیه میکنند
                    در هر مکانی خاص هستید با کفش های ما بدرخشید و خاص باشید
                </p>
                <div class="discount-wrapper">
                    <img src="./assets/img/svg.png" alt="" class="svg-img-offer">
                    <h1 class="discount">
                        30% off
                    </h1>
                </div>
                <div class="flex-center buttons-wrapper">
                    <a href="#" class="btn">خرید کنید</a>
                    <a href="#" class="btn primary">بیشتر بدانید</a>
                </div>
            </div>
            <div class="column hero-image">
                <img src="./assets/img/hero-shoe.png" alt="" class="hero-shoe">
                <div class="rating-group">
                    <div class="flex row">
                        <h1>4.5</h1>
                        <div class="flex-center starts-container">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                    <p>400k <span class="muted">نظرات</span></p>
                </div>
            </div>
        </div>
    </section>

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
                <img src="./assets/img/shoe-1.jpeg" alt="">
            </div>
            <div class="column">
                <h2 class="sub-title">درباره ما</h2>
                <h1 class="heading-1 gradient-text">بالاترین کیفیت کفش اینجا پیدا کنید</h1>
                <div class="content">
                    <p class="muted">
                    کفش ها به عنوان یکی از مهم ترین اجزای پوشاک روزانه نقش بسزایی 
                    در راحتی سلامت و سبک زندگی افراد ایفا میکنند انتخاب یک جفت کفش مناسب
                    نه تنها ظاهر شیک و جذابی به شما میبخشد بلکه سلامت پاها و ستون
                    فقرات شمارا نیز تضمین میکند با توجه به فعالیت های روزمره و محیطی
                    که در آن قرار دارید انتخاب کفش مناسب بسیار اهمیت دارد
                </p>
                <p class="muted">
                    کفش های ما با طراحی مدرن و استفاده از بهترین مواد اولیه تجربه ای
                    متفاوت از راحتی و کیفیت را برای شما به ارمغان می آوردند رویه کفش ها
                    از جنس مقاوم و قابل تنفس طراحی شده است تا علاوه بر دوام بالا حس 
                    سبکی و خنکی را برای پاها فراهم کند زیره های ضد لغزش و انعطاف پذیر
                    به شما امکان راه رفتن راحت در سطوح مختلف را می دهند در حالی که
                    طراحی ارگونومیک آنها باعث کاهش فشار روی پاشنه و قوس پا میشود
                </p>
                </div>
                 <div class="buttons-wrapper">
                <a href="#products" class="btn primary">
                    بیشتر...
                </a>
               </div>
            </div>
        </div>
     </section>


<section id="products">
  <div class="container">
    <h1 class="heading-1">
      جزئیات <span class="gradient-text">جذاب</span>
    </h1>

    <div class="products-container">
      <?php foreach($posts as $post): ?>
        <div class="product-card">
          <div class="top picture">
            <img src="uploads/<?= $post->image ?>" alt="" />
            <div class="flex-center btn share-btn">
              <i class="fa-solid fa-share"></i>
            </div>
          </div>
          <div class="middle details">
            <div class="flex row">
              <div class="flex-center">
                <h3 class="rating"><?= $post->price ?></h3>
                <div class="star flex-center">
                  <i class="fa-solid fa-star"></i>
                </div>
              </div>
              <h3 class="price"><?= $post->title ?></h3>
            </div>
            <p class="muted clamp-2 title">
              <?= $post->description ?>
            </p>
          </div>
          <div class="bottom">
            <a href="single.php?user_id=<?= $post->id ?>" class="btn flex-center add-product-text">
              خرید <i class="fa-solid fa-cart-plus"></i>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>




      <section id="sec">
        <h1>نظرات <span class="gradient-text">مشتری</span></h1>
        <div class="slider" id="slider">
    <div class="slide" id="slide">
    <div class="item slider-product">
        <div class="img1">
      <img  src="./assets/img/user-1.jpeg" alt="">
      </div>
      <div>
        <p class="muted">
            من از این کفش برای استفاده روز مره ام خریداری کردمو باید بگم
            مه از راحتی و سبکی آندشگفت زده شده ام علاوه بر ظاهر شیک و جذاب
            طراحی ارگونومیک آن باعث شد تا بعد از ساعت ها پیاده روی احساس 
            خستگی نکنم قطعا این بهترین خرید من ددر سال های اخیر بوده است
        </p>
        <div class="flex-center starts-container">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
      </div>
     </div>
      <div class="item slider-product">
        <div class="img1">
      <img  src="./assets/img/user-2.jpeg" alt="">
      </div>
        <div>
          <p class="muted">
            من از این کفش برای استفاده روز مره ام خریداری کردمو باید بگم
            مه از راحتی و سبکی آندشگفت زده شده ام علاوه بر ظاهر شیک و جذاب
            طراحی ارگونومیک آن باعث شد تا بعد از ساعت ها پیاده روی احساس 
            خستگی نکنم قطعا این بهترین خرید من ددر سال های اخیر بوده است
        </p>
        <div class="flex-center starts-container">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
        </div>
       </div>
    <div class="item slider-product">
      <div class="img1">
      <img  src="./assets/img/user-3.jpeg" alt="">
      </div>
      <div>
        <p class="muted">
            من از این کفش برای استفاده روز مره ام خریداری کردمو باید بگم
            مه از راحتی و سبکی آندشگفت زده شده ام علاوه بر ظاهر شیک و جذاب
            طراحی ارگونومیک آن باعث شد تا بعد از ساعت ها پیاده روی احساس 
            خستگی نکنم قطعا این بهترین خرید من ددر سال های اخیر بوده است
        </p>
        <div class="flex-center starts-container">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
      </div>
     </div>
    
      <div class="item slider-product">
        <div class="img1">
      <img  src="./assets/img/user-4.jpeg" alt="">
      </div>
        <div>
          <p class="muted">
            من از این کفش برای استفاده روز مره ام خریداری کردمو باید بگم
            مه از راحتی و سبکی آندشگفت زده شده ام علاوه بر ظاهر شیک و جذاب
            طراحی ارگونومیک آن باعث شد تا بعد از ساعت ها پیاده روی احساس 
            خستگی نکنم قطعا این بهترین خرید من ددر سال های اخیر بوده است
        </p>
        <div class="flex-center starts-container">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
        </div>
       </div>
      
    <div class="item slider-product">
      <div class="img1">
      <img  src="./assets/img/user-5.jpeg" alt="">
      </div>
      <div>
        <p class="muted">
            من از این کفش برای استفاده روز مره ام خریداری کردمو باید بگم
            مه از راحتی و سبکی آندشگفت زده شده ام علاوه بر ظاهر شیک و جذاب
            طراحی ارگونومیک آن باعث شد تا بعد از ساعت ها پیاده روی احساس 
            خستگی نکنم قطعا این بهترین خرید من ددر سال های اخیر بوده است
        </p>
        <div class="flex-center starts-container">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
      </div>
     </div>
    </div>
    <button class="ctrl-btn pro-prev">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
      </svg>
    </button>
    <button class="ctrl-btn pro-next">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
      </svg>
    </button>
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