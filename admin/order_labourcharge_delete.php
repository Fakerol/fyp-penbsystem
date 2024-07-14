<?php
require '../config/function.php';

$paramResult = checkParamId('index');

if (is_numeric($paramResult)) {
	
	$indexValue = validate($paramResult);

	if (isset($_SESSION['chargeItems']) && isset($_SESSION['chargeItemIds']) ) {

		unset($_SESSION['chargeItems'][$indexValue]);
		unset($_SESSION['chargeItemIds'][$indexValue]);

		redirect('orders-create.php','Labour Charge removed');

	}
	else{
		redirect('orders-create.php','There is no labour charge');

	}
}
else{
	redirect('orders-create.php','param not numeric');
}

?>