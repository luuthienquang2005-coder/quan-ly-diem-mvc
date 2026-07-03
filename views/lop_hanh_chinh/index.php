<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Lớp Hành Chính</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2><i class="fas fa-school"></i> Quản lý Lớp Hành Chính</h2>
    </div>

    <div class="toolbar-container">
        <div class="filter-group">
            <span style="color: #666;">Danh sách các lớp sinh hoạt của sinh viên</span>
        </div>
        
        <div class="action-group">
            <a href="index.php?controller=LopHC&action=them" class="btn btn-primary btn-icon">
                <i class="fas fa-plus"></i> Thêm Lớp mới
            </a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã Lớp</th>
                <th>Tên Lớp Hành Chính</th>
                <th>Khoa trực thuộc</th>
                <th width="300">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dsLop as $l): ?>
            <tr>
                <td style="font-weight: bold; color: var(--primary-color);"><?php echo $l['ma_lop_hc']; ?></td>
                <td><?php echo $l['ten_lop_hc']; ?></td>
                <td><?php echo $l['ten_khoa']; ?></td>
                <td>
                    <a href="index.php?controller=LopHC&action=chiTiet&ma_lop_hc=<?php echo $l['ma_lop_hc']; ?>" 
                       class="btn btn-info"><i class="fas fa-list"></i> DSSV</a>

                    <a href="index.php?controller=LopHC&action=sua&ma_lop_hc=<?php echo $l['ma_lop_hc']; ?>" 
                       class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
                    
                    <a href="index.php?controller=LopHC&action=xoa&ma_lop_hc=<?php echo $l['ma_lop_hc']; ?>" 
                       class="btn btn-del" onclick="return confirm('Xóa lớp này sẽ xóa toàn bộ sinh viên? (Nếu có ràng buộc khóa ngoại sẽ không xóa được)');">
                       <i class="fas fa-trash"></i> Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>