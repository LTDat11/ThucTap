<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn thông tin nhân viên
$sql = "SELECT 
ttbh.TenNhanVien,
ttbh.SoDienThoai,
ttbh.DiaChi,
COUNT(ttb.ID_TTNVBH) AS TongSoLuongDichVuBanDuoc
FROM 
ThongTinBanHang AS ttb
JOIN 
TTNhanVienBanHang AS ttbh ON ttb.ID_TTNVBH = ttbh.ID_TTNVBH
GROUP BY 
ttbh.ID_TTNVBH, ttbh.TenNhanVien, ttbh.SoDienThoai, ttbh.DiaChi
ORDER BY 
TongSoLuongDichVuBanDuoc DESC
LIMIT 10";
$result = $conn->query($sql);

// Tạo đối tượng Spreadsheet mới
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Đặt tên tiêu đề cột
$sheet->setCellValue('A1', 'Tên Nhân viên bán hàng');
$sheet->setCellValue('B1', 'Số Điện Thoại');
$sheet->setCellValue('C1', 'Địa Chỉ');
$sheet->setCellValue('D1', 'Tổng số dịch vụ bán được');

// Thêm dữ liệu vào bảng
$rowNumber = 2; // Bắt đầu từ dòng thứ 2
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['TenNhanVien']);
        $sheet->setCellValue('B' . $rowNumber, $row['SoDienThoai']);
        $sheet->setCellValue('C' . $rowNumber, $row['DiaChi']);
        $sheet->setCellValue('D' . $rowNumber, $row['TongSoLuongDichVuBanDuoc']);
        $rowNumber++;
    }
}

// Đặt tên file và xuất file
$writer = new Xlsx($spreadsheet);
$filename = 'Danh_Sach_Nhan_Vien_Ban_Hang.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit();
?>
