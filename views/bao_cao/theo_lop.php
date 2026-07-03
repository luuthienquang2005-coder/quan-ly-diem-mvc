<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê theo lớp</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <h2>Thống kê kết quả học tập theo Lớp</h2>

    <div class="toolbar-container">
        <form method="GET" action="" class="filter-group">
            <input type="hidden" name="controller" value="BaoCao">
            <input type="hidden" name="action" value="theoLop">
            
            <label style="margin:0;">Chọn Lớp hành chính:</label>
            <select name="ma_lop_hc" onchange="this.form.submit()" style="min-width: 250px;">
                <option value="">-- Vui lòng chọn lớp --</option>
                <?php foreach ($dsLop as $lop): ?>
                    <option value="<?php echo $lop['ma_lop_hc']; ?>" <?php if($lopChon == $lop['ma_lop_hc']) echo 'selected'; ?>>
                        <?php echo $lop['ten_lop_hc']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if(!empty($lopChon) && !empty($dsKetQua)): ?>
            <div class="action-group">
                <a href="index.php?controller=BaoCao&action=exportTheoLop&ma_lop_hc=<?php echo $lopChon; ?>" class="btn" 
                style="background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-flex; align-items: center; font-weight: bold;">
                    <i class="fas fa-file-excel" style="margin-right: 5px;"></i> Xuất Excel lớp này
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if(!empty($dsKetQua)): ?>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã SV</th>
                    <th>Họ Tên</th>
                    <th>GPA Hiện tại</th>
                    <th>Số môn nợ (F)</th>
                    <th>Tình trạng</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt=1; foreach ($dsKetQua as $kq): ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><?php echo $kq['ma_sv']; ?></td>
                    <td><?php echo $kq['ho_ten']; ?></td>
                    <td style="font-weight:bold;"><?php echo ($kq['gpa'] > 0) ? number_format($kq['gpa'], 2) : '-'; ?></td>
                    <td style="<?php echo ($kq['so_mon_no'] > 0) ? 'color:red; font-weight:bold;' : ''; ?>; text-align:center;">
                        <?php echo $kq['so_mon_no']; ?>
                    </td>
                    <td>
                        <?php 
                            if ($kq['gpa'] > 0 && $kq['gpa'] < 2.0) echo '<span style="color:red; font-weight:bold;"><i class="fas fa-exclamation-triangle"></i> Cảnh báo học vụ</span>';
                            else if ($kq['gpa'] >= 3.2) echo '<span style="color:green; font-weight:bold;">Tốt</span>';
                            else echo 'Bình thường';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif($lopChon != ''): ?>
        <div class="alert alert-warning">Lớp này chưa có dữ liệu điểm.</div>
    <?php endif; ?>
</div>
</body>
</html>