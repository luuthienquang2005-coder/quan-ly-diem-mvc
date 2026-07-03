<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tieu_de; ?> - UTT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tieu_de; ?> - UTT</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="./public/css/style.css">
</head>
</head>
<body>

    <?php include './views/layout/menu.php'; ?>

    <div class="utt-banner">
        <h1>Trường Đại học Công nghệ Giao thông Vận tải</h1>
        <h1>HỆ THỐNG QUẢN LÝ ĐÀO TẠO</h1>
        <p>Xin chào quản trị viên: <strong><?php echo $_SESSION['admin_name']; ?></strong></p>
    </div>

    <div class="container">
        
        <div class="dashboard-grid">
            
            <a href="index.php?controller=SinhVien" class="card student">
                <div class="card-content">
                    <div class="card-info">
                        <h3><?php echo $soLieu['sv']; ?></h3>
                        <p>Sinh Viên</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="card-footer">
                    Quản lý hồ sơ sinh viên <i class="fas fa-arrow-circle-right"></i>
                </div>
            </a>

            <a href="index.php?controller=MonHoc" class="card subject">
                <div class="card-content">
                    <div class="card-info">
                        <h3><?php echo $soLieu['mon']; ?></h3>
                        <p>Môn Học</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
                <div class="card-footer">
                    Quản lý danh mục môn <i class="fas fa-arrow-circle-right"></i>
                </div>
            </a>

            <a href="index.php?controller=LopHocPhan" class="card class">
                <div class="card-content">
                    <div class="card-info">
                        <h3><?php echo $soLieu['lop']; ?></h3>
                        <p>Lớp Học Phần</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
                <div class="card-footer">
                    Mở lớp & Nhập điểm <i class="fas fa-arrow-circle-right"></i>
                </div>
            </a>

            <a href="index.php?controller=BaoCao" class="card report">
                <div class="card-content">
                    <div class="card-info">
                        <h3>TK</h3> <p>Báo cáo & Thống kê</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="card-footer">
                    Xem biểu đồ & Kết quả <i class="fas fa-arrow-circle-right"></i>
                </div>
            </a>

        </div>

        <h3 style="color: var(--utt-blue); border-bottom: 2px solid var(--utt-orange); padding-bottom: 10px; display: inline-block;">
            <i class="fas fa-rocket"></i> Truy cập nhanh
        </h3>
        <div style="margin-top: 15px; display: flex; gap: 10px;">
            <a href="index.php?controller=LopHocPhan&action=them" style="padding: 10px 20px; background: var(--utt-blue); color: white; text-decoration: none; border-radius: 5px;">
                + Mở lớp mới
            </a>
            <a href="index.php?controller=SinhVien&action=them" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">
                + Thêm sinh viên
            </a>
            <a href="index.php?controller=BaoCao&action=truotMon" style="padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px;">
                ! Xem danh sách trượt
            </a>
        </div>

        <div class="footer">
            &copy; 2025 Trường Đại học Công nghệ Giao thông Vận tải (UTT).<br>
            Hệ thống quản lý đào tạo
        </div>
    </div>
</body>
</html>