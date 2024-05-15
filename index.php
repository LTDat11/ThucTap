<?php
// session_start();
include 'connect.php';

$sql = "SELECT TenDichVu FROM dichvu";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">CÔNG TY VIỄN THÔNG</a>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <?php
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<li class="nav-item">
                            <a class="nav-link ml-4" href="#">'.$row["TenDichVu"].'</a>
                        </li>';
                    }
                } else {
                    echo "0 results";
                }
                $conn->close();
                ?>
            </ul>
        </div>
        <?php
            if(isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo "<p class='navbar-brand'>Welcome, $username!</p>";
            } else {
                echo '<ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Đăng Nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Đăng Ký</a>
                    </li>
                    </ul>';
            }
        ?>
    </nav>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>