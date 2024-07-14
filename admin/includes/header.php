<?php 
require '../config/function.php';
require 'authentication.php';
?>

<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css"/>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
<link href="assets/css/custom.css" rel="stylesheet" />

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>PEN-B System</title>


        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css"/>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
        <link href="assets/css/custom.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="assets/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Select2 CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
        <!-- jQuery (required) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!-- Select2 JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

       <script>
            $(document).ready(function(){
                // Attach keyup event handler to all input fields
                $('input[type="text"]').keyup(function(){
                    // Convert the input value to uppercase
                    $(this).val($(this).val().toUpperCase());
                });
            });
        </script>

        <style >
            @media print {
                #myBillingArea {
                    width: 100%;
                    margin: 0;
                    padding: 0;
                }
                /* Ensure text is readable and not overlapping */
                .billing-text {
                    margin-bottom: 1rem;
                    font-size: 20px;
                }
            }

            .required {
                color: red;
            }
   
            input[readonly] {
                background-color: #f8f8f8; 
                color: #555; 
                border: 1px solid #ccc; 
            }
            
        </style>

    </head>
    <body class="sb-nav-fixed">

        <?php include('navbar.php');?>

            <div id="layoutSidenav">

        <?php include('sidebar.php');?>

            <div id="layoutSidenav_content">
                
                <main>