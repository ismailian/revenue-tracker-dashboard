<?php $root = $_SERVER['DOCUMENT_ROOT'] . "/"; ?>
<?php require_once($root . 'modules/Init.php'); ?>

<?php
############# [ HANDLE POST REQUESTS ] ##############
// >>>> PUT REQUESTS:
if (isset($_POST['addProduct'])) {
  $name = $_POST['Productname'];
  $fournisseur =  $_POST['Fournisseur'];
  $saleprice = $_POST["Saleprice"];
  $costprice = $_POST["Costprice"];

  $result = $db->put(PRODUCTS, [
    'product_name' => $name,
    'fournisseur'  => $fournisseur,
    'sale_price'   => $saleprice,
    'cost_price'   => $costprice,
  ]);
  $_SESSION['MESSAGE'] = ($result) ? "Product Was Saved Successfully." : "Product Could Not Be Saved!";
  header("Location: ${BASE_URL}products.php");
}

if (isset($_POST['saveProduct'])) {

  $date           =  $_POST['date'];
  $productId      =  $_POST['productName'];
  $allOrders      =  $_POST['allOrders'];
  $totalOrder     =  $_POST['totalOrders'];
  $totalDelivered =  $_POST['totalDelivered'];
  $ads            =  $_POST['ads'];

  // parse date:
  $date = new DateTime($date);
  $date = $date->format('Y-m-d');

  $res = $db->getOne(DATES, ['date' => $date], ['id']);
  $slp = $db->getOne(PRODUCTS, ['id' => $productId], ['product_name', 'sale_price']);

  // fees:
  $confirm_fee = $db->getOne(FEES, ['slug' => 'confirmation_fee'], ['fee_price'])->fee_price;
  $delivery_fee = $db->getOne(FEES, ['slug' => 'delivery_fee'], ['fee_price'])->fee_price;


  if ($res) { // date exists

    $q01 = $db->put(CALCULATOR, [
      'date_id' => $res->id,
      'product_id' => $productId,
      'all_orders' => $allOrders,
      'total_products' => $totalOrder,
      'total_delivered' => $totalDelivered,
      'sale_price' => $slp->sale_price,
      'ads' => $ads,
      'delivery_fee' => $delivery_fee,
      'confirmation_fee' => $confirm_fee,
    ]);

    $q02 = $db->patch(PRODUCTS, ['id' => $productId], [
      'all_orders' => $allOrders,
      'total_products' => $totalOrder,
      'total_delivered' => $totalDelivered,
      'ads' => $ads,
    ]);

    $db->put(TMP, [
      'product_id' => $productId,
      'date' => $date,
      'product_name' => $slp->product_name,
      'orders' => $allOrders,
      'confirmed' => $totalOrder,
      'delivered' => $totalDelivered,
      'ads' => $ads,
      'seen' => 0,
    ]);

    $_SESSION['MESSAGE'] = (($q01 && $q02)
      ? "Product Saved Successfully"
      : "Failed To Save Product");

    $_SESSION['tmp_product'] = (object) [
      'id' => 1,
      'name' => $slp->product_name,
      'date' => $date,
      'orders' => $allOrders,
      'confirmed' => $totalOrder,
      'delivered' => $totalDelivered,
      'sale_price' => $slp->sale_price,
      'ads' => $ads,
    ];
  } else { // date does not exist

    $db->put(DATES, ['date' => $date]);
    $newDateId = $db->getOne(DATES, ['date' => $date], ['id'])->id;

    $q01 = $db->put(CALCULATOR, [
      'date_id' => $newDateId,
      'product_id' => $productId,
      'all_orders' => $allOrders,
      'total_products' => $totalOrder,
      'total_delivered' => $totalDelivered,
      'sale_price' => $slp->sale_price,
      'ads' => $ads,
      'delivery_fee' => $delivery_fee,
      'confirmation_fee' => $confirm_fee,
    ]);

    $q02 = $db->patch(PRODUCTS, ['id' => $productId], [
      'all_orders' => $allOrders,
      'total_products' => $totalOrder,
      'total_delivered' => $totalDelivered,
      'ads' => $ads,
    ]);

    $_SESSION['tmp_product'] = (object) [
      'id' => 1,
      'name' => $slp->product_name,
      'date' => $date,
      'orders' => $allOrders,
      'confirmed' => $totalOrder,
      'delivered' => $totalDelivered,
      'sale_price' => $slp->sale_price,
      'ads' => $ads,
    ];

    $db->put(TMP, [
      'product_id' => $productId,
      'date' => $date,
      'product_name' => $slp->product_name,
      'orders' => $allOrders,
      'confirmed' => $totalOrder,
      'delivered' => $totalDelivered,
      'ads' => $ads,
      'seen' => 0,
    ]);
  }

  header("Location: ${BASE_URL}calculator.php");
}



// >> UPDATE REQUESTS:
if (isset($_POST['updateProduct'])) {
  $id = $_POST['id'];
  $pid = $_POST['productId'];
  $Allorders = $_POST['Allorders'];
  $Totalorders = $_POST['Totalorders'];
  $Totaldelivred = $_POST['Totaldelivred'];
  $ads  = $_POST['Ads'];

  $updateOnCalc = $db->patch(CALCULATOR, ['id' => $id], [
    'all_orders' => $Allorders,
    'total_products' => $Totalorders,
    'total_delivered' => $Totaldelivred,
    'ads' => $ads,
  ]);

  $updateOnProduct = $db->patch(PRODUCTS, ['id' => $pid], [
    'all_orders' => $Allorders,
    'total_products' => $Totalorders,
    'total_delivered' => $Totaldelivred,
    'ads' => $ads,
  ]);

  if ($updateOnCalc) {
    if ($updateOnProduct) {
      echo json_encode([
        "status" => "success",
        'all_orders' => $Allorders,
        'totalOrders' => $Totalorders,
        'totalDelivered' => $Totaldelivred,
        'ads' => $ads
      ]);
    } else {
      dd('Item Was Not Updated');
    }
  } else {
    dd('Item Was Not Updated');
  }
}


if (isset($_POST['updateProductStatus'])) {
  $id = $_POST['id'];
  $Productname = $_POST['Productname'];
  $Fournisseur = $_POST['Fournisseur'];
  $Saleprice = $_POST['Saleprice'];
  $Costprice = $_POST['Costprice'];

  $result = $db->patch(PRODUCTS, ['id' => $id], [
    'product_name' => $Productname,
    'fournisseur' => $Fournisseur,
    'sale_price' => $Saleprice,
    'cost_price' => $Costprice,
  ]);
  if ($result) {
    echo json_encode([
      'status' => 'success',
      'message' => 'Product Updated Successfully!',
    ]);
  } else {
    echo json_encode([
      'status' => 'fail',
      'message' => 'Faild To Update Product!',
    ]);
  }
}



// DELETE REQUESTS:
if (isset($_POST['deleteCalculatorProduct'])) {
  $cid = $_POST['id'];
  $date_id = $db->getOne(CALCULATOR, ['id' => $cid], ['date_id'])->date_id;
  $result = $db->revoke(CALCULATOR, ['id' => $cid]);
  $db->getMany(CALCULATOR, ['date_id' => $date_id], ['id']);
  if ($db->count == 0) {
    $db->revoke(DATES, ['id' => $date_id]);
  }
  echo json_encode([
    'status' => 'success',
    'message' => 'Successfully deleted.'
  ]);
}


if (isset($_POST['deleteProduct'])) {
  $id = $_POST['id'];
  $product_lookup = $db->getOne(PRODUCTS, ['id' => $id]);
  if ($db->count === 0) {
    dd('Product Does Not Exist!');
  }
  $result = $db->revoke(PRODUCTS, ['id' => $id]);
  $date_id = $db->getOne(CALCULATOR, ['product_id' => $id], ['date_id']);
  if ($db->count > 0) { // if product exists on calc
    $db->revoke(CALCULATOR, ['product_id' => $id]);
    $db->getMany(CALCULATOR, ['date_id' => $date_id->date_id], ['id']);
    if ($db->count == 0) {
      $db->revoke(DATES, ['id' => $date_id->date_id]);
    }
  }
  echo json_encode([
    'status' => ($result ? 'success' : 'fail'),
    'message' => ($result ? "Product Successfully Deleted." : "Product Deletion Failed!")
  ]);
}


// HANDLE GET REQUESTS:

if (isset($_GET['getProductsByDate'])) {

  $dateId = $_GET['getProductsByDate'];
  if (empty($dateId)) {
    echo ("<strong>No date</strong>");
    exit();
  };
  $dateResults = $db->getMany(CALCULATOR, ['date_id' => $dateId]);

  if ($db->count > 0) {
    $response = "";
    foreach ($dateResults as $dateProduct) {
      $product = $db->getOne(PRODUCTS, ['id' => ($dateProduct)->product_id], ['product_name', 'cost_price']);

      // fees:
      $confirmFee = ($dateProduct)->confirmation_fee;
      $deliveryFee = ($dateProduct)->delivery_fee;

      $allord = ($dateProduct)->all_orders;
      $totaldel = ($dateProduct)->total_delivered;
      $totalord = ($dateProduct)->total_products;
      $adv = ($dateProduct)->Ads;
      $sale = ($dateProduct)->sale_price; // price will remain the same even a new price is added in product table.
      $cost = ($product)->cost_price;

      $is_calculable = true;

      if ((int) $totaldel === 0 || (int) $totalord === 0) {
        $is_calculable = false;
      }

      $delivery_rate        = ($is_calculable) ? round(($totaldel / $totalord) * 100, 2) : (0); // Delivery Rate: 3rd TD
      $delivery_cost        = ($is_calculable) ? ($totaldel * $deliveryFee) : (0);              // Delivery Cost: 4th TD
      $total_delivery_cost  = ($is_calculable) ? ($totaldel * $cost) : (0);                     // Total Delivery Cost: 5th TD
      $ad_cost_per_delivery = ($is_calculable) ? round($adv / $totaldel, 2) : (0);              // Cost per Delivery: 7th TD
      $confirm_fees         = ($is_calculable) ? ($confirmFee * $totaldel) : (0);               // Confirm Fees: 8th TD
      $confirm_rate         = ($is_calculable) ? round(($totalord / $allord) * 100, 2) : (0);   // Confirm rate: 14TD
      $capital_per_product  = ($is_calculable) ? ($sale * $totaldel) : (0);                     // Capital Per Product: 9th TD

      // Expanses Per Product: 10th TD
      // $expenses_per_product = ($is_calculable) ? (($totaldel * $cost) + $adv + ($totaldel * $deliveryFee) + ($confirmFee * $totaldel)) : (0);
      $expenses_per_product = (($totaldel * $cost) + $adv + ($totaldel * $deliveryFee) + ($confirmFee * $totaldel));

      // $net_profit = ($is_calculable) ? (($sale * $totaldel) - $expenses_per_product) : (0); // Net Profit: 11th TD
      $net_profit = (($sale * $totaldel) - $expenses_per_product); // Net Profit: 11th TD

      // Net Profit Percentage Per Product: 12th TD
      $net_profit_percentage = ($is_calculable) ? round(($sale * $totaldel - $expenses_per_product) / $totaldel, 2) : (0);
      $expenses_percentage   = ($is_calculable) ? round($expenses_per_product / $totaldel, 2) : (0); // Expenses Percentage = 13th TD

      $response .= "<tr id='" . ($dateProduct->id) . "'>";
      $response .= "<td>" . ($product)->product_name . "</td>";
      $response .= "<td>" . ($allord) . "</td>";
      $response .= "<td>" . ($totalord) . "</td>";
      $response .= "<td>" . ($totaldel) . "</td>";
      $response .= "<td>" . ("{$confirm_rate}%") . "</td>";
      $response .= "<td>" . ("{$delivery_rate}%") . "</td>";
      $response .= "<td class='tmp_capital blue'>" . ("{$capital_per_product} MAD") . "</td>";
      $response .= "<td>" . "{$total_delivery_cost} MAD" . "</td>";
      $response .= "<td>" . ("{$delivery_cost} MAD") . "</td>";
      $response .= "<td>" . ("{$adv} MAD") . "</td>";
      $response .= "<td>" . ("{$confirm_fees} MAD") . "</td>";
      $response .= "<td class='tmp_expenses red'>" . ("{$expenses_per_product} MAD") . "</td>";
      $response .= "<td class='tmp_net green'>" . ("{$net_profit} MAD") . "</td>";
      $response .= "<td>" . ("{$net_profit_percentage} MAD") . "</td>";
      $response .= "<td>" . ("{$expenses_percentage} MAD") . "</td>";
      $response .= "<td>" . ("{$ad_cost_per_delivery} MAD") . "</td>";
      $response .= "<td>";
      $response .= "<a class='edit mr-1' title='Edit' data-toggle='tooltip' data-role='update' data-row='" . ($dateProduct)->product_id . "' data-id=" . ($dateProduct)->id . "><i class='fas fa-pencil-alt'></i></a>";
      $response .= "<a class='delete ml-1' title='Delete' data-toggle='tooltip' data-role='delete-calc' data-row='" . ($dateProduct)->product_id . "' data-id=" . ($dateProduct)->id . "><i class='fas fa-trash'></i></a>";
      $response .= "</td>";

      $response .= "</tr>";
    }
    echo $response;
  } else {
    echo "<tr class='alert-warning width-100'><td colspan='13' class='full-width text-center'><strong>No Products</strong></td></tr>";
  }
}




if (isset($_GET['getInBetweenDates'])) {
  $from = $_GET['from'];
  $from = DateTime::createFromFormat('m-d-Y', str_replace('/', '-', str_replace('"', "", $from)));
  $from = $from->format('Y-m-d');

  // the (to) date:
  $to = $_GET['to'];
  $to = DateTime::createFromFormat('m-d-Y', str_replace('/', '-', str_replace('"', "", $to)));
  $to = $to->format('Y-m-d');

  $fromId = $db->getOne(DATES, ['date' => $from], ['id']);
  $toId = $db->getOne(DATES, ['date' => $to], ['id']);

  if ($fromId and $toId) {
    $dates = getDatesFromTo($fromId->id, $toId->id);
    if (!empty($dates) && count($dates) > 0) {
      $response = "";
      foreach ($dates as $date) {
        $response .= '<tr id="' . $date->id . '" class="tr" data-id="' . $date->id . '">';
        $response .= "<td data-target='Datetime' id='openup' >" . $date->date . "</td>";
        $response .= "<td data-target='Saleprice'>" . getNetTotalPerDate($date->id) . "</td>";
        $response .= "<td>" . $date->id . "</td>";
        $response .= "</tr>";
      }
      echo $response;
    } else {
      echo "<tr class='text-center alert-warning'><td colspan='3'>No Records</td></tr>";
    }
  } else {
    echo "<tr class='text-center alert-warning'><td colspan='3'>No Records</td></tr>";
  }
}


// add new fee:
if (isset($_POST['addFee'])) {

  $feeTypeId = $db->getOne(FEE_TYPE, ['slug' => trim($_POST['fee_type'])], ['id']);
  // data container:
  $data = [
    'fee_name' => $_POST['fee_name'],
    'fee_price' => $_POST['fee_price'],
    'fee_type_id' => $feeTypeId->id,
    'product_type' => isset($_POST['product_type']) ? $_POST['product_type'] : '1',
    'date_type' => isset($_POST['date_type']) ? $_POST['date_type'] : '1',
    'slug' => trim(mb_strtolower(str_replace(' ', '_', $_POST['fee_name']))),
  ];


  $check = $db->getOne(FEES, ['fee_name' => $_POST['fee_name']]);
  if ($db->count === 0) {
    $newFee = $db->put(FEES, $data);

    if ($newFee) {
      $_SESSION['MESSAGE'] = "Fee has been saved!";
    } else {
      $_SESSION['MESSAGE'] = "Fee Could not be created!";
    }
  } else {
    $_SESSION['MESSAGE'] = "Fee Could not be created!";
  }
}


// add new fee:
if (isset($_POST['updateFee'])) {

  $feeId = $_POST['feeId'];

  // data container:
  $data = [
    'fee_name' => $_POST['fee_name'],
    'fee_price' => $_POST['fee_price'],
  ];

  $check = $db->getOne(FEES, ['fee_name' => $_POST['fee_name']]);

  if ($db->count === 1) {

    $updateFee = $db->patch(FEES, ['id' => $feeId], $data);

    if ($updateFee) {

      $_SESSION['MESSAGE'] = "Fee has been Updated.";
    } else {

      $_SESSION['MESSAGE'] = "Fee Could not be Updated!";
    }
  } else {
    $_SESSION['MESSAGE'] = "Fee Could not be Updated!";
  }
}

// handle fee delete:
if (isset($_POST['deleteFee'])) {
  $feeId = $_POST['feeId'];
  $revoke = $db->revoke(FEES, ['id' => $feeId]);
  if ($revoke) {
    echo json_encode([
      'status' => 'success',
      'message' => 'Fee Successfully Removed.',
    ]);
  } else {
    echo json_encode([
      'status' => 'failed',
      'message' => 'Something Went Wrong!',
    ]);
  }
}


// handle login and registration:
if (isset($_POST['login'])) {

  $username = $_POST['username'];
  $password = $_POST['password'];

  $login = $db->getOne(
    USER, ['username' => $username, 'password' => $password],
    ['id', 'fullname', 'username', 'email', 'role']
  );

  if ($login && $db->count === 1) {
    
    // login was successful, set session:
    $_SESSION['USER'] = $login;
    header("Location: ${BASE_URL}index.php");

  } else {
    $_SESSION['MESSAGE'] = 'Username/Password is Invalid!';
  }
}


// handle registration:
if (isset($_POST['register_user'])) {
  $fullname = $_POST['fullname'];
  $email    = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  $check = $db->getOne(USER, ['username' => $username], ['id']);
  if (!$check) {
    // username exists:
    // Permissions:
    $products_page = isset($_POST['products_page']);
    $calculator_page = isset($_POST['calculator_page']);
    $reports_page = isset($_POST['reports_page']);
    $fees_page = isset($_POST['fees_page']);
    $settings_page = isset($_POST['settings_page']);
    $index_page = isset($_POST['index_page']);


    $register = $db->put(USER, [
      'fullname' => $fullname,
      'email' => $email,
      'username' => $username,
      'password' => ($password),
    ]);

    if ($register) {
      $login = $db->getOne(USER, ['username' => $username, 'password' => ($password)], ['id', 'fullname', 'username', 'email', 'role']);

      if ($login && $db->count === 1) {
        $permissions = $db->put(ACCESS, [
          'user_id' => $login->id,
          'products_page' => $products_page,
          'calculator_page' => $calculator_page,
          'reports_page' => $reports_page,
          'fees_page' => $fees_page,
          'settings_page' => $settings_page,
          'index_page' => $index_page,
        ]);

        $_SESSION['MESSAGE'] = ("User Successfully Registered In.");
      }
    } else {
      $_SESSION['MESSAGE'] = "Registration Failed!";
    }
  } else {
    $_SESSION['MESSAGE'] = "Username Already Exists!";
  }
}


// handle update:
if (isset($_POST['update_user'])) {
  $userId = $_POST['user_id'];
  $fullname = $_POST['fullname'];
  $email    = $_POST['email'];
  $password = $_POST['password'];

  // Permissions:
  $products_page = isset($_POST['products_page']);
  $calculator_page = isset($_POST['calculator_page']);
  $reports_page = isset($_POST['reports_page']);
  $fees_page = isset($_POST['fees_page']);
  $settings_page = isset($_POST['settings_page']);
  $index_page = isset($_POST['index_page']);


  $update = $db->patch(USER, ['id' => $userId], [
    'fullname' => $fullname,
    'email' => $email,
    'password' => ($password),
  ]);

  if ($update) {
    $check = $db->getOne(USER, ['email' => $email, 'password' => ($password)], ['id', 'fullname', 'username', 'email', 'role']);

    if ($check && $db->count === 1) {

      $permissions = $db->patch(ACCESS, ['user_id' => $userId], [
        'user_id' => $check->id,
        'products_page' => $products_page,
        'calculator_page' => $calculator_page,
        'reports_page' => $reports_page,
        'fees_page' => $fees_page,
        'settings_page' => $settings_page,
        'index_page' => $index_page,
      ]);
      $_SESSION['MESSAGE'] = "User Successfully Updated";
      if ($guard->current_session()->username === $check->username) {
        $_SESSION['USER'] = $check;
        $guard->refresh();
      }
    }
  } else {
    $_SESSION['MESSAGE'] = "User Update Failed!";
  }
}

// handle logout:
if (isset($_POST['deleteUser'])) {
  $userId = $_POST['userId'];
  $revoke = $db->revoke(USER, ['id' => $userId]);
  if ($revoke) {
    $db->getOne(ACCESS, ['user_id' => $userId], ['id']);
    if ($db->count === 1) {
      $db->revoke(ACCESS, ['user_id' => $userId]);
    }
    echo json_encode([
      'status' => 'success',
      'message' => 'User Successfully Removed.',
    ]);
  } else {
    echo json_encode([
      'status' => 'failed',
      'message' => 'Something Went Wrong!',
    ]);
  }
}

// handle logout:
if (isset($_POST['logout'])) {
  if (isset($_SESSION['USER'])) {
    unset($_SESSION['USER']);
    header("Location: ${BASE_URL}index.php");
  }
}


// Provide data for charts:
if (isset($_GET['fetch_all_orders'])) {

  // check if authorized:
  if (!$guard->auth()) {
    dd("Not Authorized!");
  }

  $dates = [];
  $purchases = [];
  $specific_dates = [];
  $filled = false;

  $keyword = $_GET['fetch_all_orders'];
  if (!empty($keyword) && $keyword !== "all") {

    if (function_exists($keyword)) {
      switch ($keyword) {
        case 'today':
          $specific_dates = today();
          break;

        case 'yesterday':
          $specific_dates = yesterday();
          break;

        case 'last_week':
          $specific_dates = last_week();
          break;

        case 'this_month':
          $specific_dates = this_month();
          break;

        case 'last_month':
          $specific_dates = last_month();
          break;

        case 'last_30_days':
          $specific_dates = last_30_days();
          break;

        case 'this_year':
          $specific_dates = this_year();
          break;

        case 'range':
          $specific_dates = dateRange($_GET['from'], $_GET['to']);
          break;
      }
    }

    if (!empty($specific_dates)) {
      foreach ($specific_dates as $dateId) {
        $orders = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['date_id']);
        foreach ($orders as $order) {
          array_push($purchases, $order);
        }
      }
    }

    // die(json_encode($purchases));

  } else {
    $purchases = $db->getMany(CALCULATOR, null, ['date_id']);
    $dates_parsed = [];
    foreach ($purchases as $purchaseDate) {
      $date = $db->getOne(DATES, ['id' => $purchaseDate->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");
      if (array_search($date, $dates_parsed) === False) {
        array_push($dates, [
          "date" => $date,
          "date_orders" => getTotalOrders($purchaseDate->date_id),
        ]);
        array_push($dates_parsed, $date);
      }
    }
    $filled = true;
  }

  // loop through purchases:
  if (!$filled) {
    $dates_parsed = [];
    foreach ($purchases as $purchaseDate) {
      $date = $db->getOne(DATES, ['id' => $purchaseDate->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");
      if (array_search($date, $dates_parsed) === False) {
        array_push($dates, [
          "date" => $date,
          "date_orders" => getTotalOrders($purchaseDate->date_id),
        ]);
        array_push($dates_parsed, $date);
      }
    }
  }

  array_multisort($dates, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "dates" => $dates,
  ]);
}


// Provide Confirmation Rates charts:
if (isset($_GET['fetch_confirmation_rates'])) {

  // check if authorized:
  if (!$guard->auth()) {
    dd("Not Authorized!");
  }

  $dates = [];
  $purchases = [];
  $specific_dates = [];
  $filled = false;

  $keyword = $_GET['fetch_confirmation_rates'];
  if (!empty($keyword) && $keyword !== "all") {

    if (function_exists($keyword)) {
      switch ($keyword) {
        case 'today':
          $specific_dates = today();
          break;

        case 'yesterday':
          $specific_dates = yesterday();
          break;

        case 'last_week':
          $specific_dates = last_week();
          break;

        case 'this_month':
          $specific_dates = this_month();
          break;

        case 'last_month':
          $specific_dates = last_month();
          break;

        case 'last_30_days':
          $specific_dates = last_30_days();
          break;

        case 'this_year':
          $specific_dates = this_year();
          break;

        case 'range':
          $specific_dates = dateRange($_GET['from'], $_GET['to']);
          break;
      }
    }

    if (!empty($specific_dates)) {
      foreach ($specific_dates as $dateId) {
        $orders = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['date_id']);
        foreach ($orders as $order) {
          array_push($purchases, $order);
        }
      }
    }

    // die(json_encode($purchases));

  } else {
    $purchases = $db->getMany(CALCULATOR, null, ['date_id']);
    $dates_parsed = [];
    foreach ($purchases as $purchaseDate) {
      $date = $db->getOne(DATES, ['id' => $purchaseDate->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");
      if (array_search($date, $dates_parsed) === False) {
        $data = getConfirmationRate($purchaseDate->date_id);
        array_push($dates, [
          "date" => $date,
          "confirmed" => $data['confirmed'],
          "rate" => ($data['rate']),
        ]);
        array_push($dates_parsed, $date);
      }
    }
    $filled = true;
  }

  // loop through purchases:
  if (!$filled) {
    $dates_parsed = [];
    foreach ($purchases as $purchaseDate) {
      $date = $db->getOne(DATES, ['id' => $purchaseDate->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");
      if (array_search($date, $dates_parsed) === False) {
        $data = getConfirmationRate($purchaseDate->date_id);
        array_push($dates, [
          "date" => $date,
          "confirmed" => $data['confirmed'],
          "rate" => ($data['rate']),
        ]);
        array_push($dates_parsed, $date);
      }
    }
  }

  array_multisort($dates, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "dates" => $dates,
  ]);
}


// Provide Confirmation Rates charts:
if (isset($_GET['fetch_delivery_rates'])) {

  // check if authorized:
  if (!$guard->auth()) {
    dd("Not Authorized!");
  }

  $dates = [];
  $purchases = [];
  $specific_dates = [];
  $filled = false;

  $keyword = $_GET['fetch_delivery_rates'];
  if (!empty($keyword) && $keyword !== "all") {

    if (function_exists($keyword)) {
      switch ($keyword) {
        case 'today':
          $specific_dates = today();
          break;

        case 'yesterday':
          $specific_dates = yesterday();
          break;

        case 'last_week':
          $specific_dates = last_week();
          break;

        case 'this_month':
          $specific_dates = this_month();
          break;

        case 'last_month':
          $specific_dates = last_month();
          break;

        case 'last_30_days':
          $specific_dates = last_30_days();
          break;

        case 'this_year':
          $specific_dates = this_year();
          break;

        case 'range':
          $specific_dates = dateRange($_GET['from'], $_GET['to']);
          break;
      }
    }

    if (!empty($specific_dates)) {
      foreach ($specific_dates as $dateId) {
        $orders = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['date_id']);
        foreach ($orders as $order) {
          array_push($purchases, $order);
        }
      }
    }

    // die(json_encode($purchases));

  } else {
    $purchases = $db->getMany(CALCULATOR, null, ['date_id']);
    $dates_parsed = [];
    foreach ($purchases as $purchaseDate) {
      $date = $db->getOne(DATES, ['id' => $purchaseDate->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");
      if (array_search($date, $dates_parsed) === False) {
        $data = getDeliveryRate($purchaseDate->date_id);
        array_push($dates, [
          "date" => $date,
          "delivered" => $data['delivered'],
          "rate" => ($data['rate']),
        ]);
        array_push($dates_parsed, $date);
      }
    }
    $filled = true;
  }

  // loop through purchases:
  if (!$filled) {
    $dates_parsed = [];
    foreach ($purchases as $purchaseDate) {
      $date = $db->getOne(DATES, ['id' => $purchaseDate->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");
      if (array_search($date, $dates_parsed) === False) {
        $data = getDeliveryRate($purchaseDate->date_id);
        array_push($dates, [
          "date" => $date,
          "delivered" => $data['delivered'],
          "rate" => ($data['rate']),
        ]);
        array_push($dates_parsed, $date);
      }
    }
  }

  array_multisort($dates, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "dates" => $dates,
  ]);
}



// Provide Confirmation Rates charts:
if (isset($_GET['fetch_net_profit'])) {

  // check if authorized:
  if (!$guard->auth()) {
    dd("Not Authorized!");
  }

  $dates = [];
  $purchases = [];
  $specific_dates = [];
  $filled = false;

  $keyword = $_GET['fetch_net_profit'];
  if (!empty($keyword) && $keyword !== "all") {

    if (function_exists($keyword)) {
      switch ($keyword) {
        case 'today':
          $specific_dates = today();
          $profit = getTotalNetProfit();
          break;

        case 'yesterday':
          $specific_dates = yesterday();
          break;

        case 'last_week':
          $specific_dates = last_week();
          break;

        case 'this_month':
          $specific_dates = this_month();
          break;

        case 'last_month':
          $specific_dates = last_month();
          break;

        case 'last_30_days':
          $specific_dates = last_30_days();
          break;

        case 'this_year':
          $specific_dates = this_year();
          break;

        case 'range':
          $specific_dates = dateRange($_GET['from'], $_GET['to']);
          break;
      }
    }

    if (!empty($specific_dates)) {
      foreach ($specific_dates as $dateId) {
        $orders = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['date_id']);
        foreach ($orders as $order) {
          array_push($purchases, $order);
        }
      }
    }

    // die(json_encode($purchases));

  } else {
    $purchases = $db->getMany(CALCULATOR, null, ['date_id']);
    $dates_parsed = [];
    foreach ($purchases as $purchaseDate) {
      $date = $db->getOne(DATES, ['id' => $purchaseDate->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");
      if (array_search($date, $dates_parsed) === False) {
        array_push($dates, [
          "date" => $date,
          "profit" => getNetTotalPerDate($purchaseDate->date_id),
        ]);
        array_push($dates_parsed, $date);
      }
    }
    $filled = true;
  }

  // loop through purchases:
  if (!$filled) {
    $dates_parsed = [];
    foreach ($purchases as $purchaseDate) {
      $date = $db->getOne(DATES, ['id' => $purchaseDate->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");
      if (array_search($date, $dates_parsed) === False) {
        array_push($dates, [
          "date" => $date,
          "profit" => getTotalNetProfit($purchaseDate->date_id),
        ]);
        array_push($dates_parsed, $date);
      }
    }
    // exit();
  }

  array_multisort($dates, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "dates" => $dates,
  ]);
}



// get Top Five Products:
if (isset($_GET['fetch_top_five'])) {

  $products_capital = [];


  // check if time regulator is there!
  if (isset($_GET['t'])) {

    $date_collection = [];

    $dr = $_GET['t'];

    if ($dr === "range") {

      // get range:
      $from = isset($_GET['from']) ? ($_GET['from']) : '';
      $to   = isset($_GET['to']) ? ($_GET['to']) : '';

      $date_collection = dateRange($from, $to); // dates:

    } else {

      if ($dr === "today") $date_collection        = today();
      if ($dr === "yesterday")    $date_collection   = yesterday();
      if ($dr === "last_week")    $date_collection   = last_week();
      if ($dr === "this_month")   $date_collection   = this_month();
      if ($dr === "last_month")   $date_collection   = last_month();
      if ($dr === "last_30_days") $date_collection   =  last_30_days();
      if ($dr === "this_year")    $date_collection   = this_year();
    }


    if (!empty($date_collection)) {

      foreach ($date_collection as $date) {

        $regulated = $db->getMany(CALCULATOR, ['date_id' => ($date)->id], ['product_id', 'sale_price', 'total_delivered']);
        if (!is_null($regulated) && $db->count > 0) {
          foreach ($regulated as $product) {
            $pid = ($product)->product_id;

            $totaldel = ($product)->total_delivered;
            $sale = ($product)->sale_price;

            $result = ($sale * $totaldel);

            $name = $db->getOne(PRODUCTS, ['id' => $pid], ['product_name'])->product_name;
            $name = substr($name, 0, 20);

            array_push($products_capital, [
              "name" => $name,
              "capital" => $result,
            ]);
          }
        } //

      }
    }
  } else {

    $all = $db->getMany(CALCULATOR, null, ['product_id', 'sale_price', 'total_delivered']);
    if (!is_null($all) && $db->count > 0) {
      foreach ($all as $product) {
        $pid = ($product)->product_id;

        $totaldel = ($product)->total_delivered;
        $sale = ($product)->sale_price;

        $result = ($sale * $totaldel);

        $name = $db->getOne(PRODUCTS, ['id' => $pid], ['product_name'])->product_name;
        $name = substr($name, 0, 20);

        array_push($products_capital, [
          "name" => $name,
          "capital" => $result,
        ]);
      }
    } //

  }

  arsort($products_capital, SORT_NUMERIC);
  $top_five = array_slice($products_capital, 0, 5);

  echo json_encode($top_five);
}

// get Top Five Profit:
if (isset($_GET['fetch_top_five_profit'])) {

  $products_profit = [];


  // check if time regulator is there!
  if (isset($_GET['t'])) {

    $date_collection = [];

    $dr = $_GET['t'];

    if ($dr === "range") {

      // get range:
      $from = isset($_GET['from']) ? ($_GET['from']) : '';
      $to   = isset($_GET['to']) ? ($_GET['to']) : '';

      $date_collection = dateRange($from, $to); // dates:

    } else {

      if ($dr === "today") $date_collection = today();
      if ($dr === "yesterday") $date_collection = yesterday();
      if ($dr === "last_week") $date_collection = last_week();
      if ($dr === "this_month") $date_collection = this_month();
      if ($dr === "last_month") $date_collection = last_month();
      if ($dr === "last_30_days") $date_collection = last_30_days();
      if ($dr === "this_year") $date_collection = this_year();
    }

    if (!empty($date_collection)) {

      foreach ($date_collection as $date) {

        $regulated = $db->getMany(CALCULATOR, ['date_id' => ($date)->id]);
        if (!is_null($regulated) && $db->count > 0) {
          foreach ($regulated as $product) {
            $current_product = $db->getOne(PRODUCTS, ['id' => $product->product_id], ['product_name', 'cost_price']);
            $totaldel = ($product)->total_delivered;
            $sale = ($product)->sale_price;
            $adv = ($product)->Ads;
            $cost = ($current_product)->cost_price;

            // fees
            $confirm_fee = ($product)->confirmation_fee;
            $delivery_fee = ($product)->delivery_fee;

            $result = ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));

            $product_name = substr(($current_product)->product_name, 0, 20);

            array_push($products_profit, [
              "name" => $product_name,
              "profit" => $result,
            ]);
          }
        } //

      }
    }
  } else {


    $all = $db->getMany(CALCULATOR);

    if (!is_null($all) && $db->count > 0) {

      foreach ($all as $product) {

        $current_product = $db->getOne(PRODUCTS, ['id' => $product->product_id], ['product_name', 'cost_price']);
        $totaldel = ($product)->total_delivered;
        $sale = ($product)->sale_price;
        $adv = ($product)->Ads;
        $cost = ($current_product)->cost_price;

        // fees
        $confirm_fee = ($product)->confirmation_fee;
        $delivery_fee = ($product)->delivery_fee;

        $result = ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));

        $product_name = substr(($current_product)->product_name, 0, 20);

        array_push($products_profit, [
          "name" => $product_name,
          "profit" => $result,
        ]);
      }
    } //

  }

  arsort($products_profit, SORT_NUMERIC);
  $top_five = array_slice($products_profit, 0, 5);

  echo json_encode($top_five);
}



// Fetch product info:
if (isset($_GET['fetchProductInfo']) && isset($_GET['pid'])) {

  // get the pid:
  $pid = filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT);

  $product = $db->getOne(CALCULATOR, ['id' => $pid]);
  $self = $db->getOne(PRODUCTS, ['id' => ($product)->product_id], ['product_name']);

  if (!empty($product)) {
    echo json_encode([
      "id" => ($product)->id,
      "name" => ($self)->product_name,
      "orders" => ($product)->all_orders,
      "delivered" => ($product)->total_delivered,
      "confirmed" => ($product)->total_products,
      "ads" => ($product)->Ads,
    ]);
  } else {
    dd("Product does not exist!");
  }
}


// get User Access Roles:
if (isset($_GET['getUserAccessRoles'])) {

  if (!$guard->auth()) {
    dd("You Are Not Authorized!");
  }

  if (isset($_GET['user_id'])) {

    $userId = $_GET['user_id'];

    $access = $db->getOne(ACCESS, ['user_id' => $userId]);

    if ($access) {

      $roles = [
        'index'      => False,
        'settings'   => False,
        'products'   => False,
        'calculator' => False,
        'reports'    => False,
        'fees'       => False,
      ];

      ($access->index_page)      ? ($roles['index']      = True) : null;
      ($access->settings_page)   ? ($roles['settings']   = True) : null;
      ($access->products_page)   ? ($roles['products']   = True) : null;
      ($access->calculator_page) ? ($roles['calculator'] = True) : null;
      ($access->reports_page)    ? ($roles['reports']    = True) : null;
      ($access->fees_page)       ? ($roles['fees']       = True) : null;

      echo json_encode([
        "status" => "success",
        "roles" => $roles,
      ]);
    }
  }
}


if (isset($_POST['deleteReport'])) {

  if (!$guard->auth()) {
    dd("not authorized!");
  }

  $rid = $_POST['id'];

  // check if date exists by this date:
  $check = $db->getOne(DATES, ['id' => $rid]);
  if ($check) {

    // delete date
    $db->revoke(DATES, ['id' => $rid]);

    // remove relevent sales:
    $db->revoke(CALCULATOR, ['date_id' => $rid]);

    echo json_encode([
      "status" => "success",
      "message" => "report has been removed!",
    ]);
  }
}


if (isset($_POST['markAsSeen'])) {
  if (!$guard->auth()) {
    dd("not authorized!");
  }

  $rid = $_POST['id'];

  $check = $db->getOne(TMP, ['id' => $rid]);
  if ($check) {

    // delete date
    $db->revoke(TMP, ['id' => $rid]);

    // remove relevent sales:

    echo json_encode([
      "status" => "success",
      "message" => "report has been dismissed.",
    ]);
  } else {
    dd("object not found!");
  }
}

if (isset($_POST['dismiss_all'])) {
  if (!$guard->auth()) {
    dd("not authorized!");
  }

  // delete date
  $db->empty(TMP);

  header("Location: ${BASE_URL}calculator.php");
}
