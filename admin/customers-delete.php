<?php

require '../config/function.php'; // Include the function file

$paraResultId = checkParamId('id');


if (is_numeric($paraResultId)) {

    $customerId = validate($paraResultId);
    echo $customerId;

    $customer = getById('tbl_customers_penb', $customerId);

    if ($customer['status'] == 200) {

       $customerDeleteRes = delete('tbl_customers_penb', $customerId);
       if ($customerDeleteRes) {

        redirect('customers.php', 'Customer Data Deleted Successfully.');
           
       }
       else{

        redirect('customers.php', 'Something Went Wrong!');

       }
    }

    else{
        redirect('customers.php', $customer['message']);
    }
    
} else {
    redirect('customer.php', 'Something Went Wrong!');
}

?>
