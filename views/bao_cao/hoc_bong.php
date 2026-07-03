<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách học bổng</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Danh sách Khen thưởng / Học bổng</h2>
        <a href="index.php?controller=BaoCao&action=exportHocBong&loai=<?php echo $loai; ?>" class="btn" 
         style="background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-flex; align-items: center; font-weight: bold;">
            <i class="fas fa-file-excel" style="margin-right: 5px;"></i> Xuất Excel
        </a>
    </div>
    
    <div class="toolbar-container" style="justify-content: flex-start;">
        <a href="index.php?controller=BaoCao&action=hocBong&loai=XuatSac" 
           class="btn <?php echo ($loai=='XuatSac') ? 'btn-primary' : ''; ?>" style="background: <?php echo ($loai!='XuatSac') ? '#eee; color:#333' : ''; ?>;">
           Xuất sắc (GPA >= 3.6)
        </a>
        <a href="index.php?controller=BaoCao&action=hocBong&loai=Gioi" 
           class="btn <?php echo ($loai=='Gioi') ? 'btn-primary' : ''; ?>" style="background: <?php echo ($loai!='Gioi') ? '#eee; color:#333' : ''; ?>;">
           Giỏi (GPA >= 3.2)
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th style="background: #ffc107; color: black;">Xếp hạng</th>
                <th style="background: #ffc107; color: black;">Mã SV</th>
                <th style="background: #ffc107; color: black;">Họ Tên</th>
                <th style="background: #ffc107; color: black;">Lớp HC</th>
                <th style="background: #ffc107; color: black;">GPA Tích lũy</th>
                <th style="background: #ffc107; color: black;">Xếp loại</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($dsSV) > 0): ?>
                <?php $i=1; foreach ($dsSV as $sv): ?>
                <tr>
                    <td>#<?php echo $i++; ?></td>
                    <td><?php echo $sv['ma_sv']; ?></td>
                    <td><?php echo $sv['ho_ten']; ?></td>
                    <td><?php echo $sv['ma_lop_hc']; ?></td>
                    <td style="font-weight: bold; color: blue;"><?php echo number_format($sv['gpa'], 2); ?></td>
                    <td><?php echo ($sv['gpa'] >= 3.6) ? 'Xuất sắc' : 'Giỏi'; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">Chưa có sinh viên nào đạt tiêu chuẩn này.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>