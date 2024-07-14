<?php
include('../config/function.php');
if (!isset($_SESSION['expenseItemIds'])) {
    $_SESSION['expenseItemIds'] = [];
}

if (!isset($_SESSION['expenseItems'])) {
    $_SESSION['expenseItems'] = [];
}

if (isset($_POST['addNewExpense'])) {
    // Assuming validate function exists
    $expenseId = validate($_POST['expenseId']);
    $expenseDesc = validate($_POST['expense_desc']);
    $expensePrice = validate($_POST['expense_price']);
    $invoice = validate($_POST['invoice']);  // Retrieve the invoice number

    // Insert new product into the database
    $insertQuery = "INSERT INTO tbl_expense_penb (id, expense_desc, expense_price, expense_quantity) VALUES ('$expenseId', '$expenseDesc', '$expensePrice', 1)";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        // Initialize session arrays if they are not set
        if (!isset($_SESSION['expenseItemIds'])) {
            $_SESSION['expenseItemIds'] = [];
        }
        if (!isset($_SESSION['expenseItems'])) {
            $_SESSION['expenseItems'] = [];
        }

        // Add the new product to session
        $expenseData = [
            'expenseId' => $expenseId,
            'expense_desc' => $expenseDesc,
            'expense_price' => $expensePrice,
            'expense_quantity' => 1, // You can set default quantity here
        ];

        array_push($_SESSION['expenseItemIds'], $expenseId);
        array_push($_SESSION['expenseItems'], $expenseData);

        redirect('expenses-create.php?invoice=' . $invoice, 'New expense added successfully');
    } else {
        redirect('expenses-create.php?invoice=' . $invoice, 'Failed to add new expense');
    }
}  


if (isset($_POST['expenseIncDec'])) {
   $expenseId = validate($_POST['expenseId']);
   $quantity = validate($_POST['expense_quantity']);

   $flag = false;
   foreach ($_SESSION['expenseItems'] as $key => $item) {
       if($item['expenseId'] == $expenseId){

        $flag = true;
        $_SESSION['expenseItems'][$key]['expense_quantity'] = $quantity;

       }
   }

   if ($flag) {
       jsonResponse(200, 'success', 'Quantity Updated');
   }else{
       jsonResponse(500, 'error', 'Something went wrong');
   }

}


if (isset($_POST['saveExpenseChanges'])) {
    $expenseId = validate($_POST['eid']);
    $expenseName = validate($_POST['name']);
    $expensePrice = validate($_POST['price']);

    if ($expenseId != '') {
        $data = [
            'expense_desc' => $expenseName,
            'expense_price' => $expensePrice,
        ];

        $result = update('tbl_expense_penb', $expenseId, $data);

        if ($result) {
            foreach ($_SESSION['expenseItems'] as $key => $item) {
                if ($item['expenseId'] == $expenseId) {
                    $_SESSION['expenseItems'][$key]['expense_desc'] = $expenseName;
                    $_SESSION['expenseItems'][$key]['expense_price'] = $expensePrice;
                }
            }

            jsonResponse(200, 'success', 'Expense item updated successfully!');
        } else {
            jsonResponse(404, 'warning', 'Failed to update the item');
        }
    } else {
        jsonResponse(500, 'error', 'Invalid expense ID');
    }
}

// Function to update data
function updateExpense($tableName, $invoice, $data) {
    global $conn;

    $table = validate($tableName);
    $invoice = validate($invoice);

    $updateDataString = "";
    foreach ($data as $column => $value) {
        $column = validate($column);
        $value = validate($value);
        $updateDataString .= "$column='$value',";
    }

    $finalUpdateData = rtrim($updateDataString, ',');

    $query = "UPDATE $table SET $finalUpdateData WHERE invoice_no='$invoice'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Failed to update $table: " . mysqli_error($conn));
    }
    return $result;
}

// Main logic
if (isset($_POST['saveExpense'])) {
    $invoice = validate($_POST['invoice']);  // Use POST instead of GET

    if (!empty($invoice)) {
        $sessionExpenses = $_SESSION['expenseItems'];

        if (!empty($sessionExpenses)) {
            $totalExpense = 0;
            foreach ($sessionExpenses as $expenseItem) {
                $totalExpense += $expenseItem['expense_price'] * $expenseItem['expense_quantity'];
            }

            $dataOrderExpense = [
                'total_expense' => $totalExpense,
            ];

            $result = updateExpense('tbl_order_penb', $invoice, $dataOrderExpense);
            if (!$result) {
                jsonResponse(500, 'error', 'Failed to update expense');
            }

            // Insert order items for charges
            foreach ($sessionExpenses as $expenseItem) {
                $dataOrderItem = [
                    'invoice_no' => $invoice,
                    'expense_id' => validate($expenseItem['expenseId']),
                    'price' => validate($expenseItem['expense_price']),
                    'quantity' => validate($expenseItem['expense_quantity']),
                ];

                if (!insert('tbl_expense_item', $dataOrderItem)) {
                    jsonResponse(500, 'error', 'Failed to insert expense item');
                }
            }

            // Unset session variables if they exist
            unset($_SESSION['expenseItemIds']);
            unset($_SESSION['expenseItems']);

            jsonResponse(200, 'success', 'Expense Created Successfully');
        } else {
            jsonResponse(400, 'warning', 'No expense items found in session');
        }
    } else {
        jsonResponse(404, 'warning', 'No invoice found');
    }
}



?>




