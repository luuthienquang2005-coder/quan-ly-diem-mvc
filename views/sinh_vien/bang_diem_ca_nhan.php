<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điểm: <?php echo $sinhVien['ho_ten']; ?></title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 20px; line-height: 1.5; background: #ccc; }
        
        /* Giấy A4 mô phỏng */
        .a4-paper {
            background: white; width: 210mm; min-height: 297mm; margin: 0 auto; padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            position: relative;
        }

        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 5px 0; text-transform: uppercase; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        
        .grade-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
        .grade-table th, .grade-table td { border: 1px solid #333; padding: 8px; text-align: center; }
        .grade-table th { background-color: #eee; font-weight: bold; }
        .text-left { text-align: left !important; }
        
        .summary-box { border: 2px solid #333; padding: 15px; margin-top: 20px; width: 50%; float: right; }
        
        /* --- PHẦN SỬA LỖI GIAO DIỆN --- */
        
        /* 1. Class riêng cho cụm nút bấm trôi nổi ở góc */
        .floating-buttons { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            z-index: 999; /* Đảm bảo nút luôn nổi lên trên cùng */
            background: rgba(255,255,255,0.8); /* Nền mờ nhẹ để dễ nhìn */
            padding: 5px;
            border-radius: 5px;
        }

        .btn-print { 
            background: #007bff; color: white; padding: 10px 20px; 
            border: none; cursor: pointer; text-decoration: none; 
            font-family: Arial; border-radius: 5px; font-size: 14px;
            display: inline-block; margin-left: 5px;
        }
        
        .btn-back { background: #6c757d; }

        /* 2. Cấu hình khi IN ẤN (Ctrl + P) */
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .a4-paper { box-shadow: none; margin: 0; padding: 0; width: 100%; }
            
            /* Ẩn các nút bấm */
            .floating-buttons { display: none !important; }
            
            /* Ẩn bất cứ cái gì có class no-print (VD: cột hành động) */
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="floating-buttons">
        <a href="index.php?controller=SinhVien" class="btn-print btn-back">« Quay lại</a>
        <button onclick="window.print()" class="btn-print">🖨 In Bảng Điểm</button>
    </div>

    <div class="a4-paper">
        <div class="header">
            <h4>BỘ GIÁO DỤC VÀ ĐÀO TẠO</h4>
            <h3>TRƯỜNG ĐẠI HỌC CÔNG NGHỆ GTVT</h3>
            <hr style="width: 100px; border-top: 1px solid black;">
            <h2>BẢNG ĐIỂM CÁ NHÂN</h2>
        </div>

        <table class="info-table">
            <tr>
                <td width="150"><strong>Họ và tên:</strong></td>
                <td><?php echo $sinhVien['ho_ten']; ?></td>
                <td width="150"><strong>Mã Sinh Viên:</strong></td>
                <td><?php echo $sinhVien['ma_sv']; ?></td>
            </tr>
            <tr>
                <td><strong>Ngày sinh:</strong></td>
                <td><?php echo date('d/m/Y', strtotime($sinhVien['ngay_sinh'])); ?></td>
                <td><strong>Lớp hành chính:</strong></td>
                <td><?php echo $sinhVien['ma_lop_hc']; ?></td>
            </tr>
        </table>

        <table class="grade-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Môn Học</th>
                    <th>Số TC</th>
                    <th>Điểm TK (10)</th>
                    <th>Điểm Chữ</th>
                    <th>Điểm TK (4)</th>
                    <th>Học Kỳ</th>
                    <th class="no-print">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $stt = 1;
                foreach ($dsDiem as $diem): 
                ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td class="text-left"><?php echo $diem['ten_mon']; ?></td>
                    <td><?php echo $diem['so_tin_chi']; ?></td>
                    <td><?php echo $diem['diem_tong_ket_he_10']; ?></td>
                    <td><b><?php echo $diem['diem_chu']; ?></b></td>
                    <td><?php echo $diem['diem_tong_ket_he_4']; ?></td>
                    <td><?php echo $diem['ten_hk']; ?></td>
                    
                    <td class="no-print">
                        <a href="index.php?controller=LopHocPhan&action=nhapDiem&ma_lop_hp=<?php echo $diem['ma_lop_hp']; ?>" 
                           target="_blank" 
                           style="color: blue; text-decoration: underline; font-size: 12px;">
                           ✏ Sửa gốc
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="overflow: hidden;">
            <div class="summary-box">
                <p><strong>Tổng số tín chỉ tích lũy:</strong> <?php echo $tongTinChi; ?></p>
                <p><strong>Điểm trung bình tích lũy (CPA):</strong> <?php echo $gpa; ?> / 4.0</p>
                <p><strong>Xếp loại học lực:</strong> <?php echo $xepLoai; ?></p>
            </div>
        </div>

        <div style="margin-top: 50px; text-align: right; padding-right: 50px;">
            <p><em>Hà Nội, ngày <?php echo date('d'); ?> tháng <?php echo date('m'); ?> năm <?php echo date('Y'); ?></em></p>
            <p><strong>NGƯỜI LẬP BẢNG</strong></p>
            <br><br><br>
            <p><?php echo $_SESSION['admin_name']; ?></p>
        </div>
    </div>

</body>
</html>