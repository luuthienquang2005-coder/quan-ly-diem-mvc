<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhập điểm</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #2196F3; color: white; }
        input[type="number"] { width: 60px; text-align: center; padding: 5px; }
        .btn-save { position: fixed; bottom: 20px; right: 20px; padding: 15px 30px; background: #28a745; color: white; border: none; font-size: 16px; cursor: pointer; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); }
        .vang-btn { font-size: 12px; text-decoration: none; color: blue; }
        .alert { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <?php include './views/layout/menu.php'; ?>
    
    <h2>Nhập điểm: <?php echo $thongTinLop['ten_mon']; ?></h2>
    <a href="index.php?controller=LopHocPhan&action=chiTiet&ma_lop_hp=<?php echo $thongTinLop['ma_lop_hp']; ?>">« Quay lại chi tiết lớp</a>

    <?php if(!empty($message)): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <button type="submit" class="btn-save">💾 LƯU BẢNG ĐIỂM</button>
        
        <table>
            <thead>
                <tr>
                    <th rowspan="2">STT</th>
                    <th rowspan="2">Mã SV</th>
                    <th rowspan="2">Họ Tên</th>
                    <th colspan="3">Chuyên cần (10%)</th>
                    <th rowspan="2">Giữa kỳ<br>(30%)</th>
                    <th rowspan="2">Cuối kỳ<br>(300%)</th>
                
                    <th colspan="3">Tổng kết</th>
                </tr>
                <tr>
                    <th>Số buổi vắng</th>
                    <th>điểm thưong</th>
                    <th>Điểm CC<br>(Tự động)</th>
                    <th>Hệ 10</th>
                    <th>Chữ</th>
                    <th>Hệ 4</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt=1; foreach ($dsSinhVien as $sv): ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><?php echo $sv['ma_sv']; ?></td>
                    <td style="text-align:left;"><?php echo $sv['ho_ten']; ?></td>
                    
                    <td>
                        <span style="font-weight:bold; color:red;"><?php echo $sv['so_buoi_vang']; ?></span><br>
                        
                        <a href="index.php?controller=LopHocPhan&action=quanLyVang&ma_lop_hp=<?php echo $thongTinLop['ma_lop_hp']; ?>&ma_sv=<?php echo $sv['ma_sv']; ?>&from=nhapDiem" 
                        class="vang-btn" style="color: #007bff; text-decoration: none; font-weight: bold;">
                        <small>✎ Cập nhật vắng</small>
                        </a>
                    </td>
                    
                    <td style="background:#f9f9f9; font-weight:bold;"><?php echo $sv['diem_chuyen_can']; ?></td>
                    
                    <td>
                        <input type="number" step="0.1" min="0" max="10" 
                               name="diem_gk[<?php echo $sv['ma_sv']; ?>]" 
                               value="<?php echo $sv['diem_giua_ky']; ?>">
                    </td>
                    
                    <td>
                        <input type="number" step="0.1" min="0" max="10" 
                               name="diem_ck[<?php echo $sv['ma_sv']; ?>]" 
                               value="<?php echo $sv['diem_cuoi_ky']; ?>">
                    </td>
                    
                    <td style="background:#e3f2fd;"><?php echo $sv['diem_tong_ket_he_10']; ?></td>
                    <td style="background:#e3f2fd; font-weight:bold; color:blue;"><?php echo $sv['diem_chu']; ?></td>
                    <td style="background:#e3f2fd;"><?php echo $sv['diem_tong_ket_he_4']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</body>
</html>