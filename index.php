<?php
session_start(); // <--- THÊM DÒNG NÀY Ở TRÊN CÙNG

require_once './controllers/BaseController.php';
require_once './models/KetNoiCSDL.php';

// Lấy tên Controller và Action từ URL
// Ví dụ: index.php?controller=SinhVien&action=danhSach
// Nếu không có thì mặc định vào TrangChu
$tenController = isset($_GET['controller']) ? $_GET['controller'] : 'TrangChu';
$tenAction = isset($_GET['action']) ? $_GET['action'] : 'index';

// Chuẩn hóa tên file Controller (Thêm hậu tố 'Controller')
// Ví dụ: SinhVien -> SinhVienController
$tenClassController = $tenController . 'Controller';
$duongDanFile = "./controllers/" . $tenClassController . ".php";

// Kiểm tra file controller có tồn tại không
if (file_exists($duongDanFile)) {
    require_once $duongDanFile;
    
    // Khởi tạo đối tượng controller
    $doiTuong = new $tenClassController();
    
    // Kiểm tra action (hàm) có tồn tại trong class không
    if (method_exists($doiTuong, $tenAction)) {
        // Gọi hàm
        $doiTuong->$tenAction();
    } else {
        die("Chức năng '$tenAction' không tồn tại trong $tenClassController.");
    }
} else {
    // Nếu không tìm thấy trang, báo lỗi hoặc về trang chủ
    die("Không tìm thấy trang yêu cầu (Controller: $tenClassController).");
}
?>