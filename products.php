<?php require_once('modules/Init.php') ?>

<?php

$products = $db->getMany(PRODUCTS);

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
  <title>Adminmart Template - The Ultimate Multipurpose admin template</title>
  <!-- Custom CSS -->
  <link href="dist/css/icons/font-awesome/css/fontawesome-min.css" rel="stylesheet">
  <link href="dist/css/style.min.css" rel="stylesheet">
  <link href="dist/css/products.css" rel="stylesheet">


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
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Products Page
            </h4>
            <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 p-0">
                  <li class="breadcrumb-item"><a href="index.php" class="text-muted">Home</a></li>
                  <li class="breadcrumb-item text-muted active" aria-current="page">Products</li>
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
                    <h4 class="card-title">Products List</h4>
                  </div>
                  <div class="col-sm-4">
                    <button class="btn btn-success pull-right" type="button" id="addnew">Add new product</button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table products-list">
                    <thead class="bg-primary text-white">
                      <tr>
                        <th class='column1'>#</th>
                        <th class='column2'>Product Name</th>
                        <th class='column4'>Sale price</th>
                        <th class='column5'>Costprice</th>
                        <th class='column3'>CFRM. Rate</th>
                        <th class='column3'>DLVR. Rate</th>
                        <th class='column3'>A.N.P</th>
                        <th class='column3'>Fournisseur</th>
                        <th class='column6'>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (!empty($products) && count($products) > 0) {
                        foreach ($products as $product) {

                          $tltPrft   = getProductTotalProfit($product->id);
                          $cfrmOrdrs = getProductConfirmedOrders($product->id);
                          $avg_net = ($tltPrft > 0 && $cfrmOrdrs > 0) ? $tltPrft / $cfrmOrdrs : 0;
                          $avg_net = number_format($avg_net, 2);

                          echo "<tr id=" . $product->id . " class='tr' data-id=" . $product->id . ">";
                          echo "<td class='column1'> <i class='icon-plus'></i> </td>";
                          echo "<td class='column2' data-target='Productname'>" . substr($product->product_name, 0, 24) . "</td>";
                          echo "<td class='column4' data-target='Saleprice'>" . "{$product->sale_price} DH" . "</td>";
                          echo "<td class='column5' data-target='Costprice'>" . "{$product->cost_price} DH" . "</td>";
                          echo "<td class='column3' data-target=''>" . (getProductConfirmationRate($product->id)) . "</td>";
                          echo "<td class='column3' data-target=''>" . (getProductDeliveryRate($product->id)) . "</td>";
                          echo "<td class='column3' data-target=''>" . ($avg_net . " DH") . "</td>";
                          echo "<td class='column3' data-target='Fournisseur'>" . ($product->fournisseur) . "</td>";
                          echo "<td class='column6 actions'>";
                          echo "<a class='edit' title='Edit' data-toggle='tooltip' data-role='update' data-id=" . ($product->id) . "><i class='fas fa-pencil-alt'></i></a>";
                          echo "<a class='info' href='insight.php?pid={$product->id}' title=" . ($product->product_name) . "  data-role='info' data-id=" . ($product->id) . "><i class='fas fa-clipboard-list'></i></a>";
                          echo "<a class='delete' title='Delete' data-toggle='tooltip' data-role='delete' data-id=" . ($product->id) . "><i class='fas fa-trash'></i></a>";
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
              <strong>Add new Product</strong>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form name="dataform" method="POST" action="" class="right-side">
                <div class="d-flex flex-column w-100 mt-3">
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <label>Product Name :</label>
                    <input required class="rounded p-1 form-control" type='text' name='Productname' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <label>Fournisseur :</label>
                    <input required class="rounded p-1 form-control" type='text' name='Fournisseur' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <label>Sale Price :</label>
                    <input type="number" required class="rounded p-1 form-control" name='Saleprice' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <label>Cost Price :</label>
                    <input type="number" required class="rounded p-1 form-control" name='Costprice' autocomplete="off">
                  </div>
                </div>
                <div class="modal-footer">
                  <input class="btn btn-success w-100 rounded-0 pull-right" type="submit" name="addProduct" value="Save Product">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div id="myModal" class="modal fade" role="dialog">
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
                <input type="text" id="Productname" class="form-control" autocomplete="off">
              </div>
              <div class="form-group">
                <label>Fournisseur</label>
                <input type="text" id="Fournisseur" class="form-control" autocomplete="off">
              </div>
              <div class="form-group">
                <label>Saleprice</label>
                <input type="number" id="Saleprice" class="form-control" autocomplete="off">
              </div>

              <div class="form-group">
                <label>Costprice</label>
                <input type="number" id="Costprice" class="form-control" autocomplete="off">
              </div>
              <input type="hidden" id="userId" class="form-control">
            </div>
            <div class="modal-footer">
              <a href="javascript:void(0)" id="save" class="btn btn-primary pull-right">Update</a>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
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
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" id="delete" class="btn btn-primary">Delete</button>
              <input type="hidden" value="" id="productId">
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
  <script src="dist/js/products.js"></script>
</body>

</html>