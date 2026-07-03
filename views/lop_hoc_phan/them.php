<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mở lớp học phần</title>
    <link rel="stylesheet" href="./public/css/style.css">
    <script>
        // Hàm bật/tắt chế độ nhập học kỳ mới
        function toggleNewHK() {
            var checkbox = document.getElementById('chk_new_hk');
            var selectBox = document.getElementById('khu_vuc_chon');
            var inputBox = document.getElementById('khu_vuc_nhap');

            if (checkbox.checked) {
                // Chế độ nhập mới
                selectBox.style.display = 'none';
                inputBox.style.display = 'block';
                
                // Vô hiệu hóa select để khi submit không bị gửi dữ liệu thừa
                document.getElementById('select_hk_cu').disabled = true;
                
                // Reset ô select
                document.getElementById('select_hk_cu').value = "";
            } else {
                // Chế độ chọn cũ
                selectBox.style.display = 'block';
                inputBox.style.display = 'none';
                
                // Bật lại select
                document.getElementById('select_hk_cu').disabled = false;
            }
        }

        // Hàm tự động tính năm kết thúc (VD: Nhập 2023 -> Hiện 2023-2024)
        function autoCalcYear(input) {
            var namBatDau = parseInt(input.value);
            var namHienTai = new Date().getFullYear();

            // Chặn nhập quá năm hiện tại (Validation JS phụ trợ)
            if (namBatDau > namHienTai) {
                alert("Không được nhập năm lớn hơn năm hiện tại (" + namHienTai + ")");
                input.value = namHienTai;
                namBatDau = namHienTai;
            }

            if (!isNaN(namBatDau)) {
                document.getElementById('hien_thi_nien_khoa').value = namBatDau + " - " + (namBatDau + 1);
            }
        }
    </script>
</head>
<body>
<?php include './views/layout/menu.php'; ?>

    <div class="form-container">
        <h2><i class="fas fa-chalkboard-teacher"></i> Mở Lớp Học Phần Mới</h2>
        
        <form method="POST" action="">
            
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Mã Lớp Hiển Thị (VD: LHP-WEB-02):</label>
                    <input type="text" name="ma_lop_hien_thi" required placeholder="Nhập mã lớp..." style="width: 100%;">
                </div>
                <div style="flex: 1;">
                    <label>Môn Học:</label>
                    <select name="ma_mon" required style="width: 100%; height: 42px;">
                        <?php foreach ($dsMon as $m): ?>
                            <option value="<?php echo $m['ma_mon']; ?>"><?php echo $m['ten_mon']; ?> (<?php echo $m['so_tin_chi']; ?> TC)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="background: #eef2f7; padding: 20px; border-radius: 8px; border: 1px solid #ced4da; margin-bottom: 20px;">
                <label style="color: #0054a6; font-weight: bold; font-size: 16px; margin-bottom: 10px; display: block;">
                    <i class="fas fa-calendar-alt"></i> Thông tin Học kỳ
                </label>

                <div style="margin-bottom: 15px;">
                    <input type="checkbox" id="chk_new_hk" name="chk_new_hk" onchange="toggleNewHK()">
                    <label for="chk_new_hk" style="display:inline; cursor:pointer; color: #d63384; font-weight: bold;">
                        <i class="fas fa-plus-circle"></i> Học kỳ chưa có? Nhập mới ngay
                    </label>
                </div>

                <div id="khu_vuc_chon">
                    <select name="ma_hk" id="select_hk_cu" style="width: 100%; height: 42px; border: 1px solid #aaa;">
                        <option value="">-- Chọn Học kỳ có sẵn --</option>
                        <?php foreach ($dsHK as $hk): ?>
                            <option value="<?php echo $hk['ma_hk']; ?>">
                                <?php echo $hk['ten_hk']; ?> (Năm học: <?php echo $hk['nam_hoc']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="khu_vuc_nhap" style="display: none;">
                    <div style="display: flex; gap: 20px; align-items: flex-end;">
                        <div style="flex: 1;">
                            <label style="font-size: 13px;">Tên Học Kỳ:</label>
                            <select name="ten_hk_moi" style="width: 100%; height: 42px;">
                                <option value="Học kỳ 1">Học kỳ 1</option>
                                <option value="Học kỳ 2">Học kỳ 2</option>
                                <option value="Học kỳ Hè">Học kỳ Hè</option>
                            </select>
                        </div>

                        <div style="flex: 1;">
                            <label style="font-size: 13px;">Năm bắt đầu (Không quá <?php echo date('Y'); ?>):</label>
                            <input type="number" name="nam_bat_dau" 
                                   id="input_nam_bat_dau"
                                   min="2020" 
                                   max="<?php echo date('Y'); ?>" 
                                   value="<?php echo date('Y'); ?>"
                                   oninput="autoCalcYear(this)"
                                   style="width: 100%; height: 42px; font-weight: bold; color: #0054a6;">
                        </div>

                        <div style="flex: 1;">
                            <label style="font-size: 13px;">Niên khóa (Tự động):</label>
                            <input type="text" id="hien_thi_nien_khoa" disabled 
                                   value="<?php echo date('Y') . ' - ' . (date('Y') + 1); ?>"
                                   style="width: 100%; height: 42px; background: #ddd; color: #555;">
                        </div>
                    </div>
                    <small style="color: red; font-style: italic; margin-top: 5px; display: block;">
                        * Lưu ý: Hệ thống sẽ tự động tạo niên khóa (Ví dụ nhập 2024 sẽ thành 2024-2025).
                    </small>
                </div>
            </div>
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Giảng Viên Phụ Trách:</label>
                    <input type="text" name="giang_vien" required 
                           placeholder="Nhập tên giảng viên (VD: ThS. Nguyễn Văn A)..." 
                           style="width: 100%; height: 42px;">
                </div>
                <div style="flex: 1;">
                    <label>Phòng Học:</label>
                    <input type="text" name="phong_hoc" required placeholder="VD: P.305-H1" style="width: 100%; height: 42px;">
                </div>
            </div>

            <label>Tổng số buổi học:</label>
            <input type="number" name="tong_so_buoi" value="15" required min="1" style="width: 150px; height: 42px;">

            <div style="margin-top: 30px; text-align: right;">
                <a href="index.php?controller=LopHocPhan" class="btn" style="background: #ccc; color: #333;">Hủy</a>
                <button type="submit" class="btn btn-primary" style="padding: 10px 25px;">Lưu và Mở lớp</button>
            </div>
        </form>
    </div>
</body>
</html>