<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap_nv.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối cơ sở dữ liệu
    $conn = new mysqli('localhost', 'root', '', 'congtyvienthong');
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Lấy dữ liệu từ biểu mẫu
    $ID_KhachHang = $conn->real_escape_string($_POST['ID_KhachHang']);
    $ID_GoiDichVu = $conn->real_escape_string($_POST['ID_GoiDichVu']);
    $ID_TTNVBH = $conn->real_escape_string($_POST['ID_TTNVBH']);
    $SoLuong = $conn->real_escape_string($_POST['SoLuong']);
    $NgayDangKy = $conn->real_escape_string($_POST['NgayDangKy']);

    // Chèn dữ liệu vào bảng ThongTinBanHang
    $sql = "INSERT INTO ThongTinBanHang (ID_KhachHang, ID_GoiDichVu, ID_TTNVBH, NgayDangKy, SoLuong) 
            VALUES ('$ID_KhachHang', '$ID_GoiDichVu', '$ID_TTNVBH', '$NgayDangKy', '$SoLuong')";

    if ($conn->query($sql) === TRUE) {
        $message = "Thêm thông tin bán hàng thành công.";
    } else {
        $message = "Lỗi: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Thông Tin Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function handleSuccess(message) {
            if (message) {
                let continueAdding = confirm(message + "\nBạn có muốn tiếp tục thêm thông tin mới không?");
                if (!continueAdding) {
                    window.location.href = 'danh_sach_thong_tin_khach_hang.php';
                }
            }
        }
    </script>
</head>
<body onload="handleSuccess('<?php echo $message; ?>')">
<div class="container">
    <h2 class="mt-5">Thêm Thông Tin Bán Hàng</h2>
    <form action="them_thong_tin_ban_hang.php" method="post">
        <div class="form-group">
            <label for="khachHang">Khách Hàng</label>
            <select class="form-control" id="khachHang" name="ID_KhachHang" required>
                <?php
                // Kết nối cơ sở dữ liệu
                $conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
                if ($conn->connect_error) {
                    die("Kết nối thất bại: " . $conn->connect_error);
                }
                $sql = "SELECT ID_KhachHang, Ten FROM KhachHang";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['ID_KhachHang'] . "'>" . htmlspecialchars($row['Ten']) . "</option>";
                    }
                } else {
                    echo "<option>Không có khách hàng</option>";
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="goiDichVu">Gói Dịch Vụ</label>
            <select class="form-control" id="goiDichVu" name="ID_GoiDichVu" required>
                <?php
                // Kết nối cơ sở dữ liệu
                $conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
                if ($conn->connect_error) {
                    die("Kết nối thất bại: " . $conn->connect_error);
                }
                $sql = "SELECT ID_GoiDichVu, TenGoiDichVu FROM GoiDichVu";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['ID_GoiDichVu'] . "'>" . htmlspecialchars($row['TenGoiDichVu']) . "</option>";
                    }
                } else {
                    echo "<option>Không có gói dịch vụ</option>";
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="nhanVienBanHang">Nhân Viên Bán Hàng</label>
            <select class="form-control" id="nhanVienBanHang" name="ID_TTNVBH" required>
                <?php
                // Kết nối cơ sở dữ liệu
                $conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
                if ($conn->connect_error) {
                    die("Kết nối thất bại: " . $conn->connect_error);
                }
                $sql = "SELECT ID_TTNVBH, TenNhanVien FROM TTNhanVienBanHang";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['ID_TTNVBH'] . "'>" . htmlspecialchars($row['TenNhanVien']) . "</option>";
                    }
                } else {
                    echo "<option>Không có nhân viên bán hàng</option>";
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="soLuong">Số Lượng</label>
            <input type="number" class="form-control" id="soLuong" name="SoLuong" required min="1">
        </div>
        <div class="form-group">
            <label for="ngayDangKy">Ngày Đăng Ký</label>
            <input type="date" class="form-control" id="ngayDangKy" name="NgayDangKy" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm Thông Tin Bán Hàng</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
