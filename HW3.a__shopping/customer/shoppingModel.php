<?php
require('dbconfig.php');

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

	$rows = array(); //要回傳的陣列
	while($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r; //將此筆資料新增到陣列中
	}
	return $rows;
}

function addItem($id,$name,$price) {
    global $db;
    $sql = "select * from customer where id= $id;";
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
		$sql = "insert into customer (id, goods, price, amount, total) values (?, ?, ?, ?, ?)"; //SQL中的 ? 代表未來要用變數綁定進去的地方
		$stmt = mysqli_prepare($db, $sql); //prepare sql statement
		mysqli_stmt_bind_param($stmt, "isiii",$id,$name,$price,$amount,$price); //bind parameters with variables, with types "sss":string, string ,string
		mysqli_stmt_execute($stmt);  //執行SQL
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