<?php require_once('modules/Init.php') ?>

<?php

$users = $db->getMany(
  USER, null, 
  ['id', 'fullname', 'password', 'username', 'role']
);

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
  <link href="dist/css/settings.css" rel="stylesheet">

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
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Users Page
            </h4>
            <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 p-0">
                  <li class="breadcrumb-item"><a href="index.php" class="text-muted">Home</a></li>
                  <li class="breadcrumb-item text-muted active" aria-current="page">Users</li>
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
                      <?php unset($_SESSION['MESSAGE']) ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  <?php endif; ?>
                  <div class="col-sm-8">
                    <h4 class="card-title">Users List</h4>
                  </div>
                  <div class="col-sm-4">
                    <button class="btn btn-success pull-right" type="button" id="addnew">Add new user</button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table products-list">
                    <thead class="bg-primary text-white">
                      <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th class="text-center">Permissions</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (!empty($users) && count($users) > 0) {
                        foreach ($users as $user) {
                          echo "<tr id=" . $user->id . " class='tr' data-id=" . $user->id . ">";
                          echo "<td class='column1'> <i class='icon-plus'></i> </td>";
                          echo "<td class='column2' data-target='fullname'>" . ($user->fullname) . "</td>";
                          echo "<td class='column4' data-target='username'>" . ($user->username) . "</td>";
                          echo "<td class='column3' data-target='password'>" . ($user->password) . "</td>";
                          echo "<td class='column5 text-center' data-target='access_role'><i data-toggle='tooltip' data-title='Show Roles' class='fa fa-eye'></i></td>";
                          echo "<td class='column6 actions'>";
                          echo "<a class='edit' title='Edit' data-toggle='tooltip' data-role='update' data-id=" . ($user->id) . "><i class='fas fa-pencil-alt'></i></a>";
                          echo "<a class='delete' title='Delete' data-toggle='tooltip' data-role='delete' data-id=" . ($user->id) . "><i class='fas fa-trash'></i></a>";
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
              <strong>Add New User</strong>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form name="dataform" method="POST" action="" class="right-side">
                <div class="d-flex flex-column w-100 mt-3">
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong>Full Name</strong>
                    <input required class="rounded p-1 form-control" type='text' name='fullname' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong>User Name</strong>
                    <input required class="rounded p-1 form-control" type='text' name='username' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong>Email</strong>
                    <input required class="rounded p-1 form-control" type='email' name='email' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong>Password</strong>
                    <input type="password" required class="rounded p-1 form-control" name='password' autocomplete="off">
                  </div>

                  <!-- START -->
                  <div class="form-control bg-transparent w-100 border-0 mb-7">
                    <strong>Permissions</strong>
                    <div class="permi">

                      <div class="row">

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Product Page</label>
                            <input type="checkbox" name="products_page">
                            <span></span>
                          </label>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Calculator</label>
                            <input type="checkbox" name="calculator_page">
                            <span></span>
                          </label>
                        </div>

                      </div>

                      <div class="row">

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Reports Page</label>
                            <input type="checkbox" name="reports_page">
                            <span></span>
                          </label>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Fees Page</label>
                            <input type="checkbox" name="fees_page">
                            <span></span>
                          </label>
                        </div>

                      </div>

                      <div class="row">

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Settings Page</label>
                            <input type="checkbox" name="settings_page">
                            <span></span>
                          </label>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Dashboard</label>
                            <input type="checkbox" name="index_page">
                            <span></span>
                          </label>
                        </div>

                      </div>

                    </div>
                  </div>


                  <!-- END PERMISSIONS -->

                </div>
                <div class="modal-footer">
                  <input class="btn btn-success w-100 rounded-0 pull-right" type="submit" name="register_user" value="Save User">
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
              <strong>Update User</strong>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form name="dataform" method="POST" action="" class="right-side">
                <div class="d-flex flex-column w-100 mt-3">
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong>Full Name</strong>
                    <input required class="rounded p-1 form-control" type='text' id="fullname" name='fullname' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong>Username</strong>
                    <input required disabled class="rounded p-1 form-control" type='text' id="username" name='username' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong>Email</strong>
                    <input required class="rounded p-1 form-control" type='email' id="email" name='email' autocomplete="off">
                  </div>
                  <div class="form-control bg-transparent w-100 border-0 mb-5">
                    <strong>Password</strong>
                    <input type="password" required class="rounded p-1 form-control" id="password" name='password' autocomplete="off">
                  </div>

                  <input type="hidden" id="userId" name="user_id" value="">

                  <div class="form-control bg-transparent w-100 border-0 mb-7 access-roles">
                    <strong>Permissions</strong>
                    <div class="permi">

                      <div class="row">

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Product Page</label>
                            <input type="checkbox" id="products_page" name="products_page">
                            <span></span>
                          </label>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Calculator</label>
                            <input type="checkbox" id="calculator_page" name="calculator_page">
                            <span></span>
                          </label>
                        </div>

                      </div>

                      <div class="row">

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Reports Page</label>
                            <input type="checkbox" id="reports_page" name="reports_page">
                            <span></span>
                          </label>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Fees Page</label>
                            <input type="checkbox" id="fees_page" name="fees_page">
                            <span></span>
                          </label>
                        </div>

                      </div>

                      <div class="row">

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Settings Page</label>
                            <input type="checkbox" id="settings_page" name="settings_page">
                            <span></span>
                          </label>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center align-items-start">
                          <label class="switch">
                            <label class="toglab">Dashboard</label>
                            <input type="checkbox" id="index_page" name="index_page">
                            <span></span>
                          </label>
                        </div>

                      </div>

                    </div>
                  </div>

                </div>



                <div class="modal-footer">
                  <input type="submit" class="btn btn-success w-100 rounded-0 pull-right" name="update_user" value="Update User">
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
              Are you sure you want to delete this User ?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" id="delete" class="btn btn-primary">Delete</button>
              <input type="hidden" value="" id="userId">
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
  <script src="dist/js/settings.js"></script>
</body>

</html>