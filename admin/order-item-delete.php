<?php
require '../config/function.php';

$paramResult = checkParamId('index');

if (is_numeric($paramResult)) {
	
	$indexValue = validate($paramResult);

	if (isset($_SESSION['productItems']) && isset($_SESSION['productItemIds']) ) {

		unset($_SESSION['productItems'][$indexValue]);
		unset($_SESSION['productItemIds'][$indexValue]);

		redirect('orders-create.php','Item removed');

	}
	else{
		redirect('orders-create.php','There is no item');

	}
}
else{
	redirect('orders-create.php','param not numeric');
}

?>