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
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container"> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-users"></i> Danh Sách Thông Tin Khách Hàng</h2>
        </div>
        <div class="card-body p-5">
            <form action="" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." name="search_query" value="<?php echo htmlspecialchars($search_query); ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm Kiếm
                        </button>
                        <a href="../xuat/xuat_excel_DS_thong_tin_khach_hang.php" class="btn btn-success ml-2">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </a>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Tên Khách Hàng</th>
                            <th scope="col">Số Điện Thoại</th>
                            <th scope="col">Địa Chỉ</th>
                            <th scope="col" class="text-center">Lựa Chọn</th>
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
                                echo "<td class='text-center'>
                                    <a href='../chitiet/chi_tiet2.php?id=" . $row['ID_KhachHang'] . "' class='btn btn-info btn-sm'><i class='fas fa-info-circle'></i> Xem Chi Tiết</a>
                                    <a href='../sua/sua_thong_tin_khach_hang.php?id=" . $row['ID_KhachHang'] . "' class='btn btn-warning btn-sm ml-2'><i class='fas fa-pencil-alt'></i> Sửa</a>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>Không có dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    // Define the range of pages to display
                    $range = 2;
                    $start = max(1, $page - $range);
                    $end = min($total_pages, $page + $range);

                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "&search_query=" . urlencode($search_query) . "'>Trước</a></li>";
                    }

                    if ($start > 1) {
                        echo "<li class='page-item'><a class='page-link' href='?page=1&search_query=" . urlencode($search_query) . "'>1</a></li>";
                        if ($start > 2) {
                            echo "<li class='page-item'><span class='page-link'>...</span></li>";
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $page) {
                            echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' href='?page=$i&search_query=" . urlencode($search_query) . "'>$i</a></li>";
                        }
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) {
                            echo "<li class='page-item'><span class='page-link'>...</span></li>";
                        }
                        echo "<li class='page-item'><a class='page-link' href='?page=$total_pages&search_query=" . urlencode($search_query) . "'>$total_pages</a></li>";
                    }

                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "&search_query=" . urlencode($search_query) . "'>Kế Tiếp</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>
