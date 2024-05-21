<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tttt";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Truy vấn các gói dịch vụ có kèm ảnh
$sql = "SELECT * FROM GoiDichVu";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1>Chi tiết các gói dịch vụ</h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="card" style="width: 18rem;">
                    <img src="<?php echo $row['ImgURL']; ?>" class="card-img-top" alt="">
                    <div class="card-body">
                        <h5 class="card-title
                        "><?php echo $row['TenGoiDichVu']; ?></h5>
                        <p class="card-text">Giá: <?php echo $row['GiaTien']; ?></p>
                        <p class="card-text">Mô tả: <?php echo $row['MoTa']; ?></p>
                        <p class="card-text">Tốc độ: <?php echo $row['TocDo']; ?></p>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>


    </div>
</body>

</html>