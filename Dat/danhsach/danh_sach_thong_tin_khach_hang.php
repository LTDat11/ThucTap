<?php
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

// Pagination setup
$limit = 10; // Number of entries to show in a page.
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Prepare the search query if provided
$search_query = isset($_GET['search_query']) ? $conn->real_escape_string($_GET['search_query']) : '';
$sql_search = '';
if (!empty($search_query)) {
    $sql_search = " WHERE Ten LIKE '%$search_query%' OR SoDienThoai LIKE '%$search_query%' OR DiaChi LIKE '%$search_query%'";
}

// Fetch total number of rows in the table
$sql_total = "SELECT COUNT(*) AS total FROM KhachHang" . $sql_search;
$total_result = $conn->query($sql_total);
$total_row = $total_result->fetch_assoc();
$total_rows = $total_row['total'];

// Fetch the data for the current page
$sql = "SELECT * FROM KhachHang" . $sql_search . " ORDER BY Ten ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Calculate total pages
$total_pages = ceil($total_rows / $limit);

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Khách Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container"> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid">
    <h2 class="mt-3">Danh Sách Thông Tin Khách Hàng</h2>
    <form action="" method="GET" class="mb-3">
        <div class="form-row">
            <div class="col">
                <input type="text" class="form-control" placeholder="Tìm kiếm..." name="search_query" value="<?php echo htmlspecialchars($search_query); ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary bi bi-search"> Tìm Kiếm</button>
            </div>
        </div>
    </form>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Tên Khách Hàng</th>
                <th>Số Điện Thoại</th>
                <th>Địa Chỉ</th>
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
                    echo "<td>
                        <a href='../chitiet/chi_tiet2.php?id=" . $row['ID_KhachHang'] . "' class='btn btn-info bi bi-info-circle'> Xem Chi Tiết</a>
                        <a href='../sua/sua_thong_tin_khach_hang.php?id=" . $row['ID_KhachHang'] . "' class='btn btn-warning bi bi-pencil ml-2'> Sửa</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="../xuat/xuat_excel_DS_thong_tin_khach_hang.php" class="btn btn-success bi bi-file-earmark-arrow-down mb-3"> Xuất Excel</a>
    
    <!-- Pagination Links -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "&search_query=" . urlencode($search_query) . "'>Trước Đó</a></li>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
                } else {
                    echo "<li class='page-item'><a class='page-link' href='?page=$i&search_query=" . urlencode($search_query) . "'>$i</a></li>";
                }
            }

            if ($page < $total_pages) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "&search_query=" . urlencode($search_query) . "'>Kế Tiếp</a></li>";
            }
            ?>
        </ul>
    </nav>
</div>
<?php include '../footer.php'; ?>
<!-- <a href="them_thong_tin_ban_hang.php" class="btn btn-primary">Thêm Thông Tin Bán Hàng Mới</a> -->
<!-- </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->
