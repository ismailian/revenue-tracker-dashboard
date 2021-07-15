<?php require_once('modules/Init.php') ?>

<?php $dates = $db->getMany(DATES); ?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Adminmart Template - The Ultimate Multipurpose admin template</title>
    <!-- Custom CSS -->
    <link href="assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="dist/css/index.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div> -->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->

        <!-- HERE GOES THE HEADER -->
        <?php include(HEADER); ?>

        <!-- HERE GOES THE SIDE BAR -->
        <?php include(SIDEBAR); ?>


        <div class="page-wrapper">
            <div id="editor"></div>
            <a id="button" alt="Download PDF" onclick="generatePDF()"></a>


            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-md-5 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Morning <?= ($_SESSION['USER']->username) ?></h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-md-7 align-self-center">

                        <div class="customize-input float-right">
                            <a href="?t=all" class="btn kt-subheader__btn-secondary">Lifetime</a>
                            <a href="?t=today" class="btn kt-subheader__btn-secondary">Today</a>
                            <a href="?t=this_month" class="btn kt-subheader__btn-secondary">Month</a>
                            <a href="?t=this_year" class="btn kt-subheader__btn-secondary">Year</a>
                            <a href="#" class="btn kt-subheader__btn-daterange" id="dashboardis" data-toggle="kt-tooltip" data-title="Select dashboard daterange" data-placement="left">
                                <span class="kt-subheader__btn-daterange-title date-label" id="kt_dashboard_daterangepicker_title date-label">Today:</span>
                                <span class="kt-subheader__btn-daterange-date date-value" id="kt_dashboard_daterangepicker_date tbi"><?php echo (date('M') . " " . date('d')); ?></span>
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid" id="pdf">
                <!-- *************************************************************** -->
                <!-- Start First Cards -->
                <!-- *************************************************************** -->
                <div class="card-group">
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <?php
                                        $total_capital = 0;
                                        if (isset($_GET['t'])) {
                                            $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                            if ($time === 'today')        $total_capital = getCapital('today');
                                            if ($time === 'yesterday')    $total_capital = getCapital('yesterday');
                                            if ($time === 'last_week')    $total_capital = getCapital('last_week');
                                            if ($time === 'this_month')   $total_capital = getCapital('this_month');
                                            if ($time === 'last_month')   $total_capital = getCapital('last_month');
                                            if ($time === 'last_30_days') $total_capital = getCapital('last_30_days');
                                            if ($time === 'this_year')    $total_capital = getCapital('this_year');
                                            if ($time === "all")          $total_capital = getCapital();
                                            if ($time === 'range')        $total_capital = getCapital('range', $_GET['from'], $_GET['to']); //

                                        } else {
                                            $total_capital = getCapital("today");
                                        }
                                        ?>
                                        <h2 class="text-dark mb-1 font-weight-medium measured-capital">
                                            <?php echo ($total_capital) ?>
                                        </h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Capital</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <?php
                                        $net_profit = 0;
                                        if (isset($_GET['t'])) {
                                            $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                            if ($time === 'today')        $net_profit = getTotalNetProfit('today');
                                            if ($time === 'yesterday')    $net_profit = getTotalNetProfit('yesterday');
                                            if ($time === 'last_week')    $net_profit = getTotalNetProfit('last_week');
                                            if ($time === 'this_month')   $net_profit = getTotalNetProfit('this_month');
                                            if ($time === 'last_month')   $net_profit = getTotalNetProfit('last_month');
                                            if ($time === 'last_30_days') $net_profit = getTotalNetProfit('last_30_days');
                                            if ($time === 'this_year')    $net_profit = getTotalNetProfit('this_year');
                                            if ($time === 'range')        $net_profit = getTotalNetProfit('range', $_GET['from'], $_GET['to']);
                                            if ($time === 'all')          $net_profit = getTotalNetProfit(); //

                                        } else {
                                            $net_profit = getTotalNetProfit('today');
                                        }
                                        ?>
                                        <h2 class="text-dark mb-1 font-weight-medium">
                                            <?php
                                            // echo $net_profit;
                                            $value = floatval(str_replace(",", "", $total_capital)) - $net_profit;
                                            echo number_format($value) . " DH";
                                            ?>
                                        </h2>

                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Expenses</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-group">
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <?php
                                        $net_profit = 0;
                                        if (isset($_GET['t'])) {
                                            $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                            if ($time === 'today')        $net_profit = getTotalNetProfit('today');
                                            if ($time === 'yesterday')    $net_profit = getTotalNetProfit('yesterday');
                                            if ($time === 'last_week')    $net_profit = getTotalNetProfit('last_week');
                                            if ($time === 'this_month')   $net_profit = getTotalNetProfit('this_month');
                                            if ($time === 'last_month')   $net_profit = getTotalNetProfit('last_month');
                                            if ($time === 'last_30_days') $net_profit = getTotalNetProfit('last_30_days');
                                            if ($time === 'this_year')    $net_profit = getTotalNetProfit('this_year');
                                            if ($time === 'range')        $net_profit = getTotalNetProfit('range', $_GET['from'], $_GET['to']);
                                            if ($time === 'all')          $net_profit = getTotalNetProfit(); //

                                        } else {
                                            $net_profit = getTotalNetProfit('today');
                                        }
                                        ?>
                                        <h2 class="text-dark mb-1 font-weight-medium main-profit">
                                            <?= $net_profit ?> DH
                                        </h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Net Profit</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <?php
                                    $total_orders = 0;
                                    if (isset($_GET['t'])) {
                                        $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                        if ($time === 'today')        $total_orders = getTotalorders('today');
                                        if ($time === 'yesterday')    $total_orders = getTotalorders('yesterday');
                                        if ($time === 'last_week')    $total_orders = getTotalorders('last_week');
                                        if ($time === 'this_month')   $total_orders = getTotalorders('this_month');
                                        if ($time === 'last_month')   $total_orders = getTotalorders('last_month');
                                        if ($time === 'last_30_days') $total_orders = getTotalorders('last_30_days');
                                        if ($time === 'this_year')    $total_orders = getTotalorders('this_year');
                                        if ($time === 'range')        $total_orders = getTotalorders('range', $_GET['from'], $_GET['to']);
                                        if ($time === 'all')          $total_orders = getTotalorders(); //

                                    } else {
                                        $total_orders = getTotalorders('today');
                                    }
                                    ?>
                                    <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                                        <?php echo ($total_orders); ?>
                                    </h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Orders
                                    </h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <?php
                                        $confirmation_rate = 0;
                                        if (isset($_GET['t'])) {
                                            $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                            if ($time === 'today')        $confirmation_rate = getConfirmationRate('today');
                                            if ($time === 'yesterday')    $confirmation_rate = getConfirmationRate('yesterday');
                                            if ($time === 'last_week')    $confirmation_rate = getConfirmationRate('last_week');
                                            if ($time === 'this_month')   $confirmation_rate = getConfirmationRate('this_month');
                                            if ($time === 'last_month')   $confirmation_rate = getConfirmationRate('last_month');
                                            if ($time === 'last_30_days') $confirmation_rate = getConfirmationRate('last_30_days');
                                            if ($time === 'this_year')    $confirmation_rate = getConfirmationRate('this_year');
                                            if ($time === 'range')        $confirmation_rate = getConfirmationRate('range', $_GET['from'], $_GET['to']);
                                            if ($time === "all")          $confirmation_rate = getConfirmationRate(); //

                                        } else {
                                            $confirmation_rate = getConfirmationRate('today');
                                        }
                                        ?>
                                        <h2 class="text-dark mb-1 font-weight-medium"><?php echo ($confirmation_rate['rate'] . "%") ?></h2>
                                        <span class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">
                                            <?php echo ($confirmation_rate)['confirmed'] ?>
                                        </span>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Confirmation Rate</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <?php
                                        $delivery_rate = ["rate" => 0.00, "delivered" => 0];
                                        if (isset($_GET['t'])) {
                                            $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                            if ($time === 'today')        $delivery_rate = getDeliveryRate('today');
                                            if ($time === 'yesterday')    $delivery_rate = getDeliveryRate('yesterday');
                                            if ($time === 'last_week')    $delivery_rate = getDeliveryRate('last_week');
                                            if ($time === 'this_month')   $delivery_rate = getDeliveryRate('this_month');
                                            if ($time === 'last_month')   $delivery_rate = getDeliveryRate('last_month');
                                            if ($time === 'last_30_days') $delivery_rate = getDeliveryRate('last_30_days');
                                            if ($time === 'this_year')    $delivery_rate = getDeliveryRate('this_year');
                                            if ($time === 'range')        $delivery_rate = getDeliveryRate('range', $from = $_GET['from'], $_GET['to']);
                                            if ($time === "all")          $delivery_rate = getDeliveryRate(); //

                                        } else {
                                            $delivery_rate = getDeliveryRate('today');
                                        }
                                        ?>
                                        <h2 class="text-dark mb-1 font-weight-medium"><?php echo ($delivery_rate['rate']) . "%"; ?></h2>
                                        <span class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">
                                            <?php echo ($delivery_rate['delivered']); ?>
                                        </span>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Delivery Rate</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="globe"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- *************************************************************** -->
                <!-- End First Cards -->
                <!-- *************************************************************** -->
                <!-- *************************************************************** -->
                <!-- Start Sales Charts Section -->
                <!-- *************************************************************** -->
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Capital Per Products</h4>
                                <canvas id="myChart6" width="100%" height="100%"></canvas>
                                <ul class="list-style-none mb-0 chartdetails capital-top-five">
                                    <li class="mt-2 pp-1">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #2ecc71"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                    <li class="mt-2 pp-2">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #3498db"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                    <li class="mt-2 pp-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #95a5a6"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                    <li class="mt-2 pp-4">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #9b59b6"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                    <li class="mt-2 pp-5">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #f1c40f"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Profit Per Products</h4>
                                <canvas id="myChart5" width="100%" height="100%"></canvas>
                                <ul class="list-style-none mb-0 chartdetails profit-top-five">
                                    <li class="mt-2 pp-1">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #2ecc71"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                    <li class="mt-2 pp-2">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #3498db"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                    <li class="mt-2 pp-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #95a5a6"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                    <li class="mt-2 pp-4">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #9b59b6"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                    <li class="mt-2 pp-5">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #f1c40f"></i>
                                        <span class="text-muted">N/A</span>
                                        <span class="text-dark float-right font-weight-medium">0 DH</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Expenses</h4>
                                <canvas id="myChart7" width="100%" height="100%"></canvas>
                                <?php
                                $expenses = ["delivery" => "0", "confirmation" => "0", "cost" => "0", "ads" => "0"];
                                if (isset($_GET['t'])) {
                                    $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                    if ($time === 'today')        $expenses = getExpenses('today');
                                    if ($time === 'yesterday')    $expenses = getExpenses('yesterday');
                                    if ($time === 'last_week')    $expenses = getExpenses('last_week');
                                    if ($time === 'this_month')   $expenses = getExpenses('this_month');
                                    if ($time === 'last_month')   $expenses = getExpenses('last_month');
                                    if ($time === 'last_30_days') $expenses = getExpenses('last_30_days');
                                    if ($time === 'this_year')    $expenses = getExpenses('this_year');
                                    if ($time === 'range')        $expenses = getExpenses('range', $_GET['from'], $_GET['to']);
                                    if ($time === "all")          $expenses = getExpenses(); //

                                } else {
                                    $expenses = getExpenses('today');
                                }
                                ?>
                                <ul class="list-style-none mb-0 chartdetails expenses">

                                    <li class="mb-2 pp-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #2ecc71"></i>
                                        <span class="text-muted">Cost Price</span>
                                        <span class="text-dark float-right font-weight-medium">
                                            <?php echo ($expenses["cost"] . " DH") ?>
                                        </span>
                                    </li>

                                    <li class="pp-1">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #3498db"></i>
                                        <span class="text-muted">Delivery</span>
                                        <span class="text-dark float-right font-weight-medium"><?= ($expenses["delivery"] . " DH") ?></span>
                                    </li>

                                    <li class="mt-2 pp-2">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #95a5a6"></i>
                                        <span class="text-muted">Confirmation</span>
                                        <span class="text-dark float-right font-weight-medium"><?= ($expenses["confirmation"] . " DH") ?></span>
                                    </li>

                                    <li class="mt-2 pp-4">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #9b59b6"></i>
                                        <span class="text-muted">Ads</span>
                                        <span class="text-dark float-right font-weight-medium"><?= ($expenses["ads"] . " DH") ?></span>
                                    </li>

                                    <li class="mt-2 pp-5">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #f1c40f"></i>
                                        <span class="text-muted">Total</span>
                                        <span class="text-dark float-right font-weight-medium">00.00 DH</span>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="card">
                            <div class="d-flex align-items-start">
                                <h4 class="card-title mb-0">Net Profit Statistics</h4>
                            </div>

                            <canvas id="myChart" width="400" height="300"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="card">
                            <div class="d-flex align-items-start">
                                <h4 class="card-title mb-0">Total Orders</h4>
                            </div>

                            <canvas id="myChart4" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <!-- *************************************************************** -->
                <!-- End Sales Charts Section -->
                <!-- *************************************************************** -->
                <!-- *************************************************************** -->
                <!-- Start Location and Earnings Charts Section -->
                <!-- *************************************************************** -->
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="card">
                            <div class="d-flex align-items-start">
                                <h4 class="card-title mb-0">Confirmation Rate</h4>
                            </div>

                            <canvas id="myChart2" width="400" height="300"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="card">
                            <div class="d-flex align-items-start">
                                <h4 class="card-title mb-0">Delivery Rate</h4>
                            </div>

                            <canvas id="myChart3" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mb-2">
                    <button class="btn btn-success" onclick="toggle('kk')">Show / Hide Reports</button>
                </div>

                <!-- Reports Table -->
                <div class="container-fluid" id="kk">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row card-title">
                                        <div class="col-sm-8">
                                            <h4 class="card-title">Reports List</h4>
                                        </div>
                                        <div class="col-sm-4">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <div class="" id="exportis">
                                            <?php

                                            if (isset($_GET['t'])) {
                                                $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                                $reports = [];

                                                if ($time === 'today') $reports = ReleventProducts("today");
                                                if ($time === 'yesterday') $reports = ReleventProducts("yesterday");
                                                if ($time === 'last_week') $reports = ReleventProducts("last_week");
                                                if ($time === 'this_month') $reports = ReleventProducts("this_month");
                                                if ($time === 'last_month') $reports = ReleventProducts("last_month");
                                                if ($time === 'last_30_days') $reports = ReleventProducts("last_30_days");
                                                if ($time === 'this_year') $reports = ReleventProducts("this_year");
                                                if ($time === 'range') {
                                                    $rangeFrom = $_GET['from'];
                                                    $rangeTo = $_GET['to'];
                                                    $reports = ReleventProducts("range", $rangeFrom, $rangeTo);
                                                } elseif ($time === "all") {
                                                    $reports = ReleventProducts();
                                                }
                                            } else {
                                                $reports = ReleventProducts('today');
                                            }
                                            ?>

                                        </div>
                                        <table class="table table-bordered small" id="table">
                                            <thead class="bg-dark">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>P.N</th>
                                                    <th>All.O</th>
                                                    <th>Cfm.O</th>
                                                    <th>Dlv.O</th>
                                                    <th>Cfm.R</th>
                                                    <th>Dlv.R</th>
                                                    <th class="blue">Cptl</th>
                                                    <th>Cost.P</th>
                                                    <th>Dlv.P</th>
                                                    <th>Ads</th>
                                                    <th>Cfm.F</th>
                                                    <th class="red">Ttl.E</th>
                                                    <th class="green">Prft</th>
                                                    <th>P.P.O</th>
                                                    <th>E.P.O</th>
                                                    <th>A.C.P.D</th>
                                                </tr>
                                            </thead>
                                            <tbody class="productsContainer">
                                                <?php echo $reports; ?>
                                            </tbody>
                                        </table>

                                        <table class="table table-bordered full-width" id="table">
                                            <tfoot>
                                                <tr>
                                                    <th class="blue">Capital</th>
                                                    <td class="total_cp"></td>
                                                    <th class="green">Total Net Profit</th>
                                                    <td class="net_cp"></td>
                                                    <th class="red">Total Expenses</th>
                                                    <td class="expenses_cp"></td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>


                </style>

            </div>


            <!-- reports modal -->


            <!-- Transfered from calculator -->
            <div id="UpdateModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong>Update Product</strong>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label>Product Name</label>
                                <input disabled type="text" id="productName" data-id="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>All Orders</label>
                                <input type="number" id="allorders" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Delivered Orders</label>
                                <input type="number" id="totalDelivered" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Confirmed Orders</label>
                                <input type="number" id="totalOrders" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Ads</label>
                                <input type="number" id="Ads" class="form-control">
                            </div>
                            <input type="hidden" id="productId" class="form-control">

                        </div>

                        <div class="modal-footer">
                            <a href="javascript:void(0)" id="save" class="btn btn-primary pull-right">Update</a>
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Product delete Modal -->
            <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this product ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="delete" class="btn btn-primary">Delete</button>
                            <input type="hidden" value="" id="productId">
                        </div>
                    </div>
                </div>
            </div>



            <footer class="footer text-center text-muted">
                All Rights Reserved by PetrusProfit. Designed and Developed by <a href="https://www.venomx.me">VeNomx</a> & <a href="#">Ismail</a>.
            </footer>

        </div>

    </div>



    <!-- HERE GOES THE JS FILES -->
    <?php include(MAIN_JS); ?>
    <script src="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>
    <script src="dist/js/pages/dashboards/dashboard1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-html2pdf@1.1.4/src/html2pdf.js"></script>

    <script src="dist/js/reports.js"></script>
    <script src="dist/js/index.js"></script>
    <script>
        function generatePDF() {
            // Choose the element that our invoice is rendered in.
            const element = document.getElementById("pdf");
            // Choose the element and save the PDF for our user.
            html2pdf()
                .set({
                    html2canvas: {
                        scale: 4
                    }
                })
                .from(element)
                .save();
        }
    </script>
    <script type="text/javascript">
        var ctx = document.getElementById('myChart').getContext('2d');
        var ctx2 = document.getElementById('myChart2').getContext('2d');
        var ctx3 = document.getElementById('myChart3').getContext('2d');
        var ctx4 = document.getElementById('myChart4').getContext('2d');
        var ctx5 = document.getElementById('myChart5').getContext('2d');
        var ctx6 = document.getElementById('myChart6').getContext('2d');
        var ctx7 = document.getElementById('myChart7').getContext('2d');

        // Net Profit Chart:
        $(document).ready(() => {
            var api = "api/ajax.php?fetch_net_profit";
            var url = window.location.href;

            if (url.match("t=(.+)")) {
                api = `${api}=` + (url.match("t=(.+)")[1]);
            } else {
                api = `${api}=today`;
            }

            $.ajax({
                url: api,
                method: "GET",
                success: (response) => {
                    if (JSON.parse(response).status == 'success') {
                        const dates_orders = JSON.parse(response).dates;

                        let dates = [];
                        let sets = [];

                        dates_orders.forEach(elm => {
                            dates.push(elm.date);
                            sets.push(`${parseFloat(elm.profit)}`);
                        });

                        var myChart = new Chart(ctx, {
                            type: (dates.length > 2) ? 'line' : 'bar',
                            data: {
                                labels: dates,
                                datasets: [{
                                    label: 'Net Profit',
                                    data: sets,
                                    backgroundColor: '#5f76e8',
                                    borderColor: '#5f76e8',
                                    borderWidth: 2.5,
                                    fill: false
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    } else {
                        console.log("No Data!");
                    }
                },
                error: (xhr, err) => {
                    console.log(err);
                }
            });
        });

        // For Confirmation Rate chart:
        $(document).ready(() => {
            var api = "api/ajax.php?fetch_confirmation_rates";
            var url = window.location.href;

            if (url.match("t=(.+)")) {
                api = `${api}=` + (url.match("t=(.+)")[1]);
            } else {
                api = `${api}=today`;
            }

            $.ajax({
                url: api,
                method: "GET",
                success: (response) => {
                    if (JSON.parse(response).status == 'success') {
                        const dates_orders = JSON.parse(response).dates;

                        let dates = [];
                        let sets = [];

                        dates_orders.forEach(elm => {
                            dates.push(elm.date);
                            sets.push(`${elm.rate}`);
                        });

                        var myChart = new Chart(ctx2, {
                            type: (dates.length > 2) ? 'line' : 'bar',
                            data: {
                                labels: dates,
                                datasets: [{
                                    label: 'Confirmation Rate',
                                    data: sets,
                                    backgroundColor: '#ff4f70',
                                    borderColor: '#ff4f70',
                                    borderWidth: 2.5,
                                    fill: false
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    } else {
                        console.log("No Data!");
                    }
                },
                error: (xhr, err) => {
                    console.log(err);
                }
            });
        });

        // For Delivery Rate chart:
        $(document).ready(() => {
            var api = "api/ajax.php?fetch_delivery_rates";
            var url = window.location.href;

            if (url.match("t=(.+)")) {
                api = `${api}=` + (url.match("t=(.+)")[1]);
            } else {
                api = `${api}=today`;
            }

            $.ajax({
                url: api,
                method: "GET",
                success: (response) => {
                    if (JSON.parse(response).status == 'success') {
                        const dates_orders = JSON.parse(response).dates;

                        let dates = [];
                        let sets = [];

                        dates_orders.forEach(elm => {
                            dates.push(elm.date);
                            sets.push(`${elm.rate}`);
                        });

                        var myChart = new Chart(ctx3, {
                            type: (dates.length > 2) ? 'line' : 'bar',
                            data: {
                                labels: dates,
                                datasets: [{
                                    label: 'Delivery Rate',
                                    data: sets,
                                    backgroundColor: '#01caf1',
                                    borderColor: '#01caf1',
                                    borderWidth: 2.5,
                                    fill: false
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    } else {
                        console.log("No Data!");
                    }
                },
                error: (xhr, err) => {
                    console.log(err);
                }
            });
        });

        // The Total Orders Chart:
        $(document).ready(() => {
            var api = "api/ajax.php?fetch_all_orders";
            var url = window.location.href;

            if (url.match("t=(.+)")) {
                api = `${api}=` + (url.match("t=(.+)")[1]);
            } else {
                api = `${api}=today`;
            }

            $.ajax({
                url: api,
                method: "GET",
                success: (response) => {
                    if (JSON.parse(response).status == 'success') {
                        const dates_orders = JSON.parse(response).dates;

                        let dates = [];
                        let sets = [];

                        dates_orders.forEach(elm => {
                            dates.push(elm.date);
                            sets.push(elm.date_orders);
                        });

                        var myChart = new Chart(ctx4, {
                            type: (dates.length > 2) ? 'line' : 'bar',
                            data: {
                                labels: dates,
                                datasets: [{
                                    label: "Total Orders",
                                    data: sets,
                                    backgroundColor: '#e67e22',
                                    borderColor: '#e67e22',
                                    borderWidth: 2.5,
                                    fill: false
                                }],
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    } else {
                        console.log("No Data!");
                    }
                },
                error: (xhr, err) => {
                    console.log(err);
                }
            });
        });

        function dynamicsort(property, order) {
            var sort_order = 1;
            if (order === "desc") {
                sort_order = -1;
            }
            return function(a, b) {
                // a should come before b in the sorted order
                if (a[property] < b[property]) {
                    return -1 * sort_order;
                    // a should come after b in the sorted order
                } else if (a[property] > b[property]) {
                    return 1 * sort_order;
                    // a and b are the same
                } else {
                    return 0 * sort_order;
                }
            }
        }

        // Profit Doughnut Pie:
        $(document).ready(() => {

            // parse url:
            let apiUri = "api/ajax.php?fetch_top_five_profit";
            const currentUrl = new webkitURL(window.location.href);
            if (currentUrl.searchParams.has("t") && currentUrl.searchParams.get("t") !== "all") {
                // get the (t) param:
                const keyword = currentUrl.searchParams.get("t");
                if (keyword === "range") {
                    const rangeFrom = currentUrl.searchParams.get("from");
                    const rangeTo = currentUrl.searchParams.get("to");
                    apiUri += `&t=${keyword}&from=${rangeFrom}&to=${rangeTo}`;
                } else {
                    apiUri += `&t=${keyword}`;
                }
            } else if (currentUrl.searchParams.has("t") && currentUrl.searchParams.get("t") === "all") {
                apiUri = apiUri;
            }  else {
                apiUri += "&t=today";
            }

            $.ajax({
                url: apiUri,
                method: "GET",
                success: (data) => {
                    const _data = JSON.parse(data);
                    console.log(_data);
                    var profit = [];
                    var total = 0;
                    var percentage = [];
                    var names = [];

                    const sorted_data = _data.sort(dynamicsort("profit", "desc"));

                    sorted_data.forEach(elm => {

                        // if names.find(elm.name) && profit.find(elm.profit) { then add it to the corresponding one! }
                        if (names.indexOf(elm.name) > -1) {

                            const index = names.indexOf(elm.name);
                            profit[index] += parseFloat(elm.profit);
                            total += parseFloat(elm.profit)

                            $(`.profit-top-five .pp-${index+1} > span:last-child`).text(`${new Intl.NumberFormat().format(profit[index])} DH`);

                        } else {

                            profit.push(parseFloat(elm.profit));
                            total += parseFloat(elm.profit)
                            names.push(elm.name);

                            $(`.profit-top-five .pp-${profit.length} > span:last-child`).text(`${new Intl.NumberFormat().format(elm.profit)} DH`);
                            $(`.profit-top-five .pp-${profit.length} .text-muted`).text(`${elm.name}`);

                        }


                    });

                    profit.forEach(num => {
                        const res = (num * 100) / total;
                        percentage.push(res.toFixed(2));
                    });

                    var myDoughnutChart = new Chart(ctx5, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                backgroundColor: [
                                    "#2ecc71",
                                    "#3498db",
                                    "#95a5a6",
                                    "#9b59b6",
                                    "#f1c40f",
                                ],
                                data: (percentage.length > 0) ? percentage : ['100'],
                                borderWidth: 4
                            }],
                            labels: names,
                        },
                        options: {
                            cutoutPercentage: 83,
                            legend: {
                                display: false
                            }
                        }
                    });

                },
                error: (xhr, error) => {
                    console.log(error);
                }
            });
        });

        // Capital Doughnut Pie:
        $(document).ready(() => {

            // parse url:
            let apiUri = "api/ajax.php?fetch_top_five";
            const currentUrl = new webkitURL(window.location.href);
            if (currentUrl.searchParams.has("t") && currentUrl.searchParams.get("t") !== "all") {
                // get the (t) param:
                const keyword = currentUrl.searchParams.get("t");
                if (keyword === "range") {
                    const rangeFrom = currentUrl.searchParams.get("from");
                    const rangeTo = currentUrl.searchParams.get("to");
                    apiUri += `&t=${keyword}&from=${rangeFrom}&to=${rangeTo}`;
                } else {
                    apiUri += `&t=${keyword}`;
                }
            } else if (currentUrl.searchParams.has("t") && currentUrl.searchParams.get("t") === "all") {
                apiUri = apiUri;
            } else {
                apiUri += "&t=today";
            }

            // issue the request:
            $.ajax({
                url: apiUri,
                method: "GET",
                success: (data) => {
                    const _data = JSON.parse(data);
                    var capital = [];
                    var total = 0;
                    var percentage = [];
                    var names = [];

                    const sorted_data = _data.sort(dynamicsort("capital", "desc"));

                    sorted_data.forEach(elm => {

                        // check if already has one product:
                        if (names.indexOf(elm.name) > -1) {

                            const index = names.indexOf(elm.name);
                            capital[index] += parseFloat(elm.capital);
                            total += parseFloat(elm.capital);

                            $(`.capital-top-five .pp-${index+1} > span:last-child`).text(`${new Intl.NumberFormat().format(capital[index])} DH`);

                        } else {

                            capital.push(parseFloat(elm.capital));
                            total += parseFloat(elm.capital)
                            names.push(elm.name);

                            $(`.capital-top-five .pp-${capital.length} > span:last-child`).text(`${new Intl.NumberFormat().format(elm.capital)} DH`);
                            $(`.capital-top-five .pp-${capital.length} .text-muted`).text(`${elm.name}`);

                        }


                    });

                    capital.forEach(num => {
                        const res = (num * 100) / total;
                        percentage.push(res.toFixed(2));
                    });

                    var myDoughnutChart = new Chart(ctx6, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                backgroundColor: [
                                    "#2ecc71",
                                    "#3498db",
                                    "#95a5a6",
                                    "#9b59b6",
                                    "#f1c40f",
                                ],
                                data: (percentage.length > 0) ? percentage : ['100'],
                                borderWidth: 4
                            }],
                            labels: names,
                        },
                        options: {
                            cutoutPercentage: 83,
                            legend: {
                                display: false
                            }
                        }
                    });

                },
                error: (xhr, error) => {
                    console.log(error);
                }
            });
        });

        // Expenses Doughnut Pie:
        $(document).ready(() => {

            var total = 0;
            var expenses = [];
            var percentage = [];

            document.querySelectorAll(".expenses > li").forEach(exp => {
                const expense = (parseInt(exp.querySelector("span:last-child").textContent.replace("$", "")));
                // exp.querySelector("span:last-child").textContent = `${new Intl.NumberFormat().format(expense)} DH`;
                total += expense;
                expenses.push(expense);
            });

            expenses.forEach(exp => {
                const res = (exp * 100) / total;
                percentage.push(res.toFixed(0));
            });

            $(".expenses > li:last-child > span:last-child").text(`${new Intl.NumberFormat().format(total)} DH`);

            var myDoughnutChart = new Chart(ctx7, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        backgroundColor: [
                            "#2ecc71",
                            "#3498db",
                            "#95a5a6",
                            "#9b59b6",
                            "#f1c40f",
                        ],
                        data: percentage.every((elm) => {
                            return !isNaN(elm)
                        }) ? percentage : [100],
                        borderWidth: 4
                    }],
                    labels: ['Cost Price', 'Delivery', 'Confirmation', 'Ads', 'Total']
                },
                options: {
                    cutoutPercentage: 83,
                    legend: {
                        display: false
                    }
                }
            });
        });


        $(document).ready(() => {
            const profit = parseFloat($(".main-profit").text());
            $(".main-profit").text(`${new Intl.NumberFormat().format(profit)} DH`);
        });
    </script>

    <script>
        $(document).ready(() => {
            var total = 0;
            var expenses = 0;
            var net = 0;

            const row_capital = document.querySelectorAll(".tmp_capital");
            const row_expenses = document.querySelectorAll(".tmp_expenses");
            const row_net = document.querySelectorAll(".tmp_net");

            row_capital.forEach((r) => {
                total += parseInt(r.textContent);
            });
            row_expenses.forEach((r) => {
                expenses += parseInt(r.textContent);
            });
            row_net.forEach((r) => {
                net += parseInt(r.textContent);
            });

            const total_cp = document.querySelector(".total_cp");
            const net_cp = document.querySelector(".net_cp");
            const expenses_cp = document.querySelector(".expenses_cp");

            total_cp.textContent = `${total} DH`;
            net_cp.textContent = `${net} DH`;
            expenses_cp.textContent = `${expenses} DH`;
        });
    </script>
</body>

</html>