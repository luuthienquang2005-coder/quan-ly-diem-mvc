<?php
class QuanTriVienModel extends KetNoiCSDL {
    
    // Hàm lấy thông tin admin theo tên đăng nhập
    public function layThongTinAdmin($tenDangNhap) {
        $sql = "SELECT * FROM QuanTriVien WHERE ten_dang_nhap = :user";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user', $tenDangNhap);
        $stmt->execute();
        
        return $stmt->fetch(); // Trả về 1 dòng dữ liệu hoặc false
    }
}
?>