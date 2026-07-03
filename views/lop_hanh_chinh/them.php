<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Lớp mới</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <script>
        // Hàm JS để bật tắt chế độ nhập Khoa mới
        function toggleNewKhoa() {
            var checkbox = document.getElementById('chk_new_khoa');
            var selectBox = document.getElementById('div_select_khoa');
            var inputBox = document.getElementById('div_input_khoa');

            if (checkbox.checked) {
                selectBox.style.display = 'none';
                inputBox.style.display = 'block';
                // Reset select về rỗng để controller biết
                document.getElementById('select_khoa').value = "";
            } else {
                selectBox.style.display = 'block';
                inputBox.style.display = 'none';
                // Xóa dữ liệu trong ô nhập mới
                document.getElementsByName('ma_khoa_moi')[0].value = "";
                document.getElementsByName('ten_khoa_moi')[0].value = "";
            }
        }
    </script>
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <div class="form-container">
        <h2>Thêm Lớp Hành Chính</h2>
        <form method="POST" action="">
            <label>Mã Lớp (VD: 73DCTT23):</label>
            <input type="text" name="ma_lop_hc" required placeholder="Nhập mã lớp...">

            <label>Tên Lớp (VD: 73DCTT23 - Công nghệ thông tin K73):</label>
            <input type="text" name="ten_lop_hc" required placeholder="Nhập tên đầy đủ...">

            <label>Khoa trực thuộc:</label>
            
            <div style="margin-bottom: 10px;">
                <input type="checkbox" id="chk_new_khoa" onchange="toggleNewKhoa()">
                <label for="chk_new_khoa" style="display:inline; font-weight:normal; cursor:pointer; color: #0054a6;">
                    <i class="fas fa-plus-circle"></i> Khoa này chưa có? Nhập mới ngay
                </label>
            </div>

            <div id="div_select_khoa">
                <select name="ma_khoa" id="select_khoa" style="width: 100%;">
                    <?php foreach ($dsKhoa as $k): ?>
                        <option value="<?php echo $k['ma_khoa']; ?>"><?php echo $k['ten_khoa']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="div_input_khoa" style="display: none; background: #f1f8ff; padding: 15px; border-radius: 5px; border: 1px dashed #0054a6;">
                <label style="color: #0054a6;">Mã Khoa Mới (VD: KT, XD...):</label>
                <input type="text" name="ma_khoa_moi" placeholder="Nhập mã viết tắt...">
                
                <label style="color: #0054a6; margin-top: 10px;">Tên Khoa Mới:</label>
                <input type="text" name="ten_khoa_moi" placeholder="Nhập tên khoa đầy đủ...">
            </div>

            <div style="margin-top: 20px; text-align: right;">
                <a href="index.php?controller=LopHC" class="btn" style="background: #ccc; color: #333;">Hủy</a>
                <button type="submit" class="btn btn-primary">Lưu lại</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>