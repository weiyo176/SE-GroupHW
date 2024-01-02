<?php
session_start();

// 清除會員 Session
session_destroy();

// 跳轉回登入頁面
header("Location: login1.php");
exit();
?>
