<?php $root = $_SERVER['DOCUMENT_ROOT'] . "/"; ?>
<?php require_once($root . 'modules/Init.php'); ?>

<?php

// Get Product Totall Orders:
function getProductTotalOrders($id, String $time = null, String $from = null, String $to = null)
{
  // globalized the DB instance:
  global $db;

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $orders = 0;

  // collect all instances of it on calculator!
  $instances = [];
  $dates = [];

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['all_orders']);
  } else {

    if ($time === "today")        $dates = today();           // today
    if ($time === "yesterday")    $dates = yesterday();       // yesterday
    if ($time === "last_week")    $dates = last_week();       // last_week
    if ($time === "this_month")   $dates = this_month();      // this_month
    if ($time === "last_month")   $dates = last_month();      // last_month
    if ($time === "last_30_days") $dates = last_30_days();    // last_30_days
    if ($time === "this_year")    $dates = this_year();       // this_year
    if ($time === "range")        $dates = dateRange($from, $to); // range

    if (!empty($dates)) {
      foreach ($dates as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['all_orders']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  if (!empty($instances)) {
    foreach ($instances as $instance) {
      $orders += $instance->all_orders;
    }
  }

  return $orders;
}



// Get Product Total Capital:
function getProductTotalCapital($id, String $time = null, string $from = null, String $to = null)
{
  // globalized the DB instance:
  global $db;

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $capital = 0;

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dates = [];

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['sale_price', 'total_delivered']);
  } else {

    if ($time === "today")        $dates = today();           // today
    if ($time === "yesterday")    $dates = yesterday();       // yesterday
    if ($time === "last_week")    $dates = last_week();       // last_week
    if ($time === "this_month")   $dates = this_month();      // this_month
    if ($time === "last_month")   $dates = last_month();      // last_month
    if ($time === "last_30_days") $dates = last_30_days();    // last_30_days
    if ($time === "this_year")    $dates = this_year();       // this_year
    if ($time === "range")        $dates = dateRange($from, $to); // range

    if ($dates) {
      foreach ($dates as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['sale_price', 'total_delivered']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }


  // loop through product instances:
  foreach ($instances as $instance) {

    $totaldel = (int) ($instance)->total_delivered;
    $sale = (int) ($instance)->sale_price;

    $result = ($sale * $totaldel);
    $capital += $result;
  }

  return $capital;
}


function getProductTotalExpenses($id)
{
  // globalized the DB instance:
  global $db;

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $expenses = 0;

  // Grab Sale Price from calculator and everything else from product:
  $instances = $db->getMany(CALCULATOR, ['product_id' => $pid]);
  $product = $db->getOne(PRODUCTS, ['id' => $pid], ['cost_price']);


  // cose_price + delivery_fee + confirmation_fee + ads
  foreach ($instances as $instance) {

    // fees:
    $delivery_fee = ($instance)->delivery_fee;
    $confirm_fee = ($instance)->confirmation_fee;

    $expense = 0;
    $expense += (float) ($product)->cost_price * (int) ($instance)->total_delivered;
    $expense += (float) ($instance)->Ads;
    $expense += (float) ($confirm_fee) * (int) ($instance)->total_delivered;
    $expense += (float) ($delivery_fee) * (int) ($instance)->total_delivered;

    // append the expenses:
    $expenses += $expense;
  }
  return $expenses;
}

// Get Product Total Profit:
function getProductTotalProfit($id, String $time = null, String $from = null, String $to = null)
{
  // globalized the DB instance:
  global $db;

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $profit = 0;

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dates = [];

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid]);
  } else {

    if ($time === "today")        $dates = today();           // today
    if ($time === "yesterday")    $dates = yesterday();       // yesterday
    if ($time === "last_week")    $dates = last_week();       // last_week
    if ($time === "this_month")   $dates = this_month();      // this_month
    if ($time === "last_month")   $dates = last_month();      // last_month
    if ($time === "last_30_days") $dates = last_30_days();    // last_30_days
    if ($time === "this_year")    $dates = this_year();       // this_year
    if ($time === "range")        $dates = dateRange($from, $to); // range

    if ($dates) {
      foreach ($dates as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id]);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  // loop through product instances:
  foreach ($instances as $instance) {

    $current_product = $db->getOne(PRODUCTS, ['id' => ($instance)->product_id], ['cost_price']);
    $totaldel = (float) ($instance)->total_delivered;
    $sale = (float) ($instance)->sale_price;
    $adv = (float) ($instance)->Ads;
    $cost = (float) ($current_product)->cost_price;

    // fees:
    $delivery_fee = ($instance)->delivery_fee;
    $confirm_fee = ($instance)->confirmation_fee;

    $profit += ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));
  }

  return $profit;
}



// Get Product Confirmation Rate:
function getProductConfirmationRate($id, String $time = null, String $from = null, String $to = null)
{
  // globalized the DB instance:
  global $db;

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $orders = 0;
  $confirmed = 0;

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dates = [];

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['all_orders', 'total_products']);
  } else {

    if ($time === "today")        $dates = today();           // today
    if ($time === "yesterday")    $dates = yesterday();       // yesterday
    if ($time === "last_week")    $dates = last_week();       // last_week
    if ($time === "this_month")   $dates = this_month();      // this_month
    if ($time === "last_month")   $dates = last_month();      // last_month
    if ($time === "last_30_days") $dates = last_30_days();    // last_30_days
    if ($time === "this_year")    $dates = this_year();       // this_year
    if ($time === "range")        $dates = dateRange($from, $to); // range

    if ($dates) {
      foreach ($dates as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['all_orders', 'total_products']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  // loop through product instances:
  foreach ($instances as $instance) {

    $confirmed += $instance->total_products;
    $orders += $instance->all_orders;
  }

  $percentage = ($orders > 0 && $confirmed > 0) ? (($confirmed * 100) / $orders) : 0;
  return round($percentage, 2) . "%";
}



// Get Product Total Confirmed Orders:
function getProductConfirmedOrders($id, string $time = null, string $from = null, string $to = null)
{
  // globalized the DB instance:
  global $db;

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $confirmed = 0;

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dates = [];

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['total_products']);
  } else {

    if ($time === "today")        $dates = today();           // today
    if ($time === "yesterday")    $dates = yesterday();       // yesterday
    if ($time === "last_week")    $dates = last_week();       // last_week
    if ($time === "this_month")   $dates = this_month();      // this_month
    if ($time === "last_month")   $dates = last_month();      // last_month
    if ($time === "last_30_days") $dates = last_30_days();    // last_30_days
    if ($time === "this_year")    $dates = this_year();       // this_year
    if ($time === "range")        $dates = dateRange($from, $to); // range

    if ($dates) {
      foreach ($dates as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['total_products']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  // loop through product instances:
  foreach ($instances as $instance) {

    $confirmed += (int) ($instance)->total_products;
  }

  return $confirmed;
}


// Get Product delivery Rate:
function getProductDeliveryRate($id, string $time = null, string $from = null, string $to = null)
{
  // globalized the DB instance:
  global $db;

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $orders = 0;
  $delivered = 0;

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dates = [];

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['total_products', 'total_delivered']);
  } else {

    if ($time === "today")        $dates = today();           // today
    if ($time === "yesterday")    $dates = yesterday();       // yesterday
    if ($time === "last_week")    $dates = last_week();       // last_week
    if ($time === "this_month")   $dates = this_month();      // this_month
    if ($time === "last_month")   $dates = last_month();      // last_month
    if ($time === "last_30_days") $dates = last_30_days();    // last_30_days
    if ($time === "this_year")    $dates = this_year();       // this_year
    if ($time === "range")        $dates = dateRange($from, $to); // range

    if ($dates) {
      foreach ($dates as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['total_products', 'total_delivered']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }


  // loop through product instances:
  foreach ($instances as $instance) {

    $delivered += $instance->total_delivered;
    $orders += $instance->total_products;
  }

  $percentage = ($orders > 0 && $delivered > 0) ? ($delivered * 100) / $orders : 0;
  return round($percentage, 2) . "%";
}


// Get Product Delivery Orders:
function getProductDeliveredOrders($id, string $time = null, string $from = null, string $to = null)
{
  // globalized the DB instance:
  global $db;

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $delivered = 0;

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dates = [];

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['total_delivered']);
  } else {

    if ($time === "today")        $dates = today();           // today
    if ($time === "yesterday")    $dates = yesterday();       // yesterday
    if ($time === "last_week")    $dates = last_week();       // last_week
    if ($time === "this_month")   $dates = this_month();      // this_month
    if ($time === "last_month")   $dates = last_month();      // last_month
    if ($time === "last_30_days") $dates = last_30_days();    // last_30_days
    if ($time === "this_year")    $dates = this_year();       // this_year
    if ($time === "range")        $dates = dateRange($from, $to); // range

    if ($dates) {
      foreach ($dates as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['total_delivered']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  // loop through product instances:
  foreach ($instances as $instance) {

    $delivered += (int) ($instance)->total_delivered;
  }

  return $delivered;
}


// Get product Average Net Profit:
function getProductAverageNetprofit($id, string $time = null, string $from = null, string $to = null)
{

  $pid = filter_var($id, FILTER_VALIDATE_INT);
  $capital = 0;
  $expenses = 0;
  $delivered = 0;

  // Get Product capital:
  if ($time === null) {

    $capital = getProductTotalCapital($pid);
    $expenses = getProductTotalExpenses($pid);
    $delivered = getProductDeliveredOrders($pid);
  } else {

    $capital = getProductTotalCapital($pid, $time, $from, $to);
    $expenses = getProductTotalExpenses($pid, $time, $from, $to);
    $delivered = getProductDeliveredOrders($pid, $time, $from, $to);
  }

  // final result:
  $result = ($delivered > 0) ? ((float) $capital - (float) $expenses) / (int) $delivered : 0;
  // $result = ($capital - $expenses) / (int) $delivered;
  return $result;
}



// Product's Orders:
if (isset($_GET['fetch_product_orders']) && isset($_GET['pid'])) {

  $pid = filter_var($_GET['pid'], FILTER_VALIDATE_INT);

  // data to return:
  $dates = [];
  $parsed_dates = [];

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dateRecords = [];
  $time = isset($_GET['t']) ? ($_GET['t']) : null;

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['all_orders', 'date_id']);
  } else {

    if ($time === "today")        $dateRecords = today();           // today
    if ($time === "yesterday")    $dateRecords = yesterday();       // yesterday
    if ($time === "last_week")    $dateRecords = last_week();       // last_week
    if ($time === "this_month")   $dateRecords = this_month();      // this_month
    if ($time === "last_month")   $dateRecords = last_month();      // last_month
    if ($time === "last_30_days") $dateRecords = last_30_days();    // last_30_days
    if ($time === "this_year")    $dateRecords = this_year();       // this_year
    if ($time === "range")        $dateRecords = dateRange($_GET['from'], $_GET['to']); // range

    if ($dateRecords) {
      foreach ($dateRecords as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['all_orders', 'date_id']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  if (!empty($instances)) {

    foreach ($instances as $instance) {
      $date = $db->getOne(DATES, ['id' => ($instance)->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");

      if (array_search($date, $parsed_dates) === False) {
        array_push($dates, [
          "date" => $date,
          "orders" => ($instance)->all_orders,
        ]);
        array_push($parsed_dates, $date);
      } else {
        $dates[array_search($date, $parsed_dates)]['orders'] += (int) $instance->all_orders;
      }
    }
  }

  array_multisort($dates, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "dates" => $dates,
  ]);
}



// Product's Confirmation Rate:
if (isset($_GET['fetch_product_confirmation_rate']) and isset($_GET['pid'])) {

  $pid = filter_var($_GET['pid'], FILTER_VALIDATE_INT);

  // data to return:
  $rates = [];
  $all = 0;
  $confirmed = 0;
  $parsed_rates = [];

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dateRecords = [];
  $time = isset($_GET['t']) ? ($_GET['t']) : null;

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['all_orders', 'total_products', 'date_id']);
  } else {

    if ($time === "today")        $dateRecords = today();           // today
    if ($time === "yesterday")    $dateRecords = yesterday();       // yesterday
    if ($time === "last_week")    $dateRecords = last_week();       // last_week
    if ($time === "this_month")   $dateRecords = this_month();      // this_month
    if ($time === "last_month")   $dateRecords = last_month();      // last_month
    if ($time === "last_30_days") $dateRecords = last_30_days();    // last_30_days
    if ($time === "this_year")    $dateRecords = this_year();       // this_year
    if ($time === "range")        $dateRecords = dateRange($_GET['from'], $_GET['to']); // range

    if ($dateRecords) {
      foreach ($dateRecords as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['all_orders', 'total_products', 'date_id']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  if (!empty($instances)) {

    foreach ($instances as $instance) {
      $date = $db->getOne(DATES, ['id' => ($instance)->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");


      $rate = ((int) ($instance)->total_products * 100) / (int) ($instance)->all_orders;

      if (array_search($date, $parsed_rates) === False) {
        array_push($rates, [
          "date" => $date,
          "orders" => ($instance)->all_orders,
          "confirmed" => ($instance)->total_products,
          "rate" => round($rate, 2),
        ]);
        array_push($parsed_rates, $date);
      } else {
        $index = array_search($date, $parsed_rates);
        $rates[$index]['orders'] += (int) $instance->all_orders;
        $rates[$index]['confirmed'] += (int) $instance->total_products;
        $new_rate = ((int) $rates[$index]['confirmed'] * 100) / (int) $rates[$index]['orders'];
        $rates[$index]['rate'] = round($new_rate, 2);
      }
    }
  }

  array_multisort($rates, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "rates" => $rates,
  ]);
}


// Product's delivery Rate:
if (isset($_GET['fetch_product_delivery_rate']) and isset($_GET['pid'])) {

  $pid = filter_var($_GET['pid'], FILTER_VALIDATE_INT);

  // data to return:
  $rates = [];
  $all = 0;
  $delivered = 0;
  $parsed_rates = [];

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dateRecords = [];
  $time = isset($_GET['t']) ? ($_GET['t']) : null;

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid], ['all_orders', 'total_delivered', 'date_id']);
  } else {

    if ($time === "today")        $dateRecords = today();           // today
    if ($time === "yesterday")    $dateRecords = yesterday();       // yesterday
    if ($time === "last_week")    $dateRecords = last_week();       // last_week
    if ($time === "this_month")   $dateRecords = this_month();      // this_month
    if ($time === "last_month")   $dateRecords = last_month();      // last_month
    if ($time === "last_30_days") $dateRecords = last_30_days();    // last_30_days
    if ($time === "this_year")    $dateRecords = this_year();       // this_year
    if ($time === "range")        $dateRecords = dateRange($_GET['from'], $_GET['to']); // range

    if ($dateRecords) {
      foreach ($dateRecords as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id], ['all_orders', 'total_delivered', 'date_id']);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  if (!empty($instances)) {

    foreach ($instances as $instance) {
      $date = $db->getOne(DATES, ['id' => ($instance)->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");

      $rate = ((int) ($instance)->total_delivered * 100) / (int) ($instance)->all_orders;

      if (array_search($date, $parsed_rates) === False) {
        array_push($rates, [
          "date" => $date,
          "orders" => ($instance)->all_orders,
          "delivered" => ($instance)->total_delivered,
          "rate" => round($rate, 2),
        ]);
        array_push($parsed_rates, $date);
      } else {
        $index = array_search($date, $parsed_rates);
        $rates[$index]['orders'] += (int) $instance->all_orders;
        $rates[$index]['delivered'] += (int) $instance->total_delivered;
        $new_rate = ((int) $rates[$index]['delivered'] * 100) / (int) $rates[$index]['orders'];
        $rates[$index]['rate'] = round($new_rate, 2);
      }
    }
  }

  array_multisort($rates, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "rates" => $rates,
  ]);
}



// Fetch Product Profit:
if (isset($_GET['fetch_product_profit']) && isset($_GET['pid'])) {

  $pid = filter_var($_GET['pid'], FILTER_VALIDATE_INT);

  // data to return:
  $profits = [];
  $parsed_dates = [];

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dateRecords = [];
  $time = isset($_GET['t']) ? ($_GET['t']) : null;

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid]);
  } else {

    if ($time === "today")        $dateRecords = today();           // today
    if ($time === "yesterday")    $dateRecords = yesterday();       // yesterday
    if ($time === "last_week")    $dateRecords = last_week();       // last_week
    if ($time === "this_month")   $dateRecords = this_month();      // this_month
    if ($time === "last_month")   $dateRecords = last_month();      // last_month
    if ($time === "last_30_days") $dateRecords = last_30_days();    // last_30_days
    if ($time === "this_year")    $dateRecords = this_year();       // this_year
    if ($time === "range")        $dateRecords = dateRange($_GET['from'], $_GET['to']); // range

    if ($dateRecords) {
      foreach ($dateRecords as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id]);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  if (!empty($instances)) {
    foreach ($instances as $instance) {
      $actual_product = $db->getOne(PRODUCTS, ['id' => $instance->product_id]);
      $totaldel = (int) ($instance)->total_delivered;
      $totalord = (int) ($instance)->total_products;
      $sale     = (float) ($instance)->sale_price;
      $adv      = (float) ($instance)->Ads;
      $cost     = (float) ($actual_product)->cost_price;

      // fees:
      $delivery_fee = ($instance)->delivery_fee;
      $confirm_fee = ($instance)->confirmation_fee;

      $result = ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));

      // grab the date:
      $date = $db->getOne(DATES, ['id' => ($instance)->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");

      if (array_search($date, $parsed_dates) === False) {
        array_push($profits, [
          "date" => $date,
          "profit" => (float) $result,
        ]);
        array_push($parsed_dates, $date);
      } else {
        $index = array_search($date, $parsed_dates);
        $profits[$index]["profit"] += (float) $result;
      }
    }
  }

  array_multisort($profits, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "profit" => $profits,
  ]);
}


// Fetch Product Profit:
if (isset($_GET['fetch_product_average_profit']) && isset($_GET['pid'])) {

  $pid = filter_var($_GET['pid'], FILTER_VALIDATE_INT);

  // data to return:
  $profits = [];
  $parsed_dates = [];

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dateRecords = [];
  $time = isset($_GET['t']) ? ($_GET['t']) : null;

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid]);
  } else {

    if ($time === "today")        $dateRecords = today();           // today
    if ($time === "yesterday")    $dateRecords = yesterday();       // yesterday
    if ($time === "last_week")    $dateRecords = last_week();       // last_week
    if ($time === "this_month")   $dateRecords = this_month();      // this_month
    if ($time === "last_month")   $dateRecords = last_month();      // last_month
    if ($time === "last_30_days") $dateRecords = last_30_days();    // last_30_days
    if ($time === "this_year")    $dateRecords = this_year();       // this_year
    if ($time === "range")        $dateRecords = dateRange($_GET['from'], $_GET['to']); // range

    if ($dateRecords) {
      foreach ($dateRecords as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id]);
        $instances = array_merge($instances, $tmp);
      }
    }
  }

  if (!empty($instances)) {
    foreach ($instances as $instance) {
      $actual_product = $db->getOne(PRODUCTS, ['id' => $instance->product_id]);
      $totaldel = (int) ($instance)->total_delivered;
      $totalord = (int) ($instance)->total_products;
      $sale     = (float) ($instance)->sale_price;
      $adv      = (float) ($instance)->Ads;
      $cost     = (float) ($actual_product)->cost_price;
      $sales    = (int) ($instance)->all_orders;

      // fees:
      $delivery_fee = ($instance)->delivery_fee;
      $confirm_fee = ($instance)->confirmation_fee;

      $result = ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));
      $result = ($result > 0) ? round($result / $totaldel, 2) : (0); // $totaldel
      // $result = round($result / $sales, 2);

      // grab the date:
      $date = $db->getOne(DATES, ['id' => ($instance)->date_id], ['date'])->date;
      $date = new Datetime($date);
      $date = $date->format("M d");

      if (array_search($date, $parsed_dates) === False) {

        array_push($profits, [
          "date" => $date,
          "orders" => $totaldel,
          "profit" => (float) $result,
        ]);
        array_push($parsed_dates, $date); //

      } else {
        $index = array_search($date, $parsed_dates);
        // $profits[$index]["profit"] += (float) $result;
      }
    }
  }

  array_multisort($profits, SORT_ASC);
  echo json_encode([
    "status" => "success",
    "profit" => $profits,
  ]);
}


if (isset($_GET['fetch_product_info']) && isset($_GET['pid'])) {

  $pid = filter_var($_GET['pid'], FILTER_VALIDATE_INT);

  // fetch cost price
  $total_cost = 0;
  $cost_price = $db->getOne(PRODUCTS, ['id' => $pid], ['cost_price'])->cost_price;

  // fetch confirmation fees:
  $confirm_fees = 0;
  // $confirm_fee = $db->getOne(FEES, ['slug' => 'confirmation_fee'], ['fee_price'])->fee_price;

  // fetch delivery fees:
  $delivery_fees = 0;
  // $delivery_fee = $db->getOne(FEES, ['slug' => 'delivery_fee'], ['fee_price'])->fee_price;

  // fetch ads cost:
  $ads_cost = 0;

  // fetch net profit:
  $profit = 0;
  $sales = 0;
  $delivered = 0;
  $confirmed = 0;

  // Grab Sale Price from calculator and everything else from product:
  $instances = [];
  $dateRecords = [];
  $time = isset($_GET['t']) ? ($_GET['t']) : null;

  if ($time === null) {

    $instances = $db->getMany(CALCULATOR, ['product_id' => $pid]);
  } else {

    if ($time === "today")        $dateRecords = today();           // today
    if ($time === "yesterday")    $dateRecords = yesterday();       // yesterday
    if ($time === "last_week")    $dateRecords = last_week();       // last_week
    if ($time === "this_month")   $dateRecords = this_month();      // this_month
    if ($time === "last_month")   $dateRecords = last_month();      // last_month
    if ($time === "last_30_days") $dateRecords = last_30_days();    // last_30_days
    if ($time === "this_year")    $dateRecords = this_year();       // this_year
    if ($time === "range")        $dateRecords = dateRange($_GET['from'], $_GET['to']); // range

    if ($dateRecords) {
      foreach ($dateRecords as $dateId) {
        $tmp = $db->getMany(CALCULATOR, ['product_id' => $pid, 'date_id' => $dateId->id]);
        $instances = array_merge($instances, $tmp);
      }
    }
  }


  if (!empty($instances)) {
    foreach ($instances as $instance) {

      $totaldel = (int) ($instance)->total_delivered;
      $totalord = (int) ($instance)->all_orders;
      $sale     = (float) ($instance)->sale_price;
      $adv      = (float) ($instance)->Ads;
      $cost     = (float) $cost_price;

      // fees:
      $delivery_fee = ($instance)->delivery_fee;
      $confirm_fee  = ($instance)->confirmation_fee;

      $profit += ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));

      $delivery_fees += (float) ($delivery_fee * $totaldel);
      $confirm_fees  += (float) ($confirm_fee * $totaldel);
      $total_cost    += (float) ($cost_price * $totaldel); // changed from (cost_price * total_orders)
      $ads_cost      += (float) ($instance)->Ads;

      $sales     += (int) ($instance)->all_orders;
      $delivered += (int) ($instance)->total_delivered;
      $confirmed += (int) ($instance)->total_products;
    }
  }

  echo json_encode([
    "status" => "success",
    "cost_price" => $total_cost,
    "confirm_fees" => $confirm_fees,
    "delivery_fees" => $delivery_fees,
    "ads" => $ads_cost,
    "profit" => $profit,
    "sales" => $sales,
    "delivered" => $delivered,
    "confirmed" => $confirmed,
  ]);
}
