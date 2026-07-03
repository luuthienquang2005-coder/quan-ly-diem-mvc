<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách trượt môn</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2> Danh sách Sinh viên Trượt môn (Điểm F)</h2>
        <a href="index.php?controller=BaoCao&action=exportTruotMon" class="btn" 
        style="background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-flex; align-items: center; font-weight: bold;">
            <i class="fas fa-file-excel" style="margin-right: 5px;"></i> Xuất Excel
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th style="background: #dc3545;">Mã SV</th>
                <th style="background: #dc3545;">Họ Tên</th>
                <th style="background: #dc3545;">Lớp HC</th>
                <th style="background: #dc3545;">Môn Trượt</th>
                <th style="background: #dc3545;">Lớp HP</th>
                <th style="background: #dc3545;">Điểm TK</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($dsTruot) > 0): ?>
                <?php foreach ($dsTruot as $row): ?>
                <tr>
                    <td><?php echo $row['ma_sv']; ?></td>
                    <td><?php echo $row['ho_ten']; ?></td>
                    <td><?php echo $row['ma_lop_hc']; ?></td>
                    <td style="color: red; font-weight: bold;"><?php echo $row['ten_mon']; ?></td>
                    <td><?php echo $row['ma_lop_hien_thi']; ?></td>
                    <td style="font-weight: bold;"><?php echo $row['diem_tong_ket_he_10']; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">Không có sinh viên nào trượt môn.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>