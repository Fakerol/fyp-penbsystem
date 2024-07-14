<?php include('includes/header.php'); ?>

<style>
    .status-completed {
        background-color: #26de81; /* Light green */
    }
    .status-awaiting {
        background-color: #f7b731; /* Light yellow */
    }
    .status-cancel {
        background-color: #fc5c65; /* Light red */
    }
    .table-header {
        background-color: #95a5a6; /* Dark background */
        color: #ffffff; /* White text color */
    }
</style>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Order List
                <a href="orders-create.php" class="btn btn-primary float-end">New Order</a>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" 
                                name="start_date" 
                                class="form-control"
                                value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>"
                                placeholder="Start Date"
                                >
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" 
                                name="end_date" 
                                class="form-control"
                                value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>"
                                placeholder="End Date"
                                >
                            </div>
                            <div class="col-md-4">
                                <label for="order_status" class="form-label">Order Status</label>
                                <select name="order_status" class="form-select">
                                    <option value="">-- Select Order Status --</option>
                                    <option value="Quotation" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'Quotation' ? 'selected' : '' ?>>Quotation</option>
                                    <option value="Awaiting Payment" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'Awaiting Payment' ? 'selected' : '' ?>>Awaiting Payment</option>
                                    <option value="Completed" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="Cancel" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'Cancel' ? 'selected' : '' ?>>Cancel</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-4">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="orders.php" class="btn btn-danger">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="card mt-2 shadow-sm">
        <div class="card-body">
            <?php
                // Pagination variables
                $limit = 20;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Define the conditions array
                $conditions = [];

                if (isset($_GET['start_date']) || isset($_GET['end_date']) || isset($_GET['order_status'])) {
                    $startDate = validate($_GET['start_date']);
                    $endDate = validate($_GET['end_date']);
                    $orderStatus = validate($_GET['order_status']);

                    if ($startDate != '') {
                        $conditions[] = "o.order_date >= '$startDate'";
                    }
                    if ($endDate != '') {
                        $conditions[] = "o.order_date <= '$endDate'";
                    }
                    if ($orderStatus != '') {
                        $conditions[] = "o.order_status = '$orderStatus'";
                    }

                    $query = "SELECT o.*, c.* FROM tbl_order_penb o, tbl_customers_penb c 
                              WHERE c.id = o.customer_id";

                    if (count($conditions) > 0) {
                        $query .= " AND " . implode(' AND ', $conditions);
                    }

                    $query .= " ORDER BY o.id DESC LIMIT $limit OFFSET $offset";
                } else {
                    $query = "SELECT o.*, c.* FROM tbl_order_penb o, tbl_customers_penb c 
                              WHERE c.id = o.customer_id ORDER BY o.id DESC LIMIT $limit OFFSET $offset";
                }

                $orders = mysqli_query($conn, $query);
                if ($orders) {
                    $totalSum = 0;
                    $totalExpense = 0;
                    $totalProfit = 0;

                    if (mysqli_num_rows($orders) > 0) {
            ?>
                        <table class="table table-hover align-items-center justify-content-center">
                            <thead class="table-header">
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Order Date</th>
                                    <th>Customer Name</th>
                                    <th>Plate Number</th>
                                    <th>Order Status</th>
                                    <th>Total Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $orderItem) : 
                                    $statusClass = '';
                                    if ($orderItem['order_status'] == 'Completed') {
                                        $statusClass = 'status-completed';
                                    } elseif ($orderItem['order_status'] == 'Awaiting Payment') {
                                        $statusClass = 'status-awaiting';
                                    } elseif ($orderItem['order_status'] == 'Cancel') {
                                        $statusClass = 'status-cancel';
                                    }
                                ?>
                                    <tr class="<?= $statusClass; ?>" 
                                        data-invoice="<?= $orderItem['invoice_no']; ?>"
                                        data-customer="<?= $orderItem['fld_customer_name']; ?>"
                                        data-plate="<?= $orderItem['plate_number']; ?>"
                                        data-status="<?= $orderItem['order_status']; ?>">
                                        <td class="fw-bold"><?= $orderItem['invoice_no']; ?></td>
                                        <td><?= date('d-M-Y', strtotime($orderItem['order_date'])); ?></td>
                                        <td><?= $orderItem['fld_customer_name']; ?></td>
                                        <td><?= $orderItem['plate_number']; ?></td>
                                        <td><?= $orderItem['order_status']; ?></td>
                                        <td>RM<?= number_format($orderItem['total_amount'], 2); ?></td>
                                        <td>
                                            <a href="orders-view.php?invoice=<?= $orderItem['invoice_no']; ?>" class="btn btn-info btn-md">
                                                <i class="bi-info-circle View"></i>  
                                            </a>
                                            <a href="<?php
                                                if ($orderItem['order_status'] == 'Quotation') {
                                                    echo 'print-quotation.php?invoice_no=' . $orderItem['invoice_no'];
                                                } elseif ($orderItem['order_status'] == 'Awaiting Payment') {
                                                    echo 'order-print.php?invoice_no=' . $orderItem['invoice_no'];
                                                } elseif ($orderItem['order_status'] == 'Completed') {
                                                    echo 'print-receipt.php?invoice_no=' . $orderItem['invoice_no'];
                                                }
                                            ?>" class="btn btn-primary btn-md print-btn">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="#" class="btn btn-secondary btn-md update-status-btn" data-bs-toggle="modal" data-bs-target="#updateStatus" data-invoice="<?= $orderItem['invoice_no']; ?>">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $totalSum += $orderItem['total_amount']; 
                                    $totalExpense += $orderItem['total_expense'];
                                    $totalrofit = $totalSum - $totalExpense;
                                    ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="float-end">
                            <div class="mt-0" style="margin-right: 170px;">
                                <h6 class="text-start">Total:</h6>
                                <h5 class="fw-bold">RM<?= number_format($totalSum, 2); ?></h5>
                            </div>
                            
                        </div>

                        <?php
                        // Get total number of records for pagination
                        $totalQuery = "SELECT COUNT(*) as total FROM tbl_order_penb o, tbl_customers_penb c WHERE c.id = o.customer_id";
                        if (count($conditions) > 0) {
                            $totalQuery .= " AND " . implode(' AND ', $conditions);
                        }
                        $totalResult = mysqli_query($conn, $totalQuery);
                        $totalRow = mysqli_fetch_assoc($totalResult);
                        $totalOrders = $totalRow['total'];
                        $totalPages = ceil($totalOrders / $limit);

                        // Pagination controls
                        if ($totalPages > 1) {
                            echo '<nav aria-label="Page navigation">';
                            echo '<ul class="pagination justify-content-center">';
                            if ($page > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Previous</a></li>';
                            }
                            for ($i = 1; $i <= $totalPages; $i++) {
                                $active = $i == $page ? 'active' : '';
                                echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                            }
                            if ($page < $totalPages) {
                                echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Next</a></li>';
                            }
                            echo '</ul>';
                            echo '</nav>';
                        }
                    } else {
                        echo "<p>No orders found.</p>";
                    }
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            ?>
        </div>
    </div>
</div>





<div class="modal fade" id="updateStatus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <div class="mb-3">
                        <label for="invoiceNumber" class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" id="invoiceNumber" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customerName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="plateNumber" class="form-label">Plate Number</label>
                        <input type="text" class="form-control" id="plateNumber" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="orderStatus" class="form-label">Order Status <span class="required">*</span></label>
                        <select class="form-select" id="orderStatus">
                            <option value="Quotation">Quotation</option>
                            <option value="Awaiting Payment">Awaiting Payment</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancel">Cancel</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateOrderStatusButton">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
  document.querySelectorAll('.update-status-btn').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('tr');
        const invoice = row.getAttribute('data-invoice');
        const customer = row.getAttribute('data-customer');
        const plate = row.getAttribute('data-plate');
        const status = row.getAttribute('data-status');
        
        document.getElementById('invoiceNumber').value = invoice;
        document.getElementById('customerName').value = customer;
        document.getElementById('plateNumber').value = plate;
        document.getElementById('orderStatus').value = status;
    });
});

document.getElementById('updateOrderStatusButton').addEventListener('click', function() {

    const invoice = document.getElementById('invoiceNumber').value;
    const status = document.getElementById('orderStatus').value;

    if(invoice !== ''){
        var data = {
            'updateStatusBtn': true,
            'invoice_no': invoice,
            'order_status': status
        };

    $.ajax({
        url: 'update-order-status.php',
        method: 'POST',
        data: data,
        success: function(response) {
            var res = JSON.parse(response);

                    if (res.status == 200) {
                        swal(res.message, res.message, res.status_type).then((value) => {
                            if (value) {
                                window.location.reload(); // Refresh the page
                            }
                        });
                       

                    } else {
                        swal(res.message, res.message, res.status_type);
                    }
                }
       
            });
        } else{
            swal("No invoice Selected.", "", "warning");
         
        }
        
    var updateModal = bootstrap.Modal.getInstance(document.getElementById('updateStatus'));
    updateModal.hide();

});


</script>


<?php include('includes/footer.php'); ?>
