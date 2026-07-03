<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Sinh viên</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include './views/layout/menu.php'; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2><i class="fas fa-user-graduate"></i> Quản lý Sinh viên</h2>
    </div>

    <div class="toolbar-container">
        
        <form action="" method="GET" class="filter-group">
            <input type="hidden" name="controller" value="SinhVien">
            <input type="hidden" name="action" value="index">

            <select name="khoa" onchange="this.form.submit()">
                <option value="">-- Chọn Khóa --</option>
                <?php foreach ($dsKhoa as $maKhoa => $tenKhoa): ?>
                    <option value="<?php echo $maKhoa; ?>" <?php if($khoa_chon == $maKhoa) echo 'selected'; ?>>
                        <?php echo $tenKhoa; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="lop" onchange="this.form.submit()">
                <option value="">-- Tất cả lớp --</option>
                <?php foreach ($dsLopHienThi as $lop): ?>
                    <option value="<?php echo $lop['ma_lop_hc']; ?>" <?php if($lop_chon == $lop['ma_lop_hc']) echo 'selected'; ?>>
                        <?php echo $lop['ten_lop_hc']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div style="position: relative;">
                <input type="text" name="tu_khoa" value="<?php echo isset($tu_khoa) ? $tu_khoa : ''; ?>" placeholder="Mã SV hoặc Tên...">
                <button type="submit" style="position: absolute; right: 5px; top: 5px; border: none; background: none; color: var(--primary-color); cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <a href="index.php?controller=SinhVien" class="btn" style="background: #e2e6ea; color: #333; height: 40px; display: flex; align-items: center;" title="Làm mới">
                <i class="fas fa-sync-alt"></i>
            </a>
        </form>

        <div class="action-group">
            <form action="index.php?controller=SinhVien&action=importExcel" method="POST" enctype="multipart/form-data" class="import-form">
                <input type="file" name="file_excel" accept=".csv" required style="width: 180px; font-size: 12px; border:none; padding:0;">
                <button type="submit" class="btn btn-success btn-icon" style="height: 30px; font-size: 12px;">
                    <i class="fas fa-upload"></i> Import
                </button>
            </form>

            <a href="index.php?controller=SinhVien&action=exportExcel" class="btn btn-warning btn-icon" style="background: #ffc107; color: #333;">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </a>

            <a href="index.php?controller=SinhVien&action=them" class="btn btn-primary btn-icon">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>Lớp</th>
                <th width="280">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dsSinhVien as $sv): ?>
            <tr>
                <td><?php echo $sv['ma_sv']; ?></td>
                <td><?php echo $sv['ho_ten']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($sv['ngay_sinh'])); ?></td>
                <td><?php echo $sv['gioi_tinh']; ?></td>
                <td><?php echo $sv['ten_lop_hc']; ?></td>
                <td>
                    <a href="index.php?controller=SinhVien&action=sua&ma_sv=<?php echo $sv['ma_sv']; ?>" 
                       class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
                    
                    <a href="index.php?controller=SinhVien&action=xoa&ma_sv=<?php echo $sv['ma_sv']; ?>" 
                       class="btn btn-del"
                       onclick="return confirm('Bạn chắc chắn muốn xóa?');"><i class="fas fa-trash"></i> Xóa</a>

                    <a href="index.php?controller=SinhVien&action=xemDiem&ma_sv=<?php echo $sv['ma_sv']; ?>" 
                       class="btn btn-info"><i class="fas fa-eye"></i> Xem</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div> 

</body>
</html>