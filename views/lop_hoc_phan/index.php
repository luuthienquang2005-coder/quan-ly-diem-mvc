<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Lớp Học Phần</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px; }
        .btn-add { background-color: #008CBA; }
        .btn-view { background-color: #2196F3; }
    </style>
</head>
<body>
    <?php include './views/layout/menu.php'; ?>
    
   <div style="background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center;">
    
    <form action="" method="GET">
        <input type="hidden" name="controller" value="LopHocPhan">
        <label style="font-weight:bold;">📅 Chọn Học kỳ làm việc:</label>
        <select name="ma_hk" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px;">
            <option value="">-- Tất cả lịch sử --</option>
            <?php foreach ($dsHocKy as $hk): ?>
                <option value="<?php echo $hk['ma_hk']; ?>" <?php if($ma_hk_chon == $hk['ma_hk']) echo 'selected'; ?>>
                    <?php echo $hk['ten_hk']; ?> (<?php echo $hk['nam_hoc']; ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <a href="index.php?controller=LopHocPhan&action=them" class="btn btn-add">+ Mở Lớp Mới</a>
</div>

    <table>
        <thead>
            <tr>
                <th>Mã Lớp HP</th>
                <th>Môn Học</th>
                <th>Học Kỳ</th>
                <th>Giảng Viên</th>
                <th>Phòng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dsLop as $lop): ?>
            <tr>
                <td><?php echo $lop['ma_lop_hien_thi']; ?></td>
                <td><?php echo $lop['ten_mon']; ?></td>
                <td><?php echo $lop['ten_hk']; ?></td>
                <td><?php echo $lop['giang_vien_phu_trach']; ?></td>
                <td><?php echo $lop['phong_hoc']; ?></td>
                <td>
                    <a href="index.php?controller=LopHocPhan&action=chiTiet&ma_lop_hp=<?php echo $lop['ma_lop_hp']; ?>" 
                    class="btn btn-view">Chi tiết</a>
                    
                    <a href="index.php?controller=LopHocPhan&action=sua&ma_lop_hp=<?php echo $lop['ma_lop_hp']; ?>" 
                    class="btn" style="background-color: #ff9800;">Sửa</a>
                    
                    <a href="index.php?controller=LopHocPhan&action=xoa&ma_lop_hp=<?php echo $lop['ma_lop_hp']; ?>" 
                    class="btn btn-del" onclick="return confirm('CẢNH BÁO: Xóa lớp này sẽ xóa luôn danh sách sinh viên bên trong! Bạn chắc chứ?');">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>