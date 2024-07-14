
<?php include('includes/header.php');
function generateRandomID(){
  return 'P' . mt_rand(1000000, 9999999);
}

 ?>


   <div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Add Product
              <a href="products.php" class="btn btn-danger float-end">Back</a>
            </h4>
         </div>
         <div class="card-body">
         	<?php alertMessage(); ?>

         	<form action="code.php" method="post" enctype="multipart/form-data">

         		<div class="row">

         			<div class="col-md-12 mb-3">
	         			<label for="">Product ID </label> <i>(Auto-generated ID)</i>
	         			<input type="text" name="id" value="<?php echo  generateRandomID(); ?>" readonly class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Product Name <span class="required">*</span></label>
	         			<input type="text" name="name" required class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Price <span class="required">*</span></label>
	         			<input type="text" name="price" required class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Description </label>
	         			<input type="text" name="des"  class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Quantity </label>
	         			<input type="text" name="quantity"  class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Product Image </label>
	         			<i>(jpg, jpeg and png only)</i>
	         			<input type="file" name="image"  class="form-control" accept=".jpg, .jpeg, .png"/>
	         		</div>

	   				

	         		<div class="col-md-12 mb-3 text-end">
	         			<button type="submit" name="saveProduct" class="btn btn-primary">Save</button>
	         		</div>

         		</div>
         		
         	</form>

         	
    

         </div>
      </div>
   </div>

   

<?php include('includes/footer.php'); ?>