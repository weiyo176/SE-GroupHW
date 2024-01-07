<?php
require('shoppingModel.php');

$act=$_REQUEST['act'];
switch ($act) {
  case "register":
    $email = $_REQUEST['email'];
    $password = $_REQUEST['pwd'];
    $confirm_password = $_REQUEST['confirm_password'];
    $name = $_REQUEST['name'];
    // 驗證步驟...
    $items =register($email, $password, $confirm_password, $name);
    echo json_encode($items);
    return;
  case "blogin":
    $email = $_REQUEST['email'];
    $password = $_REQUEST['pwd'];
    $items=login($email,$password); 
    echo json_encode($items);
    return;
  case "checkStatus":
    $id=(int)$_REQUEST['id'];
    $rule=(int)$_REQUEST['rule'];
    checkStatus($id,$rule);
    return;
  case "orderList":
    $bID=(int)$_REQUEST['bID'];
    $rule=(int)$_REQUEST['rule'];
    $items=getOrderList($rule,$bID);
    echo json_encode($items);
    return;  
case "listitem":
  $items=getItemList();
  echo json_encode($items);
  return;  
case "listcart":
  $customer=getCartList();
  echo json_encode($customer);
  return;  
case "addItem":
    $id = $_POST['id'];  
    $name = $_POST['name'];  
    $price = $_POST['price'];
	$userID = $_POST['userID']; 	
    // 驗證步驟...
    addItem($id, $name, $price, $userID);
    return;
case "minusItem":
    $gID=(int)$_REQUEST['gID']; //$_GET, $_REQUEST
    $ifplus=(int)$_REQUEST['ifplus'];
    //verify
    minusItem($gID,$ifplus);
    return;
case "delItem":
	$gID=(int)$_REQUEST['gID']; //$_GET, $_REQUEST
	//verify
	delItem($gID);
	return;
case "listJob":
  $bID=(int)$_REQUEST['bID'];
  $jobs=getJobList($bID);
  echo json_encode($jobs);
  return;  
case "addJob":	
  $bID=(int)$_REQUEST['bID'];
	$jsonStr = $_POST['dat'];
	$job = json_decode($jsonStr);
	//should verify first
	addJob($job->name,$job->price,$job->description,$job->id,$bID);
	return;
case "delJob":
	$id=(int)$_REQUEST['id']; //$_GET, $_REQUEST
	//verify
	delJob($id);
	return;
case "checkout":
    CheckOut();
    return;	
case "checkmyorder":
    $cID = $_REQUEST['cID']; // 獲取前端傳遞的cID參數
    $myorder = getmyorder($cID);
    echo json_encode($myorder);
    return;
case "submitRating":
	$gID = $_POST['gID']; 
	$rating = $_POST['rating']; 
	SubmitRating($gID, $rating);
	return;  
default:  

  
}

?>