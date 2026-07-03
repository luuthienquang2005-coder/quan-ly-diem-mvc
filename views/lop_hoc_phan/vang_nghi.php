<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết vắng nghỉ</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; padding: 20px; }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h3 { margin: 0; font-size: 18px; }
        .header .info { font-size: 14px; opacity: 0.9; margin-top: 5px;}

        .content { padding: 20px; }

        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .back-btn:hover { background: rgba(255,255,255,0.3); }

        .add-form {
            display: flex;
            gap: 10px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }
        .add-form input {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            flex: 1;
        }
        .add-form button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .add-form button:hover { background: #218838; }

        table { width: 100%; border-collapse: collapse; }
        th { background-color: #e9ecef; color: #495057; text-align: left; padding: 12px; font-size: 14px; }
        td { border-bottom: 1px solid #dee2e6; padding: 12px; color: #333; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f8f9fa; }

        .btn-del {
            color: #dc3545;
            background: none;
            border: 1px solid #dc3545;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            transition: 0.2s;
        }
        .btn-del:hover { background: #dc3545; color: white; }
        
        .empty-state { text-align: center; color: #6c757d; padding: 20px; font-style: italic; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div>
                <h3><?php echo $sinhVien['ho_ten']; ?></h3>
                <div class="info">MSSV: <?php echo $sinhVien['ma_sv']; ?> | Tổng vắng: <strong><?php echo count($dsVang); ?></strong> buổi</div>
            </div>
            
            <?php 
                // Nếu from = nhapDiem thì về trang Nhập Điểm, ngược lại về trang Chi tiết lớp
                if (isset($from) && $from == 'nhapDiem') {
                    $linkQuayLai = "index.php?controller=LopHocPhan&action=nhapDiem&ma_lop_hp=$ma_lop_hp";
                    $textQuayLai = "↩ Quay lại Nhập điểm";
                } else {
                    $linkQuayLai = "index.php?controller=LopHocPhan&action=chiTiet&ma_lop_hp=$ma_lop_hp";
                    $textQuayLai = "↩ Quay lại Lớp học";
                }
            ?>
            <a href="<?php echo $linkQuayLai; ?>" class="back-btn"><?php echo $textQuayLai; ?></a>
        </div>

        <div class="content">
            <form method="POST" class="add-form">
                <input type="date" name="ngay_vang" required title="Chọn ngày vắng">
                <input type="text" name="ly_do" placeholder="Nhập lý do (VD: Ốm, Việc riêng...)" required>
                <button type="submit">+ Thêm</button>
            </form>

            <?php if(count($dsVang) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="30%">Ngày vắng</th>
                        <th>Lý do</th>
                        <th width="15%" style="text-align: center;">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dsVang as $v): ?>
                    <tr>
                        <td><strong><?php echo date('d/m/Y', strtotime($v['ngay_vang'])); ?></strong></td>
                        <td><?php echo $v['ly_do']; ?></td>
                        <td style="text-align: center;">
                            <a href="index.php?controller=LopHocPhan&action=quanLyVang&ma_lop_hp=<?php echo $ma_lop_hp; ?>&ma_sv=<?php echo $sinhVien['ma_sv']; ?>&xoa_id=<?php echo $v['id']; ?><?php echo ($from != '') ? "&from=$from" : ''; ?>" 
                               class="btn-del" onclick="return confirm('Bạn chắc chắn muốn xóa ngày nghỉ này?');">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="empty-state">Sinh viên này đi học đầy đủ (Chưa vắng buổi nào).</div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>