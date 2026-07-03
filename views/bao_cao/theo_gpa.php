<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê theo GPA</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <h2>Thống kê Sinh viên theo Xếp loại GPA</h2>

    <div class="toolbar-container">
        <form method="GET" action="" class="filter-group">
            <input type="hidden" name="controller" value="BaoCao">
            <input type="hidden" name="action" value="theoGPA">
            
            <label style="margin:0;">Chọn Mức điểm (A - F):</label>
            <select name="diem_chu" onchange="this.form.submit()" style="min-width: 200px; font-weight:bold; color: var(--primary-color);">
                <option value="">-- Chọn mức điểm --</option>
                <?php foreach ($thangDiem as $key => $val): ?>
                    <option value="<?php echo $key; ?>" <?php if($diemChon == $key) echo 'selected'; ?>>
                        Điểm <?php echo $key; ?> (<?php echo $val['min'] . ' - ' . $val['max']; ?>) - <?php echo $val['label']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if(!empty($diemChon) && !empty($dsKetQua)): ?>
            <div class="action-group">
                <a href="index.php?controller=BaoCao&action=exportTheoGPA&diem_chu=<?php echo $diemChon; ?>" class="btn" 
                   style="background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-flex; align-items: center; font-weight: bold;">
                    <i class="fas fa-file-excel" style="margin-right: 5px;"></i> Xuất Excel DS này
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if($diemChon != ''): ?>
        <div style="margin-bottom: 10px; font-style: italic;">
            Đang hiển thị danh sách sinh viên có GPA thuộc mức <strong><?php echo $diemChon; ?></strong> 
            (<?php echo $thangDiem[$diemChon]['label']; ?>)
        </div>

        <?php if(count($dsKetQua) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã SV</th>
                        <th>Họ Tên</th>
                        <th>Ngày sinh</th>
                        <th>Lớp Hành Chính</th>
                        <th>Khoa</th>
                        <th>GPA (Hệ 4)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $stt=1; foreach ($dsKetQua as $sv): ?>
                    <tr>
                        <td><?php echo $stt++; ?></td>
                        <td><?php echo $sv['ma_sv']; ?></td>
                        <td><?php echo $sv['ho_ten']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($sv['ngay_sinh'])); ?></td>
                        <td><?php echo $sv['ten_lop_hc']; ?></td>
                        <td><?php echo $sv['ma_khoa']; ?></td>
                        <td style="font-weight: bold; color: #0054a6; font-size: 16px;">
                            <?php echo number_format($sv['gpa_tich_luy'], 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">
                Không tìm thấy sinh viên nào có mức điểm <strong><?php echo $diemChon; ?></strong>.
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-info">
            Vui lòng chọn một mức điểm để xem thống kê.
        </div>
    <?php endif; ?>

</div>
</body>
</html>