<?php 
include('includes/header.php'); 

if (!isset($_GET['invoice_no'])) {
    echo '<script>window.location.href = "orders.php"</script>';
    exit;
}

$invoiceNo = validate($_GET['invoice_no']);

$orderQuery = "SELECT o.*, c.* FROM tbl_order_penb o JOIN tbl_customers_penb c ON c.id = o.customer_id WHERE o.invoice_no = '$invoiceNo' LIMIT 1";
$orderResult = mysqli_query($conn, $orderQuery);

if ($orderResult && mysqli_num_rows($orderResult) > 0) {
    $orderData = mysqli_fetch_assoc($orderResult);
} else {
    echo "<h5>No order found</h5>";
    exit;
}
?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Invoice
                        <a href="orders.php" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <div id="myBillingArea">
                        <table style="width: 100%; margin-bottom: 10px;">
                            <tbody>
                                <tr>
                                    <td style="text-align: left;">
                                        <h4 style="font-size: 23px;">KSQ 10 ENTERPRISE</h4>
                                    </td>
                                    <td style="text-align: right;">
                                        <h4 style="font-size: 23px;">Workshop & Repair</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left;">
                                        <p>9632 JARAK ATAS</p>
                                        <p>TASEK GELUGOR 13310</p>
                                        <p>PULAU PINANG MALAYSIA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right;">
                                        <div>
                                            <p><?= $orderData['fld_customer_name'];?> - <?= $orderData['plate_number']; ?></p>
                                            <p><?= $orderData['fld_customer_address1']; ?></p>
                                            <p><?= $orderData['fld_customer_address2']; ?></p>
                                            <p><?= $orderData['fld_customer_city'];?> <?= $orderData['fld_customer_poscode'];?></p>
                                            <p><?= $orderData['fld_customer_state']; ?></p>
                                            <p><?= $orderData['fld_customer_phone']; ?> 
                                                <span style="margin-left: 20px;"><?= $orderData['fld_customer_email']; ?></span>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left;">
                                        <h1 style="font-size: 36px;"><?= $invoiceNo; ?></h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right;">
                                        <div style="display: flex; justify-content: flex-end;">
                                            <div style="margin-right: 50px;">
                                                <p><strong>Invoice Date:</strong></p>
                                                <p><?= date('d M Y', strtotime($orderData['order_date'])); ?></p>
                                            </div>
                                            <div style="margin-right: 50px;">
                                                <p><strong>Payment Mode:</strong></p>
                                                <p><?= $orderData['payment_mode']; ?></p>
                                            </div>
                                            <div style="margin-right: 30px;">
                                                <p><strong>Saleperson:</strong></p>
                                                
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="table-responsive mb-3">
                            <table style="width:100%;" cellpadding="5">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $orderItemsQuery = "SELECT * FROM tbl_order_items WHERE order_id = '{$orderData['id']}'";
                                    $orderItemsResult = mysqli_query($conn, $orderItemsQuery);
                                    if ($orderItemsResult && mysqli_num_rows($orderItemsResult) > 0) {
                                        $i = 1;
                                        $totalAmount = 0;
                                        while ($item = mysqli_fetch_assoc($orderItemsResult)) {
                                            $totalAmount += $item['price'] * $item['quantity'];
                                            ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $item['product_id']; ?></td>
                                                <td>RM<?= number_format($item['price'], 2) ?></td>
                                                <td><?= $item['quantity']; ?></td>
                                                <td>RM<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="4" align="right"><strong>Grand Total:</strong></td>
                                            <td><strong>RM<?= number_format($totalAmount, 2); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align: left;">
                                                <p>Payment Communication: <strong><?= $invoiceNo; ?></strong></p>
                                                <p>Payment terms: <strong>30 days</strong></p>
                                                <p>Transfer To:</p>
                                                <p>MAYBANK</p>
                                                <p>557410562928</p>
                                                <p>KSQ 10 ENTERPRISE</p>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        echo "<tr><td colspan='5'>No items found for this order.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-primary px-4 mx-1" onclick="window.print();">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
