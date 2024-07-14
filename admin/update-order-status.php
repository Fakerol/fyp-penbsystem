<?php include('../config/function.php');


if(isset($_POST["updateStatusBtn"])) {
    $invoice = validate($_POST['invoice_no']);
    $orderStatus = validate($_POST['order_status']);

    if ($invoice != '') {
    
	    $data = [
	    	'order_status' => $orderStatus,
	    ];

	    $result = updateStatus('tbl_order_penb', $invoice, $data); 

	    if($result) {
	        jsonResponse(200, 'success', 'Order Status Updated Successfully!');
	    } else {
	        jsonResponse(404, 'warning', 'Failed to update order');
	    }
	} else {
    jsonResponse(500, 'error', 'Something went wrong');
}

}
?>
