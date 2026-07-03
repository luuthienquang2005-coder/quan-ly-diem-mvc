<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết Lớp học</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .info-box { background: #e3f2fd; padding: 15px; border-left: 5px solid #2196F3; margin-bottom: 20px; }
        .add-student-box { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; display: flex; gap: 10px; align-items: center;}
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #4CAF50; color: white; }
        .btn-del { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <?php include './views/layout/menu.php'; ?>

    <div class="info-box">
        <h2>Lớp: <?php echo $thongTinLop['ma_lop_hien_thi']; ?> - <?php echo $thongTinLop['ten_mon']; ?></h2>
        <p>Giảng viên: <strong><?php echo $thongTinLop['giang_vien_phu_trach']; ?></strong> | Phòng: <?php echo $thongTinLop['phong_hoc']; ?></p>
        <p>Tổng số buổi: <?php echo $thongTinLop['tong_so_buoi_hoc']; ?></p>
    </div>

    <div class="add-student-box" style="display: block;">
        
        <div style="margin-bottom: 15px; border-bottom: 1px dashed #ccc; padding-bottom: 10px;">
            <strong>Thêm thủ công:</strong>
            <form action="index.php?controller=LopHocPhan&action=themSinhVien" method="POST" style="display:inline-block;">
                <input type="hidden" name="ma_lop_hp" value="<?php echo $thongTinLop['ma_lop_hp']; ?>">
                <input type="text" name="ma_sv" placeholder="Mã SV" required style="padding: 5px;">
                <button type="submit">Thêm</button>
            </form>
            
            <a href="index.php?controller=LopHocPhan&action=themNhieu&ma_lop_hp=<?php echo $thongTinLop['ma_lop_hp']; ?>" 
            class="btn" style="background:#2196F3; color:white; text-decoration:none; padding:6px 10px; margin-left:10px;">
            + Chọn từ danh sách lớp HC
            </a>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            
            <form action="index.php?controller=LopHocPhan&action=importExcel" method="POST" enctype="multipart/form-data">
                <strong>Import Excel (CSV):</strong>
                <input type="hidden" name="ma_lop_hp" value="<?php echo $thongTinLop['ma_lop_hp']; ?>">
                <input type="file" name="file_excel" accept=".csv" required>
                <button type="submit" style="background: #28a745; color: white; border: none; padding: 5px 10px;">Upload</button>
                <small style="color: #666;">(File CSV: Cột A chứa Mã SV)</small>
            </form>
            <a href="index.php?controller=LopHocPhan&action=nhapDiem&ma_lop_hp=<?php echo $thongTinLop['ma_lop_hp']; ?>" 
            class="btn" style="background:#673AB7; color:white; padding:10px 20px; font-weight:bold;">
            ✎ VÀO SỔ ĐIỂM
            </a>
            <a href="index.php?controller=LopHocPhan&action=exportExcel&ma_lop_hp=<?php echo $thongTinLop['ma_lop_hp']; ?>" 
            class="btn" style="background: #ffc107; color: black; text-decoration:none; padding:8px 15px; font-weight: bold;">
            ⬇ Xuất Excel danh sách
            </a>
        </div>

    </div>

    <h3>Danh sách Sinh viên trong lớp (<?php echo count($dsSinhVienLop); ?> sinh viên)</h3>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã SV</th>
                <th>Họ Tên</th>
                <th>Lớp HC</th>
                <th>Điểm CC</th>
                <th>Điểm GK</th>
                <th>Điểm CK</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php $stt = 1; foreach ($dsSinhVienLop as $sv): ?>
            <tr>
                <td><?php echo $stt++; ?></td>
                <td><?php echo $sv['ma_sv']; ?></td>
                <td style="text-align:left;"><?php echo $sv['ho_ten']; ?></td>
                <td><?php echo $sv['ma_lop_hc']; ?></td>
                <td><?php echo $sv['diem_chuyen_can']; ?></td>
                <td><?php echo $sv['diem_giua_ky']; ?></td>
                <td><?php echo $sv['diem_cuoi_ky']; ?></td>
                <td>
                    <a href="index.php?controller=LopHocPhan&action=xoaSinhVien&id=<?php echo $sv['id']; ?>&ma_lop_hp=<?php echo $thongTinLop['ma_lop_hp']; ?>" 
                       class="btn-del" onclick="return confirm('Xóa sinh viên này khỏi lớp?');">Hủy học phần</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>