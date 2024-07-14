<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Weekly Financial Report</h4>
        </div>
        <div class="card-body">
            <?php
                

                // Fetch available months for selection
                $sql = "SELECT DISTINCT DATE_FORMAT(order_date, '%Y-%m') as month FROM tbl_order_penb ORDER BY month";
                $result = $conn->query($sql);

                $months = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $months[] = $row['month'];
                    }
                } else {
                    echo "No months found.";
                }
            ?>

            <!-- Month Selection Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="month">Select Month:</label>
                    <select id="month" name="month" class="form-control" required>
                        <option value="">Select a month</option>
                        <?php
                        foreach ($months as $month) {
                            echo "<option value='$month'>$month</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Generate Report</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $selected_month = $_POST['month'];

                // Fetch weekly data for the selected month
                $sql = "
                    SELECT 
                        WEEK(order_date, 3) as week, -- Use mode 3 to start weeks on Monday
                        SUM(total_amount) as total_revenue,
                        SUM(total_expense) as total_expense
                    FROM tbl_order_penb
                    WHERE DATE_FORMAT(order_date, '%Y-%m') = '$selected_month'
                    GROUP BY WEEK(order_date, 3)
                    ORDER BY WEEK(order_date, 3)
                ";
                $result = $conn->query($sql);

                $weekly_data = array(
                    'week' => array(),
                    'total_revenue' => array(),
                    'total_expense' => array(),
                    'total_profit' => array()
                );

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $weekly_data['week'][] = 'Week ' . $row['week'];
                        $weekly_data['total_revenue'][] = $row['total_revenue'];
                        $weekly_data['total_expense'][] = $row['total_expense'];
                        $weekly_data['total_profit'][] = $row['total_revenue'] - $row['total_expense'];
                    }
                } else {
                    echo "No data found for the selected month.";
                }

                // Encode data to JSON format
                $weekly_data_json = json_encode($weekly_data);
            ?>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- Chart for Weekly Revenue, Expense, and Profit -->
            <canvas id="weeklyFinancialChart"></canvas>

            <script>
                // Data from the database
                const weeklyData = JSON.parse('<?php echo $weekly_data_json; ?>');

                // Chart for Weekly Revenue, Expense, and Profit
                const ctxWeekly = document.getElementById('weeklyFinancialChart').getContext('2d');
                const weeklyFinancialChart = new Chart(ctxWeekly, {
                    type: 'bar',
                    data: {
                        labels: weeklyData.week,
                        datasets: [
                            {
                                label: 'Total Revenue',
                                data: weeklyData.total_revenue,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Total Expense',
                                data: weeklyData.total_expense,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Total Profit',
                                data: weeklyData.total_profit,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Week'
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Amount'
                                }
                            }
                        }
                    }
                });
            </script>

            <?php
            }
            ?>
        </div>
    </div>
</div>


<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Monthly Financial Report</h4>
        </div>
        <div class="card-body">
            <?php
                
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch monthly data
                $sql = "
                    SELECT 
                        DATE_FORMAT(order_date, '%Y-%m') as month,
                        SUM(total_amount) as total_revenue,
                        SUM(total_expense) as total_expense
                    FROM tbl_order_penb
                    GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                    ORDER BY DATE_FORMAT(order_date, '%Y-%m')
                ";
                $result = $conn->query($sql);

                $monthly_data = array(
                    'month' => array(),
                    'total_revenue' => array(),
                    'total_expense' => array(),
                    'total_profit' => array()
                );

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $monthly_data['month'][] = $row['month'];
                        $monthly_data['total_revenue'][] = $row['total_revenue'];
                        $monthly_data['total_expense'][] = $row['total_expense'];
                        $monthly_data['total_profit'][] = $row['total_revenue'] - $row['total_expense'];
                    }
                } else {
                    echo "0 results";
                }

                

                // Encode data to JSON format
                $monthly_data_json = json_encode($monthly_data);
            ?>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- Chart for Monthly Revenue, Expense, and Profit -->
            <canvas id="monthlyFinancialChart"></canvas>

            <script>
                // Data from the database
                const monthlyData = JSON.parse('<?php echo $monthly_data_json; ?>');

                // Chart for Monthly Revenue, Expense, and Profit
                const ctxMonthly = document.getElementById('monthlyFinancialChart').getContext('2d');
                const monthlyFinancialChart = new Chart(ctxMonthly, {
                    type: 'bar',
                    data: {
                        labels: monthlyData.month,
                        datasets: [
                            {
                                label: 'Total Revenue',
                                data: monthlyData.total_revenue,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Total Expense',
                                data: monthlyData.total_expense,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Total Profit',
                                data: monthlyData.total_profit,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }
                        ]
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
                                    text: 'Amount'
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>



<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Financial Report</h4>
        </div>
        <div class="card-body">
            <?php
                // Fetch order data
                $sql = "SELECT invoice_no, total_amount, total_expense FROM tbl_order_penb";
                $result = $conn->query($sql);

                $data = array(
                    'invoice_no' => array(),
                    'total_amount' => array(),
                    'total_expense' => array()
                );

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $data['invoice_no'][] = $row['invoice_no'];
                        $data['total_amount'][] = $row['total_amount'];
                        $data['total_expense'][] = $row['total_expense'];
                    }
                } else {
                    echo "0 results";
                }

                // Fetch aggregated data by customer_name
                $sql = "
                    SELECT c.fld_customer_name, SUM(o.total_amount) as total_amount
                    FROM tbl_order_penb o
                    JOIN tbl_customers_penb c ON o.customer_id = c.id
                    GROUP BY c.fld_customer_name
                ";
                $result = $conn->query($sql);

                $customer_data = array(
                    'fld_customer_name' => array(),
                    'total_amount' => array()
                );

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $customer_data['fld_customer_name'][] = $row['fld_customer_name'];
                        $customer_data['total_amount'][] = $row['total_amount'];
                    }
                } else {
                    echo "0 results";
                }

                $conn->close();

                // Encode data to JSON format
                $data_json = json_encode($data);
                $customer_data_json = json_encode($customer_data);
            ?>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- Container for Line Chart -->
            <div class="chart-container mb-4">
                <canvas id="lineChart"></canvas>
            </div>

            <!-- Container for Bar Chart -->
            <div class="chart-container">
                <canvas id="customerChart"></canvas>
            </div>

            <script>
                // Data from the database
                const data = JSON.parse('<?php echo $data_json; ?>');
                const customerData = JSON.parse('<?php echo $customer_data_json; ?>');

                // Line Chart
                const ctxLine = document.getElementById('lineChart').getContext('2d');
                const lineChart = new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: data.invoice_no,
                        datasets: [
                            {
                                label: 'Total Revenue',
                                data: data.total_amount,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                fill: false
                            },
                            {
                                label: 'Total Expense',
                                data: data.total_expense,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Invoice No'
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Amount'
                                }
                            }
                        }
                    }
                });

                // Bar Chart for Customer Name vs Total Amount
                const ctxCustomer = document.getElementById('customerChart').getContext('2d');
                const customerChart = new Chart(ctxCustomer, {
                    type: 'bar',
                    data: {
                        labels: customerData.fld_customer_name,
                        datasets: [
                            {
                                label: 'Total Amount',
                                data: customerData.total_amount,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Customer Name'
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Total Amount'
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>
