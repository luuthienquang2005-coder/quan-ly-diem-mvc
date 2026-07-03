<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Lớp</title>
    <link rel="stylesheet" href="./public/css/style.css">
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <div class="form-container">
        <h2>Cập nhật Lớp: <?php echo $lop['ma_lop_hc']; ?></h2>
        <form method="POST" action="">
            <label>Mã Lớp (Không thể sửa):</label>
            <input type="text" value="<?php echo $lop['ma_lop_hc']; ?>" disabled style="background: #eee;">

            <label>Tên Lớp:</label>
            <input type="text" name="ten_lop_hc" value="<?php echo $lop['ten_lop_hc']; ?>" required>

            <label>Khoa trực thuộc:</label>
            <select name="ma_khoa" required>
                <?php foreach ($dsKhoa as $k): ?>
                    <option value="<?php echo $k['ma_khoa']; ?>" 
                        <?php if($k['ma_khoa'] == $lop['ma_khoa']) echo 'selected'; ?>>
                        <?php echo $k['ten_khoa']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div style="margin-top: 20px; text-align: right;">
                <a href="index.php?controller=LopHC" class="btn" style="background: #ccc; color: #333;">Hủy</a>
                <button type="submit" class="btn btn-warning">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>