<?php
session_start();
include 'connect.php';

$hoten = $sdt = $diachi = $tendangnhap = $matkhau = "";
$error = "";

if(isset($_POST['register'])){
    $hoten = $_POST['hoten'];
    $sdt = $_POST['sdt'];
    $diachi = $_POST['diachi'];
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = $_POST['matkhau'];

    // Check if any input field is empty
    if(empty($hoten) || empty($sdt) || empty($diachi) || empty($tendangnhap) || empty($matkhau)){
        $error = "Vui lòng điền đầy đủ thông tin";
    } else {
        // Check if the phone number already exists
        $sql_check_sdt = "SELECT * FROM KhachHang WHERE SDT = '$sdt'";
        $result_check_sdt = $conn->query($sql_check_sdt);

        if ($result_check_sdt->num_rows > 0) {
            $error = "Số điện thoại đã tồn tại";
        } else {
            // Hash the password
            $hashedPassword = md5($matkhau);

            // Insert into KhachHang table
            $sql_khachhang = "INSERT INTO KhachHang (HoTen, SDT, DiaChi) VALUES ('$hoten', '$sdt', '$diachi')";
            $conn->query($sql_khachhang);

            // Get the last inserted ID_KhachHang
            $id_khachhang = $conn->insert_id;

            // Insert into TaiKhoanDangNhap table
            $sql_taikhoan = "INSERT INTO TaiKhoanDangNhap (TenDangNhap, MatKhau, ID_KhachHang) VALUES ('$tendangnhap', '$hashedPassword', '$id_khachhang')";
            $conn->query($sql_taikhoan);

            $_SESSION['username'] = $tendangnhap;
            header("Location: index.php");
            $hoten = $sdt = $diachi = $tendangnhap = $matkhau = "";
            exit;
        }
    }
}
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <!-- top nav -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="https://upload.wikimedia.org/wikipedia/vi/thumb/6/6c/Logo_Dai_hoc_Can_Tho.svg/282px-Logo_Dai_hoc_Can_Tho.svg.png?20200610062224" alt="Logo" width="50" height="50" class="d-inline-block align-top">
            <strong style="margin-left: 20px;">CÔNG TY VIỄN THÔNG</strong>
        </a>
    </nav>

    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <h3>Đăng ký tài khoản</h3>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label>Họ tên</label>
                        <!-- <input type="text" class="form-control" name="hoten"> -->
                        <input type="text" class="form-control" name="hoten" value="<?php echo $hoten; ?>">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <!-- <input type="text" class="form-control" name="sdt"> -->
                        <input type="text" class="form-control" name="sdt" value="<?php echo $sdt; ?>">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <!-- <input type="text" class="form-control" name="diachi"> -->
                        <input type="text" class="form-control" name="diachi" value="<?php echo $diachi; ?>">
                    </div>
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <!-- <input type="text" class="form-control" name="tendangnhap"> -->
                        <input type="text" class="form-control" name="tendangnhap" value="<?php echo $tendangnhap; ?>">
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" class="form-control" name="matkhau"> 
                    </div>
                    <button type="submit" class="btn btn-primary" name="register">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>
    <!-- bot nav -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-top: 20px;">
            <div class="container justify-content-center">
                <div class="navbar-nav">
                    <span class="navbar-text">
                        Địa chỉ: 123 Đường ABC, Thành phố XYZ
                    </span>
                </div>  
                <div class="navbar-nav ml-4">
                    <span class="navbar-text">
                        Số điện thoại: 0123456789
                    </span>
                </div>
                <div class="navbar-nav ml-4">
                    <span class="navbar-text">
                        Email: example@example.com
                    </span>
                </div>
            </div>
    </nav>
</body>
</html>
