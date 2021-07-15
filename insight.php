<?php require_once('modules/Init.php') ?>

<?php

if (isset($_GET['pid'])) {
    
    $pid = filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT);
    $mainProduct = $db->getOne(PRODUCTS, ['id' => $pid]);

    if ($db->count === 0) {
        header("Location: index.php");
    }
    
} else {
    header("Location: index.php");
}

?>



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
    <title>The Petrus Net Profit And Insights</title>
    <!-- Custom CSS -->
    <link href="assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="dist/css/insight.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- HERE GOES THE HEADER -->
        <?php include(HEADER); ?>

        <!-- HERE GOES THE SIDE BAR -->
        <?php include(SIDEBAR); ?>

        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1"><?= ($mainProduct->product_name) ?>!</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-5 align-self-center">
                        <div class="customize-input float-right">
                            <a href="<?php echo "?pid={$mainProduct->id}&t=today"; ?>" class="btn kt-subheader__btn-secondary">Today</a>
                            <a href="<?php echo "?pid={$mainProduct->id}&t=this_month"; ?>" class="btn kt-subheader__btn-secondary">Month</a>
                            <a href="<?php echo "?pid={$mainProduct->id}&t=this_year"; ?>" class="btn kt-subheader__btn-secondary">Year</a>
                            <a href="#" class="btn kt-subheader__btn-daterange" id="dashboardis2" data-toggle="kt-tooltip" data-title="Select dashboard daterange" data-placement="left">
                                <span class="kt-subheader__btn-daterange-title date-label" id="kt_dashboard_daterangepicker_title"></span>
                                <span class="kt-subheader__btn-daterange-date date-value" id="kt_dashboard_daterangepicker_date tbi">
                                    Lifetime
                                </span>
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
            <div class="container-fluid">
                <!-- *************************************************************** -->
                <!-- Start First Cards -->
                <!-- *************************************************************** -->
                <div class="card-group d-flex justify-content-center">
                    <div class="row">


                        <div class="col-md-4 card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <?php
                                        $total_capital = 0;
                                        $total_profit = 0;
                                        if (isset($_GET['t'])) {
                                            $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                            if ($time === 'today') {
                                                $total_capital = getProductTotalCapital(($mainProduct)->id, 'today');
                                                $total_profit = getProductTotalProfit(($mainProduct)->id, 'today');
                                            } elseif ($time === 'yesterday') {
                                                $total_capital = getProductTotalCapital(($mainProduct)->id, 'yesterday');
                                                $total_profit = getProductTotalProfit(($mainProduct)->id, 'yesterday');
                                            } elseif ($time === 'last_week') {
                                                $total_capital = getProductTotalCapital(($mainProduct)->id, 'last_week');
                                                $total_profit = getProductTotalProfit(($mainProduct)->id, 'last_week');
                                            } elseif ($time === 'this_month') {
                                                $total_capital = getProductTotalCapital(($mainProduct)->id, 'this_month');
                                                $total_profit = getProductTotalProfit(($mainProduct)->id, 'this_month');
                                            } elseif ($time === 'last_month') {
                                                $total_capital = getProductTotalCapital(($mainProduct)->id, 'last_month');
                                                $total_profit = getProductTotalProfit(($mainProduct)->id, 'last_month');
                                            } elseif ($time === 'last_30_days') {
                                                $total_capital = getProductTotalCapital(($mainProduct)->id, 'last_30_days');
                                                $total_profit = getProductTotalProfit(($mainProduct)->id, 'last_30_days');
                                            } elseif ($time === 'this_year') {
                                                $total_capital = getProductTotalCapital(($mainProduct)->id, 'this_year');
                                                $total_profit = getProductTotalProfit(($mainProduct)->id, 'this_year');
                                            } elseif ($time === 'range') {
                                                $from = $_GET['from'];
                                                $to = $_GET['to'];
                                                $total_capital = getProductTotalCapital(($mainProduct)->id, 'range', $from, $to);
                                                $total_profit = getProductTotalProfit(($mainProduct)->id, 'range', $from, $to);
                                            }
                                        } else {
                                            $total_capital = getProductTotalCapital(($mainProduct)->id);
                                            $total_profit = getProductTotalProfit(($mainProduct)->id);
                                        }
                                        ?>
                                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium all-capital">
                                            <?= ($total_capital) ?> </h2>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Capital
                                        </h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <div class="d-inline-flex align-items-center">

                                            <h2 class="text-dark mb-1 font-weight-medium">
                                                <?= number_format($total_capital - $total_profit, 0, ".", ",") . " DH" ?>
                                            </h2>

                                        </div>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Expenses</h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4 card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <div class="d-inline-flex align-items-center">
                                            <?php
                                            $average_net = 0;

                                            if (isset($_GET['t'])) {
                                                $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                                if ($time === 'today') {
                                                    $average_net = getProductAverageNetprofit(($mainProduct)->id, 'today');
                                                } elseif ($time === 'yesterday') {
                                                    $average_net = getProductAverageNetprofit(($mainProduct)->id, 'yesterday');
                                                } elseif ($time === 'last_week') {
                                                    $average_net = getProductAverageNetprofit(($mainProduct)->id, 'last_week');
                                                } elseif ($time === 'this_month') {
                                                    $average_net = getProductAverageNetprofit(($mainProduct)->id, 'this_month');
                                                } elseif ($time === 'last_month') {
                                                    $average_net = getProductAverageNetprofit(($mainProduct)->id, 'last_month');
                                                } elseif ($time === 'last_30_days') {
                                                    $average_net = getProductAverageNetprofit(($mainProduct)->id, 'last_30_days');
                                                } elseif ($time === 'this_year') {
                                                    $average_net = getProductAverageNetprofit(($mainProduct)->id, 'this_year');
                                                } elseif ($time === 'range') {
                                                    $from = $_GET['from'];
                                                    $to = $_GET['to'];
                                                    $average_net = getProductAverageNetprofit(($mainProduct)->id, 'range', $from, $to);
                                                }
                                            } else {
                                                $average_net = getProductAverageNetprofit(($mainProduct)->id);
                                            }
                                            ?>
                                            <h2 class="text-dark mb-1 font-weight-medium avg-net">
                                                <?= ($average_net) ?>
                                            </h2>
                                        </div>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Average Net Profit</h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-4 card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <div class="d-inline-flex align-items-center">
                                            <?php
                                            $total_profit = 0;
                                            if (isset($_GET['t'])) {
                                                $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                                if ($time === 'today') {
                                                    $total_profit = getProductTotalProfit(($mainProduct)->id, 'today');
                                                } elseif ($time === 'yesterday') {
                                                    $total_profit = getProductTotalProfit(($mainProduct)->id, 'yesterday');
                                                } elseif ($time === 'last_week') {
                                                    $total_profit = getProductTotalProfit(($mainProduct)->id, 'last_week');
                                                } elseif ($time === 'this_month') {
                                                    $total_profit = getProductTotalProfit(($mainProduct)->id, 'this_month');
                                                } elseif ($time === 'last_month') {
                                                    $total_profit = getProductTotalProfit(($mainProduct)->id, 'last_month');
                                                } elseif ($time === 'last_30_days') {
                                                    $total_profit = getProductTotalProfit(($mainProduct)->id, 'last_30_days');
                                                } elseif ($time === 'this_year') {
                                                    $total_profit = getProductTotalProfit(($mainProduct)->id, 'this_year');
                                                } elseif ($time === 'range') {
                                                    $from = $_GET['from'];
                                                    $to = $_GET['to'];
                                                    $total_profit = getProductTotalProfit(($mainProduct)->id, 'range', $from, $to);
                                                }
                                            } else {
                                                $total_profit = getProductTotalProfit(($mainProduct)->id);
                                            }
                                            ?>
                                            <h2 class="text-dark mb-1 font-weight-medium all-profit">
                                                <sup class="set-doller">$</sup><?= ($total_profit) ?>
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


                        <div class="col-md-4 card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <div class="d-inline-flex align-items-center">
                                            <?php
                                            $all_orders = 0;
                                            if (isset($_GET['t'])) {
                                                $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                                if ($time === 'today') {
                                                    $all_orders = getProductTotalOrders(($mainProduct)->id, 'today');
                                                } elseif ($time === 'yesterday') {
                                                    $all_orders = getProductTotalOrders(($mainProduct)->id, 'yesterday');
                                                } elseif ($time === 'last_week') {
                                                    $all_orders = getProductTotalOrders(($mainProduct)->id, 'last_week');
                                                } elseif ($time === 'this_month') {
                                                    $all_orders = getProductTotalOrders(($mainProduct)->id, 'this_month');
                                                } elseif ($time === 'last_month') {
                                                    $all_orders = getProductTotalOrders(($mainProduct)->id, 'last_month');
                                                } elseif ($time === 'last_30_days') {
                                                    $all_orders = getProductTotalOrders(($mainProduct)->id, 'last_30_days');
                                                } elseif ($time === 'this_year') {
                                                    $all_orders = getProductTotalOrders(($mainProduct)->id, 'this_year');
                                                } elseif ($time === 'range') {
                                                    $from = $_GET['from'];
                                                    $to = $_GET['to'];
                                                    $all_orders = getProductTotalOrders(($mainProduct)->id, 'range', $from, $to);
                                                }
                                            } else {
                                                $all_orders = getProductTotalOrders(($mainProduct)->id);
                                            }
                                            ?>
                                            <h2 class="text-dark mb-1 font-weight-medium"><?= ($all_orders) ?></h2>
                                        </div>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Orders</h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <div class="d-inline-flex align-items-center">
                                            <?php
                                            $confirmation_rate = 0;
                                            $confirmed = 0;

                                            if (isset($_GET['t'])) {
                                                $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                                if ($time === 'today') {
                                                    $confirmation_rate = getProductConfirmationRate(($mainProduct)->id, 'today');
                                                    $confirmed = getProductConfirmedOrders(($mainProduct)->id, 'today');
                                                } elseif ($time === 'yesterday') {
                                                    $confirmation_rate = getProductConfirmationRate(($mainProduct)->id, 'yesterday');
                                                    $confirmed = getProductConfirmedOrders(($mainProduct)->id, 'yesterday');
                                                } elseif ($time === 'last_week') {
                                                    $confirmation_rate = getProductConfirmationRate(($mainProduct)->id, 'last_week');
                                                    $confirmed = getProductConfirmedOrders(($mainProduct)->id, 'last_week');
                                                } elseif ($time === 'this_month') {
                                                    $confirmation_rate = getProductConfirmationRate(($mainProduct)->id, 'this_month');
                                                    $confirmed = getProductConfirmedOrders(($mainProduct)->id, 'this_month');
                                                } elseif ($time === 'last_month') {
                                                    $confirmation_rate = getProductConfirmationRate(($mainProduct)->id, 'last_month');
                                                    $confirmed = getProductConfirmedOrders(($mainProduct)->id, 'last_month');
                                                } elseif ($time === 'last_30_days') {
                                                    $confirmation_rate = getProductConfirmationRate(($mainProduct)->id, 'last_30_days');
                                                    $confirmed = getProductConfirmedOrders(($mainProduct)->id, 'last_30_days');
                                                } elseif ($time === 'this_year') {
                                                    $confirmation_rate = getProductConfirmationRate(($mainProduct)->id, 'this_year');
                                                    $confirmed = getProductConfirmedOrders(($mainProduct)->id, 'this_year');
                                                } elseif ($time === 'range') {
                                                    $from = $_GET['from'];
                                                    $to = $_GET['to'];
                                                    $confirmation_rate = getProductConfirmationRate(($mainProduct)->id, 'range', $from, $to);
                                                    $confirmed = getProductConfirmedOrders(($mainProduct)->id, 'range', $from, $to);
                                                }
                                            } else {
                                                $confirmation_rate = getProductConfirmationRate(($mainProduct)->id);
                                                $confirmed = getProductConfirmedOrders(($mainProduct)->id);
                                            }
                                            ?>
                                            <h2 class="text-dark mb-1 font-weight-medium">
                                                <?= ($confirmation_rate) ?>
                                            </h2>
                                            <span class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">
                                                <?= ($confirmed) ?>
                                            </span>
                                        </div>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Confirmation Rate</h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="globe"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 card border-right">
                            <div class="card-body">
                                <div class="d-flex d-lg-flex d-md-block align-items-center">
                                    <div>
                                        <div class="d-inline-flex align-items-center">
                                            <?php
                                            $delivery_rate = 0;
                                            $delivered = 0;

                                            if (isset($_GET['t'])) {
                                                $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                                if ($time === 'today') {
                                                    $delivery_rate = getProductDeliveryRate(($mainProduct)->id, 'today');
                                                    $delivered = getProductDeliveredOrders(($mainProduct)->id, 'today');
                                                } elseif ($time === 'yesterday') {
                                                    $delivery_rate = getProductDeliveryRate(($mainProduct)->id, 'yesterday');
                                                    $delivered = getProductDeliveredOrders(($mainProduct)->id, 'yesterday');
                                                } elseif ($time === 'last_week') {
                                                    $delivery_rate = getProductDeliveryRate(($mainProduct)->id, 'last_week');
                                                    $delivered = getProductDeliveredOrders(($mainProduct)->id, 'last_week');
                                                } elseif ($time === 'this_month') {
                                                    $delivery_rate = getProductDeliveryRate(($mainProduct)->id, 'this_month');
                                                    $delivered = getProductDeliveredOrders(($mainProduct)->id, 'this_month');
                                                } elseif ($time === 'last_month') {
                                                    $delivery_rate = getProductDeliveryRate(($mainProduct)->id, 'last_month');
                                                    $delivered = getProductDeliveredOrders(($mainProduct)->id, 'last_month');
                                                } elseif ($time === 'last_30_days') {
                                                    $delivery_rate = getProductDeliveryRate(($mainProduct)->id, 'last_30_days');
                                                    $delivered = getProductDeliveredOrders(($mainProduct)->id, 'last_30_days');
                                                } elseif ($time === 'this_year') {
                                                    $delivery_rate = getProductDeliveryRate(($mainProduct)->id, 'this_year');
                                                    $delivered = getProductDeliveredOrders(($mainProduct)->id, 'this_year');
                                                } elseif ($time === 'range') {
                                                    $from = $_GET['from'];
                                                    $to = $_GET['to'];
                                                    $delivery_rate = getProductDeliveryRate(($mainProduct)->id, 'range', $from, $to);
                                                    $delivered = getProductDeliveredOrders(($mainProduct)->id, 'range', $from, $to);
                                                }
                                            } else {
                                                $delivery_rate = getProductDeliveryRate(($mainProduct)->id);
                                                $delivered = getProductDeliveredOrders(($mainProduct)->id);
                                            }
                                            ?>
                                            <h2 class="text-dark mb-1 font-weight-medium">
                                                <?= ($delivery_rate) ?>
                                            </h2>
                                            <span class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">
                                                <?= ($delivered) ?>
                                            </span>
                                        </div>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Delivery Rate</h6>
                                    </div>
                                    <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                                    </div>
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
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-5 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Capital Breaking (All Sales)</h4>
                                <canvas id="myChart6" width="100%" height="100%"></canvas>
                                <ul class="list-style-none mb-0 chartdetails all-sales">
                                    <li>
                                        <i class="fas fa-circle font-10 mr-2" style="color: #2ecc71"></i>
                                        <span class="text-muted">Cost Price</span>
                                        <span class="text-dark float-right font-weight-medium cost-price">$00.00</span>
                                    </li>
                                    <li class="mt-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #3498db"></i>
                                        <span class="text-muted">Confirmation Fees</span>
                                        <span class="text-dark float-right font-weight-medium confirm-fees">$00.00</span>
                                    </li>
                                    <li class="mt-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #95a5a6"></i>
                                        <span class="text-muted">Delivery Fees</span>
                                        <span class="text-dark float-right font-weight-medium delivery-fees">$00.00</span>
                                    </li>
                                    <li class="mt-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #9b59b6"></i>
                                        <span class="text-muted">Ads</span>
                                        <span class="text-dark float-right font-weight-medium ads">$00.00</span>
                                    </li>
                                    <li class="mt-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #f1c40f"></i>
                                        <span class="text-muted">Net Profit</span>
                                        <span class="text-dark float-right font-weight-medium net-profit">$00.00</span>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Capital Breaking (Single)</h4>
                                <canvas id="myChart7" width="100%" height="100%"></canvas>
                                <ul class="list-style-none mb-0 chartdetails single-sale">
                                    <li>
                                        <i class="fas fa-circle font-10 mr-2" style="color: #2ecc71"></i>
                                        <span class="text-muted">Cost Price</span>
                                        <span class="text-dark float-right font-weight-medium cost-price">$00.00</span>
                                    </li>
                                    <li class="mt-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #3498db"></i>
                                        <span class="text-muted">Confirmation Fees</span>
                                        <span class="text-dark float-right font-weight-medium confirm-fees">$00.00</span>
                                    </li>
                                    <li class="mt-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #95a5a6"></i>
                                        <span class="text-muted">Delivery Fees</span>
                                        <span class="text-dark float-right font-weight-medium delivery-fees">$00.00</span>
                                    </li>

                                    <li class="mt-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #9b59b6"></i>
                                        <span class="text-muted">Ads</span>
                                        <span class="text-dark float-right font-weight-medium ads">$00.00</span>
                                    </li>

                                    <li class="mt-3">
                                        <i class="fas fa-circle font-10 mr-2" style="color: #f1c40f"></i>
                                        <span class="text-muted">Net Profit</span>
                                        <span class="text-dark float-right font-weight-medium net-profit">$00.00</span>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row d-flex justify-content-center">
                    <div class="col-md-8 col-lg-8 ">
                        <div class="card">
                            <div class="d-flex align-items-start">
                                <h4 class="card-title mb-0">Total Orders</h4>
                            </div>

                            <canvas id="myChart5" width="400" height="250"></canvas>
                        </div>
                    </div>
                </div>
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
                                <h4 class="card-title mb-0">Avg Net Profit Per Sale</h4>
                            </div>

                            <canvas id="myChart4" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <script src="assets/libs/jquery/dist/jquery.min.js"></script>
                <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
                <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
                <!-- apps -->
                <!-- apps -->
                <script src="dist/js/app-style-switcher.js"></script>
                <script src="dist/js/feather.min.js"></script>
                <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
                <script src="dist/js/sidebarmenu.js"></script>
                <!--Custom JavaScript -->
                <script src="dist/js/custom.min.js"></script>
                <!--This page JavaScript -->
                <script src="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
                <script src="assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>
                <script src="dist/js/pages/dashboards/dashboard1.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
                <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
                <script src="dist/js/insight.js"></script>

                <script>
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var ctx2 = document.getElementById('myChart2').getContext('2d');
                    var ctx3 = document.getElementById('myChart3').getContext('2d');
                    var ctx4 = document.getElementById('myChart4').getContext('2d');
                    var ctx5 = document.getElementById('myChart5').getContext('2d');
                    var ctx6 = document.getElementById('myChart6').getContext('2d');
                    var ctx7 = document.getElementById('myChart7').getContext('2d');


                    // Net Profit Chart:
                    $(document).ready(() => {
                        var api = "api/ajax.php?pid=" + <?= $mainProduct->id ?> + "&fetch_product_profit";
                        var url = window.location.href;
                        if (url.match("t=.*")) {
                            api += `&${url.match("t=.+").toString()}`;
                        }

                        $.ajax({
                            url: api,
                            method: "GET",
                            success: (response) => {
                                if (JSON.parse(response).status == 'success') {
                                    const data = JSON.parse(response).profit;

                                    let profit = [];
                                    let sets = [];

                                    data.forEach(elm => {
                                        profit.push(elm.date);
                                        sets.push(`${parseFloat(elm.profit)}`);
                                    });

                                    var myChart = new Chart(ctx, {
                                        type: (profit.length > 2) ? 'line' : 'bar',
                                        data: {
                                            labels: profit,
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
                        var api = "api/ajax.php?pid=" + <?= $mainProduct->id ?> + "&fetch_product_confirmation_rate";
                        var url = window.location.href;
                        if (url.match("t=.*")) {
                            api += `&${url.match("t=.+").toString()}`;
                        }

                        $.ajax({
                            url: api,
                            method: "GET",
                            success: (response) => {
                                if (JSON.parse(response).status == 'success') {
                                    const confirm_rates = JSON.parse(response).rates;

                                    let rates = [];
                                    let sets = [];

                                    confirm_rates.forEach(elm => {
                                        rates.push(elm.date);
                                        sets.push(`${elm.rate}`);
                                    });

                                    var myChart = new Chart(ctx2, {
                                        type: (rates.length > 2) ? 'line' : 'bar',
                                        data: {
                                            labels: rates,
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
                        var api = "api/insight.php?pid=" + <?= $mainProduct->id ?> + "&fetch_product_delivery_rate";
                        var url = window.location.href;
                        if (url.match("t=.*")) {
                            api += `&${url.match("t=.+").toString()}`;
                        }

                        $.ajax({
                            url: api,
                            method: "GET",
                            success: (response) => {
                                if (JSON.parse(response).status == 'success') {
                                    const delivery_rates = JSON.parse(response).rates;

                                    let rates = [];
                                    let sets = [];

                                    delivery_rates.forEach(elm => {
                                        rates.push(elm.date);
                                        sets.push(`${elm.rate}`);
                                    });

                                    var myChart = new Chart(ctx3, {
                                        type: (rates.length > 2) ? 'line' : 'bar',
                                        data: {
                                            labels: rates,
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


                    // average Net Profit
                    $(document).ready(() => {
                        var api = "api/ajax.php?pid=" + <?= $mainProduct->id ?> + "&fetch_product_average_profit";
                        var url = window.location.href;
                        if (url.match("t=.*")) {
                            api += `&${url.match("t=.+").toString()}`;
                        }

                        $.ajax({
                            url: api,
                            method: "GET",
                            success: (response) => {
                                if (JSON.parse(response).status == 'success') {
                                    const data = JSON.parse(response).profit;

                                    let profit = [];
                                    let sets = [];

                                    data.forEach(elm => {
                                        profit.push(elm.date);
                                        sets.push(`${parseFloat(elm.profit)}`);
                                    });

                                    var myChart = new Chart(ctx4, {
                                        type: (profit.length > 2) ? 'line' : 'bar',
                                        data: {
                                            labels: profit,
                                            datasets: [{
                                                label: 'Average Net Profit',
                                                data: sets,
                                                backgroundColor: '#e67e22',
                                                borderColor: '#e67e22',
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
                        var api = "api/insight.php?pid=" + <?php echo $mainProduct->id ?> + "&fetch_product_orders";
                        var url = window.location.href;
                        if (url.match("t=.*")) {
                            api += `&${url.match("t=.+").toString()}`;
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
                                        sets.push(elm.orders);
                                    });

                                    var myChart = new Chart(ctx5, {
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

                    // Left-Pie
                    $(document).ready(() => {
                        var api = "api/insight.php?pid=" + <?= $mainProduct->id ?> + "&fetch_product_info";
                        const url = window.location.href;
                        if (url.match("t=.*")) {
                            api += `&${url.match("t=.+").toString()}`;
                        }

                        $.ajax({
                            url: api,
                            method: "GET",
                            success: (response) => {
                                const data = JSON.parse(response);
                                if (data.status === 'success') {

                                    //  const
                                    var total = 0;
                                    var percentage = [];

                                    const cost_price = parseFloat(data.cost_price);
                                    $(".all-sales .cost-price").text(`${new Intl.NumberFormat().format(cost_price)} DH`);
                                    total += cost_price;

                                    const confirm_fees = parseFloat(data.confirm_fees);
                                    $(".all-sales .confirm-fees").text(`${new Intl.NumberFormat().format(confirm_fees)} DH`);
                                    total += confirm_fees;

                                    const delivery_fees = parseFloat(data.delivery_fees);
                                    $(".all-sales .delivery-fees").text(`${new Intl.NumberFormat().format(delivery_fees)} DH`);
                                    total += delivery_fees;

                                    const ads = parseFloat(data.ads);
                                    $(".all-sales .ads").text(`${new Intl.NumberFormat().format(ads)} DH`);
                                    total += ads;

                                    const net_profit = parseFloat(data.profit);
                                    $(".all-sales .net-profit").text(`${new Intl.NumberFormat().format(net_profit)} DH`);
                                    total += net_profit;

                                    (cost_price > 0) ? percentage.push(((cost_price * 100) / total).toFixed(0)): null;
                                    (confirm_fees > 0) ? percentage.push(((confirm_fees * 100) / total).toFixed(0)): null;
                                    (delivery_fees > 0) ? percentage.push(((delivery_fees * 100) / total).toFixed(0)): null;
                                    (ads > 0) ? percentage.push(((ads * 100) / total).toFixed(0)): null;
                                    (net_profit > 0) ? percentage.push(((net_profit * 100) / total).toFixed(0)): null;

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
                                            labels: ["Cost Price", "Confirmation Fees", "Delivery Fees", "Ads", "Net Profit"],
                                        },
                                        options: {
                                            cutoutPercentage: 83,
                                            legend: {
                                                display: false
                                            }
                                        }
                                    });
                                }

                            },
                            error: (xhr, err) => {

                            }
                        });
                    });

                    // Right-Pie
                    $(document).ready(() => {
                        var api = "api/insight.php?pid=" + <?= $mainProduct->id ?> + "&fetch_product_info";
                        const url = window.location.href;
                        if (url.match("t=.*")) {
                            api += `&${url.match("t=.+").toString()}`;
                        }


                        $.ajax({
                            url: api,
                            method: "GET",
                            success: (response) => {
                                const data = JSON.parse(response);
                                if (data.status === 'success') {

                                    //  const
                                    var total = 0;
                                    var percentage = [];

                                    const cond1 = (data.cost_price > 0 && data.sales > 0);
                                    const single_sale_cost = cond1 ? parseFloat(parseFloat(data.cost_price) / parseInt(data.delivered)) : 0;
                                    $(".single-sale .cost-price").text(`${new Intl.NumberFormat().format(single_sale_cost)} DH`);
                                    total += single_sale_cost;

                                    const cond2 = (data.confirm_fees > 0 && data.confirmed > 0);
                                    const single_sale_cf = cond2 ? parseFloat(parseFloat(data.confirm_fees) / parseInt(data.delivered)) : 0;
                                    $(".single-sale .confirm-fees").text(`${new Intl.NumberFormat().format(single_sale_cf)} DH`);
                                    total += single_sale_cf;


                                    const cond3 = (data.delivery_fees > 0 && data.delivered > 0);
                                    const single_sale_df = cond3 ? parseFloat(parseFloat(data.delivery_fees) / parseInt(data.delivered)) : 0;
                                    $(".single-sale .delivery-fees").text(`${new Intl.NumberFormat().format(single_sale_df)} DH`);
                                    total += single_sale_df;

                                    const cond4 = (data.ads > 0 && data.sales > 0);
                                    const single_sale_ads = cond4 ? parseFloat(parseFloat(data.ads) / parseInt(data.delivered)) : 0;
                                    $(".single-sale .ads").text(`${new Intl.NumberFormat().format(single_sale_ads)} DH`);
                                    total += single_sale_ads;

                                    const cond5 = (data.profit > 0 && data.sales > 0);
                                    const single_sale_profit = cond5 ? parseFloat(parseFloat(data.profit) / parseInt(data.delivered)) : 0;
                                    $(".single-sale .net-profit").text(`${new Intl.NumberFormat().format(single_sale_profit)} DH`);
                                    total += single_sale_profit;

                                    cond1 ? percentage.push(((single_sale_cost * 100) / total).toFixed(0)) : null;
                                    cond2 ? percentage.push(((single_sale_cf * 100) / total).toFixed(0)) : null;
                                    cond3 ? percentage.push(((single_sale_df * 100) / total).toFixed(0)) : null;
                                    cond4 ? percentage.push(((single_sale_ads * 100) / total).toFixed(0)) : null;
                                    cond5 ? percentage.push(((single_sale_profit * 100) / total).toFixed(0)) : null;


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
                                                data: (percentage.length > 0) ? percentage : ['100'],
                                                borderWidth: 4
                                            }],
                                            labels: ["Cost Price", "Confirmation Fees", "Delivery Fees", "Ads", "Net Profit"],
                                        },
                                        options: {
                                            cutoutPercentage: 83,
                                            legend: {
                                                display: false
                                            }
                                        }
                                    });
                                }

                            },
                            error: (xhr, err) => {

                            }
                        });
                    });

                    $(document).ready(() => {
                        const capital = parseInt($('.all-capital').text().trim().match("[0-9].+"));
                        const profit = parseInt($('.all-profit').text().trim().match("[0-9\-].+"));
                        const avg_net = parseInt($('.avg-net').text().trim().match("[0-9\-].+"));
                        $(".all-capital").text(`${new Intl.NumberFormat().format(isNaN(capital) ? 0 : capital)} DH`); //  ? new Intl.NumberFormat().format(capital) : (0)
                        $(".all-profit").text(`${new Intl.NumberFormat().format(isNaN(profit) ? 0 : profit)} DH`); // ? new Intl.NumberFormat().format(profit) : (0)
                        $(".avg-net").text(`${new Intl.NumberFormat().format(isNaN(avg_net) ? 0 : avg_net)} DH`); // ? new Intl.NumberFormat().format(profit) : (0)
                    });
                </script>
</body>

</html>