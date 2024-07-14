<?php include('includes/header.php'); ?>


   <div class="container-fluid px-4">
      <div class="card mt-4 shadow-sm">
         <div class="card-header">
            <h4 class="mb-0">Admins/Staff 
              <a href="admins-create.php" class="btn btn-primary float-end">Add Admin</a>
            </h4>
         </div>
         <div class="card-body">
            <?php alertMessage(); ?>

            <?php
                  $admins = getAll('tbl_admins_penb');
                  if(!$admins){
                     echo '<h4>Something went wrong!<h4>';
                     return false;
                  }
                  if(mysqli_num_rows($admins) > 0){   
             ?>

            <div class="table-responsive">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>

                     </tr>
                  </thead>
                  <tbody>
                     
                     <?php foreach ($admins as $adminItem): ?>
                         
                      
                     <tr>
                        <td><?= $adminItem['id']  ?></td>
                        <td><?= $adminItem['name']  ?></td>
                        <td><?= $adminItem['phone']  ?></td>
                        <td><?= $adminItem['email']  ?></td>
                        <td>
                           <a href="admins-edit.php?id=<?= $adminItem['id']?>" class="btn btn-primary btn-lg editProduct">
                              <i class="bi bi-pencil-square"></i> <!-- Icon for Edit -->
                           </a>
                           <a href="admins-delete.php?id=<?= $adminItem['id']?>" class="btn btn-danger btn-lg">
                               <i class="bi bi-trash"></i> <!-- Icon for Delete -->
                           </a>
                        </td>



                     </tr>
                        <?php endforeach ?>

                       
                  </tbody>
                  
               </table>
            </div>
             <?php
               }
               else{
                  ?>
                  <tr>
               <td colspan="4">No Record Found</td>
            </tr>
            <?php
               }
               ?>

         </div>
      </div>
   </div>

<?php include('includes/footer.php'); ?>