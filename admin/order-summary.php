<?php 
include('includes/header.php'); 

if (!isset($_SESSION['productItems'])) {
    echo '<script>window.location.href = "orders-create.php"</script>';
}
?>

<div class="modal fade" id="orderSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="orderSuccessModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"> 
      <div class="modal-body">
        
        <div class="mb-3 p-4">
            <h5 id="orderPlaceSuccessMessage"></h5>
        </div>
      
        <a href="orders.php" class="btn btn-secondary">Close</a>
        
        
    </div>
    </div>
  </div>
</div>


<div class="container-fluid px-4">
   
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Order Summary
                        <a href="orders-create.php" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body ">
                    
                    <div id="myBillingArea" >
                        <?php
                        if (isset($_SESSION['plate'])) {
                            $plate = validate($_SESSION['plate']);
                            $invoiceNo = validate($_SESSION['invoice_no']);

                            $customerQuery = mysqli_query($conn, "SELECT * FROM tbl_customers_penb WHERE plate_number='$plate' LIMIT 1");
                            if ($customerQuery) {
                                if (mysqli_num_rows($customerQuery) > 0) {
                                    $cRowData = mysqli_fetch_assoc($customerQuery);
                                    ?>
                                    <table style="width: 100%; margin-bottom: 10px; margin-top: -30px;">
                                        <tbody>
                                            <tr style="margin-top: 0px;">
                                                <td style="text-align: left; margin-top: 0px;">
                                                    <h4 style="font-size: 23px; line-height: 20px; margin: 2px; padding: 0;">KSQ 10 ENTERPRISE</h4>
                                                </td>
                                                <td style="text-align: right;">
                                                    <h4 style="font-size: 23px; line-height: 30px; margin: 2px; padding: 0;">Workshop & Repair</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="text-align: left;">
                                                    <div style="text-align: left; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 10px;">
                                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">9632 JARAK ATAS</p>
                                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">TASEK GELUGOR 13310</p>
                                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">PULAU PINANG MALAYSIA</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                               <tr style="margin-right: 20px;">
                                                <td colspan="2" style="text-align: right;">
                                                    <div style="display: inline-block; text-align: left;">
                                                        <p style="font-size: 18px; line-height: 24px; margin: 2px; padding: 0;"><?= $cRowData['fld_customer_name'];?> - <?= $cRowData['plate_number']; ?></p>
                                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><?= $cRowData['fld_customer_address1']; ?></p>
                                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><?= $cRowData['fld_customer_address2']; ?></p>
                                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><?= $cRowData['fld_customer_city'];?> <?= $cRowData['fld_customer_poscode'];?></p>
                                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><?= $cRowData['fld_customer_state']; ?></p>
                                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">
                                                            <?= $cRowData['fld_customer_phone']; ?> 
                                                            
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <br>
                                            <tr>
                                                <td colspan="2" style="text-align: left;">   
                                                    <h1 style="font-size: 36px; line-height: 42px; margin-left: 20px; padding: 0;"><?= $invoiceNo; ?></h1>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="text-align: right;">
                                                    <div style="display: flex; justify-content: flex-end; align-items: center;">
                                                        <div style="margin-right: 50px; text-align: left;">
                                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><strong>Invoice Date:</strong></p>
                                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><?= date('d M Y'); ?></p>
                                                        </div>
                                                        <div style="margin-right: 50px; text-align: left;">
                                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><strong>Payment Mode:</strong></p>
                                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><?=$_SESSION['payment_mode']; ?></p>
                                                        </div>

                                                        <div style="text-align: left; margin-right: 30px;">
                                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><strong>Order Status:</strong></p>
                                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><?=$_SESSION['order_status']; ?></p>
                                                        </div>
        
                                                        <div style="text-align: left; margin-right: 30px;">
                                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><strong>Saleperson:</strong></p>
                                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;"><?=$_SESSION['loggedInUser']['user_id']; ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    
                                    <?php
                                } else {
                                    echo "<h5>No customer found</h5>";
                                    return;
                                }
                            }
                        }
                        ?>

                        <?php
                        if (isset($_SESSION['productItems']) || isset($_SESSION['chargeItems'])) {
                            $sessionProducts = $_SESSION['productItems'];
                            $sessionCharges = $_SESSION['chargeItems'];
                            ?>
                            <div class="table-responsive mb-3">
    <table style="width:100%;" cellpadding="5">
        <thead>
            <tr>
                <th align="start" style="border-bottom: 1px solid #ccc;">Product</th>
                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalAmount = 0;
            foreach ($sessionProducts as $key => $row) :
                $totalAmount += $row['price'] * $row['quantity'];
            ?>
                <tr>
                    <td style="border-bottom: 1px solid #ccc;"><?= $row['name']; ?></td>
                    <td style="border-bottom: 1px solid #ccc;">RM<?= number_format($row['price'], 2) ?></td>
                    <td style="border-bottom: 1px solid #ccc;"><?= $row['quantity']; ?></td>
                    <td style="border-bottom: 1px solid #ccc;">RM<?= number_format($row['price'] * $row['quantity'], 2)?></td>
                </tr>
            <?php endforeach; ?>
           
            <tr>
                <td colspan="3" align="end" style="">Subtotal: </td>
                <td colspan="1" style="font-weight: bold;">RM<?= number_format($totalAmount, 2); ?></td>
            </tr>
            <?php
            $totalLabourAmount = 0; // Initialize the variable here
            if (!empty($sessionCharges)): ?>
                <thead>
                    <tr>
                        <th align="start" style="border-bottom: 1px solid #ccc;">Labour Charge</th>
                        <th align="start" style="border-bottom: 1px solid #ccc;" width="10%"></th>
                        <th align="start" style="border-bottom: 1px solid #ccc;" width="10%"></th>
                        <th align="start" style="border-bottom: 1px solid #ccc;" width="10%"></th>
                    </tr>
                </thead>
                <?php
                foreach ($sessionCharges as $key => $row) :
                    $totalLabourAmount += $row['charge_price'] * $row['charge_quantity'];
                ?>
                    <tr>
                        <td style="border-bottom: 1px solid #ccc;"><?= $row['charge_desc']; ?></td>
                        <td style="border-bottom: 1px solid #ccc;">RM<?= number_format($row['charge_price'], 2) ?></td>
                        <td style="border-bottom: 1px solid #ccc;"><?= $row['charge_quantity']; ?></td>
                        <td style="border-bottom: 1px solid #ccc;">RM<?= number_format($row['charge_price'] * $row['charge_quantity'], 2)?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" align="end" style="">Subtotal: </td>
                    <td colspan="1" style="font-weight: bold;">RM<?= number_format($totalLabourAmount, 2); ?></td>
                </tr>
            <?php endif; ?>
            <?php
                $grandTotal = $totalAmount + $totalLabourAmount;
            ?>
            <tr style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">
                <td colspan="3" align="end" style="font-weight: bold;">Total: </td>
                <td colspan="1" style="font-weight: bold;">RM<?= number_format($grandTotal, 2); ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: left;">   
                    <p style="font-size: 16px; line-height: 20px; margin-left: 0px; padding: 0;">Payment Communication: <strong><?= $invoiceNo; ?></strong></p>
                    <p style="font-size: 16px; line-height: 20px; margin-left: 0px; padding: 0;">Payment terms: <strong>30 days</strong></p>
                    <p style="font-size: 16px; line-height: 20px; margin-left: 0px; padding: 0;">Transfer To:</p>
                    <p style="font-size: 16px; line-height: 10px; margin-left: 0px; padding: 0;">MAYBANK</p>
                    <p style="font-size: 16px; line-height: 10px; margin-left: 0px; padding: 0;">557410562928</p>
                    <p style="font-size: 16px; line-height: 10px; margin-left: 0px; padding: 0;">KSQ 10 ENTERPRISE</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>


                            <?php
                        }
                        else {
                            echo '<h5 class="text-center">No Item Added</h5>';
                        }
                        ?>
                    </div>

                    <?php  if (isset($_SESSION['productItems'])) :  ?>
                        
                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-primary px-4 mx-1" id="saveOrder">Save</button>
                        </div>

                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
