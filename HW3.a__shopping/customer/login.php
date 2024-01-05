<?php
session_start();

// 接收表單提交的帳號和密碼
$email = $_POST['email'];
$password = $_POST['password'];

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

// 使用預處理語句以防止 SQL 注入攻擊
$sql = "SELECT * FROM customers WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 帳號存在，檢查密碼是否正確
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    if (password_verify($password, $hashedPassword)) {
        // 密碼正確，設定會員 Session 並重定向到會員資料頁面
        $_SESSION['email'] = $email;
        header("Location: member_profile.php");
        exit();
    }
}

// 登入失敗，重定向回登入頁面並傳遞錯誤訊息
header("Location: login1.php?error=1");
exit();
?>
