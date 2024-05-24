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

// Truy vấn thông tin bán hàng
$sql = "SELECT 
    ttb.ID_ThongTinBanHang,
    ttb.ID_TTNVBH,
    ttb.ID_KhachHang,
    ttb.ID_GoiDichVu,
    ttb.NgayDangKy,
    nv.TenNhanVien,
    kh.Ten AS TenKhachHang,
    gdv.TenGoiDichVu
FROM ThongTinBanHang AS ttb
JOIN TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
JOIN KhachHang AS kh ON ttb.ID_KhachHang = kh.ID_KhachHang
JOIN GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
ORDER BY ttb.NgayDangKy DESC";
$result = $conn->query($sql);

$conn->close();
?>


<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container"> -->
<?php include '../menu.php'; ?>
<div class="container">
    <h2 class="mt-3">Danh Sách Thông Tin Bán Hàng</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Nhân Viên</th>
                <th>Tên Khách Hàng</th>
                <th>Tên Gói Dịch Vụ</th>
                <th>Ngày Bán</th>
                <th>Lựa Chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['TenNhanVien']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenKhachHang']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NgayDangKy']) . "</td>";
                    echo "<td>
                            <a href='../sua/sua_thong_tin_ban_hang.php?id=" . $row['ID_ThongTinBanHang'] . "' class='btn btn-warning'>Sửa</a>
                            <a href='../xoa/xoa_thong_tin_ban_hang.php?id=" . $row['ID_ThongTinBanHang'] . "' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>
                            </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="../them/them_thong_tin_ban_hang.php" class="btn btn-primary mb-3">Thêm Thông Tin Bán Hàng Mới</a>
</div>
<?php include '../footer.php'; ?>
<!-- </div>  -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    // Hide submenus
    $('#body-row .collapse').collapse('hide');

    // Collapse/Expand icon
    $('#collapse-icon').addClass('fa-angle-double-left');

    // Collapse click
    $('[data-toggle=sidebar-colapse]').click(function() {
        SidebarCollapse();
    });

    function SidebarCollapse() {
        $('.menu-collapsed').toggleClass('d-none');
        $('.sidebar-submenu').toggleClass('d-none');
        $('.submenu-icon').toggleClass('d-none');
        $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');

        // Treating d-flex/d-none on separators with title
        var SeparatorTitle = $('.sidebar-separator-title');
        if (SeparatorTitle.hasClass('d-flex')) {
            SeparatorTitle.removeClass('d-flex');
        } else {
            SeparatorTitle.addClass('d-flex');
        }

        // Collapse/Expand icon
        $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
    }
</script>

</body>

</html> -->