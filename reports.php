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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="dist/css/reports.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- HERE GOES THE TOP NAV -->
        <?php include(HEADER); ?>

        <!-- HERE GOES THE SIDE BAR -->
        <?php include(SIDEBAR); ?>

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-md-5 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Reports Page
                        </h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">Home</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Reports</li>
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
                            <a href="#" class="btn kt-subheader__btn-daterange" id="demo" data-toggle="kt-tooltip" data-title="Select dashboard daterange" data-placement="left">
                                <span class="kt-subheader__btn-daterange-title date-label" id="kt_dashboard_daterangepicker_title"></span>
                                <span class="kt-subheader__btn-daterange-date date-value" id="kt_dashboard_daterangepicker_date">
                                    Lifetime
                                    <?php  ?>
                                </span>
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row card-title">
                                    <div class="col-sm-8">
                                        <h4 class="card-title">Reports List</h4>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table sortable">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class='column1'>#</th>
                                                <th class='column2'>Date</th>
                                                <th class='column3'>Total Capital</th>
                                                <th class='column4'>Total Profit</th>
                                                <th class='column5'>Confirm rate</th>
                                                <th class='column6'>Delivery rate</th>
                                                <th class='column7'>Control</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($_GET['t'])) {
                                                $time = filter_var($_GET['t'], FILTER_SANITIZE_STRING);

                                                $reports = [];

                                                if ($time === 'today')        $reports = today();
                                                if ($time === 'yesterday')    $reports = yesterday();
                                                if ($time === 'last_week')    $reports = last_week();
                                                if ($time === 'this_month')   $reports = this_month();
                                                if ($time === 'last_month')   $reports = last_month();
                                                if ($time === 'last_30_days') $reports = last_30_days();
                                                if ($time === 'this_year')    $reports = this_year();
                                                if ($time === 'all')          $reports = $dates;
                                                if ($time === 'range')        $reports = dateRange($_GET['from'], $_GET['to']);

                                                if (!empty($reports) && count($reports) > 0) {
                                                    foreach ($reports as $date) {

                                                        $cp_total = number_format(floatval(str_replace(",", "", getCpTotalPerDate($date->id))));
                                                        $nt_total = number_format(floatval(str_replace(",", "", getNetTotalPerDate($date->id))));
                                                        $cf_rate = getConRatePerDate($date->id);
                                                        $dv_rate = getDevRatePerDate($date->id);

                                                        echo '<tr id="' . $date->id . '" class="tr" data-id="' . $date->id . '">';
                                                        echo "<td class='column1'>" . $date->id . "</td>";
                                                        echo "<td data-target='Datetime' id='openup' class='column2'>" . $date->date . "</td>";
                                                        echo "<td data-target='KK' class='column3'>" . ($cp_total . " DH") . "</td>";
                                                        echo "<td data-target='Saleprice' class='column4'>" . ($nt_total . " DH") . "</td>";
                                                        echo "<td data-target='KK' class='column5'>" . ($cf_rate) . "</td>";
                                                        echo "<td data-target='KKK' class='column6'>" . ($dv_rate) . "</td>";
                                                        echo "<td class='column6 actions' class='column7'>";
                                                        echo "<a class='download' title='Download PDF' data-toggle='tooltip' data-role='download' data-id=" . ($date->id) . "><i class='fas fa-download'></i></a>";
                                                        echo "<a class='delete' title='Delete' data-toggle='tooltip' data-role='delete' data-id=" . ($date->id) . "><i class='fas fa-trash'></i></a>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr class='text-center alert-warning'><td colspan='7'>No Records</td></tr>";
                                                }
                                            } else {
                                                if (!empty($dates) && count($dates) > 0) {
                                                    foreach ($dates as $date) {

                                                        $cp_total = number_format(floatval(str_replace(",", "", getCpTotalPerDate($date->id))));
                                                        $nt_total = number_format(floatval(str_replace(",", "", getNetTotalPerDate($date->id))));
                                                        $cf_rate = getConRatePerDate($date->id);
                                                        $dv_rate = getDevRatePerDate($date->id);

                                                        echo '<tr id="' . $date->id . '" class="tr" data-id="' . $date->id . '">';
                                                        echo "<td>" . $date->id . "</td>";
                                                        echo "<td data-target='Datetime' id='openup'>" . $date->date . "</td>";
                                                        echo "<td data-target='KK'>" . ($cp_total . " DH") . "</td>";
                                                        echo "<td data-target='Saleprice'>" . ($nt_total . " DH") . "</td>";
                                                        echo "<td data-target='KK'>" . ($cf_rate) . "</td>";
                                                        echo "<td data-target='KKK'>" . ($dv_rate) . "</td>";
                                                        echo "<td class='column6 actions'>";
                                                        echo "<a class='download' title='Download PDF' data-toggle='tooltip' data-role='download' data-id=" . ($date->id) . "><i class='fas fa-download'></i></a>";
                                                        echo "<a class='delete' title='Delete' data-toggle='tooltip' data-role='delete' data-id=" . ($date->id) . "><i class='fas fa-trash'></i></a>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr class='text-center alert-warning'><td colspan='7'>No Records</td></tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="centralModalFluid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-fluid modal-xl" role="document">
                    <!--Content-->
                    <div class="modal-content">
                        <!--Header-->
                        <div class="modal-header">
                            <h4 class="modal-title w-100" id="myModalLabel">Reports For date</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!--Body-->
                        <div class="modal-body">
                            <div class="table-wrapper" id="exportis">
                                <table class="table table-bordered small" id="table">
                                    <thead class="full-width">
                                        <tr>
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
                                            <th>Ctrl</th>
                                        </tr>
                                        <style>
                                        </style>
                                    </thead>
                                    <tbody class="productsContainer">
                                    </tbody>
                                </table>

                                <table class="table table-bordered" id="table">
                                    <tfoot>
                                        <tr>
                                            <th class="blue">Capital</th>
                                            <td class="total_cp"></td>
                                            <th class="red">Total Expenses</th>
                                            <td class="expenses_cp"></td>
                                            <th class="green">Total Net Profit</th>
                                            <td class="net_cp"></td>
                                            
                                        </tr>
                                </table>
                            </div>
                        </div>
                        <!--Footer-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success">Download PDF</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
                        </div>
                    </div>
                    <!--/.Content-->
                </div>
            </div>


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
                                <label>Confirmed Orders</label>
                                <input type="number" id="totalOrders" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Delivered Orders</label>
                                <input type="number" id="totalDelivered" class="form-control">
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
            <div class="modal fade" id="calcModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <button type="button" id="delete-calc" class="btn btn-primary">Delete</button>
                            <input type="hidden" value="" id="calcId">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Product delete Modal -->
            <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="hidden" value="" id="reportId">
                        </div>
                    </div>
                </div>
            </div>






            <footer class="footer text-center text-muted">
                All Rights Reserved by Adminmart. Designed and Developed by <a href="https://wrappixel.com">WrapPixel</a>.
            </footer>

        </div>

    </div>

    <!-- HERE GOES THE JS FILE -->
    <?php include(MAIN_JS); ?>
    <script src="dist/js/reports.js"></script>

</body>

</html>