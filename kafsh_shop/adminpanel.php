<?php
require_once "loader.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['login']) || $_SESSION['role'] != "admin") {
    header('Location: ./index1.php');
    exit;
}
$stmt = $conn->prepare("SELECT * FROM orders ORDER BY order_date DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$query = "SELECT id, username FROM `users` WHERE role = ?";
$stmt = $conn->prepare($query);
$stmt->bindValue(1, "admin");
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt = $conn->query("SELECT * FROM post ORDER BY id DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt_customers = $conn->prepare("SELECT id, username, email, mobile FROM users WHERE role = 'customer' ORDER BY id DESC");
$stmt_customers->execute();
$customers = $stmt_customers->fetchAll(PDO::FETCH_ASSOC);
$stmt_employees = $conn->prepare("SELECT id, username, email, mobile, role FROM users WHERE role = 'admin' ORDER BY id DESC");
$stmt_employees->execute();
$employees = $stmt_employees->fetchAll(PDO::FETCH_ASSOC);
if (isset($_POST['submit'])) {
    $target_dir = "uploads/";
    $uploadOk = 1;
    if (empty($_FILES['fileToUpload']['tmp_name'])) {
        echo "<p class='alert alert-danger'>Please select an image to upload.</p>";
        $uploadOk = 0;
    } else {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            echo "<p class='alert alert-danger'>File is not an image.</p>";
            $uploadOk = 0;
        }
    }
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $image = ($uploadOk) ? basename($_FILES["fileToUpload"]["name"]) : '';
    $admin_id = isset($_POST['admin']) ? trim($_POST['admin']) : '';
    if (empty($admin_id)) {
        echo "<p class='alert alert-danger'>Please select an admin for this post.</p>";
        $uploadOk = 0;
    }
    if ($uploadOk) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $image)) {
            try {
                $query = "INSERT INTO `post` (title, price, description, image, admin_id) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bindValue(1, $title);
                $stmt->bindValue(2, $price);
                $stmt->bindValue(3, $description);
                $stmt->bindValue(4, $image);
                $stmt->bindValue(5, $admin_id);
                if ($stmt->execute()) {
                    echo "<p class='alert alert-success'>Post created successfully!</p>";
                } else {
                    echo "<p class='alert alert-danger'>Error inserting post into database. Please try again.</p>";
                }
            } catch (PDOException $e) {
                echo "<p class='alert alert-danger'>Database error: " . $e->getMessage() . "</p>";
            }
        } else {

            echo "<p class='alert alert-danger'>Sorry, there was an error uploading your file. Check server permissions for the '{$target_dir}' directory.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ادمین پنل</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: #ccc;
            padding: 15px 20px;
            font-size: 1.1em;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
            color: white;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }
        .sidebar .nav-link i {
            margin-left: 10px;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            margin-left: 270px;
        }
        .content-section {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }
        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .sidebar .sidebar-brand {
            font-size: 1.5em;
            font-weight: bold;
            padding: 20px;
            text-align: center;
            background-color: #2c3338;
            border-bottom: 1px solid #495057;
        }
@media (max-width: 768px) {
    .sidebar {
        width: 250px;
        position: fixed;
        left: -250px;
        top: 0;
        height: 100vh;
        z-index: 1000;
        transition: left 0.3s ease-in-out;
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar-brand {
        padding: 15px 20px;
    }

    .sidebar ul.nav-item {
        margin-bottom: 10px;
    }

    .sidebar ul.nav-link {
        padding: 10px 20px;
        font-size: 16px;
    }
    .sidebar-toggler {
        display: block;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1001;
        background: #333;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 20px;
    }
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
    .content-section {
        padding: 20px;
        margin-right: 0;
    }
    .main-content table {
        width: 100%;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
        margin-bottom: 20px;
    }
    .main-content th,
    .main-content td {
        padding: 8px 10px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group input[type="text"],
    .form-group input[type="file"],
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
    }
    .form-group button {
        width: 100%;
        padding: 12px;
    }
    .content-section:not(.active) {
        display: none;
    }
}
@media (max-width: 480px) {
    .sidebar {
        width: 200px;
    }
    .sidebar-brand a {
        font-size: 20px;
    }
    .sidebar ul.nav-link {
        font-size: 14px;
        padding: 8px 15px;
    }
    .sidebar-toggler {
        font-size: 18px;
        padding: 8px 12px;
    }
    .main-content {
        padding: 15px;
    }
    .main-content table {
        font-size: 12px;
    }
}
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <a href="#" class="text-white text-decoration-none">داشبورد ادمین</a>
        </div>
        <ul class="nav flex-column">
                    <li class="nav-item">
            <a class="nav-link" id="nav-add-product" href="#">
                <i class="fas fa-plus-circle"></i> افزودن محصول
            </a>
        </li>
            <li class="nav-item">
                <a class="nav-link" id="nav-products" href="#">
                    <i class="fas fa-shopping-bag"></i> لیست محصولات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav-customers" href="#">
                    <i class="fas fa-users"></i> لیست مشتریان
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav-employees" href="#">
                    <i class="fas fa-user-tie"></i> لیست کارمندان
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div id="content-add-product" class="content-section me-5">
        <h2>داشبورد ادمین</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">

            <label for="admin">ادمین </label>
               <select id="admin" name="admin" required>
                   <option value="">یک ادمین انتخاب کنید</option>
                   <?php foreach ($admins as $admin): ?>
                   <option value="<?php echo htmlspecialchars($admin->id); ?>"><?php echo htmlspecialchars($admin->username); ?></option>
                 <?php endforeach; ?>
               </select>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" placeholder="عنوان" name="title" required>
            </div>
            <div class="form-group">
                <label for="image">قیمت:</label>
                <input class="form-control" type="text" name="price" required>
            </div>
            <div class="form-group">
                <label for="image">تصویر:</label>
                <input class="form-control" type="file" name="fileToUpload" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="description">متن پست</label>
                <textarea class="form-control" placeholder="متن" name="description" id="description" cols="30" rows="10" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">افزودن پست</button>
            </div>
        </form>
                    </div>
        <div id="content-products" class="content-section">
            <h2>لیست محصولات</h2>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>شناسه</th>
        <th>تصویر</th>
        <th>عنوان</th>
        <th>قیمت</th>
        <th>توضیحات</th>
        <th>عملیات</th>
    </tr>
    <?php foreach ($posts as $post): ?>
    <tr>
        <td><?= $post['id'] ?></td>
        <td>
            <?php if (!empty($post['image'])): ?>
                <img src="uploads/<?= $post['image'] ?>" width="80" height="80" style="object-fit:cover;">
            <?php else: ?>
                بدون تصویر
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($post['title']) ?></td>
        <td><?= number_format($post['price']) ?> تومان</td>
        <td><?= nl2br(htmlspecialchars($post['description'])) ?></td>
        <td>
            <a href="edit_post.php?id=<?= $post['id'] ?>">ویرایش</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
        </div>
        <div id="content-customers" class="content-section">
            <h2>لیست مشتریان</h2>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>شناسه</th>
        <th>نام کاربری</th>
        <th>ایمیل</th>
        <th>موبایل</th>
        <th>عملیات</th>
    </tr>
    <?php foreach ($customers as $customer): ?>
    <tr>
        <td><?= $customer['id'] ?></td>
        <td><?= htmlspecialchars($customer['username']) ?></td>
        <td><?= htmlspecialchars($customer['email']) ?></td>
        <td><?= htmlspecialchars($customer['mobile']) ?></td>
        <td>
            <a href="customer_orders.php?id=<?= $customer['id'] ?>">نمایش سفارشات</a> |
            <a href="delete_customer.php?id=<?= $customer['id'] ?>"
               onclick="return confirm('آیا از حذف این مشتری مطمئن هستید؟')">حذف</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
        </div>
        <div id="content-employees" class="content-section">
    <h1>لیست کارمندان</h1>

    <table>
        <thead>
            <tr>
                <th>شناسه</th>
                <th>نام کاربری</th>
                <th>ایمیل</th>
                <th>موبایل</th>
                <th>نقش</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?php echo htmlspecialchars($employee['id']); ?></td>
                <td><?php echo htmlspecialchars($employee['username']); ?></td>
                <td><?php echo htmlspecialchars($employee['email']); ?></td>
                <td><?php echo htmlspecialchars($employee['mobile']); ?></td>
                <td><?php echo htmlspecialchars($employee['role']); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="5">هیچ کارمندی یافت نشد.</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarToggler = document.querySelector('.sidebar-toggler');

            if (sidebarToggler) {
                sidebarToggler.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                    mainContent.classList.toggle('sidebar-active');
                });
            }

            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    document.querySelectorAll('.sidebar .nav-link').forEach(navLink => {
                        navLink.classList.remove('active');
                    });
                    this.classList.add('active');

                    const target = this.id.replace('nav-', 'content-');

                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.remove('active');
                    });

                    document.getElementById(target).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>