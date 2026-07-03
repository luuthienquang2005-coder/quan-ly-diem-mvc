<?php
class ChiTietVangNghiModel extends KetNoiCSDL {
    // Lấy danh sách các ngày nghỉ của 1 sinh viên trong 1 lớp
    public function layDanhSach($ma_lop_hp, $ma_sv) {
        $sql = "SELECT * FROM ChiTietVangNghi WHERE ma_lop_hp = :malop AND ma_sv = :masv ORDER BY ngay_vang DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':malop' => $ma_lop_hp, ':masv' => $ma_sv]);
        return $stmt->fetchAll();
    }

    // Thêm ngày nghỉ
    public function themMoi($ma_lop_hp, $ma_sv, $ngay_vang, $ly_do) {
        $sql = "INSERT INTO ChiTietVangNghi (ma_lop_hp, ma_sv, ngay_vang, ly_do) VALUES (:malop, :masv, :ngay, :lydo)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':malop' => $ma_lop_hp, ':masv' => $ma_sv, ':ngay' => $ngay_vang, ':lydo' => $ly_do]);
    }

    // Xóa ngày nghỉ (Hủy điểm danh)
    public function xoa($id) {
        $sql = "DELETE FROM ChiTietVangNghi WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Đếm tổng số buổi nghỉ để cập nhật vào bảng điểm
    public function demSoBuoiNghi($ma_lop_hp, $ma_sv) {
        $sql = "SELECT count(*) FROM ChiTietVangNghi WHERE ma_lop_hp = :malop AND ma_sv = :masv";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':malop' => $ma_lop_hp, ':masv' => $ma_sv]);
        return $stmt->fetchColumn();
    }
}
?>