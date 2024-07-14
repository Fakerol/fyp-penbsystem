<?php include('includes/header.php'); ?>

<?php

function getAllCust($table_name, $start_from, $results_per_page, $search = '') {
   global $conn; // Ensure $conn is defined in your db.php

   $sql = "SELECT * FROM $table_name";
   if (!empty($search)) {
      $search = mysqli_real_escape_string($conn, $search);
      $sql .= " WHERE id LIKE '%$search%' OR plate_number LIKE '%$search%' OR fld_customer_name LIKE '%$search%' OR fld_customer_address1 LIKE '%$search%' OR fld_customer_city LIKE '%$search%' OR fld_customer_state LIKE '%$search%'";
   }
   $sql .= " ORDER BY time DESC"; // Correct placement of ORDER BY clause
   $sql .= " LIMIT $start_from, $results_per_page";

   $result = mysqli_query($conn, $sql);

   return $result;
}

function countTotalRecords($table_name, $search = '') {
   global $conn; // Assuming $conn is your database connection variable

   $sql = "SELECT COUNT(*) as total FROM $table_name";
   if (!empty($search)) {
      $search = mysqli_real_escape_string($conn, $search);
      $sql .= " WHERE id LIKE '%$search%' OR plate_number LIKE '%$search%' OR fld_customer_name LIKE '%$search%' OR fld_customer_address1 LIKE '%$search%' OR fld_customer_city LIKE '%$search%' OR fld_customer_state LIKE '%$search%'";
   }

   $result = mysqli_query($conn, $sql);
   $row = mysqli_fetch_assoc($result);
   return $row['total'];
}
?>

<style>
   .pagination .page-link {
      color: #007bff; /* Bootstrap primary blue color */
   }

   .pagination .page-item.active .page-link {
      background-color: black;
      border-color: black;
      color: black;
   }

   .pagination .page-link:hover {
      color: #0056b3; /* Darker shade for hover effect */
   }
</style>

<div class="container-fluid px-4">
   <div class="card mt-4 shadow-sm">
      <div class="card-header">
         <h4 class="mb-0">Customers List
            <a href="customers-create.php" class="btn btn-primary float-end">Add Customer</a>
         </h4>
      </div>
      <div class="card-body">
         <?php alertMessage(); ?>

         <!-- Search Form -->
         <form method="GET" action="">
            <div class="input-group mb-3">
               <input type="text" name="search" class="form-control" placeholder="Search Customers" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
               <button class="btn btn-primary" type="submit">Search</button>
            </div>
         </form>

         <?php
            $results_per_page = 15;

            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $start_from = ($page - 1) * $results_per_page;

            $customers = getAllCust('tbl_customers_penb', $start_from, $results_per_page, $search);
            if(!$customers){
               echo '<h4>Something went wrong!<h4>';
               return false;
            }
            if(mysqli_num_rows($customers) > 0){   
         ?>

         <div class="table-responsive">
            <table class="table table-hover">
               <thead>
                  <tr>
                     <th>Customer ID</th>
                     <th>Plate Number</th>
                     <th>Name</th>
                     <th>City</th>
                     <th>State</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php while ($customerItem = mysqli_fetch_assoc($customers)): ?>
                  <tr>
                     <td><?= $customerItem['id'] ?></td>
                     <td><?= $customerItem['plate_number'] ?></td>
                     <td><?= $customerItem['fld_customer_name'] ?></td>
                     <td><?= $customerItem['fld_customer_city'] ?></td>
                     <td><?= $customerItem['fld_customer_state'] ?></td>

                     
                     <td>
                        <?php if ($_SESSION['loggedInUser']['name'] == 'ADMIN'): ?>
                        <a href="customers-edit.php?id=<?= $customerItem['id'] ?>" class="btn btn-primary btn-md editProduct">
                           <i class="bi bi-pencil-square"></i> <!-- Icon for Edit -->
                        </a>
                        <a href="customers-delete.php?id=<?= $customerItem['id'] ?>" class="btn btn-danger btn-md">
                           <i class="bi bi-trash"></i> <!-- Icon for Delete -->
                        </a>
                        <?php endif; ?>
                     </td>
                     
                  </tr>
                  <?php endwhile; ?>
               </tbody>
            </table>
         </div>
         <?php
            } else {
         ?>
         <tr>
            <td colspan="7">No Record Found</td>
         </tr>
         <?php
            }
         ?>

         <!-- Pagination Links -->
         <div class="text-center">
            <ul class="pagination justify-content-center">
               <?php
                  $total_pages = ceil(countTotalRecords('tbl_customers_penb', $search) / $results_per_page);

                  for ($i=1; $i<=$total_pages; $i++) {
                     $search_query = !empty($search) ? '&search=' . urlencode($search) : '';
                     echo '<li class="page-item"><a class="page-link" href="?page='.$i.$search_query.'">'.$i.'</a></li>';
                  }
               ?>
            </ul>
         </div>

      </div>
   </div>
</div>

<?php include('includes/footer.php'); ?>
