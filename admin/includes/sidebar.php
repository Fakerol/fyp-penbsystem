

<div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>

                    <a class="nav-link" href="orders.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                        Order
                    </a>
                    <a class="nav-link" href="customers.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        Customer
                    </a>


                    <div class="collapse" id="collapseCustomers" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="customers.php">Customers List</a>
      
                            
                        </nav>
                    </div>


                    <a class="nav-link collapsed" href="#" 
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseProducts"
                    aria-expanded="false" aria-controls="collapseProducts">

                        <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                        Inventory
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseProducts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                           
                            <a class="nav-link" href="products.php">Product</a>
                            <a class="nav-link" href="labour.php">Labour Charge</a>
                            
                        </nav>
                    </div>


                     <a class="nav-link collapsed" href="#" 
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseAccounts"
                    aria-expanded="false" aria-controls="collapseAccounts">

                        <div class="sb-nav-link-icon"><i class="fas fa-calculator"></i></div>
                        Accounting
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseAccounts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="expenses.php">Expenses</a>
                            <a class="nav-link" href="report.php">Report</a>
                        </nav>
                    </div>

                    
                    <?php if ($_SESSION['loggedInUser']['name'] == 'ADMIN'): ?>
                    <a class="nav-link collapsed" href="#" 
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseAdmins"
                            aria-expanded="false" aria-controls="collapseAdmins">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Admins/Staff
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <!-- Check if user is admin to display the collapsible content -->
                        
                            <div class="collapse" id="collapseAdmins" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="admins.php">Admin List</a>
                                </nav>
                            </div>
                        <?php endif; ?>


                    <a class="nav-link" href="user-manual.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                        User Manual
                    </a>
                    

                    

                    
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
               <?= $_SESSION['loggedInUser']['name']; ?>
            </div>
        </nav>
    </div>