<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn thông tin khách hàng
$sql = "SELECT
kh.Ten,
kh.SoDienThoai,
kh.DiaChi,
COUNT(DISTINCT dv.ID_DichVu) AS SoLuongLoaiDichVu
FROM
ThongTinBanHang AS ttb
JOIN
KhachHang AS kh ON ttb.ID_KhachHang = kh.ID_KhachHang
JOIN
GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN
DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
GROUP BY
kh.ID_KhachHang, kh.Ten, kh.SoDienThoai, kh.DiaChi
ORDER BY
SoLuongLoaiDichVu DESC";
$result = $conn->query($sql);

// Tạo đối tượng Spreadsheet mới
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Đặt tên tiêu đề cột
$sheet->setCellValue('A1', 'Tên Khách Hàng');
$sheet->setCellValue('B1', 'Số Điện Thoại');
$sheet->setCellValue('C1', 'Địa Chỉ');
$sheet->setCellValue('D1', 'Số Dịch Vụ Sử Dụng');

// Thêm dữ liệu vào bảng
$rowNumber = 2; // Bắt đầu từ dòng thứ 2
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['Ten']);
        $sheet->setCellValue('B' . $rowNumber, $row['SoDienThoai']);
        $sheet->setCellValue('C' . $rowNumber, $row['DiaChi']);
        $sheet->setCellValue('D' . $rowNumber, $row['SoLuongLoaiDichVu']);
        $rowNumber++;
    }
}

// Đặt tên file và xuất file
$writer = new Xlsx($spreadsheet);
$filename = 'Danh_Sach_Khach_Hang_Su_Dung_Nhieu_Dich_Vu_Nhat.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit();
?>