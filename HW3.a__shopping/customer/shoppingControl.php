<?php
require('shoppingModel.php');

$act=$_REQUEST['act'];
switch ($act) {
  case "checkStatus":
    $id=(int)$_REQUEST['id'];
    $rule=(int)$_REQUEST['rule'];
    checkStatus($id,$rule);
    return;
  case "orderList":
    $rule=(int)$_REQUEST['rule'];
    $items=getOrderList($rule);
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
    // 驗證步驟...
    addItem($id, $name, $price);
    return;
    case "minusItem":
      $id=(int)$_REQUEST['id']; //$_GET, $_REQUEST
      $ifplus=(int)$_REQUEST['ifplus'];
      //verify
      minusItem($id,$ifplus);
      return;
case "delItem":
	$id=(int)$_REQUEST['id']; //$_GET, $_REQUEST
	//verify
	delItem($id);
	return;
case "listJob":
  $jobs=getJobList();
  echo json_encode($jobs);
  return;  
case "addJob":
	
	$jsonStr = $_POST['dat'];
	$job = json_decode($jsonStr);
	//should verify first
	addJob($job->name,$job->price,$job->description,$job->id);
	return;
case "delJob":
	$id=(int)$_REQUEST['id']; //$_GET, $_REQUEST
	//verify
	delJob($id);
	return;
default:  

  
}

?>