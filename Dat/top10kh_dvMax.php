<?php
//nhân viên phải đăng nhập mới xem được
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap_nv.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


//Truy vấn thông tin 10 khách hàng dùng nhiều dịch vụ nhất
$sql = "SELECT
kh.ID_KhachHang,
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
SoLuongLoaiDichVu DESC;
";
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
        <h2 class="mt-5">Danh Sách Top 10 Khách Hàng Sử Dụng Nhiều DV Nhất</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Địa Chỉ</th>
                    <th>Số Dịch Vụ Sử Dụng</th>
                    <th>Lựa Chọn</th>
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
                        echo "<td>" . htmlspecialchars($row['SoLuongLoaiDichVu']) . "</td>";
                        echo "<td>
                        <a href='chi_tiet.php?id=" . $row['ID_KhachHang'] . "' class='btn btn-info'>Xem Chi Tiết</a>
                         <a href='sua_thong_tin_khach_hang.php?id=" . $row['ID_KhachHang'] . "' class='btn btn-warning'>Sửa</a>
                    </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="them_thong_tin_ban_hang.php" class="btn btn-primary">Thêm Thông Tin Bán Hàng Mới</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>