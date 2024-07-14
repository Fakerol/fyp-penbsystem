<?php include('includes/header.php');
function generateRandomExpenseID(){
    $randomNumber = mt_rand(1000000, 9999999);
    return "E" . $randomNumber;
}
?>

<style>
     .quantityExpenseInput{
                width: 50px !important;
                padding: 6px 3px;
                text-align: center;
                border: 1px solid #cfb1b1;
                outline: 0;
                margin-right: 1px;

            }
</style>

    <!-- Retreive customre data -->
<div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Expenses
              <a href="expenses.php" class="btn btn-danger mx-2 btn-md float-end">Back</a>
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
                        <div class="card card-body shadow border-1 mb-4">
                           <div class="row">
                              <div class="col-md-6">
                                 <h4>Order Details</h4>
                                 <label class="mb-1">
                                    Invoice No: 
                                    <span class="fw-bold"><?= $orderData['invoice_no']?></span>
                                 </label>
                                 <br/>
                                 <label class="mb-1">
                                    Order Status: 
                                    <span class="fw-bold"><?= $orderData['order_status']?></span>
                                 </label>
                                 <br/>
                                 
                              </div>

                              <div class="col-md-6">
                                 <h4>User Details</h4>
                                 <label class="mb-1">
                                    Full Name: 
                                    <span class="fw-bold"><?= $orderData['fld_customer_name']?></span>
                                 </label>
                                 <br/>
                                 <label class="mb-1">
                                    Plate Number: 
                                    <span class="fw-bold"><?= $orderData['plate_number']?></span>
                                 </label>
                                 <br>
                            
                                 
                                
                                 
                              </div>
                           </div>
                           
                        </div>

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



<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Expenses</h4>
        </div>

        <div class="card-body">
             <?php alertMessage(); ?>
            <form action="expense-code.php?invoice=<?= $invoiceNo ?>" method="post">
                <div class="row">
                    <h5 class="mb-2">Add New Expense</h5>

                    <input type="hidden" name="expenseId" value="<?php echo generateRandomExpenseID(); ?>" readonly class="form-control"/>

                    <div class="col-md-5 mb-3">
                        <label> Expense Description <span class="required">*</span></label>
                        <input type="text" name="expense_desc" class="form-control"/>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Price <span class="required">*</span></label>
                        <input type="number" step="0.01" name="expense_price" class="form-control"/>
                    </div>

                    <div class="col-md-2 mb-3 align-self-end">
                        <br/>
                        <button type="submit" name="addNewExpense" class="btn btn-primary">Add</button>
                    </div>
                </div>
                <input type="hidden" name="invoice" value="<?= $invoiceNo ?>">
            </form>
        </div>
    </div>
</div>

<!-- Expense Items Table -->
<div class="container-fluid px-4">
    <div class="card mt-0">
        <?php
        if (isset($_SESSION['expenseItems'])) {
            $sessionExpenses = $_SESSION['expenseItems'];
            if (empty($sessionExpenses)) {
                   unset($_SESSION['expenseItems']);
                   unset($_SESSION['expenseItemIds']);
                }
        
        ?>
    <div class="card-body" id="expenseArea">
        
       
    <div class="table-responsive mb-3" id="expenseContent">

    <table class="table table-bordered table-stripe">
        <thead>
            <tr>
                <th>No</th>
                <th>Expense Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $i = 1;
                $totalAmount = 0; // Add this line to calculate the total amount
                foreach ($sessionExpenses as $key => $expense) : 
                    $totalAmount += $expense['expense_price'] * $expense['expense_quantity']; // Update the total amount
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $expense['expense_desc']; ?></td>
                <td>RM<?= number_format($expense['expense_price'], 2); ?></td>
                <td>
                    <div class="input-group qtyExpenseBox">

                        <input type="hidden" class="expenseId" value="<?= $expense['expenseId']; ?>">

                        <button class="input-group-text expenseDecrement">-</button>

                        <input type="text" value="<?= $expense['expense_quantity']; ?>" class="qty quantityExpenseInput">
                        
                        <button class="input-group-text expenseIncrement">+</button>
                    </div>
                </td>
                <td>RM<?= number_format($expense['expense_price'] * $expense['expense_quantity'], 2); ?></td>
                <td>
                    <a href="#" class="btn btn-primary btn-lg editExpense" data-bs-toggle="modal" data-bs-target="#editExpenseModal" data-index="<?= $key; ?>">
                        <i class="bi bi-pencil-square"></i> <!-- Icon for Edit -->
                    </a>
                    <input type="hidden" name="invoice" value="<?= $invoiceNo ?>">
                    <a href="expense_item_delete.php?index=<?= $key; ?>&invoice=<?= $invoiceNo; ?>" class="btn btn-danger btn-lg">
                        <i class="bi bi-trash"></i> <!-- Icon for Delete -->
                    </a>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                <td colspan="2" id="overallTotal">RM<?= number_format($totalAmount, 2); ?></td> <!-- Update the total amount display -->
            </tr>
        </tfoot>
    </table>


                    
        <div class="d-flex justify-content-center">
            
         <a href="expense-summary.php?invoice=<?= $invoiceNo; ?>" class="btn btn-primary btn-md">Submit
            <a/>
        </div>

    
</div>
</div>
<?php
        } else {
            echo "<h5>No Expense Added!</h5>";
        }
    ?>
</div>
</div>

<!-- Define the edit expense item modal -->
<div class="modal fade" id="editExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Expense Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editExpenseForm">
                    <input type="hidden" id="editExpenseId" readonly>
                     <input type="hidden" id="editItemIndex" readonly>
                    <div class="mb-3">
                        <label for="editExpenseName">Expense Description</label>
                        <input type="text" class="form-control" id="editExpenseName" name="expenseName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editExpensePrice">Price</label>
                        <input type="number" step="0.01" class="form-control" id="editExpensePrice" name="expensePrice">
                    </div>
                     
                    <button type="submit" class="btn btn-primary saveExpenseChanges">Save Changes</button>
                </form>
                <div class="mb-3">
                        <label><em>If there is no data displayed, please refresh the page.</em></label>
                    </div>
            </div>
        </div>
    </div>
</div>


<!-- Edit Existing Charge -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll('.editExpense');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const item = <?= json_encode($sessionExpenses); ?>[index];

            document.querySelector('#editExpenseId').value = item.expenseId; 
            document.querySelector('#editExpenseName').value = item.expense_desc;
            document.querySelector('#editExpensePrice').value = item.expense_price;
            document.querySelector('#editItemIndex').value = index; // Add index to hidden input
        });
    });

    document.querySelector('#editExpenseForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var e_id = $('#editExpenseId').val();
        var e_name = $('#editExpenseName').val();
        var e_price = $('#editExpensePrice').val();
        var e_index = $('#editItemIndex').val(); // Get the index value

        if (e_id !== '') {
            var data = {
                'saveExpenseChanges': true,
                'eid': e_id,
                'name': e_name,
                'price': e_price,
                'index': e_index // Include index in data
            };

            $.ajax({
                url: 'expense-code.php',
                type: 'POST',
                data: data,
                success: function(response) {
                  
                    try {
                        var res = JSON.parse(response);

                        if (res.status === 200) {
                            swal(res.message, res.message, res.status_type).then((value) => {
                                if (value) {
                                    window.location.reload(); // Refresh the page
                                }
                            });

                            // Update the DOM
                            var row = document.querySelector(`tr[data-index="${e_index}"]`);
                            if (row) {
                                row.querySelector('.expense-name').innerText = e_name;
                                row.querySelector('.expense-price').innerText = `RM${parseFloat(e_price).toFixed(2)}`;
                                var qty = row.querySelector('.quantityInput').value;
                                row.querySelector('.total-price').innerText = `RM${(parseFloat(e_price) * qty).toFixed(2)}`;
                            }
                        } else {
                            swal(res.message, res.message, res.status_type);
                        }
                    } catch (e) {
                        console.error("Parsing error:", e);
                        swal("Failed to process response.", "", "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                    console.log(xhr.responseText);
                }
            });

        } else {
            swal("No item Selected.", "", "warning");
        }
    });
});

</script>

<!-- Hide full order detail -->
<script>
function toggleOrderDetails() {
    var table = document.getElementById('orderDetailsTable');
    if (table.style.display === 'none') {
        table.style.display = 'block';
        localStorage.setItem('orderDetailsOpen', 'true'); // Store state
    } else {
        table.style.display = 'none';
        localStorage.setItem('orderDetailsOpen', 'false'); // Store state
    }
}

// Check localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    var isOpen = localStorage.getItem('orderDetailsOpen');
    if (isOpen === 'true') {
        document.getElementById('orderDetailsTable').style.display = 'block';
    } else {
        document.getElementById('orderDetailsTable').style.display = 'none';
    }
});
</script>



<?php include('includes/footer.php'); ?>