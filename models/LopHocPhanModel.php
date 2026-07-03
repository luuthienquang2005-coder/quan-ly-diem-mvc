<?php
class LopHocPhanModel extends KetNoiCSDL {
    // Lấy danh sách tất cả các lớp học phần (kèm tên môn, tên học kỳ)
    // Cập nhật hàm này nhận thêm $ma_hk
    public function layTatCa($ma_hk = null) {
        $sql = "SELECT lhp.*, mh.ten_mon, hk.ten_hk 
                FROM LopHocPhan lhp
                JOIN MonHoc mh ON lhp.ma_mon = mh.ma_mon
                JOIN HocKy hk ON lhp.ma_hk = hk.ma_hk";
        
        // Nếu có mã học kỳ thì lọc, không thì lấy hết
        if ($ma_hk != null) {
            $sql .= " WHERE lhp.ma_hk = :mahk";
        }

        $sql .= " ORDER BY lhp.ma_lop_hp DESC";
        
        $stmt = $this->conn->prepare($sql);
        if ($ma_hk != null) {
            $stmt->execute([':mahk' => $ma_hk]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    // Lấy thông tin chi tiết 1 lớp
    public function layThongTin($id) {
        $sql = "SELECT lhp.*, mh.ten_mon, mh.so_tin_chi, hk.ten_hk 
                FROM LopHocPhan lhp
                JOIN MonHoc mh ON lhp.ma_mon = mh.ma_mon
                JOIN HocKy hk ON lhp.ma_hk = hk.ma_hk
                WHERE lhp.ma_lop_hp = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Mở lớp mới
    public function themMoi($ma_hien_thi, $ma_mon, $ma_hk, $gv, $phong, $so_buoi) {
        $sql = "INSERT INTO LopHocPhan (ma_lop_hien_thi, ma_mon, ma_hk, giang_vien_phu_trach, phong_hoc, tong_so_buoi_hoc) 
                VALUES (:ma, :mamon, :mahk, :gv, :phong, :sobuoi)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ma' => $ma_hien_thi,
            ':mamon' => $ma_mon,
            ':mahk' => $ma_hk,
            ':gv' => $gv,
            ':phong' => $phong,
            ':sobuoi' => $so_buoi
        ]);
    }
    // Cập nhật thông tin lớp học phần
    public function capNhat($ma_lop_hp, $ma_hien_thi, $ma_mon, $ma_hk, $gv, $phong, $so_buoi) {
        $sql = "UPDATE LopHocPhan SET 
                ma_lop_hien_thi = :ma,
                ma_mon = :mamon,
                ma_hk = :mahk,
                giang_vien_phu_trach = :gv,
                phong_hoc = :phong,
                tong_so_buoi_hoc = :sobuoi
                WHERE ma_lop_hp = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $ma_lop_hp,
            ':ma' => $ma_hien_thi,
            ':mamon' => $ma_mon,
            ':mahk' => $ma_hk,
            ':gv' => $gv,
            ':phong' => $phong,
            ':sobuoi' => $so_buoi
        ]);
    }

    // Xóa lớp học phần
    // Lưu ý: Phải xóa bảng điểm (sinh viên trong lớp) trước mới xóa được lớp (do ràng buộc khóa ngoại)
    public function xoa($ma_lop_hp) {
        try {
            $this->conn->beginTransaction();

            // 1. Xóa tất cả sinh viên trong lớp này trước
            $sql1 = "DELETE FROM BangDiem WHERE ma_lop_hp = :id";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([':id' => $ma_lop_hp]);

            // 2. Xóa lớp học phần
            $sql2 = "DELETE FROM LopHocPhan WHERE ma_lop_hp = :id";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([':id' => $ma_lop_hp]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>