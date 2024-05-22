<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dang_nhap_nv.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kiểm tra xem ID gói dịch vụ đã được truyền qua URL hay không
if (!isset($_GET['id'])) {
    header("Location: danh_sach_thong_tin_dich_vu.php"); // Nếu không, chuyển hướng đến trang danh sách dịch vụ
    exit();
}

$idGoiDichVu = $_GET['id'];

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn để lấy ID_DichVu từ ID_GoiDichVu
$sqlGetDichVu = "SELECT ID_DichVu FROM GoiDichVu WHERE ID_GoiDichVu = ?";
$stmtGetDichVu = $conn->prepare($sqlGetDichVu);
$stmtGetDichVu->bind_param("i", $idGoiDichVu);
$stmtGetDichVu->execute();
$resultGetDichVu = $stmtGetDichVu->get_result();

if ($resultGetDichVu->num_rows == 0) {
    echo "<script>alert('Gói dịch vụ không tồn tại.');</script>";
    header("refresh:0.5; url=danh_sach_thong_tin_dich_vu.php");
    exit();
}

$row = $resultGetDichVu->fetch_assoc();
$idDichVu = $row['ID_DichVu'];

$stmtGetDichVu->close();

// Kiểm tra xem có ràng buộc khóa ngoại nào đang sử dụng gói dịch vụ này không
$sqlCheck = "SELECT * FROM ThongTinBanHang WHERE ID_GoiDichVu = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $idGoiDichVu);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    echo "<script>alert('Không thể xóa gói dịch vụ này vì đang được sử dụng.');</script>";
    header("refresh:0.5; url=chi_tiet_dich_vu.php?id=$idDichVu");
    exit();
} else {
    // Nếu không có ràng buộc khóa ngoại, tiến hành xóa gói dịch vụ
    $sqlDelete = "DELETE FROM GoiDichVu WHERE ID_GoiDichVu = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idGoiDichVu);

    if ($stmtDelete->execute()) {
        echo "<script>alert('Xóa gói dịch vụ thành công.');</script>";
        header("refresh:0.5; url=chi_tiet_dich_vu.php?id=$idDichVu");
        exit();
    } else {
        echo "<script>alert('Xóa gói dịch vụ thất bại.');</script>";
        header("refresh:0.5; url=chi_tiet_dich_vu.php?id=$idDichVu");
        exit();
    }
}

$stmtCheck->close();
$stmtDelete->close();
$conn->close();
?>
