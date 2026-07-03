<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Môn Học</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        input { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #ff9800; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <?php include './views/layout/menu.php'; ?>

    <div class="form-container">
        <h2>Sửa Môn Học</h2>
        <form method="POST">
            <label>Mã Môn</label>
            <input type="text" name="ma_mon" value="<?php echo $monHoc['ma_mon']; ?>" readonly style="background-color: #eee;">
            
            <label>Tên Môn</label>
            <input type="text" name="ten_mon" value="<?php echo $monHoc['ten_mon']; ?>" required>
            
            <label>Số Tín Chỉ</label>
            <input type="number" name="so_tin_chi" value="<?php echo $monHoc['so_tin_chi']; ?>" required>
            
            <button type="submit">Cập nhật</button>
            <a href="index.php?controller=MonHoc">Hủy</a>
        </form>
    </div>
</body>
</html>