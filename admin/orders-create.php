<?php include('includes/header.php'); 

//PRODUCT ID
function generateRandomID(){
  return 'DP' . mt_rand(1000000, 9999999);
}

//
function generateRandomCustID(){
  return mt_rand(1000000, 9999999);
}

//LABOUR CHARGE
function generateRandomChargeID(){
    $randomNumber = mt_rand(1000000, 9999999);
    return "LB" . $randomNumber;
}

?>
<style>
   
    .modal-md {
        max-width: 60%;
    }
    .select2-container {
        z-index: 2050; /* Ensure the z-index is higher than the modal */
    }
    .mySelect2 {
        min-width: 100%;
    } /* This closing brace was missing */
    
    .modal-lg .modal-body .form-select,
    .modal-lg .modal-body .form-control {
        width: 100%; /* Ensure form elements take the full width in the modal */
    }

    .col-md-6.mb-3 {
    margin-bottom: 1rem;
    }

    label {
    display: block;
    margin-bottom: 0.5rem;
    }

    select.form-select {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

</style>

<!------ Tab -------->
<div class="container-fluid px-4">
    <div class="card mt-3">
        <div class="card-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Product</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Labour Charge</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Setup</button>
            </li>
          </ul>



        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            
            <div class="row mt-3">
                    <div class="col-md-2 mt-0 mb-3">
                        <h5 class="mb-2">Add New Product</h5>
                    </div>
                    <div class="col-md-4 mb-3 mt-0 text-end ms-auto">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#selectProductModal">
                            Select Existing Product
                        </button>
                    </div>
                </div>
                
            <form action="orders-code.php" method="post">

               <input type="hidden" id="productId" name="productId" value="<?php echo generateRandomID(); ?>" readonly class="form-control"/>

                  <div class="col-md-12 mb-3">
                      <label for="product_name">Product Name <span class="required">*</span></label>
                      <input type="text" id="product_name" name="product_name" class="form-control"/>
                  </div>
                  <div class="col-md-12 mb-3">
                      <label for="product_price">Price <span class="required">*</span></label>
                      <input type="number" step="0.01" id="product_price" name="product_price" class="form-control"/>
                  </div>
                  <div class="col-md-2 mb-3 align-self-end">
                      <br/>
                      <button type="submit" name="addNewProduct" class="btn btn-primary">Add New Product</button>
                  </div>
            </form>

      <div class="card mt-0">
            <?php
            if (isset($_SESSION['productItems'])) {
                $sessionProducts = $_SESSION['productItems'];
                
                if (empty($sessionProducts)) {
                   unset($_SESSION['productItems']);
                   unset($_SESSION['productItemIds']);
                }

            ?>
        <div class="card-body" id="productArea">
           <?php alertMessage(); ?>
            
                <div class="table-responsive mb-3" id="productContent">
                    <table class="table table-bordered table-stripe">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Product Quantity</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $itemTotalAmount = 0; // total amount products
                    foreach ($sessionProducts as $key => $item) : 
                        $itemTotalAmount += $item['price'] * $item['quantity'];
                        $disableEdit = strpos($item['product_id'], 'P') === 0;
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $item['name']; ?></td>
                        <td>RM<?= number_format($item['price'], 2); ?></td>
                        <td>
                            <div class="input-group qtyBox">
                                <input type="hidden" class="prodId" value="<?= $item['product_id']; ?>">
                                <button class="input-group-text decrement">-</button>
                                <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput">
                                <button class="input-group-text increment">+</button>
                            </div>
                        </td>
                        <td>RM<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-lg editProduct <?= $disableEdit ? 'disabled' : ''; ?>" data-bs-toggle="modal" data-bs-target="#editItemModal" data-index="<?= $key; ?>" <?= $disableEdit ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                                <i class="bi bi-pencil-square"></i> <!-- Icon for Edit -->
                            </a>
                            <a href="order-item-delete.php?index=<?= $key; ?>" class="btn btn-danger btn-lg">
                                <i class="bi bi-trash"></i> <!-- Icon for Delete -->
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                        <td colspan="2" id="overallTotal">RM<?= number_format($itemTotalAmount, 2); ?></td> <!-- Update the total amount display -->
                    </tr>
                </tfoot>
            </table>
            <div class="mt-0">
                <p class="required mx-10" style="font-size: 14px;"><em> ** Existing Product cannot be edited. </em></p>
            </div>
                  
            </div>    
        </div>   
        <?php
          } else {
           echo "<h5>No Product Added!</h5>";
          }
        ?>   
      </div>
           
    </div>


    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="row mt-3">
            <div class="col-md-2 mt-0 mb-3">
                <h5 class="mb-2">Add Labour Charge</h5>
            </div>
            <div class="col-md-4 mb-3 mt-0 text-end ms-auto">
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#selectChargeModal">
                            Select Existing Charge
                </button>
            </div>
                    
            </div>
           <form action="orders-code.php" method="post">
                    
                    
                    <input type="hidden" name="chargeId" value="<?php echo generateRandomChargeID(); ?>" readonly class="form-control"/>
                    

                    <div class="col-md-12 mb-3">
                        <label > Labour Charge Description <span class="required">*</span></label>
                        <input type="text" name="charge_desc" class="form-control"/>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label >Price <span class="required">*</span></label>
                        <input type="number" step="0.01" name="charge_price" class="form-control"/>
                    </div>


                    <!-- Add New Product Button -->
                    <div class="col-md-2 mb-3 align-self-end">
                        <br/>
                        <button type="submit" name="addNewCharge" class="btn btn-primary">Add Labour Charge</button>
                    </div>
                
        </form>

        <div class="card mt-0">
                <?php
                if (isset($_SESSION['chargeItems'])) {
                    $sessionCharges = $_SESSION['chargeItems'];
                ?>
                <div class="card-body" id="chargeArea">
                    <?php alertMessage(); ?>
                    <div class="table-responsive mb-3" id="chargeContent">
                        <table class="table table-bordered table-stripe">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Labour Charge</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 1;
                                    $totalAmount = 0; // Total amount labour charge
                                    foreach ($sessionCharges as $key => $charge) : 
                                        $totalAmount += $charge['charge_price'] * $charge['charge_quantity'];
                                        $disableEdit = strpos($charge['chargeId'], 'LC') === 0;
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $charge['charge_desc']; ?></td>
                                    <td>RM<?= number_format($charge['charge_price'], 2); ?></td>
                                    <td>
                                        <div class="input-group qtyChargeBox">
                                            <input type="hidden" class="chargeId" value="<?= $charge['chargeId']; ?>">
                                            <button class="input-group-text chargeDecrement">-</button>
                                            <input type="text" value="<?= $charge['charge_quantity']; ?>" class="qty quantityChargeInput">
                                            <button class="input-group-text chargeIncrement">+</button>
                                        </div>
                                    </td>
                                    <td>RM<?= number_format($charge['charge_price'] * $charge['charge_quantity'], 2); ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-lg editCharge <?= $disableEdit ? 'disabled' : ''; ?>" data-bs-toggle="modal" data-bs-target="#editChargeModal" data-index="<?= $key; ?>" <?= $disableEdit ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="order_labourcharge_delete.php?index=<?= $key; ?>" class="btn btn-danger btn-lg">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                                    <td colspan="2" id="overallTotal">RM<?= number_format($totalAmount, 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="mt-0">
                            <p class="required mx-10" style="font-size: 14px;"><em> ** Existing Labour Charge cannot be edited. </em></p>
                        </div>
                    </div>
                </div>
                <?php
                } else {
                    echo "<h5>No Labour Charge Added!</h5>";
                }
                ?>
            </div>

          </div>


      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="col-md-2 mt-3 mb-3">
                <h5 class="mb-2">Order Info</h5>
            </div>

            <div class="col-md-12 mt-3">
                <label>Plate Number<span class="required">*</span></label>
                <input type="text" id="plate" class="form-control" value="">
            </div>
            <div class="col-md-12 mt-3">
                <label>Payment Mode<span class="required">*</span></label>
                <select id="payment_mode" class="form-select">
                    <option value="">-- Select Payment --</option>
                    <option value="Cash Payment">Cash Payment</option>
                    <option value="Online Payment">Online Payment</option>
                </select>
            </div>
         
            <div class="col-md-12 mt-3">
                <label>Order Status<span class="required">*</span></label>
                <select id="order_status" class="form-select">
                    <option value="">-- Select One --</option>
                    <option value="Quotation">Quotation</option>
                    <option value="Awaiting Payment">Awaiting Payment</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancel">Cancel</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <br>
                <button class="btn btn-warning w-100 proceedToPlace">Proceed To Place Order</button>
            </div>
        </div>


        </div>

        </div>
    </div>
</div>

<!---------------------------------------------------- Modal -------------------------------------------------------------->

<!-- Modal for add new customer -->
<div class="modal fade" id="addCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid px-4">

            <input type="hidden" class="form-control" id="c_id" required readonly value="<?php echo  generateRandomCustID(); ?>" />
        
        <div class="mb-3">
            <label>Customer Name <span class="required">*</span></label>
            <input type="text" class="form-control" required id="c_name" required />
        </div>
         <div class="mb-3">
            <label>Plate Number <span class="required">*</span></label>
            <input type="text" class="form-control" required id="c_plate" required />
        </div>
        <div class="mb-3">
            <label>Phone Number <span class="required">*</span></label>
            <input type="number" class="form-control" id="c_phone" />
        </div>
         <div class="mb-3">
            <label>Email <span class="required">*</span></label>
            <input type="email" class="form-control" id="c_email" />
        </div>
         <div class="mb-3">
            <label>Address 1 <span class="required">*</span></label>
            <input type="text" class="form-control" id="c_address1" />
        </div>
        <div class="mb-3">
            <label>Address 2 (optional)</label>
            <input type="text" class="form-control" id="c_address2" />
        </div>
        <div class="mb-3">
            <label>City <span class="required">*</span></label>
            <input type="text" class="form-control" id="c_city" />
        </div>
        <div class="mb-3">
            <label>Poscode <span class="required">*</span></label>
            <input type="number" class="form-control" id="c_poscode" />
        </div>
        <div class="mb-3">
            <label>State <span class="required">*</span></label>
            <input type="text" class="form-control" id="c_state" />
        </div>

      </div>
      <div class="mt-0">
                <p class="required mx-10" style="font-size: 14px;"><em> * is required</em></p>
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary saveCustomer">Save</button>
      </div>
    </div>
    </div>
  </div>
</div>

<!-- Modal Select Existing Product-->
<div class="modal fade" id="selectProductModal" tabindex="-1" aria-labelledby="selectProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Using 'modal-lg' for a wider modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectProductModalLabel">Select Existing Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="orders-code.php" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product-select">Select Product <span class="required">*</span></label>
                            <div>
                                <select name="product_id" id="product-select" class="form-select mySelect2">
                                    <option value="">-- Select Product --</option>
                                    <?php

                                    function getAllProducts($query) {
                                        // Assuming you have a database connection $conn
                                        global $conn;
                                        
                                        $result = mysqli_query($conn, $query);
                                        return $result;
                                    }

                                    $query = "SELECT * FROM tbl_products_penb WHERE id LIKE 'P%'";
                                    $products = getAllProducts($query);
                                    if ($products) {
                                        if (mysqli_num_rows($products) > 0) {
                                            while ($prodItem = mysqli_fetch_assoc($products)) {
                                                echo "<option value='{$prodItem['id']}'>{$prodItem['fld_product_name']}</option>";
                                            }
                                        } else {
                                            echo '<option value="">No product found!</option>';
                                        }
                                    } else {
                                        echo '<option value="">Something went wrong!</option>';
                                    }
                                    ?>

                                </select>
                            </div>

                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" name="quantity" value="1" class="form-control"/>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="addItem" class="btn btn-primary">Add Item</button>
                      </div>
                </form>


            </div>
        </div>
    </div>
</div>

<!-- Modal Select Existing Labour Charge-->
<div class="modal fade" id="selectChargeModal" tabindex="-1" aria-labelledby="selectChargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Using 'modal-lg' for a wider modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectChargeModal">Select Existing Charge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="orders-code.php" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="charge-select">Select Labour Charge <span class="required">*</span></label>
                            <div>
                                <select name="chargeId" id="charge-select" class="form-select mySelect3">
                                    <option value="">-- Select Charges --</option>
                                    <?php

                                    function getAllCharges($query) {
                                        // Assuming you have a database connection $conn
                                        global $conn;
                                        
                                        $result = mysqli_query($conn, $query);
                                        return $result;
                                    }

                                    $query = "SELECT * FROM tbl_labourcharge_penb WHERE id LIKE 'LC%'";
                                    $charges = getAllCharges($query);
                                    if ($charges) {
                                        if (mysqli_num_rows($charges) > 0) {
                                            while ($chargeItem = mysqli_fetch_assoc($charges)) {
                                                echo "<option value='{$chargeItem['id']}'>{$chargeItem['charge_desc']}</option>";
                                            }
                                        } else {
                                            echo '<option value="">No labour charge found!</option>';
                                        }
                                    } else {
                                        echo '<option value="">Something went wrong!</option>';
                                    }
                                    ?>

                                </select>
                            </div>

                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Quantity <span class="required">*</span></label>
                            <input type="number" name="quantity" value="1" class="form-control"/>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="addCharge" class="btn btn-primary">Add Item</button>
                      </div>
                </form>


            </div>
        </div>
    </div>
</div>

<!-- Define the edit item modal -->
<div class="modal fade" id="editItemModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <input type="hidden" id="editItemId" readonly>
                    <input type="hidden" id="editItemIndex" readonly>
                    <div class="mb-3">
                        <label for="editItemName">Product Name</label>
                        <input type="text" class="form-control" id="editItemName" name="itemName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editItemPrice">Product Price (Optional)</label>
                        <input type="number" step="0.01" class="form-control" id="editItemPrice" name="itemPrice">
                    </div>
                    <button type="submit" class="btn btn-primary saveItemChanges">Save Changes</button>
                </form>
            
                <div class="mt-3">
                            <p class="required mx-10" style="font-size: 14px;"><em>If there is no data displayed, please refresh the page.</em></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Define the edit CHARGE modal -->
<div class="modal fade" id="editChargeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editChargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Labour Charge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editChargeForm">
                    <input type="hidden" id="editChargeId" readonly>
                    <input type="hidden" id="editItemIndex" readonly>
                    <div class="mb-3">
                        <label for="editChargeName">Labour Charge Description</label>
                        <input type="text" class="form-control" id="editChargeName" name="chargeName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editChargePrice">Price</label>
                        <input type="number" step="0.01" class="form-control" id="editChargePrice" name="chargePrice">
                    </div>
                    <button type="submit" class="btn btn-primary saveChargeChanges">Save Changes</button>
                </form>
                <div class="mt-3">
                            <p class="required mx-10" style="font-size: 14px;"><em>If there is no data displayed, please refresh the page.</em></p>
                </div>
            </div>
        </div>
    </div>
</div>


<!----------------------------------------------------------- Script --------------------------------------------------------------------------->



<script>
$(document).ready(function() {
    // Initialize Select2 for product modal
    $('#selectProductModal').on('shown.bs.modal', function () {
        $('.mySelect2').select2({
            dropdownParent: $('#selectProductModal'),
            width: 'resolve' // Adjust width as needed
        });
    });

    // Initialize Select2 for charge modal
    $('#selectChargeModal').on('shown.bs.modal', function () {
        $('.mySelect3').select2({
            dropdownParent: $('#selectChargeModal'),
            width: 'resolve' // Adjust width as needed
        });
    });
});
</script>



<!-- Edit Existing Product -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll('.editProduct');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const item = <?= json_encode($sessionProducts); ?>[index];

            document.querySelector('#editItemId').value = item.product_id; 
            document.querySelector('#editItemName').value = item.name;
            document.querySelector('#editItemPrice').value = item.price;
            document.querySelector('#editItemIndex').value = index;
        });
    });

    document.querySelector('#editItemForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var i_id = $('#editItemId').val();
        var i_name = $('#editItemName').val();
        var i_price = $('#editItemPrice').val();
        var i_index = $('#editItemIndex').val();

        if (i_id !== '') {
            var data = {
                'saveChangesBtn': true,
                'iid': i_id,
                'name': i_name,
                'price': i_price,
                'index': i_index
            };

            $.ajax({
                url: 'orders-code.php',
                type: 'POST',
                data: data,
                success: function(response) {
                    var res = JSON.parse(response);

                    if (res.status == 200) {
                        swal(res.message, res.message, res.status_type).then((value) => {
                            if (value) {
                                window.location.reload();
                            }
                        });

                        var row = document.querySelector(`tr[data-index="${i_index}"]`);
                        if (row) {
                            row.querySelector('.product-name').innerText = i_name;
                            row.querySelector('.product-price').innerText = `RM${parseFloat(i_price).toFixed(2)}`;
                            var qty = row.querySelector('.quantityInput').value;
                            row.querySelector('.total-price').innerText = `RM${(parseFloat(i_price) * qty).toFixed(2)}`;
                        }
                    } else {
                        swal(res.message, res.message, res.status_type);
                    }
                },
                error: function(xhr, status, error) {
                    swal("Error", "An error occurred while processing your request.", "error");
                    console.error(xhr.responseText);
                }
            });
        } else {
            swal("No item Selected.", "", "warning");
        }
    });
});

</script>


<!-- Edit Existing Charge -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll('.editCharge');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const item = <?= json_encode($sessionCharges); ?>[index];

            document.querySelector('#editChargeId').value = item.chargeId; 
            document.querySelector('#editChargeName').value = item.charge_desc;
            document.querySelector('#editChargePrice').value = item.charge_price;
            document.querySelector('#editItemIndex').value = index;
        });
    });

    document.querySelector('#editChargeForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var c_id = $('#editChargeId').val();
        var c_name = $('#editChargeName').val();
        var c_price = $('#editChargePrice').val();
        var c_index = $('#editItemIndex').val();

        if (c_id !== '') {
            var data = {
                'saveChargeChanges': true,
                'cid': c_id,
                'name': c_name,
                'price': c_price,
                'index': c_index
            };

            $.ajax({
                url: 'orders-code.php',
                type: 'POST',
                data: data,
                success: function(response) {
                    console.log(response); // Log the full response
                    try {
                        var res = JSON.parse(response);

                        if (res.status == 200) {
                            swal(res.message, res.message, res.status_type).then((value) => {
                                if (value) {
                                    window.location.reload();
                                }
                            });

                            var row = document.querySelector(`tr[data-index="${c_index}"]`);
                            if (row) {
                                row.querySelector('.charge-name').innerText = c_name;
                                row.querySelector('.charge-price').innerText = `RM${parseFloat(c_price).toFixed(2)}`;
                                var qty = row.querySelector('.quantityInput').value;
                                row.querySelector('.total-price').innerText = `RM${(parseFloat(c_price) * qty).toFixed(2)}`;
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

 <script>
    $(document).ready(function() {
      // Check if there's a stored tab index
      var activeTab = localStorage.getItem('activeTab');
      if (activeTab) {
        // Remove the active class from currently active tab and pane
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        
        // Add the active class to the stored tab and pane
        $('#' + activeTab).addClass('active');
        $('#' + $('#' + activeTab).attr('data-bs-target').substring(1)).addClass('show active');
      }

      // Save the active tab index to local storage when a tab is clicked
      $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var tabId = $(e.target).attr('id');
        localStorage.setItem('activeTab', tabId);
      });
    });
  </script>

<?php include('includes/footer.php'); ?>