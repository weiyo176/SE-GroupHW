<?php
session_start();

// 檢查會員是否已登入
if (!isset($_SESSION['email'])) {
    header("Location: blogin1.php");
    exit();
}

// 取得會員帳號
$email = $_SESSION['email'];

// 取得表單提交的資料
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

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

// 取得會員資料
$sql = "SELECT * FROM business WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    // 檢查舊密碼是否正確
    if (password_verify($old_password, $hashed_password)) {
        // 檢查新密碼是否一致
        if ($new_password === $confirm_password) {
            // 更新密碼
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE business SET password = '$new_hashed_password' WHERE email = '$email'";

            if ($conn->query($update_sql) === TRUE) {
                echo "<script>alert('密碼變更成功');</script>";
            } else {
                echo "<script>alert('密碼變更失敗');</script>";
            }
        } else {
            echo "<script>alert('新密碼輸入不一致');</script>";
        }
    } else {
        echo "<script>alert('舊密碼輸入錯誤');</script>";
    }
} else {
    echo "找不到會員資料";
}
header("Location: blogin1.php");
exit();
$conn->close();
?>
