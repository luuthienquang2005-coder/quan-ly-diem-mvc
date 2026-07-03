<?php
class BangDiemModel extends KetNoiCSDL {
    // Lấy danh sách sinh viên trong một lớp học phần
    public function layDanhSachSinhVien($ma_lop_hp) {
        $sql = "SELECT bd.*, sv.ho_ten, sv.ma_lop_hc 
                FROM BangDiem bd
                JOIN SinhVien sv ON bd.ma_sv = sv.ma_sv
                WHERE bd.ma_lop_hp = :malop
                ORDER BY sv.ma_sv ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':malop' => $ma_lop_hp]);
        return $stmt->fetchAll();
    }

    // Thêm sinh viên vào lớp (Khởi tạo điểm = 0)
    public function themSinhVienVaoLop($ma_lop_hp, $ma_sv) {
        // Kiểm tra xem đã tồn tại chưa để tránh lỗi
        $checkSql = "SELECT count(*) FROM BangDiem WHERE ma_lop_hp = :malop AND ma_sv = :masv";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->execute([':malop' => $ma_lop_hp, ':masv' => $ma_sv]);
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Đã tồn tại
        }

        $sql = "INSERT INTO BangDiem (ma_lop_hp, ma_sv) VALUES (:malop, :masv)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':malop' => $ma_lop_hp, ':masv' => $ma_sv]);
    }
    
    // Xóa sinh viên khỏi lớp
    public function xoaSinhVienKhoiLop($id_bang_diem) {
        $sql = "DELETE FROM BangDiem WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id_bang_diem]);
    }
    // Kiểm tra nhanh xem sinh viên đã có trong lớp chưa (Trả về true/false)
    public function kiemTraTonTai($ma_lop_hp, $ma_sv) {
        $sql = "SELECT count(*) FROM BangDiem WHERE ma_lop_hp = :malop AND ma_sv = :masv";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':malop' => $ma_lop_hp, ':masv' => $ma_sv]);
        return $stmt->fetchColumn() > 0;
    }
    // Cập nhật số buổi vắng (Hàm này được gọi từ Controller Vắng Nghỉ ở trên)
    public function capNhatSoBuoiVang($ma_lop_hp, $ma_sv, $so_buoi) {
        $sql = "UPDATE BangDiem SET so_buoi_vang = :so WHERE ma_lop_hp = :malop AND ma_sv = :masv";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':so' => $so_buoi, ':malop' => $ma_lop_hp, ':masv' => $ma_sv]);
    }

    // Hàm lưu điểm và TỰ ĐỘNG TÍNH TOÁN
    public function luuDiem($ma_lop_hp, $ma_sv, $diem_cc, $diem_gk, $diem_ck) {
        // 1. Tính điểm hệ 10
        // Công thức: CC 10% + GK 30% + CK 60%
        $tong_ket_10 = ($diem_cc * 0.1) + ($diem_gk * 0.3) + ($diem_ck * 0.6);
        $tong_ket_10 = round($tong_ket_10, 1); // Làm tròn 1 chữ số thập phân

        // 2. Quy đổi sang Điểm Chữ và Hệ 4 (Theo quy chế tín chỉ thông thường)
        $diem_chu = 'F';
        $tong_ket_4 = 0.0;

        if ($tong_ket_10 >= 8.5) { $diem_chu = 'A'; $tong_ket_4 = 4.0; }
        else if ($tong_ket_10 >= 8.0) { $diem_chu = 'B+'; $tong_ket_4 = 3.5; }
        else if ($tong_ket_10 >= 7.0) { $diem_chu = 'B'; $tong_ket_4 = 3.0; }
        else if ($tong_ket_10 >= 6.5) { $diem_chu = 'C+'; $tong_ket_4 = 2.5; }
        else if ($tong_ket_10 >= 5.5) { $diem_chu = 'C'; $tong_ket_4 = 2.0; }
        else if ($tong_ket_10 >= 5.0) { $diem_chu = 'D+'; $tong_ket_4 = 1.5; }
        else if ($tong_ket_10 >= 4.0) { $diem_chu = 'D'; $tong_ket_4 = 1.0; }
        else { $diem_chu = 'F'; $tong_ket_4 = 0.0; }

        // 3. Update vào CSDL
        $sql = "UPDATE BangDiem SET 
                diem_chuyen_can = :cc,
                diem_giua_ky = :gk,
                diem_cuoi_ky = :ck,
                diem_tong_ket_he_10 = :tk10,
                diem_tong_ket_he_4 = :tk4,
                diem_chu = :chu
                WHERE ma_lop_hp = :malop AND ma_sv = :masv";
                
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':cc' => $diem_cc,
            ':gk' => $diem_gk,
            ':ck' => $diem_ck,
            ':tk10' => $tong_ket_10,
            ':tk4' => $tong_ket_4,
            ':chu' => $diem_chu,
            ':malop' => $ma_lop_hp,
            ':masv' => $ma_sv
        ]);
    }
    // Lấy toàn bộ điểm của 1 sinh viên
    public function layBangDiemCaNhan($ma_sv) {
        $sql = "SELECT bd.*, lhp.ma_lop_hien_thi, lhp.ma_hk, mh.ten_mon, mh.so_tin_chi, hk.ten_hk
                FROM BangDiem bd
                JOIN LopHocPhan lhp ON bd.ma_lop_hp = lhp.ma_lop_hp
                JOIN MonHoc mh ON lhp.ma_mon = mh.ma_mon
                JOIN HocKy hk ON lhp.ma_hk = hk.ma_hk
                WHERE bd.ma_sv = :masv
                ORDER BY lhp.ma_hk ASC, mh.ten_mon ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':masv' => $ma_sv]);
        return $stmt->fetchAll();
    }
}
?>