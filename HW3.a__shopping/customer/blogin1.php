<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>會員登入</title>
<style type="text/css">
body {
  background-color: #f2f2f2;
  font-family: Arial, sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

#cont {
  width: 300px;
  border-radius: 10px;
  background-color: #fff;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 20px;
}

form {
  text-align: center;
}

label {
  display: block;
  text-align: left;
  margin-bottom: 5px;
  color: #555;
}

input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border-radius: 5px;
  border: 1px solid #ccc;
  box-sizing: border-box;
  margin-bottom: 10px;
}

input[type="submit"],
input[type="reset"] {
  display: inline-block;
  padding: 10px 20px;
  font-size: 14px;
  background-color: #690;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

input[type="submit"]:hover,
input[type="reset"]:hover {
  background-color: #4e60a8;
}

.error-message {
  color: red;
  margin-top: 10px;
  text-align: center;
  display: none;
}
a.home-link {
      position: relative;
      display: block;
      text-align: center;
      background-color: #4CAF50;
      color: #fff;
      padding: 8px 16px;
      text-decoration: none;
      border-radius: 4px;
      margin-top: 20px; /* 與表格的距離 */
      margin-left: calc(100% - 150px); /* 與表格右邊的距離 */
    }

    a.home-link:hover {
      background-color: #45a049;
    }
</style>
</head>
<body>
<div id="cont">
  <h2>商家登入</h2>
  <form action="blogin.php" method="post">
    <label for="email">帳號:</label>
    <input type="text" name="email" id="email" required>
    <br>
    <label for="password">密碼：</label>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="submit" value="登入">
    <input type="reset">
  </form>
  <p><a href="login1.php" class="home-link">回到首頁</a></p>
  <?php
  if (isset($_GET['error']) && $_GET['error'] === '1') {
    echo "<script>alert('登入失敗，請檢查帳號和密碼');</script>";
  }
  ?>
</div>
</body>
</html>
