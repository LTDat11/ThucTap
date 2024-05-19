<?php
session_start();

// Check if the employee is logged in
if (!isset($_SESSION['ID_NhanVien'])) {
    // Redirect to the login page or display an error message
    header("Location: dang_nhap_nv.php");
    exit;
}
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Congtyvienthong";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

// Thực hiện truy vấn
$sql = "SELECT 
dv.ID_DichVu,
dv.TenDichVu,
SUM(ttb.SoLuong) AS TongSoGoiDaBan
FROM 
ThongTinBanHang AS ttb
JOIN 
GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN 
DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
GROUP BY 
dv.ID_DichVu, dv.TenDichVu
ORDER BY 
TongSoGoiDaBan DESC
LIMIT 10;
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
        <h2 class="mt-5">Top 10 DV Bán Chạy Nhất</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên Dịch Vụ</th>
                    <th>Số gói dịch vụ bán được</th>
                    <th>Xem chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TongSoGoiDaBan']) . "</td>";
                        echo "<td><a href='DVmax_chitiet.php?id=" . $row['ID_DichVu'] . "' class='btn btn-info'>Xem chi tiết</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>

        </table>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>