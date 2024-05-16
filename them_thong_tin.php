<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối cơ sở dữ liệu
    $conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Lấy dữ liệu từ biểu mẫu
    $ID_KhachHang = $conn->real_escape_string($_POST['ID_KhachHang']);
    $ID_GoiDichVu = $conn->real_escape_string($_POST['ID_GoiDichVu']);
    $ID_TTNVBH = $conn->real_escape_string($_POST['ID_TTNVBH']);
    $SoLuong = $conn->real_escape_string($_POST['SoLuong']);
    $NgayDangKy = $conn->real_escape_string($_POST['NgayDangKy']);

    // Chèn dữ liệu vào bảng ThongTinBanHang
    $sql = "INSERT INTO ThongTinBanHang (ID_KhachHang, ID_GoiDichVu, ID_TTNVBH, NgayDangKy, SoLuong) 
            VALUES ('$ID_KhachHang', '$ID_GoiDichVu', '$ID_TTNVBH', '$NgayDangKy', '$SoLuong')";

    if ($conn->query($sql) === TRUE) {
        echo "Thêm thông tin bán hàng thành công.";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
