<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Expenses
              <a href="expenses.php" class="btn btn-danger mx-2 btn-md float-end">Back</a>
            </h4>
         </div>
         <div class="card-body">

            

<ul class="nav nav-tabs" id="myTab" role="tablist">
  
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Order</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Product</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="expense-tab" data-bs-toggle="tab" data-bs-target="#expense" type="button" role="tab" aria-controls="expense" aria-selected="false">Expense Item</button>
  </li>

  <li class="nav-item" role="presentation">
    <button class="nav-link " id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Report</button>
  </li>
</ul>

<?php 
               if (isset($_GET['invoice'])) {

                  $invoiceNo = validate($_GET['invoice']);

                  $query = "SELECT o.*, c.* FROM tbl_order_penb o, tbl_customers_penb c WHERE c.id = o.customer_id AND invoice_no='$invoiceNo' ORDER BY o.id DESC";

                  $orders = mysqli_query($conn, $query);
                  if ($orders) {
                     if (mysqli_num_rows($orders) > 0) {

                        $orderData = mysqli_fetch_assoc($orders);
                        $orderId = $orderData['id'];
                        ?>


<div class="tab-content" id="myTabContent">
    


    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <div class="row mt-4">

          <div class="col-md-12">
             <h4>Order Details</h4>
             <div class="mb-3">
                <label for="invoiceNo" class="form-label">Invoice No:</label>
                <input type="text" class="form-control" id="invoiceNo" value="<?= $orderData['invoice_no']?>" readonly>
             </div>
             <div class="mb-3">
                <label for="orderStatus" class="form-label">Order Status:</label>
                <input type="text" class="form-control" id="orderStatus" value="<?= $orderData['order_status']?>" readonly>
             </div>
          </div>
      </div>
    <div class="row mt-4">
        <div class="col-md-12">
         <h4>Customer Details</h4>
         
         <div class="mb-3">
            <label for="customerName" class="form-label">Customer Name:</label>
            <input type="text" class="form-control" id="customerName" value="<?= $orderData['fld_customer_name']?>" readonly>
         </div>
         <div class="mb-3">
            <label for="plateNumber" class="form-label">Plate Number:</label>
            <input type="text" class="form-control" id="plateNumber" value="<?= $orderData['plate_number']?>" readonly>
         </div>
         
      </div>
    </div>
    </div>
 
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
    <?php

    $orderItemQuery = 
    "SELECT 
            oi.quantity AS orderItemQuantity, oi.price AS orderItemPrice, o.invoice_no, p.id AS product_id, 
            p.fld_product_name AS product_name, lb.id AS labor_id, lb.charge_desc AS labor_name
        FROM  tbl_order_penb AS o JOIN tbl_order_items AS oi ON oi.order_id = o.id
        LEFT JOIN tbl_products_penb AS p ON p.id = oi.product_id
        LEFT JOIN tbl_labourcharge_penb AS lb ON lb.id = oi.product_id 
        WHERE o.invoice_no = '$invoiceNo'";

    $orderItemRes = mysqli_query($conn, $orderItemQuery);

    if ($orderItemRes) {
        if (mysqli_num_rows($orderItemRes) > 0) {
            $products = [];
            $laborCharges = [];
            $totalProductAmount = 0;
            $totalLaborChargeAmount = 0;

            while ($orderItemRow = mysqli_fetch_assoc($orderItemRes)) {
                if (!empty($orderItemRow['labor_id'])) {
                    // It's a labor charge
                    $laborCharges[] = $orderItemRow;
                    $totalLaborChargeAmount += $orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'];
                } else {
                    // It's a product
                    $products[] = $orderItemRow;
                    $totalProductAmount += $orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'];
                }
            }
            ?>
             <h4 class="mt-4">Order Item Details</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr >
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?= $product['product_name']; ?></td>
                        <td width="15%" class="text-end">
                            RM<?= number_format($product['orderItemPrice'], 2); ?>
                        </td>
                        <td width="15%" class="text-end">
                            <?= $product['orderItemQuantity']; ?>
                        </td>
                        <td width="15%" class="text-end">
                            RM<?= number_format($product['orderItemPrice'] * $product['orderItemQuantity'], 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <tr>
                        <td class="text-end fw-bold">Subtotal:</td>
                        <td colspan="3" class="text-end fw-bold">
                            RM<?= number_format($totalProductAmount, 2); ?>
                        </td>
                    </tr>

                    <!-- Labor Charges Section -->
                    <tr>
                        <td colspan="4" class="fw-bold">Labor Charges</td>
                    </tr>
                    <?php foreach ($laborCharges as $laborCharge) : ?>
                    <tr>
                        <td><?= $laborCharge['labor_name']; ?></td>
                        <td width="15%" class="text-end">
                            RM<?= number_format($laborCharge['orderItemPrice'], 2); ?>
                        </td>
                        <td width="15%" class="text-end">
                            <?= $laborCharge['orderItemQuantity']; ?>
                        </td>
                        <td width="15%" class="text-end">
                            RM<?= number_format($laborCharge['orderItemPrice'] * $laborCharge['orderItemQuantity'], 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <tr>
                        <td class="text-end fw-bold">Subtotal:</td>
                        <td colspan="3" class="text-end fw-bold">
                            RM<?= number_format($totalLaborChargeAmount, 2); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">Grand Total:</td>
                        <td colspan="3" class="text-end fw-bold">
                            RM<?= number_format($totalLaborChargeAmount + $totalProductAmount, 2); ?>
                        </td>
                    </tr>
                </tbody>
            </table>

                                  <?php
                                    
                                 }
                                 else{
                                  echo '<h5>Something went wrong</h5>';
                                  return false;
                                 }
                               }
                               else{
                                  echo '<h5>Something went wrong</h5>';
                                  return false;
                               }
                            ?>


                            <?php
                            
                         }
                         else{
                            echo '<h5>No record found</h5>';
                            return false;
                         }
                      }
                      else{
                         echo '<h5>Something went wrong</h5>';
                      }
                   }
                   else{
                      ?>
                      <div class="text-center py-5">
                         <h5>No Invoice Number Found</h5>
                         <div>
                      <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back to Order List</a>
                      </div>
                      </div>

                      <?php
                   }
                ?>
      </div>

    <div class="tab-pane fade" id="expense" role="tabpanel" aria-labelledby="expense-tab">
    <?php
        // Ensure $invoiceNo is set
        if (isset($invoiceNo)) {
            // Update the query to include e.expense_id and ensure the join conditions are correct
            $expenseQuery = "SELECT 
                e.invoice_no,
                p.expense_desc,
                e.price AS expensePrice,
                e.quantity AS expenseQuantity
            FROM 
                tbl_expense_item e
            JOIN 
                tbl_expense_penb p ON e.expense_id = p.id
            WHERE 
                e.invoice_no = '$invoiceNo'";

            $expenseRes = mysqli_query($conn, $expenseQuery);

            // Check for SQL errors
            if (!$expenseRes) {
                echo "SQL Error: " . mysqli_error($conn);
            } else {
                if (mysqli_num_rows($expenseRes) > 0) {
                    $expenses = [];
                    $totalExpenseAmount = 0;

                    while ($expenseItemRow = mysqli_fetch_assoc($expenseRes)) {
                       
                        // Ensure expense_id is being fetched and checked
                        if (!empty($expenseItemRow['expense_desc'])) {
                            $expenses[] = $expenseItemRow;
                            $totalExpenseAmount += $expenseItemRow['expensePrice'] * $expenseItemRow['expenseQuantity'];
                        }
                    }
                    ?>
                    <h4 class="mt-4">Expense Details</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Expense Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expenses as $expense) : ?>
                            <tr>
                                <td><?= htmlspecialchars($expense['expense_desc']); ?></td>
                                <td width="15%" class="text-end">
                                    RM<?= number_format($expense['expensePrice'], 2); ?>
                                </td>
                                <td width="15%" class="text-end">
                                    <?= htmlspecialchars($expense['expenseQuantity']); ?>
                                </td>
                                <td width="15%" class="text-end">
                                    RM<?= number_format($expense['expensePrice'] * $expense['expenseQuantity'], 2); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td class="text-end fw-bold">Total:</td>
                                <td colspan="3" class="text-end fw-bold">
                                    RM<?= number_format($totalExpenseAmount, 2); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo '<h5>No expenses found for this invoice.</h5>';
                }
            }
        } else {
            echo '<h5>Invoice number is not set.</h5>';
        }
    ?>
    </div>

    <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
     <h4 class="mt-4">Report</h4>    
     <?php
    $revenue = $totalLaborChargeAmount + $totalProductAmount;
    $expenses = $totalExpenseAmount;
    $profit = $revenue - $expenses;
    $roi = ($profit / $expenses) * 100;
    $margin = ($profit / $revenue) * 100;
    ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Metric</th>
                <th>Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Revenue</td>
                <td class="text-end">RM<?= number_format($revenue, 2); ?></td>
            </tr>
            <tr>
                <td>Expenses</td>
                <td class="text-end">RM<?= number_format($expenses, 2); ?></td>
            </tr>
            <tr>
                <td>Profit</td>
                <td class="text-end" style="color: <?= $profit >= 0 ? 'green' : 'red'; ?>;">RM<?= number_format($profit, 2); ?></td>
            </tr>
            <tr>
                <td>Return on Investment (ROI)</td>
                <td class="text-end" style="color: <?= $roi >= 0 ? 'green' : 'red'; ?>;"><?= number_format($roi, 2); ?>%</td>
            </tr>
            <tr>
                <td>Profit Margin</td>
                <td class="text-end" style="color: <?= $margin >= 0 ? 'green' : 'red'; ?>;"><?= number_format($margin, 2); ?>%</td>
            </tr>
        </tbody>
    </table>
    </div>



</div>
</div>
</div>
</div>



    <?php include('includes/footer.php'); ?>