<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Expense List</h4>
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
                                    <option value="Awaiting Payment" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'Awaiting Payment' ? 'selected' : '' ?>>Awaiting Payment</option>
                                    <option value="Completed" <?= isset($_GET['order_status']) && $_GET['order_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    
                                </select>
                            </div>
                            <div class="col-md-3 mt-4">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="expenses.php" class="btn btn-danger">Clear</a>
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

                // Initialize total variables
                $totalRevenue = 0;
                $totalExpenses = 0;
                $totalProfit = 0;

                // Define the conditions array
                $conditions = [];

                $startDate = isset($_GET['start_date']) ? validate($_GET['start_date']) : '';
                $endDate = isset($_GET['end_date']) ? validate($_GET['end_date']) : '';
                $orderStatus = isset($_GET['order_status']) ? validate($_GET['order_status']) : '';

                if ($startDate != '') {
                    $conditions[] = "o.order_date >= '$startDate'";
                }
                if ($endDate != '') {
                    $conditions[] = "o.order_date <= '$endDate'";
                }
                if ($orderStatus != '') {
                    $conditions[] = "o.order_status = '$orderStatus'";
                }

                $query = "SELECT COUNT(*) AS total FROM tbl_order_penb o, tbl_customers_penb c 
                          WHERE c.id = o.customer_id";

                if (count($conditions) > 0) {
                    $query .= " AND " . implode(' AND ', $conditions);
                }

                $result = mysqli_query($conn, $query);
                $totalRows = mysqli_fetch_assoc($result)['total'];
                $totalPages = ceil($totalRows / $limit);

                $query = "SELECT o.*, c.* FROM tbl_order_penb o, tbl_customers_penb c 
                          WHERE c.id = o.customer_id AND  o.order_status IN ('Awaiting payment', 'Completed')";

                if (count($conditions) > 0) {
                    $query .= " AND " . implode(' AND ', $conditions);
                }

                $query .= " ORDER BY o.id DESC LIMIT $limit OFFSET $offset";

                $orders = mysqli_query($conn, $query);
                if ($orders) {
                    if (mysqli_num_rows($orders) > 0) {
            ?>
            <table class="table table-hover align-items-center justify-content-center">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Plate No</th>
                        <th>Order Status</th>
                        <th>Revenue</th>
                        <th>Expenses</th>
                        <th>Profit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($orderItem = mysqli_fetch_assoc($orders)) : ?>
                        <?php
                            $revenue = $orderItem['total_amount'];
                            $expenses = $orderItem['total_expense'];
                            $profit = $revenue - $expenses;

                            // Calculate Margin Profit
                            $marginProfit = $revenue > 0 ? ($profit / $revenue) * 100 : 0;

                            // Accumulate totals
                            $totalRevenue += $revenue;
                            $totalExpenses += $expenses;
                            $totalProfit += $profit;

                            $disablePlusButton = ($expenses > 0 || $orderItem['order_status'] == 'Quotation' || $orderItem['order_status'] == 'Cancel') ? 'disabled' : '';
                        ?>
                        <tr>
                            <td class="fw-bold"><?= $orderItem['invoice_no']; ?></td>
                            <td><?= date('d-M-Y', strtotime($orderItem['order_date'])); ?></td>
                            <td><?= $orderItem['fld_customer_name']; ?></td>
                            <td><?= $orderItem['plate_number']; ?></td>
                            <td><?= $orderItem['order_status']; ?></td>
                            <td>RM<?= number_format($revenue, 2); ?></td>
                            <td>RM<?= number_format($expenses, 2); ?></td>
                            <td>RM<?= number_format($profit, 2); ?></td>
                           
                            <td>
                                <a href="expenses-create.php?invoice=<?= $orderItem['invoice_no']; ?>" class="btn btn-info mb-0 px-2 btn-md <?= $disablePlusButton; ?>">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                                <a href="expenses-view.php?invoice=<?= $orderItem['invoice_no']; ?>" class="btn btn-primary mb-0 px-2 btn-md">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Pagination links -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>&order_status=<?= $orderStatus ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>&order_status=<?= $orderStatus ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>&order_status=<?= $orderStatus ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="d-flex justify-content-between m-3 float-end">
                <div class="mx-3">
                    <h6 class="text-start">Total Revenue:</h6>
                    <h5 class="fw-bold">RM<?= number_format($totalRevenue, 2); ?></h5>
                </div>
                <div class="mx-3">
                    <h6 class="text-start">Total Expense:</h6>
                    <h5 class="fw-bold">RM<?= number_format($totalExpenses, 2); ?></h5>
                </div>
                <div class="mx-3">
                    <h6 class="text-start">Total Profit:</h6>
                    <h5 class="fw-bold">RM<?= number_format($totalProfit, 2); ?></h5>
                </div>
            </div>

            <?php
                    } else {
                        echo "<h5>No record available</h5>";
                    }
                } else {
                    echo "<h5>Something went wrong</h5>";
                }
            ?>

            <div class="mt-0">
                <p class="required mx-10" style="font-size: 14px;"><em> ** Only "Awaiting Payment" and "Completed" order statuses can create expense details. <br> ** Once the expense details are created, it is not allowed to create them again.</em></p>
            </div>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
