<?php

if (isset($_POST['updateCustomer'])) {

    $customerId = validate($_POST['id']); 

    $customerData = getById('tbl_customers_penb', $customerId);
    if ($customerData['status'] != 200) {
        redirect('customers-edit.php?id='.$customerId,'Please fill in required fields.');
    }


    // Validate other fields as needed
    $type = validate($_POST['type']);
    $plate = validate($_POST['plate']);
    $name = validate($_POST['name']);
    $gender = validate($_POST['gender']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    $address1 = validate($_POST['address1']);
    $address2 = validate($_POST['address2']);
    $city = validate($_POST['city']);
    $poscode = validate($_POST['poscode']);
    $state = validate($_POST['state']);

    $EmailCheckQuery = "SELECT * FROM tbl_customers_penb WHERE fld_customer_email = '$email' AND id != '$customerId'";
    $checkResult = mysqli_query($conn, $EmailCheckQuery);
    if ($checkResult) {
        if (mysqli_num_rows($checkResult) > 0) {
        
        redirect('customers-edit.php?id=' .$customerId, 'Email already used by another user.');
        }
    }


    if ($name != '' && $email != '') {

        $data = [
            'fld_customer_type' => $type,
            'plate_number' => $plate,
            'fld_customer_name' => $name,
            'fld_customer_gender' => $gender,
            'fld_customer_phone' => $phone,
            'fld_customer_email' => $email,
            'fld_customer_address1' => $address1,
            'fld_customer_address2' => $address2,
            'fld_customer_city' => $city,
            'fld_customer_poscode' => $poscode,
            'fld_customer_state' => $state
        ];
        $result = update('tbl_customers_penb', $customerId, $data);
        if ($result) {
            redirect('customers-edit.php?id='.$customerId,'Customer Updated Successfully!');
        }
        else{
        redirect('customers-edit.php?id='.$customerId,'Something went   wrong');

        }
    }
    else{
        redirect('customers-create.php','Please fill required fields');

        }
}


if (isset($_POST['addItem'])) {

    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $checkProduct = mysqli_query($conn, "SELECT * FROM tbl_products_penb WHERE id= '$productId' LIMIT 1");
    if ($checkProduct) {
        if(mysqli_num_rows($checkProduct) > 0){
            $row = mysqli_fetch_assoc($checkProduct);
            if ($row['fld_product_quantity'] < $quantity) {
                // If requested quantity is higher than available stock, set the available quantity and redirect with error message
                $availableQuantity = $row['fld_product_quantity'];
                redirect('orders-create.php', 'Only '.$availableQuantity.' quantity available');
                
            }

            // If quantity is available, add the product to session and redirect with success message
            $productData = [
                'product_id' => $row['id'],
                'name' => $row['fld_product_name'],
                'price' => $row['fld_product_price'],
                'des' => $row['fld_product_desc'],
                'quantity' => $quantity,
                'image' => $row['fld_product_image'],
            ];

            if (!in_array($row['id'], $_SESSION['productItemIds'])) {

                array_push($_SESSION['productItemIds'], $row['id']);
                array_push($_SESSION['productItems'], $productData);

            } else {
                
                foreach ($_SESSION['productItems'] as $key => $productSessionItem) {
                    if ($productSessionItem['product_id'] == $row['id']) {
                        
                        $newQuantity = $productSessionItem['quantity'] + $quantity;

                        $productData = [
                                    'product_id' => $row['id'],
                                    'name' => $row['fld_product_name'],
                                    'price' => $row['fld_product_price'],
                                    'des' => $row['fld_product_desc'],
                                    'quantity' => $newQuantity,
                                    'image' => $row['fld_product_image'],
                        ];
                        $_SESSION['productItems'][$key] = $productData;
                    }
                }
            }
            
            redirect('orders-create.php', 'Item Added '. $row['fld_product_name']);

        } else {
            // If no such product found, redirect with error message
            redirect('orders-create.php', 'No such product found!');
        }

    } else {
        // If query fails, redirect with error message
        redirect('orders-create.php', 'Something went wrong!');
    }
}


?>