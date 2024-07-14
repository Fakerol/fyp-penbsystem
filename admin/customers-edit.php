
<?php 
include('includes/header.php');

 ?>

   <div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Update Customer
              <a href="customers.php" class="btn btn-danger mx-2 btn-md float-end">Back</a>
            </h4>
         </div>
         <div class="card-body">
            <?php alertMessage(); ?>

            <form action="code.php" method="post">

                <?php
                $paramValue = checkParamId("id");

                if(!is_numeric($paramValue)){
                    echo '<h5>'.$paramValue.'</h5>';
                    return false;
                }

                $customer = getById('tbl_customers_penb', $paramValue);
                if ($customer['status'] == 200) {
                   ?>

                     <div class="row">

                    <div class="col-md-12 mb-3">
                        <label for="">Customer ID </label> <i>(Auto-generated ID)</i>
                        <input type="text" name="id" value="<?= $customer['data']['id']; ?>" readonly class="form-control" />
                    </div>

        
                    <div class="col-md-6 mb-3">
                        <label for="">Plate Number *</label>
                        <input type="text" name="plate" value="<?= $customer['data']['plate_number']; ?>"required class="form-control" />
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="">Customer Name *</label>
                        <input type="text" name="name" value="<?= $customer['data']['fld_customer_name']; ?>" required class="form-control" />
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="">Phone Number *</label>
                        <input type="number" name="phone" value="<?= $customer['data']['fld_customer_phone']; ?>" required class="form-control" />
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="">Email *</label>
                        <input type="email" name="email" value="<?= $customer['data']['fld_customer_email']; ?>" required class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">Address 1</label>
                        <input type="text" name="address1" value="<?= $customer['data']['fld_customer_address1']; ?>" class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">Address 2</label>
                        <input type="text" name="address2" value="<?= $customer['data']['fld_customer_address2']; ?>" class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">City</label>
                        <input type="text" name="city" value="<?= $customer['data']['fld_customer_city']; ?>" class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">Poscode</label>
                        <input type="number" name="poscode" value="<?= $customer['data']['fld_customer_poscode']; ?>" class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">State</label>
                        <input type="text" name="state" value="<?= $customer['data']['fld_customer_state']; ?>" class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3 text-end">
                        <button type="submit" name="updateCustomer" class="btn btn-primary">Save</button>
                    </div>

                </div>

                   <?php
                }
                else{
                    echo '<h5>'.$customer['message'].'</h5>';
                    return false;
                }

                ?>

              
                
            </form>

            
            

         </div>
      </div>
   </div>

<?php include('includes/footer.php'); ?>