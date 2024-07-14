<?php include('includes/header.php'); ?>

  <div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Update Product
              <a href="products.php" class="btn btn-danger float-end">Back</a>
            </h4>
         </div>
         <div class="card-body">
         	<?php alertMessage(); ?>

         	<form action="code.php" method="post" enctype="multipart/form-data">

         		<?php
                if (isset($_GET['id'])) {
                    if ($_GET['id'] != '') {
                        $productId = $_GET['id'];
                    } else {
                        echo '<h5>No ID Found</h5>';
                        return false;
                    }
                } else {
                    echo '<h5>No ID given in params!</h5>';
                    return false;
                }

                $produtData = getById('tbl_products_penb', $productId);

                if ($produtData) {
                    if ($produtData['status'] == 200) {
                ?>

         		<div class="row">

         			<div class="col-md-12 mb-3">
	         			<label for="">Product ID </label> <i>(Auto-generated ID)</i>
	         			<input type="text" name="id" value="<?= $produtData['data']['id']; ?>" readonly class="form-control" />
                            </div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Product Name *</label>
	         			<input type="text" name="fld_product_name" required value="<?= $produtData['data']['fld_product_name'] ?>" class="form-control" />
                            </div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Price *</label>
	         			<input type="text" name="fld_product_price"  value=" <?= number_format($produtData['data']['fld_product_price'], 2)?>" class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Description </label>
	         			<input type="text" name="fld_product_desc"  value="<?= $produtData['data']['fld_product_desc'] ?>" class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
	         			<label for="">Quantity </label>
	         			<input type="text" name="fld_product_quantity"  value="<?= $produtData['data']['fld_product_quantity'] ?>" class="form-control" />
	         		</div>

	         		<div class="col-md-6 mb-3">
					    <label for="">Product Image </label>
					    <i>(jpg, jpeg and png only)</i>
					    <input type="text" name="current_image" placeholder="No Image Added" value="<?= $produtData['data']['fld_product_image'] ?>" readonly class="form-control" />
					</div>

	         		<div class="col-md-12 mb-3 text-end">
	         			<button type="submit" name="updateProduct" class="btn btn-primary">Update</button>
	         		</div>

         		</div>
         		<?php
                    } else {
                        echo '<h5>' . $produtData['message'] . '</h5>';
                    }
                } else {
                    echo '<h5>Something Went Wrong!</h5>';
                    return false;
                }
                ?>
         	</form>

         </div>
      </div>
   </div>
<?php include('includes/footer.php'); ?>
