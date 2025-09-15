<?php
require_once "loader.php";
if (!isset($_GET['id'])) {
    die("شناسه محصول ارسال نشده.");
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM post WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("محصول پیدا نشد.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
        $stmt = $conn->prepare("UPDATE post SET title=?, price=?, description=?, image=? WHERE id=?");
        $stmt->execute([$title, $price, $description, $image, $id]);
    } else {
        $stmt = $conn->prepare("UPDATE post SET title=?, price=?, description=? WHERE id=?");
        $stmt->execute([$title, $price, $description, $id]);
    }
    header("Location: adminpanel.php");
    exit;
}
?>
<h2>ویرایش محصول</h2>
<form method="post" enctype="multipart/form-data">
    <label>عنوان:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>"><br><br>
    <label>قیمت:</label><br>
    <input type="number" name="price" value="<?= $post['price'] ?>"><br><br>
    <label>توضیحات:</label><br>
    <textarea name="description"><?= htmlspecialchars($post['description']) ?></textarea><br><br>
    <label>تصویر جدید (در صورت نیاز):</label><br>
    <input type="file" name="image"><br>
    <?php if (!empty($post['image'])): ?>
        <img src="uploads/<?= $post['image'] ?>" width="100">
    <?php endif; ?><br><br>
    <button type="submit">ذخیره تغییرات</button>
</form>