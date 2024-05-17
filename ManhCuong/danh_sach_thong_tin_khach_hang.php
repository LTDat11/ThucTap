<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'tttt');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn thông tin khách hàng
$sql = "SELECT KhachHang.Ten, KhachHang.SoDienThoai, KhachHang.DiaChi, DichVu.TenDichVu, GoiDichVu.TenGoiDichVu, ThongTinBanHang.SoLuong, ThongTinBanHang.NgayDangKy
        FROM ThongTinBanHang
        JOIN KhachHang ON ThongTinBanHang.ID_KhachHang = KhachHang.ID_KhachHang
        JOIN GoiDichVu ON ThongTinBanHang.ID_GoiDichVu = GoiDichVu.ID_GoiDichVu
        JOIN DichVu ON GoiDichVu.ID_DichVu = DichVu.ID_DichVu";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Khách Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Danh Sách Thông Tin Khách Hàng</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Địa Chỉ</th>
                    <th>Tên Dịch Vụ</th>
                    <th>Tên Gói Dịch Vụ</th>
                    <th>Số Lượng</th>
                    <th>Ngày Đăng Ký</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Ten']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['SoDienThoai']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DiaChi']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['SoLuong']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['NgayDangKy']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="them_thong_tin_ban_hang.php" class="btn btn-primary">Thêm Thông Tin Bán Hàng Mới</a>
        <a href="dangxuatNV.php" class="btn btn-primary">LOGOUT</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>