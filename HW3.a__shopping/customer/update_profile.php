<?php
session_start();

// 檢查會員是否已登入
if (!isset($_SESSION['username'])) {
    header("Location: login1.php");
    exit();
}

// 取得會員資料
$username = $_SESSION['username'];

// 獲取表單提交的資料
$interest = $_POST['interest'];
$expertise = $_POST['expertise'];
$new_username = $_POST['new_username'];

// 建立資料庫連接
$servername = "localhost";
$username_db = "110213065";
$password_db = "z2112240";
$dbname = "110213065";
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接失敗：" . $conn->connect_error);
}

// 更新會員資料
if (!empty($new_username)) {
    // 檢查新帳號是否已存在
    $check_username_sql = "SELECT * FROM users WHERE username = '$new_username'";
    $check_username_result = $conn->query($check_username_sql);

    if ($check_username_result->num_rows > 0) {
        // 新帳號已存在，顯示錯誤訊息
        header("Location: member_profile.php?error=2");
        exit();
    }

    // 更新帳號及其他資料
    $update_sql = "UPDATE users SET username = '$new_username', interest = '$interest', expertise = '$expertise' WHERE username = '$username'";
} else {
    // 只更新興趣和專長，不更新帳號
    $update_sql = "UPDATE users SET interest = '$interest', expertise = '$expertise' WHERE username = '$username'";
}

if ($conn->query($update_sql) === TRUE) {
    // 資料更新成功
    $_SESSION['username'] = $new_username; // 更新 session 中的 username
    header("Location: member_profile.php?success=1");
    exit();
} else {
    // 資料更新失敗
    header("Location: member_profile.php?error=1");
    exit();
}

$conn->close();
?>
