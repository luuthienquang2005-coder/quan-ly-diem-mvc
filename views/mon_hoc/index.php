<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Môn học</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px; }
        .btn-add { background-color: #008CBA; }
        .btn-del { background-color: #f44336; }
    </style>
</head>
<body>
    <?php include './views/layout/menu.php'; ?>
    
    <h2>Danh mục Môn Học</h2>
    <a href="index.php?controller=MonHoc&action=them" class="btn btn-add">+ Thêm Môn mới</a>

    <table>
        <thead>
            <tr>
                <th>Mã Môn</th>
                <th>Tên Môn</th>
                <th>Số Tín Chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dsMonHoc as $mh): ?>
            <tr>
                <td><?php echo $mh['ma_mon']; ?></td>
                <td><?php echo $mh['ten_mon']; ?></td>
                <td><?php echo $mh['so_tin_chi']; ?></td>
                <td>
                    <a href="index.php?controller=MonHoc&action=sua&ma_mon=<?php echo $mh['ma_mon']; ?>" 
                    class="btn" style="background-color: #ff9800;">Sửa</a>
                    
                    <a href="index.php?controller=MonHoc&action=xoa&ma_mon=<?php echo $mh['ma_mon']; ?>" 
                    class="btn btn-del" onclick="return confirm('Xóa môn này?');">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
