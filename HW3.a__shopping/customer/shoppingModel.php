<?php
require('dbconfig.php');

function checkStatus($oID,$rule) {
	global $db;
	//if rule=1, select all status=未處理 or status=處理中
	if ($rule == 1)
		$sql = "select * from customer where oID=$oID && (status = \"未處理\" or status = \"處理中\");";
	//if rule=2, select all status=處理中
	else if ($rule == 2)
		$sql = "select * from customer where oID=$oID && status = \"寄送中\";";
	$stmt = mysqli_prepare($db, $sql ); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$status = "處理中";
	$status2 = "寄送中";
	$status3 = "已寄送";
	// 如果status是未處理將資料庫update成處理中，如果是處理中則設成寄送中
	while($r = mysqli_fetch_assoc($result)) {
		if ($r['status'] == "未處理") {
			$updateSql = "UPDATE customer SET status = ? WHERE gID = ?";
			$updateStmt = mysqli_prepare($db, $updateSql);
			mysqli_stmt_bind_param($updateStmt, "si", $status, $r['gID']);
			mysqli_stmt_execute($updateStmt);
		}
		else if ($r['status'] == "處理中") {
			$updateSql = "UPDATE customer SET status = ? WHERE gID = ?";
			$updateStmt = mysqli_prepare($db, $updateSql);
			mysqli_stmt_bind_param($updateStmt, "si", $status2, $r['gID']);
			mysqli_stmt_execute($updateStmt);
		}
		else if ($r['status'] == "寄送中") {
			$updateSql = "UPDATE customer SET status = ? WHERE gID = ?";
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
function getOrderList($rule) {
	global $db;
	if ($rule == 1)
		$sql = "select * from customer where status = \"未處理\" or status = \"處理中\";";
	else if ($rule == 2)
		$sql = "select * from customer where status = \"寄送中\";";
	// $sql = "select * from customer where status = \"未處理\" or status = \"處理中\";";
	$stmt = mysqli_prepare($db, $sql ); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果

	$rows = array(); //要回傳的陣列
	while($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	return $rows;
}
function getItemList() {
	global $db;
	$sql = "select * from items;";
	$stmt = mysqli_prepare($db, $sql ); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$rows = array(); //要回傳的陣列
	while($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	return $rows;
}
function getCartList() {
	global $db;
	$sql = "select * from customer;";
	$stmt = mysqli_prepare($db, $sql ); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$totalPrice=0;
	$rows = array(); //要回傳的陣列
	while($r = mysqli_fetch_assoc($result)) {
		$totalPrice += $r['total'];
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	$newData = array(
		'goods' => 'TotalPrice',
		'total' => $totalPrice
	);
	$rows[] = $newData;
	return $rows;
}

function addItem($id,$name,$price) {
    global $db;
    $sql = "select * from customer where id= $id && cID = 1;";
    $stmt = mysqli_prepare($db, $sql); //prepare sql statement
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); //取得查詢結果
    $row=mysqli_fetch_assoc($result);
    if ($row) {
        $newAmount = $row['amount'] + 1;
        $newTotal = $newAmount * $row['price'];
        $updateSql = "UPDATE customer SET amount = ?, total = ? WHERE id = ?";
        $updateStmt = mysqli_prepare($db, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "iii", $newAmount, $newTotal, $id);
        mysqli_stmt_execute($updateStmt);
    }
    else {
		$amount=1;
		$cID=1;
		$sql = "insert into customer (cID,id, goods, price, amount, total) values (?, ?, ?, ?, ?, ?)"; //SQL中的 ? 代表未來要用變數綁定進去的地方
		$stmt = mysqli_prepare($db, $sql); //prepare sql statement
		mysqli_stmt_bind_param($stmt, "iisiii",$cID,$id,$name,$price,$amount,$price); //bind parameters with variables, with types "sss":string, string ,string
		mysqli_stmt_execute($stmt);  //執行SQL
    }
    return True;
}

function minusItem($id,$ifplus) {
	global $db;

	$sql = "select * from customer where id= $id;";
    $stmt = mysqli_prepare($db, $sql); //prepare sql statement
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); //取得查詢結果
	$row=mysqli_fetch_assoc($result);
	if ($row && $ifplus) 
        $newAmount = $row['amount'] + 1;
	else 
		$newAmount = $row['amount'] - 1;
	if ($newAmount >= 0){
		$newTotal = $newAmount * $row['price'];
		$updateSql = "UPDATE customer SET amount = ?, total = ? WHERE id = ?";
		$updateStmt = mysqli_prepare($db, $updateSql);
		mysqli_stmt_bind_param($updateStmt, "iii", $newAmount, $newTotal, $id);
		mysqli_stmt_execute($updateStmt);
	}
	return True;
}
function delItem($id) {
	global $db;

	$sql = "delete from customer where id=?;"; //SQL中的 ? 代表未來要用變數綁定進去的地方
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "i", $id); //bind parameters with variables, with types "sss":string, string ,string
	mysqli_stmt_execute($stmt);  //執行SQL
	return True;
}
function getJobList() {
	global $db;
	$sql = "select * from items;";
	$stmt = mysqli_prepare($db, $sql ); //precompile sql指令，建立statement 物件，以便執行SQL
	mysqli_stmt_execute($stmt); //執行SQL
	$result = mysqli_stmt_get_result($stmt); //取得查詢結果

	$rows = array(); //要回傳的陣列
	while($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	return $rows;
}

function addJob($name,$price,$description,$jobID) {
	global $db;
	if($jobID>0) {
		$sql = "update items set name=?, price=?, description=? where id=?"; //SQL中的 ? 代表未來要用變數綁定進去的地方
		$stmt = mysqli_prepare($db, $sql); //prepare sql statement
		mysqli_stmt_bind_param($stmt, "sssi", $name, $price,$description,$jobID); //bind parameters with variables, with types "sss":string, string ,string
	} else {
		$sql = "insert into items (name, price, description) values (?, ?, ?)"; //SQL中的 ? 代表未來要用變數綁定進去的地方
		$stmt = mysqli_prepare($db, $sql); //prepare sql statement
		mysqli_stmt_bind_param($stmt, "sss", $name, $price,$description); //bind parameters with variables, with types "sss":string, string ,string
	}
	mysqli_stmt_execute($stmt);  //執行SQL
	return True;
}

function updateJob($id, $name,$price,$description) {
	echo $id, $name,$price,$description;
	return;
}

function delJob($id) {
	global $db;

	$sql = "delete from items where id=?;"; //SQL中的 ? 代表未來要用變數綁定進去的地方
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "i", $id); //bind parameters with variables, with types "sss":string, string ,string
	mysqli_stmt_execute($stmt);  //執行SQL
	return True;
}
?>