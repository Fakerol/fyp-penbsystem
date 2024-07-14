
<?php include('includes/header.php');

function generateRandomID(){
  return mt_rand(1000000, 9999999);
}

 ?>


   <div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Add Customer
              <a href="customers.php" class="btn btn-danger float-end">Back</a>
            </h4>
         </div>
         <div class="card-body">
         	<?php alertMessage(); ?>

         	<form action="code.php" method="post">

         		<div class="row">

         			<div class="col-md-12 mb-3">
	         			<label for="">Customer ID </label> <i>(Auto-generated ID)</i>
	         			<input type="text" name="id" value="<?php echo  generateRandomID(); ?>" readonly class="form-control" />
	         		</div>

	   
	         		<div class="col-md-12 mb-3">
	         			<label for="">Plate Number <span class="required">*</span></label>
	         			<input type="text" name="plate" required class="form-control" />
	         		</div>

	         		<div class="col-md-12 mb-3">
	         			<label for="">Customer Name <span class="required">*</span></label>
	         			<input type="text" name="name" required class="form-control" />
	         		</div>

	         	

	         		<div class="col-md-6 mb-3">
	         			<label for="">Phone Number</label>
	         			<input type="number" name="phone" class="form-control" />
	         		</div>


	         		<div class="col-md-6 mb-3">
	         			<label for="">Email</label>
	         			<input type="email" name="email" class="form-control" />
	         		</div>

	   				<div class="col-md-12 mb-3">
	         			<label for="">Address 1</label>
	         			<input type="text" name="address1" class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Address 2</label>
	         			<input type="text" name="address2" class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">City</label>
	         			<input type="text" name="city" class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Poscode</label>
	         			<input type="number" name="poscode" class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">State</label>
	         			<input type="text" name="state" class="form-control" />
	         		</div>

	         		<div class="col-md-12 mb-3 text-end">
	         			<button type="submit" name="saveCustomer" class="btn btn-primary">Save</button>
	         		</div>

         		</div>
         		
         	</form>

         	
            

         </div>
      </div>
   </div>

<?php include('includes/footer.php'); ?>