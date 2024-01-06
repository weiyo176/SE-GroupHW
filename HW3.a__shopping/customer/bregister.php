<?php
// 建立資料庫連接
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "mvc_c";
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接失敗：" . $conn->connect_error);
}
// 取得表單提交的資料
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$name = $_POST['name'];

// 檢查密碼是否一致
if ($password !== $confirm_password) {
    echo "<script>alert('密碼輸入不一致');</script>";
} else {
    // 檢查帳號是否已存在
    $check_sql = "SELECT * FROM business WHERE email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<script>alert('帳號已存在');</script>";
    } else {
        // 新增會員資料
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO business (email, password, name ) VALUES ('$email', '$hashed_password', '$name')";

        if ($conn->query($insert_sql) == TRUE) {
            echo "<script>alert('註冊成功');</script>";
        } else {
            echo "<script>alert('註冊失敗');</script>";
        }
    }
}
header("Location: login1.php");
exit();
$conn->close();
?>
