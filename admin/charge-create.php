
<?php include('includes/header.php');
function generateRandomID(){
  return 'LC' . mt_rand(1000000, 9999999);
}

 ?>


   <div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Add Labour Charge
              <a href="products.php" class="btn btn-danger float-end">Back</a>
            </h4>
         </div>
         <div class="card-body">
         	<?php alertMessage(); ?>

         	<form action="code.php" method="post" >

         		<div class="row">

         			<div class="col-md-12 mb-3">
	         			<label for="">Charge ID </label> <i>(Auto-generated ID)</i>
	         			<input type="text" name="id" value="<?php echo  generateRandomID(); ?>" readonly class="form-control" />
	         		</div>

	         		<div class="col-md-12 mb-3">
	         			<label for="">Charge Description<span class="required">*</span></label>
	         			<input type="text" name="desc" required class="form-control" />
	         		</div>

	         		<div class="col-md-12 mb-3">
	         			<label for="">Price <span class="required">*</span></label>
	         			<input type="text" name="price" required class="form-control" />
	         		</div>


	         		<div class="col-md-12 mb-3">
	         			<label for="">Stock Quantity </label>
	         			<input type="text" readonly name="quantity" value="200" class="form-control" />
	         		</div>

	         		
	         		<div class="col-md-12 mb-3 text-end">
	         			<button type="submit" name="saveCharge" class="btn btn-primary">Save</button>
	         		</div>

         		</div>
         		
         	</form>

         	
    

         </div>
      </div>
   </div>

   

<?php include('includes/footer.php'); ?>