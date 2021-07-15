<?php require_once('modules/Init.php') ?>

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
  <link href="dist/css/icons/font-awesome/css/fontawesome-min.css" rel="stylesheet">
  <link href="dist/css/style.min.css" rel="stylesheet">
  <link href="dist/css/fees.css" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>

  <!-- ============================================================== -->
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->

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
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Fees Page
            </h4>
            <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 p-0">
                  <li class="breadcrumb-item"><a href="index.php" class="text-muted">Home</a></li>
                  <li class="breadcrumb-item text-muted active" aria-current="page">Fees</li>
                </ol>
              </nav>
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
                  <?php if (isset($_SESSION['MESSAGE'])) : ?>
                    <div class="w-100 alert alert-primary alert-dismissible fade show">
                      <strong><?= $_SESSION['MESSAGE'] ?></strong>
                      <?php unset($_SESSION['MESSAGE']); ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  <?php endif; ?>
                  <div class="col-sm-8">
                    <h4 class="card-title">Fees List</h4>
                  </div>
                  <div class="col-sm-4">
                    <button class="btn btn-success pull-right" type="button" disabled id="addnew">Add new fee</button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table products-list">
                    <?php $fees = $db->getMany(FEES); ?>
                    <thead class="bg-primary text-white">
                      <tr>
                        <th>#</th>
                        <th>Fee Name</th>
                        <th>Fee Price</th>
                        <th>Fee Type</th>
                        <th>Product Type</th>
                        <th>Date Type</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (!empty($fees) && count($fees) > 0) {
                        foreach ($fees as $fee) {

                          $fee_type = $db->getOne(FEE_TYPE, ['id' => $fee->fee_type_id], ['category']);
                          $date_type = $db->getOne(DATE_TYPE, ['id' => $fee->date_type], ['category']);
                          $product_type = $db->getOne(PRODUCT_TYPE, ['id' => $fee->product_type], ['type']);

                          echo "<tr id=" . $fee->id . " class='tr' data-id=" . $fee->id . ">";
                          echo "<td class='column1'> <i class='icon-plus'></i> </td>";
                          echo "<td class='column2' data-target='fee_name'>" . ($fee->fee_name) . "</td>";
                          echo "<td class='column3' data-target='fee_price'>" . ($fee->fee_price) . "</td>";
                          echo "<td class='column4' data-target='fee_type'>" . ($fee_type->category) . "</td>";
                          echo "<td class='column5' data-target='product_type'>" . ($product_type->type) . "</td>";
                          echo "<td class='column5' data-target='date_type'>" . ($date_type->category) . "</td>";
                          echo "<td class='column6 actions'>";
                          echo "<a class='edit' title='Edit' data-toggle='tooltip' data-role='update' data-id=" . ($fee->id) . "><i class='fas fa-pencil-alt'></i></a>";
                          echo "<a class='info' title=" . ($fee->slug) . " data-toggle='popover' data-role='info' data-id=" . ($fee->id) . "><i class='fas fa-clipboard-list'></i></a>";
                          echo "<a class='delete' title='Delete' data-toggle='tooltip' data-role='delete' data-id=" . ($fee->id) . "><i class='fas fa-trash'></i></a>";
                          echo "</td>";
                          echo "</tr>";
                        }
                      } else {
                        echo "<tr class='text-center alert-warning'><td colspan='5'>No Products Saved.</td></tr>";
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


      <div id="myModal3" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <strong>Add New Fee</strong>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form name="dataform" method="POST" action="" class="right-side">
                <div class="d-flex flex-column w-100 mt-3">
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong class="d-block mb-2">Fee Name :</strong>
                    <input required class="rounded p-1 form-control" type='text' name='fee_name' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong class="d-block mb-2">Fee Price :</strong>
                    <input type="number" required class="rounded p-1 form-control" name='fee_price' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong class="d-block mb-2">Fee Type :</strong>
                    <?php $feetypes = $db->getMany(FEE_TYPE, null, ['id', 'category']); ?>
                    <select class="form-control" name="fee_type">
                      <option selected disabled>Choose Fee Type</option>
                      <option value="regular">Regular Fee</option>
                      <option value="monthly">Monthly Fee</option>
                      <option value="exceptional">Exeptional Fee</option>
                    </select><br>
                  </div>

                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong class="d-block mb-2">Product Type :</strong>
                    <?php $productTypes = $db->getMany(PRODUCT_TYPE, null, ['id', 'type']); ?>
                    <select class="form-control" name="product_type">
                      <option>Choose Product Type</option>
                      <?php foreach ($productTypes as $productType) : ?>
                        <option value="<?= $productType->id ?>"><?= $productType->type ?></option>
                      <?php endforeach; ?>
                    </select>
                    <br>
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong class="d-block mb-2">Fee Date :</strong>
                    <?php $dateTypes = $db->getMany(DATE_TYPE, null, ['id', 'category']); ?>
                    <select class="form-control" name="date_type">
                      <option>Choose Date Type</option>
                      <?php foreach ($dateTypes as $dateType) : ?>
                        <option value="<?= $dateType->id ?>"><?= $dateType->category ?></option>
                      <?php endforeach; ?>
                    </select><br>
                  </div>

                </div>
                <div class="modal-footer">
                  <input class="btn btn-success w-100 rounded-0 pull-right" type="submit" name="addFee" value="Save Fee">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- update fee -->
      <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <strong>Update Fee</strong>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form name="dataform" method="POST" action="" class="right-side">
                <div class="d-flex flex-column w-100 mt-3">
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong class="d-block mb-2">Fee Name :</strong>
                    <input required class="rounded p-1 form-control" type='text' id="fee_name" name='fee_name' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong class="d-block mb-2">Fee Price :</strong>
                    <input type="number" required class="rounded p-1 form-control" id="fee_price" name='fee_price' autocomplete="off">
                  </div>
                  <?php if (False) : ?>
                    <div class="form-control bg-transparent w-100 border-0 mb-5">
                      <strong class="d-block mb-2">Fee Type :</strong>
                      <?php $feetypes = $db->getMany(FEE_TYPE, null, ['id', 'category']); ?>
                      <select class="form-control" name="fee_type" id="fee_type">
                        <option selected disabled>Choose Fee Type</option>
                        <option value="regular">Regular Fee</option>
                        <option value="monthly">Monthly Fee</option>
                        <option value="exceptional">Exeptional Fee</option>
                      </select><br>
                    </div>

                    <div class="form-control bg-transparent w-100 border-0 mb-5">
                      <strong class="d-block mb-2">Product Type :</strong>
                      <?php $productTypes = $db->getMany(PRODUCT_TYPE, null, ['id', 'type']); ?>
                      <select class="form-control" name="product_type" id="product_type">
                        <option>Choose Product Type</option>
                        <?php foreach ($productTypes as $productType) : ?>
                          <option value="<?= $productType->id ?>"><?= $productType->type ?></option>
                        <?php endforeach; ?>
                      </select>
                      <br>
                    </div>
                    <div class="form-control bg-transparent w-100 border-0 mb-5">
                      <strong class="d-block mb-2">Fee Date :</strong>
                      <?php $dateTypes = $db->getMany(DATE_TYPE, null, ['id', 'category']); ?>
                      <select class="form-control" name="date_type" id="date_type">
                        <option>Choose Date Type</option>
                        <?php foreach ($dateTypes as $dateType) : ?>
                          <option value="<?= $dateType->id ?>"><?= $dateType->category ?></option>
                        <?php endforeach; ?>
                      </select><br>
                    </div>
                  <?php endif ?>
                </div>
                <div class="modal-footer">
                  <input type="hidden" value="1" id="feeId" name="feeId">
                  <input class="btn btn-success w-100 rounded-0 pull-right" id="updateFee" type="submit" name="updateFee" value="Save Fee">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <input type="hidden" value="" id="fid">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" id="deleteFee" class="btn btn-primary">Delete</button>
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
  <script src="dist/js/fees.js"></script>

</body>

</html>