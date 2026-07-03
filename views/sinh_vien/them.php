<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sinh viên</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<?php include './views/layout/menu.php'; ?>

<div class="form-container">
    <h2>Thêm Sinh viên Mới</h2>
    
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Mã Sinh Viên</label> <span>*</span>
            <input type="text" name="ma_sv" required placeholder="VD: SV001">
        </div>
        
        <div class="form-group">
            <label>Họ Tên</label>
            <input type="text" name="ho_ten" required placeholder="VD: Nguyễn Văn A">
        </div>

        <div class="form-group">
            <label>Ngày Sinh</label>
            <input type="date" name="ngay_sinh" required>
        </div>

        <div class="form-group">
            <label>Giới Tính</label>
            <select name="gioi_tinh">
                <option value="Nam">Nam</option>
                <option value="Nu">Nữ</option>
            </select>
        </div>

        <div class="form-group">
            <label>Lớp Hành Chính</label>
            <select name="ma_lop_hc">
                <?php foreach ($dsLop as $lop): ?>
                    <option value="<?php echo $lop['ma_lop_hc']; ?>">
                        <?php echo $lop['ten_lop_hc']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Lưu Sinh Viên</button>
        <a href="index.php?controller=SinhVien" style="margin-left: 10px; text-decoration: none;">Hủy</a>
    </form>
</div>

</body>
</html>