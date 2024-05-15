<?php

include 'connect.php';

session_start();

if(isset($_POST['login'])){
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = $_POST['matkhau'];

    // Hash the password
    $hashedPassword = md5($matkhau);

    // Check if the account exists in TaiKhoanDangNhap table
    $sql = "SELECT * FROM TaiKhoanDangNhap WHERE TenDangNhap = '$tendangnhap' AND MatKhau = '$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['LoaiTaiKhoan'] == 0) {
            // Set session variables
            $_SESSION['username'] = $tendangnhap;
            $_SESSION['role'] = 'user';

            header("Location: index.php");
            exit;
        } else {
            // Set session variables
            $_SESSION['username'] = $tendangnhap;
            $_SESSION['role'] = 'admin';

            header("Location: admin.php");
            exit;
        }
    } else {
        echo "<script>alert('Tên đăng nhập hoặc mật khẩu không đúng!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <!-- top nav -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="https://upload.wikimedia.org/wikipedia/vi/thumb/6/6c/Logo_Dai_hoc_Can_Tho.svg/282px-Logo_Dai_hoc_Can_Tho.svg.png?20200610062224" alt="Logo" width="30" height="30" class="d-inline-block align-top">
            CÔNG TY VIỄN THÔNG
        </a>
    </nav>

    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <h3>Đăng Nhập</h3>
            </div>
            <div class="card-body">
            <form method="post" action="">
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" class="form-control" name="tendangnhap">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" class="form-control" name="matkhau">
            </div>
            <button type="submit" class="btn btn-primary" name="login">Đăng nhập</button>
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