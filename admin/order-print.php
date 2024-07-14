<?php include('includes/header.php'); ?>


<style>
    .table-responsive table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .table-responsive thead {
      background-color: #f2f2f2;
    }

    .table-responsive thead th, .table-responsive tbody td {
      padding: 10px;
      text-align: left;
    }

    .table-responsive tbody tr {
      border-bottom: 1px solid #ccc;
    }

    .table-responsive tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .billing-header, .billing-address, .billing-customer, .billing-details {
      margin-bottom: 10px;
    }

    .billing-header h4, .billing-header p, .billing-address p, .billing-customer p {
      margin: 2px 0;
    }

    .billing-header h1, .billing-details p {
      margin: 0;
    }

    .billing-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 18px;
      line-height: 24px;
      color: #555;
    }

    .billing-footer p {
      margin: 5px 0;
    }

    /* New styles for color accents */
    .billing-header {
      background-color: #f2f2f2;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .billing-header h1 {
      font-size: 28px;
      color: #333;
      margin-top: 10px;
    }

    .billing-address p {
      margin-bottom: 5px;
    }

    .billing-customer p {
      margin-bottom: 5px;
    }

    .table-bordered th, .table-bordered td {
      border: 1px solid #ddd;
      padding: 8px;
    }

    .table-bordered thead th {
      background-color: #f2f2f2;
    }

    .table-bordered tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .text-end {
      text-align: right;
    }

    .fw-bold {
      font-weight: bold;
    }

    .btn-print {
      background-color: #007bff;
      color: #fff;
      border-color: #007bff;
      padding: 10px 20px;
      font-size: 16px;
      margin-right: 10px;
    }

    .btn-print:hover, .btn-print:focus {
      background-color: #0056b3;
      border-color: #0056b3;
    }

    .btn-download {
      background-color: #28a745;
      color: #fff;
      border-color: #28a745;
      padding: 10px 20px;
      font-size: 16px;
    }

    .btn-download:hover, .btn-download:focus {
      background-color: #218838;
      border-color: #218838;
    }
    p, td{
      font-size: 25px;
    }
  </style>

<div class="container-fluid px-4">
  <div class="card mt-4 shadow-sm">
    <div class="card-header">
      <h4 class="mb-0">Print Invoice
        <a href="orders.php" class="btn btn-danger mx-2 btn-md float-end">Back</a>
      </h4>
    </div>
    <div class="card-body">
    <div id="myBillingArea">
      <?php
      

      if (isset($_GET['invoice_no'])) {
        $invoiceNo = validate($_GET['invoice_no']);
        if ($invoiceNo == '') {
          echo '<div class="text-center py-5"><h5>No Invoice Number Found</h5><div><a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back to Order List</a></div></div>';
        } else {
          $orderQuery = "SELECT o.*, c.* FROM tbl_order_penb o, tbl_customers_penb c WHERE c.id=o.customer_id AND invoice_no='$invoiceNo' LIMIT 1";
          $orderQueryRes = mysqli_query($conn, $orderQuery);
          if (!$orderQueryRes) {
            echo '<h5>Something went wrong</h5>';
          } else if (mysqli_num_rows($orderQueryRes) > 0) {
            $orderDataRow = mysqli_fetch_assoc($orderQueryRes);
            ?>
            <div class="billing-header">
              <div class="d-flex justify-content-between">
                <div>
                  <h4>KSQ10 ENTERPRISE</h4>
                  <p>Workshop & Repair</p>
                </div>
                <h1 style="color: #007bff;"><?= $orderDataRow['invoice_no']; ?></h1>
              </div>
            </div>
            <div class="billing-address">
              <p><strong>From:</strong></p>
              <p>KSQ10 GLOBAL ENTERPRISE</p>
              <p>9632 JARAK ATAS, TASEK GELUGOR</p>
              <p>13310 PULAU PINANG MALAYSIA</p>
            </div>
            <br>
            <div class="billing-customer">
              <p><strong>Invoice to:</strong></p>
              <p><?= $orderDataRow['fld_customer_name']; ?> - <strong><?= $orderDataRow['plate_number']; ?> </strong></p>
              <p><?= $orderDataRow['fld_customer_address1']; ?></p>
              <p><?= $orderDataRow['fld_customer_address2']; ?></p>
              <p><?= $orderDataRow['fld_customer_city']; ?> <?= $orderDataRow['fld_customer_poscode']; ?></p>
              <p><?= $orderDataRow['fld_customer_state']; ?></p>
              
            </div>
            <br>
            <div class="billing-details">
                <div class="d-flex justify-content-start">
                    <div class="me-5">
                        <p><strong>Order Status:</strong> <?= $orderDataRow['order_status']; ?></p>
                    </div>
                    <div class="me-5" style="margin-left: 30px;">
                        <p><strong>Salesperson:</strong> <?= $orderDataRow['order_place_by_id']; ?></p>
                    </div>
                    <div class="me-5" style="margin-left: 30px;">
                        <p><strong>Date:</strong> <?= date('d M Y'); ?></p>
                    </div>
                </div>
            </div>
            <?php
          } else {
            echo '<h5>No data found</h5>';
          }
        }
      } else {
        echo '<h5>No Invoice Number Found</h5>';
      }
      ?>

      <?php
      $orderItemQuery = 
      "SELECT 
            oi.quantity AS orderItemQuantity, oi.price AS orderItemPrice, o.invoice_no, p.id AS product_id, 
            p.fld_product_name AS product_name, lb.id AS labor_id, lb.charge_desc AS labor_name
        FROM  tbl_order_penb AS o JOIN tbl_order_items AS oi ON oi.order_id = o.id
        LEFT JOIN tbl_products_penb AS p ON p.id = oi.product_id
        LEFT JOIN tbl_labourcharge_penb AS lb ON lb.id = oi.product_id 
        WHERE o.invoice_no = '$invoiceNo'";

      $orderItemRes = mysqli_query($conn, $orderItemQuery);

      if ($orderItemRes) {
        if (mysqli_num_rows($orderItemRes) > 0) {
          $products = [];
          $laborCharges = [];
          $totalProductAmount = 0;
          $totalLaborChargeAmount = 0;

          while ($orderItemRow = mysqli_fetch_assoc($orderItemRes)) {
            if (!empty($orderItemRow['labor_id'])) {
              // It's a labor charge
              $laborCharges[] = $orderItemRow;
              $totalLaborChargeAmount += $orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'];
            } else {
              // It's a product
              $products[] = $orderItemRow;
              $totalProductAmount += $orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'];
            }
          }
          ?>
          <br>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) : ?>
                <tr>
                  <td><?= $product['product_name']; ?></td>
                  <td class="text-end">RM<?= number_format($product['orderItemPrice'], 2); ?></td>
                  <td class="text-end"><?= $product['orderItemQuantity']; ?></td>
                  <td class="text-end">RM<?= number_format($product['orderItemPrice'] * $product['orderItemQuantity'], 2); ?></td>
                </tr>
              <?php endforeach; ?>

              <tr>
                <td class="text-end fw-bold">Subtotal:</td>
                <td colspan="3" class="text-end fw-bold">RM<?= number_format($totalProductAmount, 2); ?></td>
              </tr>

              <!-- Labor Charges Section -->
              <tr>
                <td colspan="4" class="fw-bold">Labor Charges</td>
              </tr>
              <?php foreach ($laborCharges as $laborCharge) : ?>
                <tr>
                  <td><?= $laborCharge['labor_name']; ?></td>
                  <td class="text-end">RM<?= number_format($laborCharge['orderItemPrice'], 2); ?></td>
                  <td class="text-end"><?= $laborCharge['orderItemQuantity']; ?></td>
                  <td class="text-end">RM<?= number_format($laborCharge['orderItemPrice'] * $laborCharge['orderItemQuantity'], 2); ?></td>
                </tr>
              <?php endforeach; ?>

              <tr>
                <td class="text-end fw-bold">Subtotal:</td>
                <td colspan="3" class="text-end fw-bold">RM<?= number_format($totalLaborChargeAmount, 2); ?></td>
              </tr>

              <tr>
                <td class="text-end fw-bold">Grand Total:</td>
                <td colspan="3" class="text-end fw-bold">RM<?= number_format($totalLaborChargeAmount + $totalProductAmount, 2); ?></td>
              </tr>
            </tbody>
          </table>
          <?php
        } else {
          echo '<h5>No record found</h5>';
        }
      } else {
        echo '<h5>Something went wrong</h5>';
      }
      ?>
      <br>
          
             <div class="billing-address">
              <p><strong>Account Information</strong></p>
              <p>MAYBANK<p>
              <p>158211803446<p>
              <p>KSQ10 ENTERPIRSE<p>

            </div>
            <br>
            <!-- Terms and Conditions -->
            <div class="billing-address">
              <p><strong>Terms and Condition:</strong></p>
              <p>- Payment is due within 30 days from the invoice date.<p>
              <p>- Please transfer funds to the designated account provided above.<p>
              <p>- Please use the invoice number <strong><?= $orderDataRow['invoice_no']; ?></strong> as the reference for all payments.</p>
             

            </div>
           
            <br>
            <div class="billing-address">
              <p><strong>Issued by</strong></p>
              <p style="font-size: 16px;">signature</p>
             <br>
              <br>
              <p>............................................</p>
              
            </div>
            <br>
            <br>
            <br>

             <div class="billing-address">
              <p>If you have any inquiries, email us on <strong>info@ksq10enterprise.com</strong> or</p>
              <p>call us on <strong>+019 344 1232</strong></p>
            
            </div>


    </div>
    <div class="mt-4 text-end">
     
      <button class="btn btn-print px-4 mx-1" onclick="downloadPDF('<?= $orderDataRow['invoice_no']; ?>')">Download PDF</button>
    </div>
  </div>
  </div>
</div>

<script>
  const { jsPDF } = window.jspdf;

  function downloadPDF(invoiceNo) {
    const element = document.querySelector("#myBillingArea");

    html2canvas(element, {
      scale: 2,
      useCORS: true
    }).then(canvas => {
      const imgData = canvas.toDataURL('image/png');
      const pdf = new jsPDF('p', 'mm', 'a4');
      const imgProps = pdf.getImageProperties(imgData);

      const pdfWidth = pdf.internal.pageSize.getWidth();
      const pdfHeight = pdf.internal.pageSize.getHeight();

      const margin = 10;
      const imgWidth = pdfWidth - 2 * margin;
      const imgHeight = (imgProps.height * imgWidth) / imgProps.width;

      pdf.addImage(imgData, 'PNG', margin, margin, imgWidth, imgHeight);

      const footerText = "";
      const footerY = pdfHeight - margin;

      pdf.setFontSize(12);
      pdf.text(footerText, margin, footerY);

      pdf.save(invoiceNo + '.pdf');
    });
  }
</script>

<?php include('includes/footer.php'); ?>
