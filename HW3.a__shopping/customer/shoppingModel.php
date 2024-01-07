<?php
require('dbconfig.php');

function register($email, $password, $confirm_password, $name)
{
	global $db;
	// 檢查密碼是否一致
	if ($password !== $confirm_password) {
		return false;
		// echo "<script>alert('密碼輸入不一致');</script>";
	} else {
		// 檢查帳號是否已存在
		$check_sql = "SELECT * FROM business WHERE email = '$email'";
		$check_result = $db->query($check_sql);

		if ($check_result->num_rows > 0) {
			return false;
			// echo "<script>alert('帳號已存在');</script>";
		} else {
			// 新增會員資料
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$insert_sql = "INSERT INTO business (email, password, name ) VALUES ('$email', '$hashed_password', '$name')";

			if ($db->query($insert_sql) == TRUE) {
				return true;
				// echo "<script>alert('註冊成功');</script>";
			} else {
				return false;
				// echo "<script>alert('註冊失敗');</script>";
			}
		}
	}
}
function login($email, $password)
{
	global $db;
	$sql = "SELECT * FROM business WHERE email = ?";
	$stmt = mysqli_prepare($db, $sql); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt); // 執行預備語句
	$result = mysqli_stmt_get_result($stmt);

	$rows = array(); //要回傳的陣列
	if ($result->num_rows > 0) {
		// 帳號存在，檢查密碼是否正確
		$row = $result->fetch_assoc();
		$hashedPassword = $row['password'];

		if (password_verify($password, $hashedPassword)) {
			// 密碼正確，設定會員 Session 並重定向到會員資料頁面
			$rows[] = $row;
			return $rows;
		}
		else
			$rows = array(
				'bID' => "0"
			);
			return $rows;
	}
	$rows = array(
		'bID' => "0"
	);
	return $rows;
}
function checkStatus($oID, $rule)
{
	global $db;
	//if rule=1, select all status=未處理 or status=處理中
	if ($rule == 1)
		$sql = "select * from mer_order where oID=$oID && (status = \"未處理\" or status = \"處理中\");";
	//if rule=2, select all status=處理中
	else if ($rule == 2)
		$sql = "select * from mer_order where oID=$oID && status = \"寄送中\";";
	$stmt = mysqli_prepare($db, $sql); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$status = "處理中";
	$status2 = "寄送中";
	$status3 = "已寄送";
	// 如果status是未處理將資料庫update成處理中，如果是處理中則設成寄送中
	while ($r = mysqli_fetch_assoc($result)) {
		if ($r['status'] == "未處理") {
			$updateSql = "UPDATE mer_order SET status = ? WHERE gID = ?";
			$updateStmt = mysqli_prepare($db, $updateSql);
			mysqli_stmt_bind_param($updateStmt, "si", $status, $r['gID']);
			mysqli_stmt_execute($updateStmt);
		} else if ($r['status'] == "處理中") {
			$updateSql = "UPDATE mer_order SET status = ? WHERE gID = ?";
			$updateStmt = mysqli_prepare($db, $updateSql);
			mysqli_stmt_bind_param($updateStmt, "si", $status2, $r['gID']);
			mysqli_stmt_execute($updateStmt);
		} else if ($r['status'] == "寄送中") {
			$updateSql = "UPDATE mer_order SET status = ? WHERE gID = ?";
			$updateStmt = mysqli_prepare($db, $updateSql);
			mysqli_stmt_bind_param($updateStmt, "si", $status3, $r['gID']);
			mysqli_stmt_execute($updateStmt);
		}
	}
	// while($r = mysqli_fetch_assoc($result)) {
	// 	$rows[] = $r; //將此筆資料新增到陣列中
	// }
	return true;
}
function getOrderList($rule,$bID)
{
	global $db;
	if ($rule == 1)
		$sql = "select * from mer_order where id=? && (status = \"未處理\" or status = \"處理中\");";
	else if ($rule == 2)
		$sql = "select * from mer_order where id=? && status = \"寄送中\";";
	// $sql = "select * from customer where status = \"未處理\" or status = \"處理中\";";
	$stmt = mysqli_prepare($db, $sql); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_bind_param($stmt, "i", $bID);
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果

	$rows = array(); //要回傳的陣列
	while ($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	return $rows;
}
function getItemList()
{
	global $db;
	$sql = "select * from items;";
	$stmt = mysqli_prepare($db, $sql); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$rows = array(); //要回傳的陣列
	while ($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	return $rows;
}
function getCartList()
{
	global $db;
	$sql = "select * from customer;";
	$stmt = mysqli_prepare($db, $sql); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$totalPrice = 0;
	$rows = array(); //要回傳的陣列
	while ($r = mysqli_fetch_assoc($result)) {
		$totalPrice += $r['total'];
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	$newData = array(
		'total' => $totalPrice
	);
	$rows[] = $newData;
	return $rows;
}

function addItem($id, $name, $price)
{
	global $db;
	$sql = "select * from customer where id= $id && cID = 1;";
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$row = mysqli_fetch_assoc($result);
	if ($row) {
		$newAmount = $row['amount'] + 1;
		$newTotal = $newAmount * $row['price'];
		$updateSql = "UPDATE customer SET amount = ?, total = ? WHERE id = ?";
		$updateStmt = mysqli_prepare($db, $updateSql);
		mysqli_stmt_bind_param($updateStmt, "iii", $newAmount, $newTotal, $id);
		mysqli_stmt_execute($updateStmt);
	} else {
		$amount = 1;
		$cID = 1;
		$sql = "insert into customer (cID,id, goods, price, amount, total) values (?, ?, ?, ?, ?, ?)"; //SQL中的 ? 代表未來要用變數綁定進去的地方
		$stmt = mysqli_prepare($db, $sql); //prepare sql statement
		mysqli_stmt_bind_param($stmt, "iisiii", $cID, $id, $name, $price, $amount, $price); //bind parameters with variables, with types "sss":string, string ,string
		mysqli_stmt_execute($stmt);  //執行SQL
	}
	return True;
}

function minusItem($gID, $ifplus)
{
	global $db;

	$sql = "select * from customer where gID= $gID;";
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$row = mysqli_fetch_assoc($result);
	if ($row && $ifplus)
		$newAmount = $row['amount'] + 1;
	else
		$newAmount = $row['amount'] - 1;
	if ($newAmount >= 0) {
		$newTotal = $newAmount * $row['price'];
		$updateSql = "UPDATE customer SET amount = ?, total = ? WHERE gID = ?";
		$updateStmt = mysqli_prepare($db, $updateSql);
		mysqli_stmt_bind_param($updateStmt, "iii", $newAmount, $newTotal, $gID);
		mysqli_stmt_execute($updateStmt);
	}
	return True;
}
function delItem($gID)
{
	global $db;

	$sql = "delete from customer where gID=?;"; //SQL中的 ? 代表未來要用變數綁定進去的地方
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "i", $gID); //bind parameters with variables, with types "sss":string, string ,string
	mysqli_stmt_execute($stmt);  //執行SQL
	return True;
}
function getJobList($bID)
{
	global $db;
	$sql = "select * from items where bID=?;";
	$stmt = mysqli_prepare($db, $sql); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_bind_param($stmt, "i", $bID);
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果

	$rows = array(); //要回傳的陣列
	while ($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	return $rows;
}

function addJob($name, $price, $description, $jobID, $bID)
{
	global $db;
	if ($jobID > 0) {
		$sql = "update items set bID=?, name=?, price=?, description=? where id=?"; //SQL中的 ? 代表未來要用變數綁定進去的地方
		$stmt = mysqli_prepare($db, $sql); //prepare sql statement
		mysqli_stmt_bind_param($stmt, "isssi", $bID, $name, $price, $description, $jobID); //bind parameters with variables, with types "sss":string, string ,string
	} else {
		$sql = "insert into items (bID,name, price, description) values (?, ?, ?, ?)"; //SQL中的 ? 代表未來要用變數綁定進去的地方
		$stmt = mysqli_prepare($db, $sql); //prepare sql statement
		mysqli_stmt_bind_param($stmt, "isss", $bID, $name, $price, $description); //bind parameters with variables, with types "sss":string, string ,string
	}
	mysqli_stmt_execute($stmt);  //執行SQL
	return True;
}

function updateJob($id, $name, $price, $description)
{
	echo $id, $name, $price, $description;
	return;
}

function delJob($id)
{
	global $db;

	$sql = "delete from items where id=?;"; //SQL中的 ? 代表未來要用變數綁定進去的地方
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "i", $id); //bind parameters with variables
	mysqli_stmt_execute($stmt);  //執行SQL
	return True;
}

function CheckOut()
{
	global $db;
	$sql = "INSERT INTO mer_order (rating, oID, gID, id, cID, goods, price, amount, total, status) SELECT rating, oID, gID, id, cID, goods, price, amount, total, status FROM customer";
	$stmt = mysqli_prepare($db, $sql);
	mysqli_stmt_execute($stmt);  //執行SQL

	$sql1 = "delete from customer;";
	$stmt = mysqli_prepare($db, $sql1);
	mysqli_stmt_execute($stmt);  //執行SQL

	return True;
}

function getmyorder()
{
	global $db;
	$sql = "select *,  CASE WHEN status = '已送達' THEN 1 ELSE 0 END AS enableRating from mer_order where cid = 1;";
	$stmt = mysqli_prepare($db, $sql ); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$rows = array(); //要回傳的陣列
	while ($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	return $rows;
}
function SubmitRating($gID, $rating)
{
	global $db;

	$sql = "update mer_order set rating=? where gID=?"; //SQL中的 ? 代表未來要用變數綁定進去的地方
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "si", $rating, $gID);
	mysqli_stmt_execute($stmt);  //執行SQL
	return True;
}
?>