<?php
//nhân viên phải đăng nhập mới xem được
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
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

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách TOP 10 Thông Tin Khách Hàng Sử Dụng Dịch Vụ Nhiều Nhất</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container"> -->
<?php include '../menu.php'; ?>
<div class="container">
    <h2 class="mt-3">Danh Sách Top 10 Khách Hàng Sử Dụng Nhiều Dịch Vụ Nhất</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Khách Hàng</th>
                <th>Số Điện Thoại</th>
                <th>Địa Chỉ</th>
                <th>Số Dịch Vụ Sử Dụng</th>
                <th>Tùy Chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $tenKhachHang = [];
                $soLuongDichVu = [];
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Ten']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SoDienThoai']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['DiaChi']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SoLuongLoaiDichVu']) . "</td>";
                    echo "<td>
                        <a href='../chitiet/chi_tiet.php?id=" . $row['ID_KhachHang'] . "' class='btn btn-info bi bi-info-circle'> Xem Chi Tiết</a>
                        </td>";
                    echo "</tr>";

                    // Thêm dữ liệu vào mảng
                    $tenKhachHang[] = $row['Ten'];
                    $soLuongDichVu[] = $row['SoLuongLoaiDichVu'];
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="../xuat/xuat_excel_top10_khach_hang_dung_nhieu_dv.php" class="btn btn-success bi bi-file-earmark-arrow-down"> Xuất Excel</a>

    <!-- Biểu đồ -->
    <div class="mt-5">
        <h2 class="mt-5">Biểu đồ Top 10 Khách Hàng Sử Dụng Nhiều Dịch Vụ Nhất</h2>
        <canvas id="myChart_kh_dv_max" class="mb-3"></canvas>
    </div>
</div>
<?php include '../footer.php'; ?>
<!-- </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    var ctx = document.getElementById('myChart_kh_dv_max').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tenKhachHang); ?>,
            datasets: [{
                label: 'Số lượng dịch vụ sử dụng',
                data: <?php echo json_encode($soLuongDichVu); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>

</html> -->