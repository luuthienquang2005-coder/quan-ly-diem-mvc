<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Môn Học</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php include './views/layout/menu.php'; ?>

    <div class="form-container">
        <h2>Thêm Môn Học Mới</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Mã Môn</label>
                <input type="text" name="ma_mon" required placeholder="VD: MH01">
            </div>
            <div class="form-group">
                <label>Tên Môn</label>
                <input type="text" name="ten_mon" required placeholder="VD: Lập trình Web">
            </div>
            <div class="form-group">
                <label>Số Tín Chỉ</label>
                <input type="number" name="so_tin_chi" required min="1" max="10" value="3">
            </div>
            <button type="submit">Lưu Môn Học</button>
            <a href="index.php?controller=MonHoc">Hủy</a>
        </form>
    </div>
</body>
</html>