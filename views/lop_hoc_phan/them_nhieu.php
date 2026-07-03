<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm nhiều sinh viên</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .filter-box { background: #f1f1f1; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .btn-save { position: fixed; bottom: 20px; right: 20px; padding: 15px 30px; background: #28a745; color: white; border: none; font-size: 16px; cursor: pointer; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); }
    </style>
    <script>
        // Script chọn tất cả checkbox
        function toggle(source) {
            checkboxes = document.getElementsByName('danh_sach_ma_sv[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                if(!checkboxes[i].disabled) {
                    checkboxes[i].checked = source.checked;
                }
            }
        }
    </script>
</head>
<body>
    <?php include './views/layout/menu.php'; ?>

    <h2>Thêm sinh viên vào lớp học phần</h2>
    <a href="index.php?controller=LopHocPhan&action=chiTiet&ma_lop_hp=<?php echo $ma_lop_hp; ?>">« Quay lại lớp học</a>

    <div class="filter-box">
        <form method="GET" action="">
            <input type="hidden" name="controller" value="LopHocPhan">
            <input type="hidden" name="action" value="themNhieu">
            <input type="hidden" name="ma_lop_hp" value="<?php echo $ma_lop_hp; ?>">
            
            <label><strong>Chọn Lớp Hành Chính để lấy danh sách:</strong></label>
            <select name="ma_lop_hc" onchange="this.form.submit()">
                <option value="">-- Chọn lớp --</option>
                <?php foreach ($dsLopHC as $lop): ?>
                    <option value="<?php echo $lop['ma_lop_hc']; ?>" 
                        <?php if($ma_lop_hc_selected == $lop['ma_lop_hc']) echo 'selected'; ?>>
                        <?php echo $lop['ten_lop_hc']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (!empty($dsSinhVienLoc)): ?>
        <form method="POST" action="">
            <button type="submit" class="btn-save">Lưu các sinh viên đã chọn</button>
            
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" onClick="toggle(this)"> Chọn tất cả</th>
                        <th>Mã SV</th>
                        <th>Họ Tên</th>
                        <th>Ngày sinh</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dsSinhVienLoc as $sv): 
                        // Kiểm tra xem sv này đã có trong lớp chưa
                        $daTonTai = $bangDiemModel->kiemTraTonTai($ma_lop_hp, $sv['ma_sv']);
                    ?>
                    <tr style="<?php echo $daTonTai ? 'background-color: #e9ecef; color: #888;' : ''; ?>">
                        <td>
                            <?php if (!$daTonTai): ?>
                                <input type="checkbox" name="danh_sach_ma_sv[]" value="<?php echo $sv['ma_sv']; ?>">
                            <?php else: ?>
                                <input type="checkbox" disabled checked> (Đã thêm)
                            <?php endif; ?>
                        </td>
                        <td><?php echo $sv['ma_sv']; ?></td>
                        <td><?php echo $sv['ho_ten']; ?></td>
                        <td><?php echo $sv['ngay_sinh']; ?></td>
                        <td><?php echo $daTonTai ? 'Đã trong lớp' : 'Chưa thêm'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php elseif ($ma_lop_hc_selected != ''): ?>
        <p>Lớp này chưa có sinh viên nào.</p>
    <?php endif; ?>
</body>
</html>