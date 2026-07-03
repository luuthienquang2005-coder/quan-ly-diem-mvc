<?php
class BaoCaoModel extends KetNoiCSDL {
    
    // 1. Lấy danh sách sinh viên Trượt môn (Điểm F)
    // Có thể lọc theo học kỳ hoặc tất cả
    public function danhSachTruotMon($ma_hk = null) {
        $sql = "SELECT sv.ma_sv, sv.ho_ten, sv.ma_lop_hc, mh.ten_mon, bd.diem_tong_ket_he_10, bd.diem_chu, lhp.ma_lop_hien_thi
                FROM BangDiem bd
                JOIN SinhVien sv ON bd.ma_sv = sv.ma_sv
                JOIN LopHocPhan lhp ON bd.ma_lop_hp = lhp.ma_lop_hp
                JOIN MonHoc mh ON lhp.ma_mon = mh.ma_mon
                WHERE bd.diem_chu = 'F'";
        
        if ($ma_hk != null) {
            $sql .= " AND lhp.ma_hk = :mahk";
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($ma_hk != null) $stmt->execute([':mahk' => $ma_hk]);
        else $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // 2. Lấy danh sách sinh viên Giỏi/Xuất sắc (Dựa trên điểm trung bình các môn đã học)
    // Logic: Tính trung bình cộng điểm hệ 4 của tất cả các môn
    public function danhSachHocBong($loai = 'XuatSac') {
        // Xuất sắc: GPA >= 3.6, Giỏi: GPA >= 3.2
        $minGPA = ($loai == 'XuatSac') ? 3.6 : 3.2;

        $sql = "SELECT sv.ma_sv, sv.ho_ten, sv.ma_lop_hc, sv.ngay_sinh,
                       AVG(bd.diem_tong_ket_he_4) as gpa,
                       COUNT(bd.ma_lop_hp) as so_mon
                FROM BangDiem bd
                JOIN SinhVien sv ON bd.ma_sv = sv.ma_sv
                GROUP BY sv.ma_sv
                HAVING gpa >= :min
                ORDER BY gpa DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':min' => $minGPA]);
        return $stmt->fetchAll();
    }

    // 3. Thống kê số lượng điểm theo các mức (Dùng cho Biểu đồ)
    // Ví dụ: A: 50, B: 30, C: 10, F: 5
    public function thongKePhoDiem() {
        $sql = "SELECT diem_chu, COUNT(*) as so_luong 
                FROM BangDiem 
                WHERE diem_chu IS NOT NULL 
                GROUP BY diem_chu 
                ORDER BY diem_chu ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 4. Lấy kết quả học tập theo Lớp Hành Chính
    public function ketQuaTheoLop($ma_lop_hc) {
        $sql = "SELECT sv.ma_sv, sv.ho_ten, 
                       AVG(bd.diem_tong_ket_he_4) as gpa,
                       COUNT(CASE WHEN bd.diem_chu = 'F' THEN 1 END) as so_mon_no
                FROM SinhVien sv
                LEFT JOIN BangDiem bd ON sv.ma_sv = bd.ma_sv
                WHERE sv.ma_lop_hc = :malop
                GROUP BY sv.ma_sv";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':malop' => $ma_lop_hc]);
        return $stmt->fetchAll();
    }
    public function layTheoKhoangDiem($min, $max) {
        // Công thức: Tổng (Điểm hệ 4 * Số tín chỉ) / Tổng Số tín chỉ
        $sql = "SELECT 
                    sv.ma_sv, sv.ho_ten, sv.ngay_sinh, lop.ten_lop_hc, lop.ma_khoa,
                    SUM(bd.diem_tong_ket_he_4 * mh.so_tin_chi) / SUM(mh.so_tin_chi) as gpa_tich_luy
                FROM SinhVien sv
                JOIN BangDiem bd ON sv.ma_sv = bd.ma_sv
                JOIN LopHocPhan lhp ON bd.ma_lop_hp = lhp.ma_lop_hp
                JOIN MonHoc mh ON lhp.ma_mon = mh.ma_mon
                JOIN LopHanhChinh lop ON sv.ma_lop_hc = lop.ma_lop_hc
                GROUP BY sv.ma_sv, sv.ho_ten, sv.ngay_sinh, lop.ten_lop_hc, lop.ma_khoa
                HAVING gpa_tich_luy >= :min AND gpa_tich_luy <= :max
                ORDER BY gpa_tich_luy DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':min' => $min, ':max' => $max]);
        return $stmt->fetchAll();
    }
}
?>