<?php
session_start();

// 檢查會員是否已登入
if (!isset($_SESSION['email'])) {
    header("Location: blogin1.php");
    exit();
}

// 取得會員資料
$email = $_SESSION['email'];

// 獲取表單提交的資料
$name = $_POST['name'];
$new_email = $_POST['new_email'];
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

// 更新會員資料
if (!empty($new_email)) {
    // 檢查新帳號是否已存在
    $check_email_sql = "SELECT * FROM business WHERE email = '$new_email'";
    $check_email_result = $conn->query($check_email_sql);

    if ($check_email_result->num_rows > 0) {
        // 新帳號已存在，顯示錯誤訊息
        header("Location: bmember_profile.php?error=2");
        exit();
    }

    // 更新帳號及其他資料
    $update_sql = "UPDATE business SET email = '$new_email', name = '$name' WHERE email = '$email'";
} 

if ($conn->query($update_sql) === TRUE) {
    // 資料更新成功
    $_SESSION['email'] = $new_email; // 更新 session 中的 email
    header("Location: bmember_profile.php?success=1");
    exit();
} else {
    // 資料更新失敗
    header("Location: bmember_profile.php?error=1");
    exit();
}

$conn->close();
?>
