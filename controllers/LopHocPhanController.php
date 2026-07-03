<?php
require_once './models/LopHocPhanModel.php';
require_once './models/MonHocModel.php';
require_once './models/BangDiemModel.php';
require_once './models/HocKyModel.php';
require_once './models/SinhVienModel.php';
require_once './models/LopHanhChinhModel.php';
require_once './models/ChiTietVangNghiModel.php';
// Đã xóa dòng require GiangVienModel để tránh lỗi

class LopHocPhanController extends BaseController {
    private $lhpModel;
    private $monHocModel;
    private $bangDiemModel;
    private $hkModel;
    // Đã xóa biến $gvModel

    public function __construct() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: index.php?controller=DangNhap');
            exit;
        }
        $this->lhpModel = new LopHocPhanModel();
        $this->monHocModel = new MonHocModel();
        $this->bangDiemModel = new BangDiemModel();
        $this->hkModel = new HocKyModel();
        // Đã xóa dòng khởi tạo GiangVienModel
    }

    // 1. Danh sách lớp
    public function index() {
        $dsHocKy = $this->hkModel->layTatCa();
        
        // Mặc định lấy học kỳ mới nhất hoặc từ URL
        $ma_hk_chon = isset($_GET['ma_hk']) ? $_GET['ma_hk'] : (isset($dsHocKy[0]['ma_hk']) ? $dsHocKy[0]['ma_hk'] : '');

        $dsLop = $this->lhpModel->layTatCa($ma_hk_chon);
        
        $this->goiGiaoDien('lop_hoc_phan/index', [
            'dsLop' => $dsLop,
            'dsHocKy' => $dsHocKy,
            'ma_hk_chon' => $ma_hk_chon
        ]);
    }

    // 2. Thêm lớp mới (Logic tạo Học kỳ nhanh + Nhập tay GV)
    public function them() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Mặc định lấy ID học kỳ từ dropdown
            $ma_hk_final = isset($_POST['ma_hk']) ? $_POST['ma_hk'] : null;

            // --- XỬ LÝ NẾU NGƯỜI DÙNG CHỌN TẠO HỌC KỲ MỚI ---
            if (isset($_POST['chk_new_hk'])) {
                $ten_hk_moi = $_POST['ten_hk_moi'];
                $nam_bat_dau = intval($_POST['nam_bat_dau']);
                $nam_hien_tai = intval(date('Y'));

                // Kiểm tra năm không được lớn hơn năm nay
                if ($nam_bat_dau > $nam_hien_tai) {
                    $this->thongBao("Lỗi: Năm học ($nam_bat_dau) không được lớn hơn năm hiện tại ($nam_hien_tai)!", "danger");
                    $this->loadViewThem(); 
                    return;
                }

                // Tạo chuỗi niên khóa
                $nam_ket_thuc = $nam_bat_dau + 1;
                $chuoi_nam_hoc = "$nam_bat_dau-$nam_ket_thuc";

                // Kiểm tra xem Học kỳ này đã có chưa?
                $id_ton_tai = $this->hkModel->kiemTraTonTai($ten_hk_moi, $chuoi_nam_hoc);
                
                if ($id_ton_tai) {
                    $ma_hk_final = $id_ton_tai;
                } else {
                    $ma_hk_final = $this->hkModel->themMoi($ten_hk_moi, $chuoi_nam_hoc);
                }
            }

            // --- KIỂM TRA CUỐI CÙNG ---
            if (empty($ma_hk_final)) {
                $this->thongBao("Lỗi: Vui lòng chọn hoặc nhập học kỳ!", "danger");
                $this->loadViewThem();
                return;
            }

            // --- TIẾP TỤC TẠO LỚP HỌC PHẦN ---
            try {
                $kq = $this->lhpModel->themMoi(
                    $_POST['ma_lop_hien_thi'],
                    $_POST['ma_mon'],
                    $ma_hk_final,
                    $_POST['giang_vien'], // <-- Nhận tên giảng viên nhập tay từ form
                    $_POST['phong_hoc'],
                    $_POST['tong_so_buoi']
                );

                if ($kq) {
                    $msg = "Mở lớp thành công!";
                    if (isset($_POST['chk_new_hk'])) $msg .= " (Đã tạo mới HK: $ten_hk_moi $chuoi_nam_hoc)";
                    $this->thongBao($msg);
                    header('Location: index.php?controller=LopHocPhan');
                    exit;
                }
            } catch (Exception $e) {
                $this->thongBao("Lỗi: Mã lớp hiển thị đã tồn tại!", "danger");
            }
        }

        $this->loadViewThem();
    }

    // Hàm phụ để load view Thêm
    private function loadViewThem() {
        $dsMon = $this->monHocModel->layTatCa();
        $dsHK = $this->hkModel->layTatCa();
        // Không lấy danh sách GV nữa để người dùng tự nhập tay
        
        $this->goiGiaoDien('lop_hoc_phan/them', [
            'dsMon' => $dsMon, 'dsHK' => $dsHK
        ]);
    }

    // 3. Sửa lớp
    public function sua() {
        if (!isset($_GET['ma_lop_hp'])) {
            header('Location: index.php?controller=LopHocPhan');
            exit;
        }
        $ma_lop_hp = $_GET['ma_lop_hp'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->lhpModel->capNhat(
                $ma_lop_hp,
                $_POST['ma_lop_hien_thi'],
                $_POST['ma_mon'],
                $_POST['ma_hk'],
                $_POST['giang_vien'], // Cập nhật tên GV nhập tay
                $_POST['phong_hoc'],
                $_POST['tong_so_buoi']
            );
            $this->thongBao("Cập nhật lớp thành công!");
            header('Location: index.php?controller=LopHocPhan');
            exit;
        }

        $lop = $this->lhpModel->layThongTin($ma_lop_hp);
        $dsMon = $this->monHocModel->layTatCa();
        $dsHocKy = $this->hkModel->layTatCa();
        
        $this->goiGiaoDien('lop_hoc_phan/sua', [
            'lop' => $lop, 
            'dsMon' => $dsMon,
            'dsHocKy' => $dsHocKy
        ]);
    }

    // 4. Xóa lớp
    public function xoa() {
        if (isset($_GET['ma_lop_hp'])) {
            if($this->lhpModel->xoa($_GET['ma_lop_hp'])) {
                $this->thongBao("Đã xóa lớp học phần!", "warning");
            } else {
                $this->thongBao("Lỗi xóa lớp!", "danger");
            }
        }
        header('Location: index.php?controller=LopHocPhan');
    }

    // 5. Xem chi tiết & Danh sách sinh viên
    public function chiTiet() {
        if (!isset($_GET['ma_lop_hp'])) header('Location: index.php?controller=LopHocPhan');
        
        $ma_lop_hp = $_GET['ma_lop_hp'];
        $thongTinLop = $this->lhpModel->layThongTin($ma_lop_hp);
        $dsSinhVienLop = $this->bangDiemModel->layDanhSachSinhVien($ma_lop_hp);

        $this->goiGiaoDien('lop_hoc_phan/chi_tiet', [
            'thongTinLop' => $thongTinLop,
            'dsSinhVienLop' => $dsSinhVienLop
        ]);
    }

    // 6. Thêm 1 sinh viên vào lớp
    public function themSinhVien() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ma_lop_hp = $_POST['ma_lop_hp'];
            $ma_sv = $_POST['ma_sv'];
            
            $this->bangDiemModel->themSinhVienVaoLop($ma_lop_hp, $ma_sv);
            $this->thongBao("Đã thêm sinh viên vào lớp.");
            header("Location: index.php?controller=LopHocPhan&action=chiTiet&ma_lop_hp=$ma_lop_hp");
        }
    }

    // 7. Thêm nhiều sinh viên (Checkbox)
    public function themNhieu() {
        if (!isset($_GET['ma_lop_hp'])) header('Location: index.php?controller=LopHocPhan');
        $ma_lop_hp = $_GET['ma_lop_hp'];
        
        $lhcModel = new LopHanhChinhModel();
        $dsLopHC = $lhcModel->layTatCa();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['danh_sach_ma_sv'])) {
            foreach ($_POST['danh_sach_ma_sv'] as $ma_sv) {
                $this->bangDiemModel->themSinhVienVaoLop($ma_lop_hp, $ma_sv);
            }
            $this->thongBao("Đã thêm danh sách sinh viên được chọn.");
            header("Location: index.php?controller=LopHocPhan&action=chiTiet&ma_lop_hp=$ma_lop_hp");
            exit;
        }

        $dsSinhVienLoc = [];
        $ma_lop_hc_selected = '';
        if (isset($_GET['ma_lop_hc'])) {
            $ma_lop_hc_selected = $_GET['ma_lop_hc'];
            $svModel = new SinhVienModel();
            $dsSinhVienLoc = $svModel->layTheoLop($ma_lop_hc_selected);
        }

        $this->goiGiaoDien('lop_hoc_phan/them_nhieu', [
            'ma_lop_hp' => $ma_lop_hp,
            'dsLopHC' => $dsLopHC,
            'dsSinhVienLoc' => $dsSinhVienLoc,
            'ma_lop_hc_selected' => $ma_lop_hc_selected,
            'bangDiemModel' => $this->bangDiemModel
        ]);
    }

    // 8. Xóa sinh viên khỏi lớp
    public function xoaSinhVien() {
         if (isset($_GET['id']) && isset($_GET['ma_lop_hp'])) {
             $this->bangDiemModel->xoaSinhVienKhoiLop($_GET['id']);
             $this->thongBao("Đã hủy học phần của sinh viên.", "warning");
             header("Location: index.php?controller=LopHocPhan&action=chiTiet&ma_lop_hp=" . $_GET['ma_lop_hp']);
         }
    }

    // 9. Import Excel
    public function importExcel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            $ma_lop_hp = $_POST['ma_lop_hp'];
            $file = $_FILES['file_excel']['tmp_name'];

            if ($_FILES['file_excel']['size'] > 0) {
                $handle = fopen($file, "r");
                fgetcsv($handle, 1000, ";"); // Bỏ qua header
                
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    $ma_sv = isset($data[1]) ? trim($data[1]) : ''; 
                    if (empty($ma_sv) && isset($data[0])) $ma_sv = trim($data[0]);

                    if (!empty($ma_sv)) {
                        $this->bangDiemModel->themSinhVienVaoLop($ma_lop_hp, $ma_sv);
                    }
                }
                fclose($handle);
                $this->thongBao("Import dữ liệu thành công!");
            }
            header("Location: index.php?controller=LopHocPhan&action=chiTiet&ma_lop_hp=$ma_lop_hp");
        }
    }

    // 10. Export Excel
    public function exportExcel() {
        if (!isset($_GET['ma_lop_hp'])) return;
        $ma_lop_hp = $_GET['ma_lop_hp'];
        $dsSinhVien = $this->bangDiemModel->layDanhSachSinhVien($ma_lop_hp);
        $thongTinLop = $this->lhpModel->layThongTin($ma_lop_hp);

        $filename = "Danh_sach_" . $thongTinLop['ma_lop_hien_thi'] . "_" . date('Ymd') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['STT', 'Mã SV', 'Họ Tên', 'Lớp HC', 'Điểm CC', 'Điểm GK', 'Điểm CK', 'Điểm TK', 'Điểm Chữ'], ";");

        $stt = 1;
        foreach ($dsSinhVien as $sv) {
            fputcsv($output, [
                $stt++, $sv['ma_sv'], $sv['ho_ten'], $sv['ma_lop_hc'],
                str_replace('.', ',', $sv['diem_chuyen_can']),
                str_replace('.', ',', $sv['diem_giua_ky']),
                str_replace('.', ',', $sv['diem_cuoi_ky']),
                str_replace('.', ',', $sv['diem_tong_ket_he_10']),
                $sv['diem_chu']
            ], ";");
        }
        fclose($output);
        exit;
    }

    // 11. Quản lý Vắng nghỉ
    public function quanLyVang() {
        $vangModel = new ChiTietVangNghiModel();
        $ma_lop_hp = $_GET['ma_lop_hp'];
        $ma_sv = $_GET['ma_sv'];
        
        $from = isset($_GET['from']) ? $_GET['from'] : ''; 
        $paramFrom = ($from != '') ? "&from=$from" : "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $vangModel->themMoi($ma_lop_hp, $ma_sv, $_POST['ngay_vang'], $_POST['ly_do']);
            $tongBuoi = $vangModel->demSoBuoiNghi($ma_lop_hp, $ma_sv);
            $this->bangDiemModel->capNhatSoBuoiVang($ma_lop_hp, $ma_sv, $tongBuoi);
            $this->thongBao("Đã thêm ngày vắng.");
            header("Location: index.php?controller=LopHocPhan&action=quanLyVang&ma_lop_hp=$ma_lop_hp&ma_sv=$ma_sv" . $paramFrom);
            exit;
        }

        if (isset($_GET['xoa_id'])) {
            $vangModel->xoa($_GET['xoa_id']);
            $tongBuoi = $vangModel->demSoBuoiNghi($ma_lop_hp, $ma_sv);
            $this->bangDiemModel->capNhatSoBuoiVang($ma_lop_hp, $ma_sv, $tongBuoi);
            $this->thongBao("Đã xóa ngày vắng.");
            header("Location: index.php?controller=LopHocPhan&action=quanLyVang&ma_lop_hp=$ma_lop_hp&ma_sv=$ma_sv" . $paramFrom);
            exit;
        }

        $dsVang = $vangModel->layDanhSach($ma_lop_hp, $ma_sv);
        $svModel = new SinhVienModel();
        $sinhVien = $svModel->layThongTin($ma_sv);

        $this->goiGiaoDien('lop_hoc_phan/vang_nghi', [
            'dsVang' => $dsVang, 'sinhVien' => $sinhVien, 'ma_lop_hp' => $ma_lop_hp, 'from' => $from
        ]);
    }

    // 12. Nhập điểm
    public function nhapDiem() {
        if (!isset($_GET['ma_lop_hp'])) header('Location: index.php?controller=LopHocPhan');
        $ma_lop_hp = $_GET['ma_lop_hp'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dsSV = $this->bangDiemModel->layDanhSachSinhVien($ma_lop_hp);
            foreach ($dsSV as $sv) {
                $ma = $sv['ma_sv'];
                $gk = isset($_POST['diem_gk'][$ma]) ? $_POST['diem_gk'][$ma] : 0;
                $ck = isset($_POST['diem_ck'][$ma]) ? $_POST['diem_ck'][$ma] : 0;
                
                // Logic tính điểm CC (Vắng 1 buổi trừ 1đ, >3 buổi 0đ)
                $so_vang = $sv['so_buoi_vang'];
                if ($so_vang > 3) { $cc = 0; $ck = 0; } 
                else { $cc = 10 - $so_vang; if ($cc < 0) $cc = 0; }
                
                $this->bangDiemModel->luuDiem($ma_lop_hp, $ma, $cc, $gk, $ck);
            }
            $this->thongBao("Đã lưu bảng điểm thành công!");
        }

        $thongTinLop = $this->lhpModel->layThongTin($ma_lop_hp);
        $dsSinhVien = $this->bangDiemModel->layDanhSachSinhVien($ma_lop_hp);
        
        $this->goiGiaoDien('lop_hoc_phan/nhap_diem', [
            'thongTinLop' => $thongTinLop, 'dsSinhVien' => $dsSinhVien
        ]);
    }
}
?>