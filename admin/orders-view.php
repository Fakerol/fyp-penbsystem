<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Order View
              <a href="orders.php" class="btn btn-danger mx-2 btn-md float-end">Back</a>
            </h4>
         </div>
         <div class="card-body">

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Order</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Customer</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Product</button>
  </li>
</ul>


<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
        <div class="row mt-4">
          <div class="col-md-12">
             <h4>Order Details</h4>
             <div class="mb-3">
                <label for="invoiceNo" class="form-label">Invoice No:</label>
                <input type="text" class="form-control" id="invoiceNo" value="<?= $orderData['invoice_no']?>" readonly>
             </div>
            
             <div class="mb-3">
                <label for="paymentMode" class="form-label">Payment Mode:</label>
                <input type="text" class="form-control" id="paymentMode" value="<?= $orderData['payment_mode']?>" readonly>
             </div>
             <div class="mb-3">
                <label for="orderStatus" class="form-label">Order Status:</label>
                <input type="text" class="form-control" id="orderStatus" value="<?= $orderData['order_status']?>" readonly>
             </div>
          </div>
      </div>
  </div>


  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
         <div class="mb-3">
            <label for="customerEmail" class="form-label">Email:</label>
            <input type="email" class="form-control" id="customerEmail" value="<?= $orderData['fld_customer_email']?>" readonly>
         </div>
         <div class="mb-3">
            <label for="customerPhone" class="form-label">Phone Number:</label>
            <input type="tel" class="form-control" id="customerPhone" value="<?= $orderData['fld_customer_phone']?>" readonly>
         </div>
         <div class="mb-3">
            <label for="customerAddress" class="form-label">Address:</label>
            <input type="text" class="form-control" id="customerAddress1" value="<?= $orderData['fld_customer_address1']?>" readonly><br>
            <input type="text" class="form-control" id="customerAddress2" value="<?= $orderData['fld_customer_address2']?>" readonly><br>
            <input type="text" class="form-control" id="customerAddress3" value="<?= $orderData['fld_customer_poscode'] . ', ' . $orderData['fld_customer_city'] . ', ' . $orderData['fld_customer_state']; ?>" readonly>
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
                <tr>
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
</div>
</div>
</div>
</div>



    <?php include('includes/footer.php'); ?>