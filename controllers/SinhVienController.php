<?php
require_once './models/SinhVienModel.php';
require_once './models/LopHanhChinhModel.php';

class SinhVienController extends BaseController {
    private $svModel;
    private $lopModel;

    public function __construct() {
        // Kiểm tra đăng nhập (Bảo vệ)
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: index.php?controller=DangNhap');
            exit;
        }
        $this->svModel = new SinhVienModel();
        $this->lopModel = new LopHanhChinhModel();
    }

    // 1. Hiển thị danh sách sinh viên
   

    public function index() {
        require_once './models/LopHanhChinhModel.php';
        $lopModel = new LopHanhChinhModel();
        
        // 1. Lấy tất cả lớp để xử lý bộ lọc
        $dsLopFull = $lopModel->layTatCa();
        
        // 2. Tách danh sách Khóa từ danh sách Lớp (Lấy 2 ký tự đầu)
        $dsKhoa = [];
        foreach ($dsLopFull as $lop) {
            // Giả sử mã lớp là 73DCTT21 -> Lấy "73"
            $khoa = substr($lop['ma_lop_hc'], 0, 2); 
            if (is_numeric($khoa)) {
                $dsKhoa[$khoa] = "Khóa $khoa"; // Dùng key để tránh trùng lặp
            }
        }
        krsort($dsKhoa); // Sắp xếp khóa mới nhất lên đầu

        // 3. Lấy tham số từ URL
        $khoa_chon = isset($_GET['khoa']) ? $_GET['khoa'] : '';
        $lop_chon = isset($_GET['lop']) ? $_GET['lop'] : '';
        $tu_khoa = isset($_GET['tu_khoa']) ? trim($_GET['tu_khoa']) : '';

        // 4. Lọc danh sách Lớp hiển thị theo Khóa đã chọn
        $dsLopHienThi = [];
        if ($khoa_chon != '') {
            foreach ($dsLopFull as $lop) {
                if (strpos($lop['ma_lop_hc'], $khoa_chon) === 0) {
                    $dsLopHienThi[] = $lop;
                }
            }
        } else {
            $dsLopHienThi = $dsLopFull; // Nếu chưa chọn khóa thì hiện hết (hoặc ẩn tùy bạn)
        }

        // 5. Lấy danh sách sinh viên
        $dsSinhVien = $this->svModel->layDanhSachLoc($khoa_chon, $lop_chon, $tu_khoa);
        
        $this->goiGiaoDien('sinh_vien/index', [
            'dsSinhVien' => $dsSinhVien,
            'dsKhoa' => $dsKhoa,
            'dsLopHienThi' => $dsLopHienThi,
            'khoa_chon' => $khoa_chon,
            'lop_chon' => $lop_chon,
            'tu_khoa' => $tu_khoa
        ]);
    }

    // 2. Chức năng Thêm mới
    public function them() {
        $error = '';
        // Nếu là POST (người dùng bấm nút Lưu)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ma_sv = $_POST['ma_sv'];
            $ho_ten = $_POST['ho_ten'];
            $ngay_sinh = $_POST['ngay_sinh'];
            $gioi_tinh = $_POST['gioi_tinh'];
            $ma_lop_hc = $_POST['ma_lop_hc'];

            // Gọi Model để lưu
            try {
                $this->svModel->themMoi($ma_sv, $ho_ten, $ngay_sinh, $gioi_tinh, $ma_lop_hc);
                // Lưu xong về lại trang danh sách
                header('Location: index.php?controller=SinhVien');
                exit;
            } catch (Exception $e) {
                $error = "Lỗi: Mã sinh viên có thể đã tồn tại!";
            }
        }

        // Lấy danh sách lớp để hiển thị trong dropdown
        $dsLop = $this->lopModel->layTatCa();
        $this->goiGiaoDien('sinh_vien/them', ['dsLop' => $dsLop, 'error' => $error]);
    }

    // 3. Chức năng Xóa
    public function xoa() {
        if (isset($_GET['ma_sv'])) {
            $this->svModel->xoa($_GET['ma_sv']);
        }
        header('Location: index.php?controller=SinhVien');
    }
    public function sua() {
        // Kiểm tra xem có mã SV trên URL không
        if (!isset($_GET['ma_sv'])) {
            header('Location: index.php?controller=SinhVien');
            exit;
        }

        $ma_sv = $_GET['ma_sv'];
        $error = '';

        // Nếu người dùng bấm Lưu (POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Mã SV thường không cho sửa, chỉ sửa các thông tin khác
                $this->svModel->capNhat($ma_sv, $_POST['ho_ten'], $_POST['ngay_sinh'], $_POST['gioi_tinh'], $_POST['ma_lop_hc']);
                header('Location: index.php?controller=SinhVien');
                exit;
            } catch (Exception $e) {
                $error = "Lỗi cập nhật dữ liệu!";
            }
        }

        // Lấy dữ liệu cũ để hiện lên form
        $sinhVien = $this->svModel->layThongTin($ma_sv);
        $dsLop = $this->lopModel->layTatCa(); // Vẫn cần ds lớp để chọn lại

        $this->goiGiaoDien('sinh_vien/sua', [
            'sinhVien' => $sinhVien, 
            'dsLop' => $dsLop,
            'error' => $error
        ]);
    }
    // --- Thêm vào SinhVienController ---

    public function xemDiem() {
        if (!isset($_GET['ma_sv'])) header('Location: index.php?controller=SinhVien');
        $ma_sv = $_GET['ma_sv'];

        // Lấy thông tin sinh viên
        $sinhVien = $this->svModel->layThongTin($ma_sv);
        
        // Lấy danh sách điểm
        require_once './models/BangDiemModel.php';
        $bangDiemModel = new BangDiemModel();
        $dsDiem = $bangDiemModel->layBangDiemCaNhan($ma_sv);

        // --- Logic Tính GPA (Trung bình tích lũy) ---
        $tongDiemTichLuy = 0;
        $tongTinChiTichLuy = 0;

        foreach ($dsDiem as $row) {
            // Chỉ tính những môn đã có điểm tổng kết (khác null)
            if ($row['diem_tong_ket_he_4'] !== null) {
                $tongDiemTichLuy += ($row['diem_tong_ket_he_4'] * $row['so_tin_chi']);
                $tongTinChiTichLuy += $row['so_tin_chi'];
            }
        }

        $gpa = 0;
        if ($tongTinChiTichLuy > 0) {
            $gpa = round($tongDiemTichLuy / $tongTinChiTichLuy, 2);
        }

        // Xếp loại học lực dựa trên GPA
        $xepLoai = 'Kém';
        if ($gpa >= 3.6) $xepLoai = 'Xuất sắc';
        elseif ($gpa >= 3.2) $xepLoai = 'Giỏi';
        elseif ($gpa >= 2.5) $xepLoai = 'Khá';
        elseif ($gpa >= 2.0) $xepLoai = 'Trung bình';
        else $xepLoai = 'Yếu';

        $this->goiGiaoDien('sinh_vien/bang_diem_ca_nhan', [
            'sinhVien' => $sinhVien,
            'dsDiem' => $dsDiem,
            'gpa' => $gpa,
            'tongTinChi' => $tongTinChiTichLuy,
            'xepLoai' => $xepLoai
        ]);
    }
    // --- Thêm vào SinhVienController.php ---

    // 1. Xuất danh sách sinh viên ra Excel (CSV)
    public function exportExcel() {
        // Lấy toàn bộ danh sách sinh viên (có thể lọc theo từ khóa nếu muốn)
        $dsSinhVien = $this->svModel->layTatCa();

        $filename = "Danh_sach_SV_Toan_Truong_" . date('Ymd_His') . ".csv";

        // Thiết lập header báo trình duyệt tải file về
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');

        // Thêm BOM để Excel hiển thị Tiếng Việt không bị lỗi font
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Ghi dòng tiêu đề (Dùng dấu chấm phẩy ; để Excel Việt Nam nhận diện cột)
        fputcsv($output, ['STT', 'Mã SV', 'Họ Tên', 'Ngày Sinh', 'Giới Tính', 'Mã Lớp HC', 'Tên Lớp'], ";");

        $stt = 1;
        foreach ($dsSinhVien as $sv) {
            fputcsv($output, [
                $stt++,
                $sv['ma_sv'],
                $sv['ho_ten'],
                $sv['ngay_sinh'], // Định dạng YYYY-MM-DD
                $sv['gioi_tinh'],
                $sv['ma_lop_hc'],
                $sv['ten_lop_hc']
            ], ";");
        }
        fclose($output);
        exit;
    }

    // 2. Nhập sinh viên từ Excel (CSV)
    public function importExcel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            $file = $_FILES['file_excel']['tmp_name'];

            if ($_FILES['file_excel']['size'] > 0) {
                $handle = fopen($file, "r");
                
                // Bỏ qua dòng tiêu đề
                fgetcsv($handle, 1000, ";"); 

                $countSuccess = 0;
                $countFail = 0;

                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    // Cấu trúc file CSV mong đợi:
                    // Cột 0: STT (bỏ qua)
                    // Cột 1: Mã SV
                    // Cột 2: Họ Tên
                    // Cột 3: Ngày Sinh (YYYY-MM-DD)
                    // Cột 4: Giới Tính
                    // Cột 5: Mã Lớp HC
                    
                    // Kiểm tra nếu cột Mã SV trống thì thử lấy cột 0 (nếu file không có STT)
                    $ma_sv = isset($data[1]) ? trim($data[1]) : '';
                    if (empty($ma_sv) && isset($data[0])) $ma_sv = trim($data[0]);
                    
                    $ho_ten = isset($data[2]) ? trim($data[2]) : (isset($data[1]) ? trim($data[1]) : '');
                    $ngay_sinh = isset($data[3]) ? trim($data[3]) : date('Y-m-d');
                    $gioi_tinh = isset($data[4]) ? trim($data[4]) : 'Nam';
                    $ma_lop_hc = isset($data[5]) ? trim($data[5]) : '';

                    if (!empty($ma_sv) && !empty($ma_lop_hc)) {
                        try {
                            // Gọi hàm themMoi có sẵn trong Model
                            $this->svModel->themMoi($ma_sv, $ho_ten, $ngay_sinh, $gioi_tinh, $ma_lop_hc);
                            $countSuccess++;
                        } catch (Exception $e) {
                            $countFail++; // Lỗi do trùng mã SV
                        }
                    }
                }
                fclose($handle);
                
                // Thông báo kết quả
                $msg = "Đã import thành công $countSuccess sinh viên.";
                if ($countFail > 0) $msg .= " Có $countFail dòng bị trùng Mã SV hoặc lỗi.";
                
                $this->thongBao($msg, ($countFail > 0) ? "warning" : "success");
            }
            header("Location: index.php?controller=SinhVien");
        }
    }
}
?>