<link rel="stylesheet" href="./public/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<nav class="navbar">
    <div class="nav-container">
        <a href="index.php?controller=TrangChu" class="nav-logo">
            <i class="fas fa-university"></i> UTT EDU
        </a>

        <div class="nav-links">
            <a href="index.php?controller=TrangChu"><i class="fas fa-home"></i> Trang chủ</a>
            <a href="index.php?controller=SinhVien"><i class="fas fa-user-graduate"></i> Sinh viên</a>
            <a href="index.php?controller=MonHoc"><i class="fas fa-book"></i> Môn học</a>
            <a href="index.php?controller=LopHocPhan"><i class="fas fa-chalkboard-teacher"></i> Lớp học phần</a>
            <a href="index.php?controller=BaoCao"><i class="fas fa-chart-bar"></i> Thống kê</a>
            <a href="index.php?controller=LopHC"><i class="fas fa-school"></i> Lớp Hành Chính</a>
        </div>

        <div class="nav-user">
            <i class="fas fa-user-circle"></i> 
            <?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin'; ?> 
            | <a href="index.php?controller=DangNhap&action=dangXuat" style="color: #ffcccb; font-weight: bold;">Thoát</a>
        </div>
    </div>
</nav>

<?php if (isset($_SESSION['flash_message'])): ?>
    <?php $msg = $_SESSION['flash_message']; ?>
    <div class="container" style="margin-bottom: 0; min-height: auto;">
        <div class="alert alert-<?php echo $msg['type']; ?>">
            <span><i class="fas fa-info-circle"></i> <?php echo $msg['msg']; ?></span>
            <span onclick="this.parentElement.style.display='none';" style="cursor:pointer; font-weight:bold;">&times;</span>
        </div>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<div class="container">