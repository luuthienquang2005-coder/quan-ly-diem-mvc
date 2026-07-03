<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sinh viên</title>
    <style>
        /* (Copy lại CSS giống file them.php hoặc dùng chung file css) */
        body { font-family: Arial, sans-serif; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 20px; background-color: #ff9800; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>

<?php include './views/layout/menu.php'; ?>

<div class="form-container">
    <h2>Cập nhật Sinh viên</h2>
    
    <form action="" method="POST">
        <div class="form-group">
            <label>Mã Sinh Viên (Không thể sửa)</label>
            <input type="text" name="ma_sv" value="<?php echo $sinhVien['ma_sv']; ?>" readonly style="background-color: #eee;">
        </div>
        
        <div class="form-group">
            <label>Họ Tên</label>
            <input type="text" name="ho_ten" value="<?php echo $sinhVien['ho_ten']; ?>" required>
        </div>

        <div class="form-group">
            <label>Ngày Sinh</label>
            <input type="date" name="ngay_sinh" value="<?php echo $sinhVien['ngay_sinh']; ?>" required>
        </div>

        <div class="form-group">
            <label>Giới Tính</label>
            <select name="gioi_tinh">
                <option value="Nam" <?php if($sinhVien['gioi_tinh'] == 'Nam') echo 'selected'; ?>>Nam</option>
                <option value="Nu" <?php if($sinhVien['gioi_tinh'] == 'Nu') echo 'selected'; ?>>Nữ</option>
            </select>
        </div>

        <div class="form-group">
            <label>Lớp Hành Chính</label>
            <select name="ma_lop_hc">
                <?php foreach ($dsLop as $lop): ?>
                    <option value="<?php echo $lop['ma_lop_hc']; ?>" 
                        <?php if($sinhVien['ma_lop_hc'] == $lop['ma_lop_hc']) echo 'selected'; ?>>
                        <?php echo $lop['ten_lop_hc']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Cập nhật</button>
        <a href="index.php?controller=SinhVien" style="margin-left: 10px;">Hủy</a>
    </form>
</div>
</body>
</html>