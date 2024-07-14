<?php
include('../config/function.php');

if (!isset($_SESSION['productItemIds'])) {
    $_SESSION['productItemIds'] = [];
}

if (!isset($_SESSION['productItems'])) {
    $_SESSION['productItems'] = [];
}

if (!isset($_SESSION['chargeItemIds'])) {
    $_SESSION['chargeItemIds'] = [];
}

if (!isset($_SESSION['chargeItems'])) {
    $_SESSION['chargeItems'] = [];
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

if (isset($_POST['addCharge'])) {

    $chargeId = validate($_POST['chargeId']);
    $quantity = validate($_POST['quantity']);

    $checkCharge = mysqli_query($conn, "SELECT * FROM tbl_labourcharge_penb WHERE id= '$chargeId' LIMIT 1");
    if ($checkCharge) {
        if(mysqli_num_rows($checkCharge) > 0){
            $row = mysqli_fetch_assoc($checkCharge);
            if ($row['charge_quantity'] < $quantity) {
                // If requested quantity is higher than available stock, set the available quantity and redirect with error message
                $availableQuantity = $row['charge_quantity'];
                redirect('orders-create.php', 'Only '.$availableQuantity.' quantity available');
            }

            // If quantity is available, add the charges to session and redirect with success message
            $chargeData = [
                'chargeId' => $row['id'],
                'charge_desc' => $row['charge_desc'],
                'charge_price' => $row['charge_price'],
                'charge_quantity' => $quantity,
            ];

            if (!isset($_SESSION['chargeItems'])) {
                $_SESSION['chargeItems'] = [];
                $_SESSION['chargeItemIds'] = [];
            }

            $chargeExists = false;
            foreach ($_SESSION['chargeItems'] as $key => $sessionItem) {
                if ($sessionItem['chargeId'] == $row['id']) {
                    $newQuantity = $sessionItem['charge_quantity'] + $quantity;
                    $_SESSION['chargeItems'][$key]['charge_quantity'] = $newQuantity;
                    $chargeExists = true;
                    break;
                }
            }

            if (!$chargeExists) {
                $_SESSION['chargeItems'][] = $chargeData;
                $_SESSION['chargeItemIds'][] = $row['id'];
            }

            redirect('orders-create.php', 'Item Added '. $row['charge_desc']);
        } else {
            redirect('orders-create.php', 'No such charge found!');
        }
    } else {
        redirect('orders-create.php', 'Something went wrong!');
    }
}



if (isset($_POST['addNewCharge'])) {
    // Assuming validate function exists
    $chargeId = validate($_POST['chargeId']);
    $chargeDesc = validate($_POST['charge_desc']);
    $chargePrice = validate($_POST['charge_price']);
    $chargeQuantity = 200;
    // Validate other fields as needed

    // Insert new product into the database
    $insertQuery = "INSERT INTO tbl_labourcharge_penb (id, charge_desc, charge_price, charge_quantity) VALUES ('$chargeId', '$chargeDesc', '$chargePrice', '$chargeQuantity')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        // Initialize session arrays if they are not set
        if (!isset($_SESSION['chargeItemIds'])) {
            $_SESSION['chargeItemIds'] = [];
        }
        if (!isset($_SESSION['chargeItems'])) {
            $_SESSION['chargeItems'] = [];
        }

        // Add the new product to session
        $chargeData = [
            'chargeId' => $chargeId,
            'charge_desc' => $chargeDesc,
            'charge_price' => $chargePrice,
            'charge_quantity' => 1, // You can set default quantity here
        ];

        array_push($_SESSION['chargeItemIds'], $chargeId);
        array_push($_SESSION['chargeItems'], $chargeData);

        redirect('orders-create.php', 'New Labour Charge added successfully');
    } else {
        redirect('orders-create.php', 'Failed to add new labour charge');
    }
}


if (isset($_POST['productIncDec'])) {
    
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $flag = false;
    foreach ($_SESSION['productItems'] as $key => $item) {
        if ($item['product_id'] == $productId) {

            $flag = true;
            $_SESSION['productItems'][$key]['quantity'] = $quantity;
        }
    }

    if($flag) {

        jsonResponse(200, 'success', 'Quantity Updated');

    }else{

        jsonResponse(500, 'error', 'Something went wrong.  Please refresh');


    }
}

if (isset($_POST['chargeIncDec'])) {
    $chargeId = validate($_POST['chargeId']);
    $quantity = validate($_POST['charge_quantity']);

    $flag = false;
    foreach ($_SESSION['chargeItems'] as $key => $item) {
        if ($item['chargeId'] == $chargeId) {
            $flag = true;
            $_SESSION['chargeItems'][$key]['charge_quantity'] = $quantity;
        }
    }

    if($flag) {
        jsonResponse(200, 'success', 'Quantity Updated');
    } else {
        jsonResponse(500, 'error', 'Something went wrong. Please refresh');
    }
}



if (isset($_POST['addNewProduct'])) {

    $productId = validate($_POST['productId']);
    $productName = validate($_POST['product_name']);
    $productPrice = validate($_POST['product_price']);
    $productDescription = validate($_POST['product_description']);
    $productQuantity = 200;
    // Validate other fields as needed

    // Insert new product into the database
    $insertQuery = "INSERT INTO tbl_products_penb (id, fld_product_name, fld_product_price, fld_product_desc, fld_product_quantity) VALUES ('$productId', '$productName', '$productPrice', '$productDescription', '$productQuantity')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
       

        // Add the new product to session
        $productData = [
            'product_id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'des' => $productDescription,
            'quantity' => 1, // You can set default quantity here
            'image' => '', // You can set image path here if needed
        ];

        array_push($_SESSION['productItemIds'], $productId);
        array_push($_SESSION['productItems'], $productData);

        redirect('orders-create.php', 'New product added successfully');
    } else {
        redirect('orders-create.php', 'Failed to add new product');
    }
}



if (isset($_POST['saveChangesBtn'])) {
    $itemId = validate($_POST['iid']);
    $itemName = validate($_POST['name']);
    $itemPrice = validate($_POST['price']);

    if ($itemId != '') {
        $data = [
            'fld_product_name' => $itemName,
            'fld_product_price' => $itemPrice,
        ];

        $result = update('tbl_products_penb', $itemId, $data);

        if ($result) {
            foreach ($_SESSION['productItems'] as $key => $item) {
                if ($item['product_id'] == $itemId) {
                    $_SESSION['productItems'][$key]['name'] = $itemName;
                    $_SESSION['productItems'][$key]['price'] = $itemPrice;
                }
            }

            jsonResponse(200, 'success', 'Item Updated Successfully!');
            //window.location.href = 'orders-create.php';
            // Use JavaScript to refresh the page after saving changes
            
        } else {
            jsonResponse(404, 'warning', 'Failed to update the item');
        }
    } else {
        jsonResponse(500, 'error', 'Something went wrong');
    }
}

if (isset($_POST['saveChargeChanges'])) {
    $chargeId = validate($_POST['cid']);
    $chargeName = validate($_POST['name']);
    $chargePrice = validate($_POST['price']);

    if ($chargeId != '') {
        $data = [
            'charge_desc' => $chargeName,
            'charge_price' => $chargePrice,
        ];

        $result = update('tbl_labourcharge_penb', $chargeId, $data);

        if ($result) {
            foreach ($_SESSION['chargeItems'] as $key => $item) {
                if ($item['chargeId'] == $chargeId) { // Corrected variable name from $itemId to $chargeId
                    $_SESSION['chargeItems'][$key]['charge_desc'] = $chargeName; // Corrected variable name from $itemName to $chargeName
                    $_SESSION['chargeItems'][$key]['charge_price'] = $chargePrice; // Corrected variable name from $itemPrice to $chargePrice
                }
            }

            jsonResponse(200, 'success', 'Item Updated Successfully!');
        } else {
            jsonResponse(404, 'warning', 'Failed to update the item');
        }
    } else {
        jsonResponse(500, 'error', 'Something went wrong');
    }
}


if (isset($_POST['proceedToPlaceBtn'])) {

    $plate = validate($_POST['plate']);
    $payment_mode = validate($_POST['payment_mode']);
    $order_status = validate($_POST['order_status']);
   

    // Check customer
    $checkCustomer = mysqli_query($conn, "SELECT * FROM tbl_customers_penb WHERE plate_number='$plate' LIMIT 1");
    if ($checkCustomer) {
        if (mysqli_num_rows($checkCustomer) > 0) {
            $_SESSION['invoice_no'] = "INV-" . rand(111111, 999999);
            $_SESSION['plate'] = $plate;
            $_SESSION['payment_mode'] = $payment_mode;
            $_SESSION['order_status'] = $order_status;
           
            jsonResponse(200, 'success', 'Customer Found');
        } else {
            $_SESSION['plate'] = $plate;
            jsonResponse(404, 'warning', 'Customer Not Found');
        }
    } else {
        jsonResponse(500, 'error', 'Something went wrong');
    }
}

if(isset($_POST['saveCustomerBtn'])) {

    $cid = validate($_POST['cid']);
    $name = validate($_POST['name']);
    $plate = validate($_POST['plate']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    $address1 = validate($_POST['address1']);
    $address2 = $_POST['address2']; // No need to validate if empty
    $city = validate($_POST['city']);
    $poscode = validate($_POST['poscode']);
    $state = validate($_POST['state']);

    if($cid != '' && $name != '' && $plate != '' && $address1 != '' && $city != '' && $poscode != '' && $state != ''){

        $data = [
            'id' => $cid,
            'plate_number' => $plate,
            'fld_customer_name' => $name,
            'fld_customer_phone' => $phone, // Corrected typo here
            'fld_customer_email' => $email,
            'fld_customer_address1' => $address1,
            'fld_customer_address2' => $address2,
            'fld_customer_city' => $city,
            'fld_customer_poscode' => $poscode,
            'fld_customer_state' => $state,
        ];

        $result = insert('tbl_customers_penb', $data);

        if($result){
            jsonResponse(200,'success','Customer Created successfully');
        } else {
            jsonResponse(500,'error','Something went wrong');
        }

    } else {
        jsonResponse(422,'warning','Please fill in required fields');
    }
}



if (isset($_POST['saveOrder'])) {

    // Validate and assign session variables
    $invoice_no = validate($_SESSION['invoice_no']);
    $plate = validate($_SESSION['plate']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $order_status = validate($_SESSION['order_status']);
    $order_place_by_id = $_SESSION['loggedInUser']['user_id'];
    
    // Check if the customer exists
    $checkCustomer = mysqli_query($conn, "SELECT * FROM tbl_customers_penb WHERE plate_number='$plate' LIMIT 1");
    if (!$checkCustomer) {
        jsonResponse(500, 'error', 'Something went wrong');
        exit; // Make sure to exit after sending a response
    }
    
    if (mysqli_num_rows($checkCustomer) > 0) {
        $customerData = mysqli_fetch_assoc($checkCustomer);

        // Check if there are products in the session
        if (!isset($_SESSION['productItems'])) {
            jsonResponse(400, 'warning', 'No item selected');
            exit; // Make sure to exit after sending a response
        }

        $sessionProducts = $_SESSION['productItems'];
        $sessionCharges = $_SESSION['chargeItems'] ?? []; // Initialize as an empty array if not set

        $totalAmount = 0;
        $totalLabourAmount = 0;
        $grandAmount = 0;

        // Calculate the total amount for products
        foreach ($sessionProducts as $amtItem) {
            $totalAmount += $amtItem['price'] * $amtItem['quantity'];
        }

        // Calculate the total amount for labour charges
        foreach ($sessionCharges as $amtCharge) {
            $totalLabourAmount += $amtCharge['charge_price'] * $amtCharge['charge_quantity'];
        }

        // Calculate the grand total amount
        $grandAmount = $totalAmount + $totalLabourAmount;

        // Prepare data for the order
        $data = [
            'customer_id' => $customerData['id'],
            'invoice_no' => $invoice_no,
            'total_amount' => $grandAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'booked',
            'payment_mode' => $payment_mode,
            'order_status' => $order_status,
            'order_place_by_id' => $order_place_by_id
        ];
        $result = insert('tbl_order_penb', $data);
        if (!$result) {
            jsonResponse(500, 'error', 'Failed to place the order');
            exit;
        }

        $lastOrderId = mysqli_insert_id($conn);

        // Insert order items for products
        foreach ($sessionProducts as $prodItem) {
            $productId = $prodItem['product_id'];
            $price = $prodItem['price'];
            $quantity = $prodItem['quantity'];

            $dataOrderItem = [
                'order_id' => $lastOrderId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
            ];
            $orderItemQuery = insert('tbl_order_items', $dataOrderItem);

            // Check for booked quantity and update the stock quantity
            $checkProductQuantityQuery = mysqli_query($conn, "SELECT * FROM tbl_products_penb WHERE id='$productId'");
            if ($checkProductQuantityQuery && mysqli_num_rows($checkProductQuantityQuery) > 0) {
                $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
                $totalProductQuantity = $productQtyData['fld_product_quantity'] - $quantity;

                $dataUpdate = [
                    'fld_product_quantity' => $totalProductQuantity
                ];
                $updateProductQty = update('tbl_products_penb', $productId, $dataUpdate);
            }
        }

        // Insert order items for charges
        foreach ($sessionCharges as $chargeItem) {
            $chargeId = $chargeItem['chargeId'];
            $price = $chargeItem['charge_price'];
            $quantity = $chargeItem['charge_quantity'];

            $dataOrderItem = [
                'order_id' => $lastOrderId,
                'product_id' => $chargeId,
                'price' => $price,
                'quantity' => $quantity,
            ];
            $orderItemQuery = insert('tbl_order_items', $dataOrderItem);
        }

        // Clear session variables
        unset($_SESSION['productItemIds']);
        unset($_SESSION['productItems']);
        unset($_SESSION['chargeItemIds']);
        unset($_SESSION['chargeItems']);
        unset($_SESSION['invoice_no']);
        unset($_SESSION['plate']);
        unset($_SESSION['payment_mode']);
        unset($_SESSION['order_status']);
        

        jsonResponse(200, 'success', 'Order Placed Successfully');
    } else {
        jsonResponse(404, 'warning', 'No customer found');
    }
}




?>
