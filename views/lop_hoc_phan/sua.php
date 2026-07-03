<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật Lớp Học Phần</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include './views/layout/menu.php'; ?>

    <div class="form-container">
        <h2><i class="fas fa-edit"></i> Cập nhật Lớp Học Phần</h2>
        
        <form method="POST" action="">
            
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Mã Lớp Hiển Thị:</label>
                    <input type="text" name="ma_lop_hien_thi" 
                           value="<?php echo $lop['ma_lop_hien_thi']; ?>" required>
                </div>
                <div style="flex: 1;">
                    <label>Môn Học:</label>
                    <select name="ma_mon" required style="height: 42px;">
                        <?php foreach ($dsMon as $m): ?>
                            <option value="<?php echo $m['ma_mon']; ?>" 
                                <?php if($lop['ma_mon'] == $m['ma_mon']) echo 'selected'; ?>>
                                <?php echo $m['ten_mon']; ?> (<?php echo $m['so_tin_chi']; ?> TC)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <label>Học Kỳ:</label>
            <select name="ma_hk" required style="margin-bottom: 15px; height: 42px;">
                <?php foreach ($dsHocKy as $hk): ?>
                    <option value="<?php echo $hk['ma_hk']; ?>" 
                        <?php if($lop['ma_hk'] == $hk['ma_hk']) echo 'selected'; ?>>
                        <?php echo $hk['ten_hk']; ?> (Năm học: <?php echo $hk['nam_hoc']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Giảng Viên Phụ Trách:</label>
                    <input type="text" name="giang_vien" 
                           value="<?php echo $lop['giang_vien_phu_trach']; ?>" required>
                </div>
                <div style="flex: 1;">
                    <label>Phòng Học:</label>
                    <input type="text" name="phong_hoc" 
                           value="<?php echo $lop['phong_hoc']; ?>" required>
                </div>
            </div>

            <label>Tổng số buổi học:</label>
            <input type="number" name="tong_so_buoi" 
                   value="<?php echo $lop['tong_so_buoi_hoc']; ?>" required min="1" style="width: 150px;">
            
            <div style="margin-top: 30px; text-align: right;">
                <a href="index.php?controller=LopHocPhan" class="btn" style="background: #ccc; color: #333;">Hủy bỏ</a>
                <button type="submit" class="btn btn-warning" style="color: #333; font-weight: bold;">
                    <i class="fas fa-save"></i> Lưu Cập Nhật
                </button>
            </div>
        </form>
    </div>
</body>
</html>