<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra và nhận giá trị sqlChitiet
if (isset($_POST['sqlChitiet']) && isset($_POST['id'])) {
    $sqlChitiet = $_POST['sqlChitiet'];
    $ID_KhachHang = $_POST['id'];
    $noiString = "AND ThongTinBanHang.ID_KhachHang = $ID_KhachHang;";
    // echo "Giá trị sqlChitiet nhận được: " . htmlspecialchars($sqlChitiet);
    // echo "Giá trị id khách hàng  nhận được: " . htmlspecialchars($ID_KhachHang);
    // Thực hiện truy vấn với giá trị sqlChitiet ở đây
    $sqlChitietFull = $sqlChitiet . $noiString;
    $result_dichvu = $conn->query($sqlChitietFull);

    // Truy vấn thông tin chi tiết của khách hàng
    $sql_khachhang = "SELECT * FROM KhachHang WHERE ID_KhachHang = $ID_KhachHang";
    $result_khachhang = $conn->query($sql_khachhang);
    $khachhang = $result_khachhang->fetch_assoc();
    // if ($result_nhanvien->num_rows == 0) {
    //     die("Nhân viên không tồn tại.");
    // }
    // $nhanvien = $result_nhanvien->fetch_assoc();



    $conn->close();

} else {
    echo "Không nhận được giá trị sqlChitiet";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Chi Tiết Khách Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Thông Tin Chi Tiết Khách Hàng</h2>
        <?php if ($khachhang): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title"><?php echo htmlspecialchars($khachhang['Ten']); ?></h4>
                    <p class="card-text"><strong>Số Điện Thoại:</strong>
                        <?php echo htmlspecialchars($khachhang['SoDienThoai']); ?></p>
                    <p class="card-text"><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($khachhang['DiaChi']); ?></p>
                </div>
            </div>

            <h3>Dịch Vụ Đã Đăng Ký</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên Dịch Vụ</th>
                        <th>Tên Gói Dịch Vụ</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Ngày Đăng Ký</th>
                        <th>Nhân Viên Bán</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_dichvu->num_rows > 0): ?>
                        <?php while ($row = $result_dichvu->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['TenDichVu']); ?></td>
                                <td><?php echo htmlspecialchars($row['TenGoiDichVu']); ?></td>
                                <td><?php echo htmlspecialchars($row['GiaTien']); ?></td>
                                <td><?php echo htmlspecialchars($row['SoLuong']); ?></td>
                                <td><?php echo htmlspecialchars($row['NgayDangKy']); ?></td>
                                <td><?php echo htmlspecialchars($row['TenNhanVien']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Khách hàng chưa đăng ký dịch vụ nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Không tìm thấy thông tin khách hàng.</p>
        <?php endif; ?>
        <a href="../top10/top10kh_dvMax.php" class="btn btn-secondary">Quay Lại</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>