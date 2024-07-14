<?php include('includes/header.php'); 

function getAllCharge($tableName, $start_from, $results_per_page, $search = '') {
    global $conn;

    $table = validate($tableName); // Assuming validate function ensures $tableName is safe

    $query = "SELECT * FROM $table WHERE id LIKE 'LC%'";

    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $query .= " AND (id LIKE '%$search%' OR charge_desc LIKE '%$search%' OR charge_price LIKE '%$search%' OR charge_quantity LIKE '%$search%')";
    }

    $query .= " ORDER BY id ASC LIMIT $start_from, $results_per_page";

    return mysqli_query($conn, $query);
}

// Function to count total records in the customers table
function countTotalRecords($table_name, $search = '') {
    global $conn; // Assuming $conn is your database connection variable

    $sql = "SELECT COUNT(*) as total FROM $table_name WHERE id LIKE 'LC%'";

    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $sql .= " AND (id LIKE '%$search%' OR charge_desc LIKE '%$search%' OR charge_price LIKE '%$search%' OR charge_quantity LIKE '%$search%')";
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
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .pagination .page-link:hover {
        color: #0056b3; /* Darker shade for hover effect */
    }
</style>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Labour Charge
                <a href="charge-create.php" class="btn btn-primary float-end">Add Charge</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <!-- Search Form -->
            <form method="GET" action="">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search Charges" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>

            <?php
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $results_per_page = 20;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $start_from = ($page - 1) * $results_per_page;

                $products = getAllCharge('tbl_labourcharge_penb', $start_from, $results_per_page, $search);
                if ($products === false) {
                    echo '<h4>Something went wrong!</h4>';
                    return false;
                }
                if (mysqli_num_rows($products) > 0) {   
            ?>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Charge Description</th>
                            <th>Price</th>
                            <th>Stock Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($productItem = mysqli_fetch_assoc($products)): ?>
                        <tr>
                            <td><?= $productItem['id'] ?></td>
                            <td><?= $productItem['charge_desc'] ?></td>
                            <td>RM<?= number_format($productItem['charge_price'], 2); ?></td>
                            <td><?= $productItem['charge_quantity'] ?></td>
                            <td>
                                <!-- Add your action buttons here -->
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
                <td colspan="5">No Record Found</td>
            </tr>
            <?php
                }
            ?>

            <!-- Pagination Links -->
            <div class="text-center">
                <ul class="pagination justify-content-center">
                    <?php
                        $total_pages = ceil(countTotalRecords('tbl_labourcharge_penb', $search) / $results_per_page);

                        for ($i=1; $i<=$total_pages; $i++) {
                            echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page='.$i.'&search='.urlencode($search).'">'.$i.'</a></li>';
                        }
                    ?>
                </ul>
            </div>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
