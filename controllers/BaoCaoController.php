<?php
require_once './models/BaoCaoModel.php';
require_once './models/LopHanhChinhModel.php';

class BaoCaoController extends BaseController {
    private $baoCaoModel;
    
    // Cấu hình thang điểm dùng chung
    private $thangDiem = [
        'A'  => ['min' => 3.60, 'max' => 4.00, 'label' => 'Xuất sắc'],
        'B+' => ['min' => 3.20, 'max' => 3.59, 'label' => 'Giỏi'],
        'B'  => ['min' => 2.50, 'max' => 3.19, 'label' => 'Khá'],
        'C+' => ['min' => 2.00, 'max' => 2.49, 'label' => 'Trung bình khá'],
        'C'  => ['min' => 2.00, 'max' => 2.49, 'label' => 'Trung bình'],
        'D+' => ['min' => 1.50, 'max' => 1.99, 'label' => 'Trung bình yếu'],
        'D'  => ['min' => 1.00, 'max' => 1.49, 'label' => 'Yếu'],
        'F'  => ['min' => 0.00, 'max' => 0.99, 'label' => 'Kém']
    ];

    public function __construct() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: index.php?controller=DangNhap');
            exit;
        }
        $this->baoCaoModel = new BaoCaoModel();
    }

    // --- HÀM HỖ TRỢ XUẤT CSV ---
    private function xuatFileCSV($tenFile, $tieuDeCot, $duLieu) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $tenFile . '_' . date('Ymd_His') . '.csv');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, $tieuDeCot, ";");
        foreach ($duLieu as $dong) {
            fputcsv($output, $dong, ";");
        }
        fclose($output);
        exit;
    }

    // =================================================================
    // 1. TRANG CHÍNH (BIỂU ĐỒ + TRA CỨU GPA)
    // =================================================================
    public function index() {
        // Lấy dữ liệu biểu đồ
        $phoDiem = $this->baoCaoModel->thongKePhoDiem();
        $labels = []; $data = [];
        foreach ($phoDiem as $item) {
            $labels[] = "Điểm " . $item['diem_chu'];
            $data[] = $item['so_luong'];
        }

        // Lấy dữ liệu lọc GPA
        $diemChon = isset($_GET['diem_chu']) ? $_GET['diem_chu'] : '';
        $dsGPA = [];
        if ($diemChon != '' && isset($this->thangDiem[$diemChon])) {
            $range = $this->thangDiem[$diemChon];
            $dsGPA = $this->baoCaoModel->layTheoKhoangDiem($range['min'], $range['max']);
        }

        $this->goiGiaoDien('bao_cao/index', [
            'chartLabels' => json_encode($labels),
            'chartData'   => json_encode($data),
            'thangDiem'   => $this->thangDiem,
            'dsGPA'       => $dsGPA,
            'diemChon'    => $diemChon
        ]);
    }

    // =================================================================
    // 2. CÁC TRANG CON (ĐÃ ĐƯỢC KHÔI PHỤC)
    // =================================================================
    
    // Trang Sinh viên Trượt môn
    public function truotMon() {
        $dsTruot = $this->baoCaoModel->danhSachTruotMon();
        $this->goiGiaoDien('bao_cao/truot_mon', ['dsTruot' => $dsTruot]);
    }

    // Trang Xét Học bổng
    public function hocBong() {
        $loai = isset($_GET['loai']) ? $_GET['loai'] : 'XuatSac';
        $dsSV = $this->baoCaoModel->danhSachHocBong($loai);
        $this->goiGiaoDien('bao_cao/hoc_bong', ['dsSV' => $dsSV, 'loai' => $loai]);
    }

    // Trang Thống kê theo Lớp
    public function theoLop() {
        $lopModel = new LopHanhChinhModel();
        $dsLop = $lopModel->layTatCa();
        
        $dsKetQua = []; 
        $lopChon = '';
        
        if (isset($_GET['ma_lop_hc']) && !empty($_GET['ma_lop_hc'])) {
            $lopChon = $_GET['ma_lop_hc'];
            $dsKetQua = $this->baoCaoModel->ketQuaTheoLop($lopChon);
        }
        
        $this->goiGiaoDien('bao_cao/theo_lop', [
            'dsLop' => $dsLop, 
            'dsKetQua' => $dsKetQua, 
            'lopChon' => $lopChon
        ]);
    }

    // =================================================================
    // 3. CÁC HÀM XUẤT EXCEL (ĐẦY ĐỦ CHO TẤT CẢ)
    // =================================================================

    // Xuất Excel: Trượt môn
    public function exportTruotMon() {
        $dsTruot = $this->baoCaoModel->danhSachTruotMon();
        $tieuDe = ['STT', 'Mã SV', 'Họ Tên', 'Lớp HC', 'Môn Trượt', 'Lớp Học Phần', 'Điểm TK (10)', 'Điểm Chữ'];
        $duLieuXuat = []; $stt = 1;
        foreach ($dsTruot as $row) {
            $duLieuXuat[] = [$stt++, $row['ma_sv'], $row['ho_ten'], $row['ma_lop_hc'], $row['ten_mon'], $row['ma_lop_hien_thi'], str_replace('.', ',', $row['diem_tong_ket_he_10']), $row['diem_chu']];
        }
        $this->xuatFileCSV('Danh_sach_truot_mon', $tieuDe, $duLieuXuat);
    }

    // Xuất Excel: Học bổng
    public function exportHocBong() {
        $loai = isset($_GET['loai']) ? $_GET['loai'] : 'XuatSac';
        $dsSV = $this->baoCaoModel->danhSachHocBong($loai);
        $tieuDe = ['Xếp hạng', 'Mã SV', 'Họ Tên', 'Lớp HC', 'GPA Tích lũy', 'Số môn', 'Xếp loại'];
        $duLieuXuat = []; $i = 1;
        foreach ($dsSV as $sv) {
            $duLieuXuat[] = ['#' . $i++, $sv['ma_sv'], $sv['ho_ten'], $sv['ma_lop_hc'], str_replace('.', ',', number_format($sv['gpa'], 2)), $sv['so_mon'], ($sv['gpa'] >= 3.6) ? 'Xuất sắc' : 'Giỏi'];
        }
        $this->xuatFileCSV('Danh_sach_hoc_bong_' . $loai, $tieuDe, $duLieuXuat);
    }

    // Xuất Excel: Theo lớp
    public function exportTheoLop() {
        if (!isset($_GET['ma_lop_hc'])) exit;
        $ma_lop = $_GET['ma_lop_hc'];
        $dsKetQua = $this->baoCaoModel->ketQuaTheoLop($ma_lop);
        $tieuDe = ['STT', 'Mã SV', 'Họ Tên', 'GPA', 'Số môn nợ', 'Tình trạng'];
        $duLieuXuat = []; $stt = 1;
        foreach ($dsKetQua as $kq) {
            $tinhTrang = 'Bình thường';
            if ($kq['gpa'] > 0 && $kq['gpa'] < 2.0) $tinhTrang = 'Cảnh báo'; else if ($kq['gpa'] >= 3.2) $tinhTrang = 'Tốt';
            $duLieuXuat[] = [$stt++, $kq['ma_sv'], $kq['ho_ten'], str_replace('.', ',', number_format($kq['gpa'], 2)), $kq['so_mon_no'], $tinhTrang];
        }
        $this->xuatFileCSV('Thong_ke_lop_' . $ma_lop, $tieuDe, $duLieuXuat);
    }

    // Xuất Excel: Theo GPA (Chức năng mới)
    public function exportTheoGPA() {
        $diemChon = isset($_GET['diem_chu']) ? $_GET['diem_chu'] : '';
        if ($diemChon == '' || !isset($this->thangDiem[$diemChon])) {
            header('Location: index.php?controller=BaoCao');
            exit;
        }
        $range = $this->thangDiem[$diemChon];
        $dsKetQua = $this->baoCaoModel->layTheoKhoangDiem($range['min'], $range['max']);

        $tieuDe = ['STT', 'Mã SV', 'Họ Tên', 'Ngày Sinh', 'Lớp HC', 'Khoa', 'GPA Tích lũy', 'Xếp loại'];
        $duLieuXuat = []; $stt = 1;
        foreach ($dsKetQua as $row) {
            $duLieuXuat[] = [$stt++, $row['ma_sv'], $row['ho_ten'], $row['ngay_sinh'], $row['ten_lop_hc'], $row['ma_khoa'], str_replace('.', ',', number_format($row['gpa_tich_luy'], 2)), $range['label'] . " ($diemChon)"];
        }
        $this->xuatFileCSV('Danh_sach_GPA_' . $diemChon, $tieuDe, $duLieuXuat);
    }
}
?>