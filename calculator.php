<?php require_once('modules/Init.php') ?>

<?php
$products  = $db->getMany(PRODUCTS);
$tmp_products  = $db->getMany(TMP, ['seen' => False]);
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tail.select@0.5.15/css/default/tail.select-light.css">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
  <title>Adminmart Template - The Ultimate Multipurpose admin template</title>
  <!-- Custom CSS -->
  <link href="dist/css/style.min.css" rel="stylesheet">
  <link href="dist/css/calculator.css" rel="stylesheet">
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
      <!-- ============================================================== -->
      <!-- Bread crumb and right sidebar toggle -->
      <!-- ============================================================== -->
      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Calculator Page
            </h4>
            <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 p-0">
                  <li class="breadcrumb-item"><a href="index.php" class="text-muted">Home</a></li>
                  <li class="breadcrumb-item text-muted active" aria-current="page">Calculator</li>
                </ol>
              </nav>
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
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row card-title">
                  <div class="col-sm-8">
                    <h4 class="card-title">History List</h4>
                  </div>
                  <div class="col-sm-4">
                    <form action="" method="post" disabled>
                      <button class="btn btn-danger btn-sm pull-right ml-1" name="dismiss_all" id="dismiss" type="submit">Remove All</button>
                    </form>
                    <button class="btn btn-success btn-sm pull-right" type="button" id="addnew">Add new reports</button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead class="bg-primary text-white">
                      <tr>
                        <th class='column1'>#</th>
                        <th class='column2'>Date</th>
                        <th class='column3'>Product Name</th>
                        <th class='column4'>Orders</th>
                        <th class='column5'>Confirmed</th>
                        <th class='column6'>Delivered</th>
                        <th class='column7'>Ads</th>
                        <th>Dismiss</th>
                      </tr>
                    </thead>
                    <tbody id="table-body">
                      <?php

                      if ($tmp_products) {
                        foreach ($tmp_products as $row) {
                          echo "<tr id='row-" . ($row)->id . "' class='tr alert-success column1' data-id=" . ($row)->id . "><td>" . ($row)->id . "</td>";
                          echo "<td data-target='Date' class='column2'>" . ($row)->date . "</td>";
                          echo "<td data-target='Productname' class='column3'>" . ($row)->product_name . "</td>";
                          echo "<td data-target='َAllorders' class='alle column4'>" . ($row)->orders . "</td>";
                          echo "<td data-target='Totalorders' class='column5'>" . ($row)->confirmed . "</td>";
                          echo "<td data-target='Totaldelivered' class='column6'>" . ($row)->delivered . "</td>";
                          echo "<td data-target='Ads' class='column7'>" . (($row)->ads . " DH") . "</td>";
                          echo "<td style='cursor: pointer;' class='column5 text-center' id='" . ($row)->id . "' data-target='dismiss-tmp'><i data-toggle='tooltip' data-title='Mark as Seen' class='fa fa-eye'></i></td>";
                          echo "</tr>";
                        }
                      } else {
                        echo "<tr id='alert' class='alert-warning text-center'><td colspan='8'>No Product Was Recently Saved.</td></tr>";
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

      <div id="myModal5" class="modal fade w-100" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <strong>Add new Product</strong>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form name="dataform" method="POST" action="" class="right-side p-3">
                <div class="totalform w-100">
                  <div class="d-flex justify-content-sm-evenly align-items-start">
                    <div class="form-group w-50 m-1 dates">
                      <label>Choose a Date :</label>
                      <input required type="text" name="date" class="form-control datetimepicker-input first" id="datetimepicker5" data-toggle="datetimepicker" data-target="#datetimepicker5" value="<?= (isset($tmp_date) ? $tmp_date : null) ?>" autocomplete="off" />
                    </div>
                    <div class="form-group w-50 m-1">
                      <label class="d-block">Product Name :</label>
                      <select class="form-control" name="productName" id="selectprod">
                        <?php
                        if (!empty($products)) {
                          foreach ($products as $product) {
                            echo "<option id='{$product->id}'  value='{$product->id}'>{$product->product_name}</option>";
                          }
                        }
                        ?>
                      </select><br>
                    </div>
                  </div>
                  <div class="d-flex justify-content-sm-evenly align-items-start">
                    <div class="form-group w-50 m-1">
                      <label class="d-block">All Orders :</label>
                      <input class="rounded-sm rounded p-1 form-control" type="number" required type='text' name='allOrders' autocomplete="off"><br>
                    </div>
                    <div class="form-group w-50 m-1">
                      <label class="d-block">Confirmed Orders :</label>
                      <input class="rounded-sm rounded p-1 form-control" type="number" required type='text' name='totalOrders' autocomplete="off"><br>
                    </div>

                  </div>
                  <div class="d-flex justify-content-sm-evenly align-items-start">
                    <div class="form-group w-50 m-1">
                      <label class="d-block">Delivered Orders :</label>
                      <input class="rounded-sm rounded p-1 form-control" type="number" required type='text' name='totalDelivered' autocomplete="off"><br>
                    </div>
                    <div class="form-group w-50 m-1">
                      <label class="d-block">Ads :</label>
                      <input class="rounded-sm rounded p-1 form-control" type="number" required type='text' name='ads' autocomplete="off">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <input class="btn btn-success w-100 rounded-0 pull-right" type="submit" name="saveProduct" value="Save Product">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
              </form>

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
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
  <script src="dist/js/calculator.js"></script>
  <?php include(MAIN_JS); ?>

  <script>
    $("td[data-target=dismiss-tmp]").on("click", () => {
      const id = $("td[data-target=dismiss-tmp]").attr("id");
      $.ajax({
        url: "api/ajax.php",
        method: "POST",
        data: {
          id: id,
          markAsSeen: true,
        },
        success: (response) => {
          if (JSON.parse(response).status === "success") {
            $(`tr[data-id=${id}]`).remove();
          }
        },
        error: (xhr, err) => {
          console.log(err);
        }
      });
    });
  </script>

  <script>
    $(document).ready(() => {
      const children = $("#table-body tr");
      if (children.length === 1 && children.eq(0).attr("id") === "alert") {
        $("#dismiss").addClass("disabled");
      }
    });
  </script>

</body>

</html>