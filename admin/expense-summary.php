<?php 
include('includes/header.php'); 

// Validate and retrieve the invoice parameter from the GET request
$invoice = validate($_GET['invoice']);

?>

<script>
function toggleOrderDetails() {
    var table = document.getElementById('orderDetailsTable');
    if (table.style.display === 'none') {
        table.style.display = 'block';
    } else {
        table.style.display = 'none';
    }
}
</script>

<div class="modal fade" id="expenseSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="expenseSuccessModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"> 
      <div class="modal-body">
        
        <div class="mb-3 p-4">
            <h5 id="expensePlaceSuccessMessage"></h5>
        </div>
      
        <a href="expenses.php" class="btn btn-secondary">Close</a>
        
        
    </div>
    </div>
  </div>
</div>

<div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Expenses
              <a href="expenses-create.php?invoice=<?= $invoice; ?>" class="btn btn-danger mx-2 btn-md float-end">Back</a>
            </h4>
         </div>
         <div class="card-body">

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

         <div class="mt-4">
                <button class="btn btn-info" onclick="toggleOrderDetails()">See Full Order Item Details</button>
            </div>
    <div id="orderDetailsTable" style="display: none;">
        <h4>Order Item Details</h4>
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
    </div>

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
                  <a href="expenses.php" class="btn btn-primary mt-4 w-25">Go Back to Expense List</a>
                  </div>
                  </div>

                  <?php
               }
            ?>

         </div> 
      </div>
   </div>

        <?php
         if (isset($_SESSION['expenseItems'])) {
                    $sessionExpenses = $_SESSION['expenseItems'];
        ?>

        
   <div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Expenses Detail</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
            <thead>
                <tr >
                    
                    <th>Expense Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                 <?php
                $totalAmount = 0;
                foreach ($sessionExpenses as $expense) : 
                    $totalAmount += $expense['expense_price'] * $expense['expense_quantity'];
                    ?>
                <tr>

                    <td><?= $expense['expense_desc']; ?></td>
                    <td width="15%" class="text-end">
                        RM<?= number_format($expense['expense_price'], 2); ?>
                    </td>
                    <td width="15%" class="text-end">
                        <?= $expense['expense_quantity']; ?>
                    </td>
                    <td width="15%" class="text-end">
                        RM<?= number_format($expense['expense_price'] * $expense['expense_quantity'], 2); ?>
                    </td>
                </tr>
                <?php endforeach; ?>

        
                <tr>
                    <td class="text-end fw-bold">Grand Total:</td>
                    <td colspan="3" class="text-end fw-bold">
                        RM<?= number_format($totalAmount, 2); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</div>
 <?php
    } else {
        echo "<h5>No Expense Added";
        return;
    }
    ?>


<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Report Overview</h4>
        </div>

        <div class="card-body">
    <?php
    $revenue = $totalLaborChargeAmount + $totalProductAmount;
    $expenses = $totalAmount;
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
    <div class="mt-4 text-end">
        
        <button type="button" class="btn btn-primary px-4 mx-1" id="saveExpense">Save</button>
    </div>
</div>

    </div>
</div>


  <script>
// Pass the PHP variable to JavaScript
var invoiceNumber = "<?php echo $invoice; ?>";

$(document).ready(function() {
    // Use invoiceNumber in your AJAX request within custom.js
    $('#saveExpense').on('click', function() {
        $.ajax({
            type: 'POST',
            url: 'expense-code.php',
            data: {
                'saveExpense': true,
                'invoice': invoiceNumber // Ensure invoice number is passed
            },
            success: function(response) {
                var res = JSON.parse(response);

                console.log(res); // Debugging line

                if (res.status == 200) {
                    swal(res.message, res.message, res.status_type);
                    $('#expensePlaceSuccessMessage').text(res.message);
                    $('#expenseSuccessModal').modal('show');
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr); // Log any AJAX errors
            }
        });
    });
});
</script>

<?php include('includes/footer.php'); ?>


 