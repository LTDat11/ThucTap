<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap_nv.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kiểm tra xem ID dịch vụ đã được truyền qua URL hay không
if (!isset($_GET['id'])) {
    header("Location: danh_sach_thong_tin_dich_vu.php"); // Nếu không, chuyển hướng đến trang danh sách dịch vụ
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID dịch vụ từ URL
$id = $_GET['id'];

// Truy vấn thông tin dịch vụ dựa trên ID
$sqlDichVu = "SELECT * FROM DichVu WHERE ID_DichVu = ?";
$stmtDichVu = $conn->prepare($sqlDichVu);
$stmtDichVu->bind_param("i", $id);
$stmtDichVu->execute();
$resultDichVu = $stmtDichVu->get_result();
$dichVu = $resultDichVu->fetch_assoc();

// Truy vấn thông tin các gói dịch vụ thuộc dịch vụ tương ứng
$sqlGoiDichVu = "SELECT * FROM GoiDichVu WHERE ID_DichVu = ?";
$stmtGoiDichVu = $conn->prepare($sqlGoiDichVu);
$stmtGoiDichVu->bind_param("i", $id);
$stmtGoiDichVu->execute();
$resultGoiDichVu = $stmtGoiDichVu->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Dịch Vụ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Chi Tiết Dịch Vụ: <?php echo htmlspecialchars($dichVu['TenDichVu']); ?></h2>
        <h4>Các Gói Cước Hiện Có:</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên Gói Dịch Vụ</th>
                    <?php
                    $showTocDo = false;
                    $tempResult = $resultGoiDichVu->fetch_all(MYSQLI_ASSOC);
                    foreach ($tempResult as $row) {
                        if (!empty($row['TocDo'])) {
                            $showTocDo = true;
                            break;
                        }
                    }

                    $resultGoiDichVu->data_seek(0);
                    if ($showTocDo) {
                        echo '<th>Tốc Độ</th>';
                    }
                    ?>
                    <th>Giá Tiền</th>
                    <th>Mô Tả</th>
                    <th>Tùy Chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $resultGoiDichVu->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                    if (!empty($row['TocDo'])) {
                        echo "<td>" . htmlspecialchars($row['TocDo']) . "</td>";
                    }
                    echo "<td>" . htmlspecialchars($row['GiaTien']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['MoTa']) . "</td>";
                    echo "<td>
                    <a href='sua_goi_cuoc.php?id=" . $row['ID_GoiDichVu'] . "' class='btn btn-warning'>Sửa</a>
                    <a href='xoa_goi_cuoc.php?id=" . $row['ID_GoiDichVu'] . "' class='btn btn-danger'>Xóa</a>
                </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="them_goi_cuoc.php?idDichVu=<?php echo $id; ?>" class="btn btn-primary">Thêm Gói Cước</a>
        <a href="danh_sach_thong_tin_dich_vu.php" class="btn btn-secondary">Quay Lại</a>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>