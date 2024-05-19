<?php
session_start();

// Check if the employee is logged in
if (!isset($_SESSION['ID_NhanVien'])) {
    // Redirect to the login page or display an error message
    header("Location: dang_nhap_nv.php");
    exit;
}
// Lấy ID Dịch Vụ từ URL
$ID_DichVu = isset($_GET['id']) ? (int) $_GET['id'] : 0;

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
dv.TenDichVu,
SUM(ttb.SoLuong) AS TongSoGoiDaBan
FROM 
ThongTinBanHang AS ttb
JOIN 
GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN 
DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
WHERE 
dv.ID_DichVu = $ID_DichVu -- Thay 1 bằng ID của dịch vụ bạn muốn hiển thị thông tin
GROUP BY 
dv.TenDichVu;
";

$result = $conn->query($sql);

$ssql1 = "SELECT 
gdv.ID_GoiDichVu,
gdv.TenGoiDichVu,
SUM(ttb.SoLuong) AS TongSoLuongBanDuoc
FROM 
ThongTinBanHang AS ttb
JOIN 
GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
WHERE 
gdv.ID_DichVu = $ID_DichVu
GROUP BY 
gdv.ID_GoiDichVu, gdv.TenGoiDichVu;
";
$result1 = $conn->query($ssql1);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <?php
        // Fetch the result from the query
        $row = $result->fetch_assoc();
        $tenDichVu = $row['TenDichVu'];
        $TongSoGoiDaBan = $row['TongSoGoiDaBan'];
        ?>

        <h2 class="mt-5">Chi tiết của dịch vụ <?php echo $tenDichVu; ?></h2>
        <p>Tổng số lượng bán được của dịch vụ: <?php echo $TongSoGoiDaBan; ?></p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Gói dịch vụ</th>
                    <th>Tổng số lượng bán được</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row1['TenGoiDichVu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row1['TongSoLuongBanDuoc']) . "</td>";

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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>