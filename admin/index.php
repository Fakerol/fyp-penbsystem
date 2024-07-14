<?php include('includes/header.php'); ?>
<style>
    .card-title {
        color: #2C2C54;
        font-size: 18px;
    }
    .card-text {
        color: #2C2C54;
        font-size: 40px;
        font-weight: bold;
        text-align: center;
    }
    .percentage {
        font-size: 1.5rem;
        text-align: center;
    }
    .text-muted {
        text-align: center;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Display total order this month--->
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-4">
            <?php
            // Get current month and previous month
            $currentMonth = date('m');
            $currentYear = date('Y');
            $previousMonth = date('m', strtotime('-1 month'));
            $previousYear = date('Y', strtotime('-1 month'));

            // SQL query to calculate the total number of orders for the current month
            $sql_current_month_orders = "SELECT COUNT(*) AS total_orders FROM tbl_order_penb WHERE MONTH(order_date) = $currentMonth AND YEAR(order_date) = $currentYear";
            $result_current_month_orders = $conn->query($sql_current_month_orders);

            // SQL query to calculate the total number of orders for the previous month
            $sql_previous_month_orders = "SELECT COUNT(*) AS total_orders FROM tbl_order_penb WHERE MONTH(order_date) = $previousMonth AND YEAR(order_date) = $previousYear";
            $result_previous_month_orders = $conn->query($sql_previous_month_orders);

            // Initialize total orders variables
            $total_orders_current = 0;
            $total_orders_previous = 0;

            if ($result_current_month_orders->num_rows > 0) {
                // Fetch the total orders for the current month
                $row_current_orders = $result_current_month_orders->fetch_assoc();
                $total_orders_current = $row_current_orders['total_orders'];
            }

            if ($result_previous_month_orders->num_rows > 0) {
                // Fetch the total orders for the previous month
                $row_previous_orders = $result_previous_month_orders->fetch_assoc();
                $total_orders_previous = $row_previous_orders['total_orders'];
            }

            // Calculate the percentage difference for orders
            $percentage_difference_orders = 0;
            if ($total_orders_previous > 0) {
                $percentage_difference_orders = (($total_orders_current - $total_orders_previous) / $total_orders_previous) * 100;
            }

            // Determine the color of the percentage difference for orders
            $percentage_color_orders = $percentage_difference_orders >= 0 ? 'green' : 'red';

            ?>


            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Orders <span id="currentMonthYear"></span></h5>
                    <p class="card-text"><?php echo $total_orders_current; ?></p>
                    <p class="percentage" style="color: <?php echo $percentage_color_orders; ?>;">
                        <?php echo $percentage_difference_orders >= 0 ? '↑' : '↓'; ?>
                        <?php echo abs(number_format($percentage_difference_orders, 2)); ?>%
                    </p>
                    <p class="text-muted">vs previous 30 days</p>
                </div>
            </div>
        </div>

        
        <div class="col-md-4">
            <?php
            // SQL query to calculate total revenue for the current month
            $sql_current_revenue = "SELECT SUM(total_amount) AS total_revenue FROM tbl_order_penb WHERE MONTH(order_date) = $currentMonth AND YEAR(order_date) = $currentYear";
            $result_current_revenue = $conn->query($sql_current_revenue);

            // SQL query to calculate total revenue for the previous month
            $sql_previous_revenue = "SELECT SUM(total_amount) AS total_revenue FROM tbl_order_penb WHERE MONTH(order_date) = $previousMonth AND YEAR(order_date) = $previousYear";
            $result_previous_revenue = $conn->query($sql_previous_revenue);

            // Initialize total revenue variables
            $total_revenue_current = 0;
            $total_revenue_previous = 0;

            if ($result_current_revenue->num_rows > 0) {
                // Fetch the total revenue for the current month
                $row_current_revenue = $result_current_revenue->fetch_assoc();
                $total_revenue_current = $row_current_revenue['total_revenue'];
            }

            if ($result_previous_revenue->num_rows > 0) {
                // Fetch the total revenue for the previous month
                $row_previous_revenue = $result_previous_revenue->fetch_assoc();
                $total_revenue_previous = $row_previous_revenue['total_revenue'];
            }

            // Calculate the percentage difference for revenue
            $percentage_difference_revenue = 0;
            if ($total_revenue_previous > 0) {
                $percentage_difference_revenue = (($total_revenue_current - $total_revenue_previous) / $total_revenue_previous) * 100;
            }

            // Determine the color of the percentage difference for revenue
            $percentage_color_revenue = $percentage_difference_revenue >= 0 ? 'green' : 'red';

            ?>
            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue <span id="currentMonthYear"></h5>
                    <p class="card-text">RM<?= number_format($total_revenue_current, 2); ?></p>
                    <p class="percentage" style="color: <?php echo $percentage_color_revenue; ?>;">
                        <?php echo $percentage_difference_revenue >= 0 ? '↑' : '↓'; ?>
                        <?php echo abs(floatval(str_replace(',', '', number_format($percentage_difference_revenue, 2)))); ?>%

                    </p>
                    <p class="text-muted">vs previous 30 days</p>
                </div>
            </div>
        </div>

    <?php
$sql_orderStatus = "SELECT order_status, COUNT(*) AS status_count
                    FROM tbl_order_penb
                    WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) 
                      AND YEAR(order_date) = YEAR(CURRENT_DATE())
                    GROUP BY order_status
                    ORDER BY CASE
                        WHEN order_status = 'Completed' THEN 1
                        WHEN order_status = 'Awaiting Payment' THEN 2
                        WHEN order_status = 'Quotation' THEN 3
                        ELSE 4
                    END";

$result = $conn->query($sql_orderStatus);
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$jsonData = json_encode($data, JSON_PRETTY_PRINT);
?>
<div class="col-md-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Order Status This Month</h5>
            <hr>
            <div class="order-status-list">
                <?php
                foreach ($data as $order) {
                    echo '<div class="order-status-item">';
                    echo '<span class="order-status">' . htmlspecialchars($order['order_status']) . '</span>';
                    echo '<span class="status-count">' . htmlspecialchars($order['status_count']) . '</span>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<style>
    .order-status-list {
        display: flex;
        flex-direction: column;
        gap: 7px;
        overflow-y: auto;

    }

    .order-status-item {
        display: flex;
        justify-content: space-between;
        padding: 6px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        height: 40px;
    }

    .order-status-item:hover {
        background-color: #f1f1f1;
    }

    .order-status {
        font-weight: bold;
    }

    .status-count {
        background-color: #007bff;
        color: white;
        padding: 2px 10px;
        border-radius: 20px;
    }
</style>




    </div>
</div>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-6">
            <?php
            // Assuming you have a valid database connection in $conn
            $sql_top_customer = "SELECT c.fld_customer_name, COUNT(o.id) AS order_count 
                                 FROM tbl_order_penb o 
                                 JOIN tbl_customers_penb c ON o.customer_id = c.id 
                                 GROUP BY c.fld_customer_name 
                                 ORDER BY order_count DESC 
                                 LIMIT 3";
            $result = $conn->query($sql_top_customer);

            $data = array(
                'fld_customer_name' => array(),
                'order_count' => array()
            );

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data['fld_customer_name'][] = $row['fld_customer_name'];
                    $data['order_count'][] = $row['order_count'];
                }
            }

            // Encode the data as JSON to pass to JavaScript
            $jsonData = json_encode($data);
            ?>
            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Customer Contribution</h5>
                    <canvas id="orderChart" width="300" height="130"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <?php
                            
                           // Fetch monthly data
            $sql = "
                SELECT 
                    DATE_FORMAT(order_date, '%Y-%m') as month,
                    SUM(total_amount) as total_revenue
                FROM tbl_order_penb
                GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                ORDER BY DATE_FORMAT(order_date, '%Y-%m')
            ";
            $result = $conn->query($sql);

            $monthly_data = array(
                'month' => array(),
                'total_revenue' => array()
            );

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $monthly_data['month'][] = $row['month'];
                    $monthly_data['total_revenue'][] = $row['total_revenue'];
                }
            } else {
                echo "0 results";
            }

            // Encode data to JSON format
            $monthly_data_json = json_encode($monthly_data);
                ?>

            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue vs Months</h5>
                    <canvas id="monthlyFinancialChart" width="300" height="130"></canvas>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data from the database
    const monthlyData = JSON.parse('<?php echo $monthly_data_json; ?>');

    // Chart for Monthly Revenue
    const ctxMonthly = document.getElementById('monthlyFinancialChart').getContext('2d');
    const monthlyFinancialChart = new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: monthlyData.month,
            datasets: [{
                label: 'Total Revenue',
                data: monthlyData.total_revenue,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Revenue'
                    }
                }
            }
        }
    });
</script>



<script>
    // Parse the JSON data
    const data = JSON.parse('<?php echo $jsonData; ?>');
    const labels = data.fld_customer_name;
    const orderCounts = data.order_count;

    const ctx = document.getElementById('orderChart').getContext('2d');
    const orderChart = new Chart(ctx, {
        type: 'bar', // You can change this to 'pie' or 'line' depending on your preference
        data: {
            labels: labels,
           datasets: [{
                label: '# of Orders',
                data: orderCounts,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    // Add more colors if you have more than 3 bars
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    // Add more border colors if you have more than 3 bars
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
                // Get current date
                var currentDate = new Date();

                // Array of month names
                var monthNames = ["January", "February", "March", "April", "May", "June",
                                  "July", "August", "September", "October", "November", "December"];

                // Construct the text
                var currentMonthYear = monthNames[currentDate.getMonth()] + " " + currentDate.getFullYear();

                // Set the text content of the span
                document.getElementById("currentMonthYear").textContent = currentMonthYear;
            </script>



<?php
// Close the connection
$conn->close();
?>

<?php include('includes/footer.php'); ?>
