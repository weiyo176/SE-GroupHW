<?php
session_start();

// 檢查會員是否已登入
if (!isset($_SESSION['email'])) {
    header("Location: blogin1.php");
    exit();
}

// 取得會員資料
$email = $_SESSION['email'];

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

// 查詢會員資料
$sql = "SELECT * FROM business WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $email = $row['email'];
} else {
    // 找不到會員資料，重定向到登入頁面
    header("Location: blogin1.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>會員資料</title>
<style type="text/css">
#cont {
  width: 400px;
  margin: 20px auto;
  border-radius: 30px;
  background-color: #690;
  padding: 40px;
  color: ivory;
  font-size: 14pt;
}
input {
  font-size: 14pt;
  font-family: Times New Roman;
}
</style>
</head>
<body>
<div id="cont">
  <h2>會員資料</h2>
  <?php
  // 顯示更新成功的訊息
  if (isset($_GET['success']) && $_GET['success'] === '1') {
      echo '<p style="color: green;">資料更新成功</p>';
  }
  // 顯示更新失敗的訊息
  if (isset($_GET['error']) && $_GET['error'] === '1') {
      echo '<p style="color: red;">資料更新失敗</p>';
  }
  ?>
  <form action="bupdate_profile.php" method="post">
    <table cellpadding="5">
      <tr>
        <td align="right">帳號：</td>
        <td align="left"><?php echo $email; ?></td>
      </tr>
      <tr>
        <td align="right">名稱：</td>
        <td align="left"><input type="text" name="name" value="<?php echo $name; ?>"></td>
      </tr>
      <tr>
        <td align="right">新帳號：</td>
        <td align="left"><input type="email" name="new_email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required></td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type="submit" value="更新資料">
        </td>
      </tr>
    </table>
  </form>
  <p><a href="bchange_password.html">變更密碼</a></p>
  <p><a href="bus.html">進入商場</a></p>
  <p><a href="logout.php">登出</a></p>
</div>
</body>
</html>
