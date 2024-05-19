<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap_nv.php");
    exit;
}

if (!isset($_POST['service']) || !isset($_POST['period'])) {
    die("Invalid request");
}

$ID_DichVu = $_POST['service'];
$period = $_POST['period'];

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Congtyvienthong";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tạo truy vấn SQL tương ứng với khoảng thời gian
switch ($period) {
    case 'week':
        $sql = "SELECT 
                ttb.NgayDangKy, 
                dv.TenDichVu, 
                gdv.TenGoiDichVu, 
                gdv.GiaTien, 
                ttb.SoLuong, 
                nv.TenNhanVien,
                (gdv.GiaTien * ttb.SoLuong) AS TongTien
            FROM 
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND ttb.NgayDangKy BETWEEN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
                AND DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY);";
        break;
    case 'month':
        $sql = "SELECT 
                ttb.NgayDangKy, 
                dv.TenDichVu, 
                gdv.TenGoiDichVu, 
                gdv.GiaTien, 
                ttb.SoLuong, 
                nv.TenNhanVien,
                (gdv.GiaTien * ttb.SoLuong) AS TongTien
            FROM 
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND MONTH(ttb.NgayDangKy) = MONTH(CURDATE())
                AND YEAR(ttb.NgayDangKy) = YEAR(CURDATE());";
        break;
    case 'year':
        $sql = "SELECT 
                ttb.NgayDangKy, 
                dv.TenDichVu, 
                gdv.TenGoiDichVu, 
                gdv.GiaTien, 
                ttb.SoLuong, 
                nv.TenNhanVien,
                (gdv.GiaTien * ttb.SoLuong) AS TongTien
            FROM 
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND YEAR(ttb.NgayDangKy) = YEAR(CURDATE());";
        break;
}

$result = $conn->query($sql);
// Load PhpSpreadsheet library
require 'vendor/autoload.php';

// PhpSpreadsheet namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
if ($result->num_rows > 0) {
    

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'Ngày Đăng Ký')
        ->setCellValue('B1', 'Tên Dịch Vụ')
        ->setCellValue('C1', 'Tên Gói Dịch Vụ')
        ->setCellValue('D1', 'Giá Tiền')
        ->setCellValue('E1', 'Số Lượng')
        ->setCellValue('F1', 'Tên Nhân Viên')
        ->setCellValue('G1', 'Tổng Tiền');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['NgayDangKy'])
            ->setCellValue('B' . $rowNumber, $row['TenDichVu'])
            ->setCellValue('C' . $rowNumber, $row['TenGoiDichVu'])
            ->setCellValue('D' . $rowNumber, $row['GiaTien'])
            ->setCellValue('E' . $rowNumber, $row['SoLuong'])
            ->setCellValue('F' . $rowNumber, $row['TenNhanVien'])
            ->setCellValue('G' . $rowNumber, $row['TongTien']);
        $rowNumber++;
    }

    // Set HTTP headers
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="chi_tiet_doanh_thu.xls"');
    header('Cache-Control: max-age=0');

    $writer = new Xls($spreadsheet);
    $writer->save('php://output');
    exit;
} else {
    echo "Không có dữ liệu";
}
?>
