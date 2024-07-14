<?php

include('../config/function.php');

if (isset($_POST['saveAdmin'])) {

	$id = validate($_POST['id']);
	$name = validate($_POST['name']);
	$phone = validate($_POST['phone']);
	$password = validate($_POST['password']);
	$email = validate($_POST['email']);
	$is_ban = validate($_POST['is_ban']) == true ? 1:0;

	if ($name != '' && $name != '' && $email != '' && $password != '') {

		$emailCheck = mysqli_query($conn, "SELECT * FROM tbl_admins_penb WHERE email='$email'");
		if ($emailCheck) {
			if (mysqli_num_rows($emailCheck) > 0 ) {
				redirect('admins-create.php','Email already been used by another user.');
			}
		}

		$bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

		$data = [
			'id' => $id,
			'name' => $name,
			'phone' => $phone,
			'password' => $bcrypt_password,
			'email' => $email,
			'is_ban' => $is_ban
		];
		$result = insert('tbl_admins_penb', $data);
		if ($result) {
			redirect('admins.php','Admin Created Successfully!');
		}else{
			redirect('admins-create.php','Something went wrong!');
		}

	}else{
		redirect('admins-create.php','Please fill in required fields.');

	}

}

if (isset($_POST['saveCustomer'])) {
	
	$id = validate($_POST['id']);
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
	

	if ($id != '') {

		$plateCheck = mysqli_query($conn, "SELECT * FROM tbl_customers_penb WHERE plate_number='$plate'");
		if ($plateCheck) {
			if (mysqli_num_rows($plateCheck) > 0 ) {
				redirect('customers-create.php','This plate number is already registered.');
			}
		}

		
		$data = [
			'id' => $id,
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
		$result = insert('tbl_customers_penb', $data);
		if ($result) {
			redirect('customers.php','Customer Created Successfully!');
		}else{
			redirect('customers-create.php','Something went wrong!');
		}

	}else{
		redirect('customers-create.php','Please fill in required fields.');

	}

}

if (isset($_POST['saveProduct'])) {
	
	$id = validate($_POST['id']);
	$name = validate($_POST['name']);
	$price = validate($_POST['price']);
	$des = validate($_POST['des']);
	$quantity = validate($_POST['quantity']);

	// Check if image is uploaded
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES["image"]["name"];
            $tmpName = $_FILES["image"]["tmp_name"];
            $newImageName = uniqid() . '_' . $fileName;
            move_uploaded_file($tmpName, 'pictures/' . $newImageName);
        } else {
            // Handle case when no image is uploaded or upload error occurred
            $newImageName = ''; // Set a default image name or handle the error as needed
        }

	if ($id != '') {

		$idCheck = mysqli_query($conn, "SELECT * FROM tbl_products_penb WHERE id='$id'");

		if ($idCheck) {
			if (mysqli_num_rows($idCheck) > 0 ) {
				redirect('products-create.php','Product ID already been used.');
			}
		}

		
		$data = [
			'id' => $id,
			'fld_product_name' => $name,
			'fld_product_price' => $price,
			'fld_product_desc' => $des,
			'fld_product_quantity' => $quantity,
			'fld_product_image' => $newImageName,
			
		];
		$result = insert('tbl_products_penb', $data);

		if ($result) {
			redirect('products.php','Product Created Successfully!');
		}else{
			redirect('products-create.php','Something went wrong!');
		}

	}else{
		redirect('products-create.php','Please fill in required fields.');

	}

}


if (isset($_POST['saveCharge'])) {
	
	$id = validate($_POST['id']);
	$desc = validate($_POST['desc']);
	$price = validate($_POST['price']);
	$quantity = validate($_POST['quantity']);
	
	
	if ($id != '') {

		$idCheck = mysqli_query($conn, "SELECT * FROM tbl_labourcharge_penb WHERE id='$id'");

		if ($idCheck) {
			if (mysqli_num_rows($idCheck) > 0 ) {
				redirect('charge-create.php','Charge ID already been used.');
			}
		}
	
		$data = [
			'id' => $id,
			'charge_desc' => $desc,
			'charge_price' => $price,
			'charge_quantity' => $quantity

		];
		$result = insert('tbl_labourcharge_penb', $data);
		if ($result) {
			redirect('labour.php','Labour Charge Created Successfully!');
		}else{
			redirect('charge-create.php','Something went wrong!');
		}

	}else{
		redirect('charge-create.php','Please fill in required fields.');

	}

}



if (isset($_POST['updateAdmin'])) {

	$adminId = validate($_POST['id']);

	$adminData = getById('tbl_admins_penb', $adminId);
	if ($adminData['status'] != 200) {
		redirect('admins-edit.php?id='.$adminId,'Please fill in required fields.');
	}

	$name = validate($_POST['name']);
	$phone = validate($_POST['phone']);
	$password = validate($_POST['password']);
	$email = validate($_POST['email']);
	$is_ban = validate($_POST['is_ban']) == true ? 1 : 0;

	$EmailCheckQuery = "SELECT * FROM tbl_admins_penb WHERE email = '$email' AND id != '$adminId'";
	$checkResult = mysqli_query($conn, $EmailCheckQuery);
	if ($checkResult) {
		if (mysqli_num_rows($checkResult) > 0) {
			redirect('admins-edit.php?id=' . $adminId, 'Email already used by another user.');
		}
	}

	if ($password != '') {
		$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
	} else {
		$hashedPassword = $adminData['data']['password'];
	}

	if ($name != '' && $email != '') {
		$data = [
			'name' => $name,
			'phone' => $phone,
			'password' => $hashedPassword, // Use the correct variable here
			'email' => $email,
			'is_ban' => $is_ban
		];
		$result = update('tbl_admins_penb', $adminId, $data);
		if ($result) {
			redirect('admins-edit.php?id=' . $adminId, 'Admin Updated Successfully!');
		} else {
			redirect('admins-edit.php?id=' . $adminId, 'Something went wrong');
		}
	} else {
		redirect('admins-edit.php?id=' . $adminId, 'Please fill required fields');
	}
}


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
		redirect('customers-edit.php?id='.$customerId,'Something went	wrong');

		}
	}
	else{
		redirect('customers-create.php','Please fill required fields');

		}
}

if (isset($_POST['updateProduct'])) {

    $productId = validate($_POST['id']); 

    $productData = getById('tbl_products_penb', $productId);
    if ($productData['status'] != 200) {
        redirect('products-edit.php?id='.$productId,'Please fill in required fields.');
    }

    // Validate other fields as needed
    $name = validate($_POST['fld_product_name']);
    $price = validate($_POST['fld_product_price']);
    $desc = validate($_POST['fld_product_desc']);
    $quantity = validate($_POST['fld_product_quantity']);

    $idCheckQuery = "SELECT * FROM tbl_products_penb WHERE id = '$productId'";
    $checkResult = mysqli_query($conn, $idCheckQuery);
    if ($checkResult) {
        if (mysqli_num_rows($checkResult) < 0) {
            redirect('products-edit.php?id='.$productId, 'No id found');
        }
    }

    if ($name != '' && $price != '' && $quantity != '') {

        $data = [
            'fld_product_name' => $name,
            'fld_product_price' => $price,
            'fld_product_desc' => $desc,
            'fld_product_quantity' => $quantity,
        ];
        $result = update('tbl_products_penb', $productId, $data);
        if ($result) {
            redirect('products-edit.php?id='.$productId,'Product Updated Successfully!');
        } else {
            redirect('products-edit.php?id='.$productId,'Something went wrong');
        }
    } else {
        redirect('products-edit.php?id='.$productId,'Please fill required fields');
    }
}


?>