<?php
class BaseController {
    
    // Hàm gọi giao diện (View)
    // Giúp truyền dữ liệu từ Controller sang View dễ dàng
    protected function goiGiaoDien($viewPath, $data = []) {
        // Giải nén mảng data thành các biến
        // Ví dụ: ['ten' => 'A'] sẽ thành biến $ten = 'A'
        extract($data);

        $viewFile = './views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            // Thông báo lỗi nếu không tìm thấy file view
            die("Lỗi hệ thống: Không tìm thấy file giao diện '$viewFile'");
        }
    }

    // --- HÀM QUAN TRỌNG ĐANG BỊ THIẾU ---
    // Hàm tạo thông báo (Flash Message)
    // Lưu thông báo vào Session để hiển thị ở trang tiếp theo
    protected function thongBao($noi_dung, $loai = 'success') {
        // $loai: 'success' (Xanh lá), 'danger' (Đỏ), 'warning' (Vàng), 'info' (Xanh dương)
        $_SESSION['flash_message'] = [
            'type' => $loai,
            'msg' => $noi_dung
        ];
    }
}
?>