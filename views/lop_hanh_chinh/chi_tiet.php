<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết lớp</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>
            <i class="fas fa-users"></i> Danh sách sinh viên: 
            <span style="color: var(--secondary-color);"><?php echo $lop['ten_lop_hc']; ?></span>
        </h2>
        <a href="index.php?controller=LopHC" class="btn" style="background: #ccc; color: #333;">« Quay lại DS Lớp</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Họ Tên</th>
                <th>Ngày Sinh</th>
                <th>Giới Tính</th>
                <th>Trạng Thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($dsSinhVien) > 0): ?>
                <?php foreach ($dsSinhVien as $sv): ?>
                <tr>
                    <td><?php echo $sv['ma_sv']; ?></td>
                    <td><?php echo $sv['ho_ten']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($sv['ngay_sinh'])); ?></td>
                    <td><?php echo $sv['gioi_tinh']; ?></td>
                    <td><?php echo $sv['trang_thai']; ?></td>
                    <td>
                        <a href="index.php?controller=SinhVien&action=xemDiem&ma_sv=<?php echo $sv['ma_sv']; ?>" 
                           class="btn btn-info btn-sm">Xem điểm</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #666; font-style: italic;">Chưa có sinh viên nào trong lớp này.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>