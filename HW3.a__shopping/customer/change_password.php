<?php
session_start();

// 檢查會員是否已登入
if (!isset($_SESSION['username'])) {
    header("Location: login1.php");
    exit();
}

// 取得會員帳號
$username = $_SESSION['username'];

// 取得表單提交的資料
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// 建立資料庫連接
$servername = "localhost";
$db_username = "110213065";
$db_password = "z2112240";
$db_name = "110213065";
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接失敗：" . $conn->connect_error);
}

// 取得會員資料
$sql = "SELECT * FROM users WHERE username = '$username'";
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
            $update_sql = "UPDATE users SET password = '$new_hashed_password' WHERE username = '$username'";

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
header("Location: login1.php");
exit();
$conn->close();
?>
