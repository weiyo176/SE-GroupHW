<?php
require('shoppingModel.php');

$act=$_REQUEST['act'];
switch ($act) {
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
case "delItem":
	$id=(int)$_REQUEST['id']; //$_GET, $_REQUEST
	//verify
	delItem($id);
	return;
default:
  
}

?>