<?php
require_once './models/LopHanhChinhModel.php';
require_once './models/KhoaModel.php';
require_once './models/SinhVienModel.php'; // Để xem chi tiết sinh viên trong lớp

class LopHCController extends BaseController {
    private $lopModel;
    private $khoaModel;

    public function __construct() {
        if (!isset($_SESSION['admin_logged_in'])) header('Location: index.php?controller=DangNhap');
        $this->lopModel = new LopHanhChinhModel();
        $this->khoaModel = new KhoaModel();
    }

    public function index() {
        $dsLop = $this->lopModel->layTatCa();
        $this->goiGiaoDien('lop_hanh_chinh/index', ['dsLop' => $dsLop]);
    }

   // Thay thế hàm them() cũ bằng hàm này
    public function them() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ma_khoa_final = $_POST['ma_khoa']; // Mặc định lấy từ dropdown

            // --- LOGIC XỬ LÝ KHOA MỚI ---
            // Nếu người dùng nhập cả Mã khoa mới và Tên khoa mới
            if (!empty($_POST['ma_khoa_moi']) && !empty($_POST['ten_khoa_moi'])) {
                $ma_khoa_moi = trim($_POST['ma_khoa_moi']);
                $ten_khoa_moi = trim($_POST['ten_khoa_moi']);

                // Kiểm tra xem mã khoa này đã có chưa
                if ($this->khoaModel->kiemTraTonTai($ma_khoa_moi)) {
                    $this->thongBao("Lỗi: Mã khoa '$ma_khoa_moi' đã tồn tại trong hệ thống!", "danger");
                    // Load lại trang để nhập lại
                    $dsKhoa = $this->khoaModel->layTatCa();
                    $this->goiGiaoDien('lop_hanh_chinh/them', ['dsKhoa' => $dsKhoa]);
                    return;
                } else {
                    // Thêm khoa mới vào CSDL
                    $this->khoaModel->themMoi($ma_khoa_moi, $ten_khoa_moi);
                    $ma_khoa_final = $ma_khoa_moi; // Gán mã mới để dùng cho việc tạo lớp
                }
            }

            // --- TIẾP TỤC TẠO LỚP NHƯ BÌNH THƯỜNG ---
            try {
                $this->lopModel->themMoi($_POST['ma_lop_hc'], $_POST['ten_lop_hc'], $ma_khoa_final);
                
                $msg = "Thêm lớp hành chính thành công!";
                if (!empty($_POST['ma_khoa_moi'])) {
                    $msg .= " (Đã tạo mới khoa: $ma_khoa_final)";
                }
                
                $this->thongBao($msg);
                header('Location: index.php?controller=LopHC');
                exit;
            } catch (Exception $e) {
                $this->thongBao("Lỗi: Mã lớp đã tồn tại!", "danger");
            }
        }

        $dsKhoa = $this->khoaModel->layTatCa();
        $this->goiGiaoDien('lop_hanh_chinh/them', ['dsKhoa' => $dsKhoa]);
    }

    public function sua() {
        $ma_lop = $_GET['ma_lop_hc'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->lopModel->capNhat($ma_lop, $_POST['ten_lop_hc'], $_POST['ma_khoa']);
            $this->thongBao("Cập nhật thành công!");
            header('Location: index.php?controller=LopHC');
            exit;
        }
        $lop = $this->lopModel->layThongTin($ma_lop);
        $dsKhoa = $this->khoaModel->layTatCa();
        $this->goiGiaoDien('lop_hanh_chinh/sua', ['lop' => $lop, 'dsKhoa' => $dsKhoa]);
    }

    public function xoa() {
        try {
            $this->lopModel->xoa($_GET['ma_lop_hc']);
            $this->thongBao("Đã xóa lớp hành chính.", "warning");
        } catch (Exception $e) {
            $this->thongBao("Không thể xóa: Lớp này đang có sinh viên!", "danger");
        }
        header('Location: index.php?controller=LopHC');
    }

    // Xem chi tiết: Hiển thị danh sách sinh viên của lớp đó
    public function chiTiet() {
        $ma_lop = $_GET['ma_lop_hc'];
        $lop = $this->lopModel->layThongTin($ma_lop);
        
        $svModel = new SinhVienModel();
        // Tận dụng hàm lọc có sẵn
        $dsSinhVien = $svModel->layDanhSachLoc('', $ma_lop);

        $this->goiGiaoDien('lop_hanh_chinh/chi_tiet', [
            'lop' => $lop,
            'dsSinhVien' => $dsSinhVien
        ]);
    }
}
?>