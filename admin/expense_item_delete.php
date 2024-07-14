<?php
require '../config/function.php';

$paramResult = checkParamId('index');
$invoice = validate($_GET['invoice']);

if (is_numeric($paramResult)) {
	
	$indexValue = validate($paramResult);

	if (isset($_SESSION['expenseItems']) && isset($_SESSION['expenseItemIds']) ) {

		unset($_SESSION['expenseItems'][$indexValue]);
		unset($_SESSION['expenseItemIds'][$indexValue]);

		redirect('expenses-create.php?invoice=' . $invoice,'expense removed');

	}
	else{
		redirect('expenses-create.php','There is no item');

	}
}
else{
	redirect('expenses-create.php','param not numeric');
}

?>