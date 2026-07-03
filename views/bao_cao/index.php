<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê báo cáo</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f6f9; }
        
        /* MENU REPORT (GIỮ NGUYÊN CŨ) */
        .menu-reports { display: flex; gap: 15px; margin-bottom: 20px; }
        .menu-reports a {
            flex: 1; padding: 20px; background: white; text-align: center;
            text-decoration: none; color: #333; border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); font-weight: bold; font-size: 16px;
            transition: 0.3s; border-bottom: 4px solid transparent;
        }
        .menu-reports a:hover { transform: translateY(-3px); }
        .menu-reports a.red { border-bottom-color: #dc3545; }
        .menu-reports a.green { border-bottom-color: #28a745; }
        .menu-reports a.blue { border-bottom-color: #007bff; }
        
        /* CHART CONTAINER (GIỮ NGUYÊN CŨ) */
        .chart-container { background: white; padding: 20px; border-radius: 8px; width: 70%; margin: 0 auto 30px auto; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

        /* --- PHẦN MỚI: GPA SEARCH STYLE --- */
        .gpa-search-container {
            background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .search-box {
            display: flex; gap: 10px; align-items: center; justify-content: center;
            background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;
        }
        .search-box select { padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-width: 250px; font-size: 14px; }
    </style>
</head>
<body>
    <?php include './views/layout/menu.php'; ?>

    <h2><i class="fas fa-chart-line"></i> Trung tâm Báo cáo & Thống kê</h2>

    <div class="menu-reports">
        <a href="index.php?controller=BaoCao&action=truotMon" class="red">
            Sinh viên Trượt môn<br><span style="font-size:12px; font-weight:normal; color:#666;">Danh sách nợ môn (F)</span>
        </a>
        <a href="index.php?controller=BaoCao&action=hocBong" class="green">
            Xét Học bổng<br><span style="font-size:12px; font-weight:normal; color:#666;">Sinh viên Xuất sắc / Giỏi</span>
        </a>
        <a href="index.php?controller=BaoCao&action=theoLop" class="blue">
            Thống kê theo Lớp<br><span style="font-size:12px; font-weight:normal; color:#666;">GPA & Tình trạng lớp</span>
        </a>
    </div>

    <div class="chart-container">
        <h3 style="text-align: center; color: #555;">Biểu đồ Phân bố Điểm (Toàn trường)</h3>
        <canvas id="gradeChart"></canvas>
    </div>

    <div class="gpa-search-container">
        <h3 style="color: #0054a6; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-filter"></i> Tra cứu chi tiết theo GPA
        </h3>

        <form method="GET" action="" class="search-box">
            <input type="hidden" name="controller" value="BaoCao">
            <input type="hidden" name="action" value="index">
            
            <label style="font-weight: bold;">Chọn mức GPA:</label>
            <select name="diem_chu" onchange="this.form.submit()">
                <option value="">-- Chọn dải điểm cần xem --</option>
                <?php foreach ($thangDiem as $key => $val): ?>
                    <option value="<?php echo $key; ?>" <?php if($diemChon == $key) echo 'selected'; ?>>
                        Điểm <?php echo $key; ?> (<?php echo $val['min'] . ' - ' . $val['max']; ?>) - <?php echo $val['label']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if(!empty($diemChon) && !empty($dsGPA)): ?>
                <a href="index.php?controller=BaoCao&action=exportTheoGPA&diem_chu=<?php echo $diemChon; ?>" 
                   class="btn" style="background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            <?php endif; ?>
        </form>

        <?php if($diemChon != ''): ?>
            <?php if(count($dsGPA) > 0): ?>
                <p style="font-style: italic; color: #555; text-align: center;">
                    Tìm thấy <strong><?php echo count($dsGPA); ?></strong> sinh viên đạt mức điểm <strong><?php echo $diemChon; ?></strong>
                </p>
                <table>
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã SV</th>
                            <th>Họ Tên</th>
                            <th>Lớp HC</th>
                            <th>Khoa</th>
                            <th>GPA (Hệ 4)</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt=1; foreach ($dsGPA as $sv): ?>
                        <tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php echo $sv['ma_sv']; ?></td>
                            <td><?php echo $sv['ho_ten']; ?></td>
                            <td><?php echo $sv['ten_lop_hc']; ?></td>
                            <td><?php echo $sv['ma_khoa']; ?></td>
                            <td style="font-weight: bold; color: #0054a6; font-size: 16px;">
                                <?php echo number_format($sv['gpa_tich_luy'], 2); ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="index.php?controller=SinhVien&action=xemDiem&ma_sv=<?php echo $sv['ma_sv']; ?>" 
                                   target="_blank" class="btn btn-info btn-sm" style="padding: 5px 10px; font-size: 12px;">
                                   <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 20px; color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; border-radius: 5px;">
                    Không có sinh viên nào trong dải điểm <strong><?php echo $diemChon; ?></strong> này.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        const ctx = document.getElementById('gradeChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $chartLabels; ?>,
                datasets: [{
                    label: 'Số lượng điểm',
                    data: <?php echo $chartData; ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)'
                    ],
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>