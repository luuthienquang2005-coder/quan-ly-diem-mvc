<?php
class SinhVienModel extends KetNoiCSDL {
    // Lấy danh sách sinh viên kèm tên lớp

    public function layTatCa($tu_khoa = '') {
        $sql = "SELECT sv.*, lop.ten_lop_hc 
                FROM SinhVien sv 
                LEFT JOIN LopHanhChinh lop ON sv.ma_lop_hc = lop.ma_lop_hc";
        
        // Nếu có từ khóa thì thêm điều kiện WHERE
        if ($tu_khoa != '') {
            $sql .= " WHERE sv.ma_sv LIKE :tu_khoa OR sv.ho_ten LIKE :tu_khoa";
        }
        
        $sql .= " ORDER BY sv.ma_sv ASC";
        
        $stmt = $this->conn->prepare($sql);
        
        if ($tu_khoa != '') {
            // Thêm dấu % để tìm kiếm tương đối
            $stmt->execute([':tu_khoa' => "%$tu_khoa%"]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll();
    }

    // Thêm sinh viên mới
    public function themMoi($ma_sv, $ho_ten, $ngay_sinh, $gioi_tinh, $ma_lop_hc) {
        $sql = "INSERT INTO SinhVien (ma_sv, ho_ten, ngay_sinh, gioi_tinh, ma_lop_hc) 
                VALUES (:ma, :ten, :ns, :gt, :malop)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma' => $ma_sv,
            ':ten' => $ho_ten,
            ':ns' => $ngay_sinh,
            ':gt' => $gioi_tinh,
            ':malop' => $ma_lop_hc
        ]);
    }

    // Xóa sinh viên
    public function xoa($ma_sv) {
        $sql = "DELETE FROM SinhVien WHERE ma_sv = :ma";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':ma' => $ma_sv]);
    }
    // Lấy thông tin 1 sinh viên theo mã (để điền vào form sửa)
    public function layThongTin($ma_sv) {
        $sql = "SELECT * FROM SinhVien WHERE ma_sv = :ma";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':ma' => $ma_sv]);
        return $stmt->fetch();
    }

    // Cập nhật thông tin sinh viên
    public function capNhat($ma_sv, $ho_ten, $ngay_sinh, $gioi_tinh, $ma_lop_hc) {
        $sql = "UPDATE SinhVien SET 
                ho_ten = :ten, 
                ngay_sinh = :ns, 
                gioi_tinh = :gt, 
                ma_lop_hc = :malop 
                WHERE ma_sv = :ma";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma' => $ma_sv,
            ':ten' => $ho_ten,
            ':ns' => $ngay_sinh,
            ':gt' => $gioi_tinh,
            ':malop' => $ma_lop_hc
        ]);
    }
    // Lấy danh sách SV thuộc 1 lớp hành chính cụ thể
    public function layTheoLop($ma_lop_hc) {
        $sql = "SELECT * FROM SinhVien WHERE ma_lop_hc = :malop ORDER BY ma_sv ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':malop' => $ma_lop_hc]);
        return $stmt->fetchAll();
    }
    // --- Thêm/Sửa hàm này trong SinhVienModel ---
    
    public function layDanhSachLoc($khoa = '', $ma_lop_hc = '', $tu_khoa = '') {
        $sql = "SELECT sv.*, lop.ten_lop_hc 
                FROM SinhVien sv 
                LEFT JOIN LopHanhChinh lop ON sv.ma_lop_hc = lop.ma_lop_hc
                WHERE 1=1"; // Kỹ thuật 1=1 để nối chuỗi AND dễ dàng
        
        $params = [];

        // 1. Lọc theo Lớp cụ thể (Nếu đã chọn lớp)
        if ($ma_lop_hc != '') {
            $sql .= " AND sv.ma_lop_hc = :malop";
            $params[':malop'] = $ma_lop_hc;
        } 
        // 2. Nếu chưa chọn lớp mà chọn Khóa -> Lọc tương đối theo mã lớp (VD: bắt đầu bằng 73)
        else if ($khoa != '') {
            $sql .= " AND sv.ma_lop_hc LIKE :khoa";
            $params[':khoa'] = "$khoa%"; // Tìm các mã lớp bắt đầu bằng số khóa
        }

        // 3. Lọc theo từ khóa tìm kiếm
        if ($tu_khoa != '') {
            $sql .= " AND (sv.ma_sv LIKE :tukhoa OR sv.ho_ten LIKE :tukhoa)";
            $params[':tukhoa'] = "%$tu_khoa%";
        }
        
        $sql .= " ORDER BY sv.ma_sv ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
?>