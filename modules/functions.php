<?php require_once 'Init.php' ?>

<?php
# -------------------------------------------------------------
# -------------------------------------------------------------
# ----------------  [ HELPER FUNCTIONS ]  ---------------------
# -------------------------------------------------------------



function getDatesFromTo($from, $to)
{

  global $db;
  $dates = $db->getMany(DATES);
  $dates_collection = array();
  if (!empty($dates)) {
    foreach ($dates as $date) {
      if ((int) $date->id >= (int) $from && (int) $date->id <= (int) $to) {
        array_push($dates_collection, $date);
      }
    }
  }
  return $dates_collection;
}


function getNetTotalPerDate($id)
{
  global $db;
  $total_net_per_date = 0;
  $products = $db->getMany(CALCULATOR, ['date_id' => $id]);

  if (!is_null($products) && $db->count > 0) {
    foreach ($products as $product) {
      $current_product = $db->getOne(PRODUCTS, ['id' => $product->product_id]);
      $totaldel = ($product)->total_delivered;
      $totalord = ($product)->total_products;
      $sale = ($product)->sale_price;
      $adv = ($product)->Ads;
      $cost = ($current_product)->cost_price;

      $confirm_fee = ($product)->confirmation_fee;
      $delivery_fee = ($product)->delivery_fee;

      $result = ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));
      // $total_net_per_date += ($result > 0) ? $result : 0;
      $total_net_per_date += ($result);
    }
  }
  return "{$total_net_per_date} DH";
}

function getCpTotalPerDate(string $id = null)
{
  global $db;
  $total_cp_per_date = 0;
  $products = [];

  if (!is_null($id)) {
    $products = $db->getMany(CALCULATOR, ['date_id' => $id]);
  } else {
    $products = $db->getMany(CALCULATOR);
  }

  if (!is_null($products) && $db->count > 0) {
    foreach ($products as $product) {
      $totaldel = ($product)->total_delivered;
      $sale = ($product)->sale_price;

      $result = ($sale * $totaldel);
      $total_cp_per_date += ($result > 0) ? $result : 0;
    }
  }

  return (number_format($total_cp_per_date, 0) . " DH");
}

function getDevRatePerDate($id)
{
  global $db;
  $total_devrate_per_date = 0;

  $products = $db->getMany(CALCULATOR, ['date_id' => $id]);

  if (!is_null($products) && $db->count > 0) {

    foreach ($products as $product) {

      $current_product = $db->getOne(PRODUCTS, ['id' => $product->product_id]);

      $totaldel = ($product)->total_delivered;
      $totalord = ($product)->total_products;

      $result = ($totaldel > 0 && $totalord > 0) ? round(($totaldel / $totalord) * 100, 2) : (0);
      $total_devrate_per_date += ($result > 0) ? $result : 0;
    }
  }
  return number_format($total_devrate_per_date / count($products), 2) . "%";
}

function getConRatePerDate($id)
{
  global $db;
  $total_conrate_per_date = 0;
  $products = $db->getMany(CALCULATOR, ['date_id' => $id]);

  if (!is_null($products) && $db->count > 0) {
    foreach ($products as $product) {
      $current_product = $db->getOne(PRODUCTS, ['id' => $product->product_id]);
      $totalord = ($product)->total_products;
      $allord = ($product)->all_orders;

      $result = round(($totalord / $allord) * 100, 2);
      $total_conrate_per_date += ($result > 0) ? $result : 0;
    }
  }
  return number_format($total_conrate_per_date / count($products), 2) . "%";
}


// Functions For Dashboard Stats:
function getTotalNetProfit(String $date = null, String $from = null, String $to = null)
{
  global $db;
  $total_net_profit = 0;

  $products = [];

  if (is_null($date)) { // if not date is provided, take all:

    $products = $db->getMany(CALCULATOR);
  } else {

    switch ($date) {

      case 'today': // today's profit:
        $date = new Datetime("today");
        $date_id = $db->getOne(DATES, ['date' => $date->format('Y-m-d')], ['id']);
        if (!empty($date_id)) {
          $products = $db->getMany(CALCULATOR, ['date_id' => $date_id->id]);
        }
        break;

      case 'yesterday': // yesterday's profit
        $date = new Datetime("yesterday");
        $date_id = $db->getOne(DATES, ['date' => $date->format('Y-m-d')], ['id']);
        if (!empty($date_id)) {
          $products = $db->getMany(CALCULATOR, ['date_id' => $date_id->id]);
        }
        break;

      case 'last_week': // last week's profit
        $dates_id = last_week();
        if (!empty($dates_id)) {
          foreach ($dates_id as $date_id) {
            $tmp_products = $db->getMany(CALCULATOR, ['date_id' => $date_id->id]);
            $products = array_merge($products, $tmp_products);
          }
        }
        break;

      case 'this_month': // this month's profit
        $dates_id = this_month();
        if (!empty($dates_id)) {
          foreach ($dates_id as $date_id) {
            $tmp_products = $db->getMany(CALCULATOR, ['date_id' => $date_id->id]);
            $products = array_merge($products, $tmp_products);
          }
        }
        break;

      case 'last_month': // last month's profit
        $dates_id = last_month();
        if (!empty($dates_id)) {
          foreach ($dates_id as $date_id) {
            $tmp_products = $db->getMany(CALCULATOR, ['date_id' => $date_id->id]);
            $products = array_merge($products, $tmp_products);
          }
        }
        break;

      case 'last_30_days': // last 30 days' profit
        $dates_id = last_30_days();
        if (!empty($dates_id)) {
          foreach ($dates_id as $date_id) {
            $tmp_products = $db->getMany(CALCULATOR, ['date_id' => $date_id->id]);
            $products = array_merge($products, $tmp_products);
          }
        }
        break;


      case 'this_year': // this year's profit
        $dates_id = this_year();
        if (!empty($dates_id)) {
          foreach ($dates_id as $date_id) {
            $tmp_products = $db->getMany(CALCULATOR, ['date_id' => $date_id->id]);
            $products = array_merge($products, $tmp_products);
          }
        }
        break;


      case 'range': // range date (from, to)
        $dates_id = dateRange($from, $to);
        if (!empty($dates_id)) {
          foreach ($dates_id as $date_id) {
            $tmp_products = $db->getMany(CALCULATOR, ['date_id' => $date_id->id]);
            $products = array_merge($products, $tmp_products);
          }
        }
        break;

      default:
        $tmp_products = $db->getMany(CALCULATOR, ['date_id' => $date]);
        $products = array_merge($products, $tmp_products);
        break;
    }
  }

  if (!empty($products) && $db->count > 0) {
    foreach ($products as $product) {
      $current_product = $db->getOne(PRODUCTS, ['id' => $product->product_id]);
      $totaldel = ($product)->total_delivered;
      $sale = ($product)->sale_price;
      $adv = ($product)->Ads;
      $cost = ($current_product)->cost_price;

      // fees
      $confirm_fee = ($product)->confirmation_fee;
      $delivery_fee = ($product)->delivery_fee;

      $result = ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));
      // $total_net_profit += ($result > 0) ? $result : 0;
      $total_net_profit += ($result);
    }
  }
  return $total_net_profit;
}


// For Orders:
function getTotalOrders(String $date = null, string $from = null, String $to = null)
{
  global $db;
  $all_orders = 0;

  if (!is_null($date)) {

    // grab total orders of the given date.
    switch ($date) {
      case 'today':
        $date_id = today();
        if (!empty($date_id)) {
          foreach ($date_id as $dateId) {
            $purchases = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders']);
            foreach ($purchases as $purchase) {
              $all_orders += (int) $purchase->all_orders;
            }
          }
        }
        break;

      case 'yesterday':
        $date_id = yesterday();
        if (!empty($date_id)) {
          foreach ($date_id as $dateId) {
            $purchases = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders']);
            foreach ($purchases as $purchase) {
              $all_orders += (int) $purchase->all_orders;
            }
          }
        }
        break;

      case 'last_week':
        $date_id = last_week();
        if (!empty($date_id)) {
          foreach ($date_id as $dateId) {
            $purchases = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders']);
            foreach ($purchases as $purchase) {
              $all_orders += (int) $purchase->all_orders;
            }
          }
        }
        break;

      case 'this_month':
        $date_id = this_month();
        if (!empty($date_id)) {
          foreach ($date_id as $dateId) {
            $purchases = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders']);
            foreach ($purchases as $purchase) {
              $all_orders += (int) $purchase->all_orders;
            }
          }
        }
        break;

      case 'last_month':
        $date_id = last_month();
        if (!empty($date_id)) {
          foreach ($date_id as $dateId) {
            $purchases = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders']);
            foreach ($purchases as $purchase) {
              $all_orders += (int) $purchase->all_orders;
            }
          }
        }
        break;

      case 'last_30_days':
        $date_id = last_30_days();
        if (!empty($date_id)) {
          foreach ($date_id as $dateId) {
            $purchases = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders']);
            foreach ($purchases as $purchase) {
              $all_orders += (int) $purchase->all_orders;
            }
          }
        }
        break;

      case 'this_year':
        $date_id = this_year();
        if (!empty($date_id)) {
          foreach ($date_id as $dateId) {
            $purchases = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders']);
            foreach ($purchases as $purchase) {
              $all_orders += (int) $purchase->all_orders;
            }
          }
        }
        break;

      case 'range':
        $date_id = dateRange($from, $to);
        if (!empty($date_id)) {
          foreach ($date_id as $dateId) {
            $purchases = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders']);
            foreach ($purchases as $purchase) {
              $all_orders += (int) $purchase->all_orders;
            }
          }
        }
        break;

      default:
        $purchases = $db->getMany(CALCULATOR, ['date_id' => $date], ['all_orders']);
        foreach ($purchases as $purchase) {
          $all_orders += (int) $purchase->all_orders;
        }
        break;
    }
  } else {
    // default: print all.
    $purchases = $db->getMany(CALCULATOR, null, ['all_orders']);
    foreach ($purchases as $purchase) {
      $all_orders += (int) $purchase->all_orders;
    }
  }

  return $all_orders;
}


// for confirmation rate:
function getConfirmationRate(String $date = null, string $from = null, string $to = null)
{
  global $db;
  $orders = 0;
  $confirmed = 0;
  $ConfirmRate = (float) 0.00;


  if (!empty($date)) {

    switch ($date) {
      case 'today':
        $date = today();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->all_orders;
                $confirmed += (int) $purchase->total_products;
              }
            }
          }
        }
        break;

      case 'yesterday':
        $date = yesterday();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->all_orders;
                $confirmed += (int) $purchase->total_products;
              }
            }
          }
        }
        break;

      case 'last_week':
        $date = last_week();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->all_orders;
                $confirmed += (int) $purchase->total_products;
              }
            }
          }
        }
        break;

      case 'this_month':
        $date = this_month();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->all_orders;
                $confirmed += (int) $purchase->total_products;
              }
            }
          }
        }
        break;

      case 'last_month':
        $date = last_month();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->all_orders;
                $confirmed += (int) $purchase->total_products;
              }
            }
          }
        }
        break;

      case 'last_30_days':
        $date = last_30_days();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->all_orders;
                $confirmed += (int) $purchase->total_products;
              }
            }
          }
        }
        break;

      case 'this_year':
        $date = this_year();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->all_orders;
                $confirmed += (int) $purchase->total_products;
              }
            }
          }
        }
        break;

      case 'range':
        $date = dateRange($from, $to);
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->all_orders;
                $confirmed += (int) $purchase->total_products;
              }
            }
          }
        }
        break;

      default:
        $all = $db->getMany(CALCULATOR, ['date_id' => $date], ['all_orders', 'total_products']);
        if (!empty($all)) {
          foreach ($all as $purchase) {
            $orders += (int) $purchase->all_orders;
            $confirmed += (int) $purchase->total_products;
          }
        }
        break;
    }
  } else {

    $all = $db->getMany(CALCULATOR, null, ['all_orders', 'total_products']);
    foreach ($all as $purchase) {
      $orders += (int) $purchase->all_orders;
      $confirmed += (int) $purchase->total_products;
    }
  }

  if ($confirmed > 0 && $orders > 0) {
    $rate = (float) ($confirmed * 100) / $orders;
    return array(
      "confirmed" => $confirmed,
      "rate" => round($rate, 2),
    );
  } else {
    return array(
      "confirmed" => "0",
      "rate" => round("0", 2),
    );
  }
}


// for delivery rate:
function getDeliveryRate(String $date = null, string $from = null, string $to = null)
{
  global $db;
  $orders = 0;
  $delivered = 0;


  if (!empty($date)) {

    switch ($date) {
      case 'today':
        $date = today();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products', 'total_delivered']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->total_products;
                $delivered += (int) $purchase->total_delivered;
              }
            }
          }
        }
        break;

      case 'yesterday':
        $date = yesterday();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products', 'total_delivered']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->total_products;
                $delivered += (int) $purchase->total_delivered;
              }
            }
          }
        }
        break;

      case 'last_week':
        $date = last_week();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products', 'total_delivered']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->total_products;
                $delivered += (int) $purchase->total_delivered;
              }
            }
          }
        }
        break;

      case 'this_month':
        $date = this_month();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products', 'total_delivered']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->total_products;
                $delivered += (int) $purchase->total_delivered;
              }
            }
          }
        }
        break;

      case 'last_month':
        $date = last_month();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products', 'total_delivered']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->total_products;
                $delivered += (int) $purchase->total_delivered;
              }
            }
          }
        }
        break;

      case 'last_30_days':
        $date = last_30_days();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products', 'total_delivered']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->total_products;
                $delivered += (int) $purchase->total_delivered;
              }
            }
          }
        }
        break;

      case 'this_year':
        $date = this_year();
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products', 'total_delivered']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->total_products;
                $delivered += (int) $purchase->total_delivered;
              }
            }
          }
        }
        break;

      case 'range':
        $date = dateRange($from, $to);
        if (!empty($date)) {
          foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id], ['all_orders', 'total_products', 'total_delivered']);
            if (!empty($all)) {
              foreach ($all as $purchase) {
                $orders += (int) $purchase->total_products;
                $delivered += (int) $purchase->total_delivered;
              }
            }
          }
        }
        break;

      default:
        $all = $db->getMany(CALCULATOR, ['date_id' => $date], ['all_orders', 'total_products', 'total_delivered']);
        if (!empty($all)) {
          foreach ($all as $purchase) {
            $orders += (int) $purchase->total_products;
            $delivered += (int) $purchase->total_delivered;
          }
        }
        break;
    }
  } else {

    $all = $db->getMany(CALCULATOR, null, ['all_orders', 'total_products', 'total_delivered']);
    if (!empty($all)) {
      foreach ($all as $purchase) {
        $orders += (int) $purchase->total_products;
        $delivered += (int) $purchase->total_delivered;
      }
    }
  }

  if ($delivered > 0 && $orders > 0) {
    $rate = ($delivered * 100) / $orders;
    return array(
      "delivered" => $delivered,
      "rate" => round($rate, 2),
    );
  } else {
    return array(
      "delivered" => "0",
      "rate" => round("0", 2),
    );
  }
}


// for confirmation rate:
function getExpenses(String $date = null, string $from = null, string $to = null)
{
  global $db;
  $orders = 0;
  $confirmed = 0;
  $delivered = 0;

  $cost = 0;
  $ads = 0;

  // fees cost:
  $delivery_cost = 0;
  $confirmation_cost = 0;

  if (!empty($date)) {
    $data = [];
    switch ($date) {
      case 'today':
        $data = ProductExpense(today());
        break;

      case 'yesterday':
        $data = ProductExpense(yesterday());
        break;

      case 'last_week':
        $data = ProductExpense(last_week());
        break;

      case 'this_month':
        $data = ProductExpense(this_month());
        break;

      case 'last_month':
        $data = ProductExpense(last_month());
        break;

      case 'last_30_days':
        $data = ProductExpense(last_30_days());
        break;

      case 'this_year':
        $data = ProductExpense(this_year());
        break;

      case 'range':
        $data = ProductExpense(dateRange($from, $to));
        break;
    }

    $orders            = $data["orders"];
    $cost              = $data["cost"];
    $ads               = $data["ads"];
    $confirmed         = $data["confirmed"];
    $delivered         = $data["delivered"];
    $confirmation_cost = $data["confirmation_fee"];
    $delivery_cost     = $data["delivery_fee"];

    //
  } else {

    $all = $db->getMany(CALCULATOR);
    if (!empty($all)) {
      foreach ($all as $purchase) {
        $mainProduct = $db->getOne(PRODUCTS, ['id' => $purchase->product_id], ['cost_price']);
        $orders    += (int) $purchase->all_orders; // all_orders
        $ads       += (float) $purchase->Ads;
        $confirmed += (int) $purchase->total_products;
        $delivered += (int) $purchase->total_delivered;

        $cost += (float) $mainProduct->cost_price * (int) $purchase->total_delivered;

        // fees:
        $delivery_fee = ($purchase)->delivery_fee;
        $confirm_fee  = ($purchase)->confirmation_fee;

        $delivery_cost     += (float) ((int) $purchase->total_delivered * ((float) $delivery_fee)); // extract Total delivery cost // 
        $confirmation_cost += (float) ((int) $purchase->total_delivered * ((float) $confirm_fee)); // extract total confirmation fee || total_products
      }
    }
  }


  $ads_cost = (float) $ads;

  return array(
    "delivery"     => $delivery_cost,
    "confirmation" => $confirmation_cost,
    "cost"         => $cost, // $cost
    "ads"          => $ads_cost,
  );
}


// For Capital:
function getCapital(String $date = null, String $from = null, string $to = null)
{

  $capital = 0.00;

  if (!is_null($date)) {

    switch ($date) {
      case 'today':
        $capital = parseCapital(today());
        $capital = (number_format($capital, 0) . " DH");
        break;

      case 'yesterday':
        $capital = parseCapital(yesterday());
        $capital = (number_format($capital, 0) . " DH");
        break;

      case 'last_week':
        $capital = parseCapital(last_week());
        $capital = (number_format($capital, 0) . " DH");
        break;

      case 'this_month':
        $capital = parseCapital(this_month());
        $capital = (number_format($capital, 0) . " DH");
        break;

      case 'last_month':
        $capital = parseCapital(last_month());
        $capital = (number_format($capital, 0) . " DH");
        break;

      case 'last_30_days':
        $capital = parseCapital(last_30_days());
        $capital = (number_format($capital, 0) . " DH");
        break;

      case 'this_year':
        $capital = parseCapital(this_year());
        $capital = (number_format($capital, 0, '.', ',') . " DH");
        break;

      case 'range':
        $capital = parseCapital(dateRange($from, $to));
        $capital = (number_format($capital, 0, '.', ',') . " DH");
        break;
    }
  } else {
    $capital = getCpTotalPerDate();
  }

  // return capital:
  return !is_null($capital) ? ($capital) : (0);
}



function ReleventProducts(String $time = null, String $from = null, String $to = null)
{
  // database:
  global $db;

  $dateResults = [];

  if ($time === null) {

    $dateResults = $db->getMany(CALCULATOR); //

  } else {

    $dateRecords = [];

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
        $tmp = $db->getMany(CALCULATOR, ['date_id' => $dateId->id]);
        $dateResults = array_merge($dateResults, $tmp);
      }
    }
  }


  // $dateResults = $db->getMany(CALCULATOR); // all dates

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

      $delivery_rate = ($is_calculable) ? round(($totaldel / $totalord) * 100, 2) : (0); // Delivery Rate: 3rd TD
      $delivery_cost = ($is_calculable) ? ($totaldel * $deliveryFee) : (0); // Delivery Cost: 4th TD
      $total_delivery_cost = ($is_calculable) ? ($totaldel * $cost) : (0); // Total Delivery Cost: 5th TD
      $ad_cost_per_delivery = ($is_calculable) ? round($adv / $totaldel, 2) : (0); // Cost per Delivery: 7th TD
      $confirm_fees = ($is_calculable) ? ($confirmFee * $totaldel) : (0); // Confirm Fees: 8th TD
      $confirm_rate = ($is_calculable) ? round(($totalord / $allord) * 100, 2) : (0); // Confirm rate: 14TD
      $capital_per_product = ($is_calculable) ? ($sale * $totaldel) : (0); // Capital Per Product: 9th TD
      
      // Expanses Per Product: 10th TD
      // $expenses_per_product = ($is_calculable) ? (($totaldel * $cost) + $adv + ($totaldel * $deliveryFee) + ($confirmFee * $totaldel)) : (0);
      $expenses_per_product = (($totaldel * $cost) + $adv + ($totaldel * $deliveryFee) + ($confirmFee * $totaldel));

      // $net_profit = ($is_calculable) ? (($sale * $totaldel) - $expenses_per_product) : (0); // Net Profit: 11th TD
      $net_profit = (($sale * $totaldel) - $expenses_per_product); // Net Profit: 11th TD

      
      // Net Profit Percentage Per Product: 12th TD
      $net_profit_percentage = ($is_calculable) ? round(($sale * $totaldel - $expenses_per_product) / $totaldel, 2) : (0);
      $expenses_percentage = ($is_calculable) ? round($expenses_per_product / $totaldel, 2) : (0); // Expenses Percentage = 13th TD

      // grab the date:
      $product_date = $db->getOne(DATES, ['id' => ($dateProduct)->date_id], ['date'])->date;


      $response .= "<tr id='" . ($dateProduct->id) . "'>";
      $response .= "<td>" . ($product_date) . "</td>";
      $response .= "<td>" . ($product)->product_name . "</td>";
      $response .= "<td>" . ($allord) . "</td>";
      $response .= "<td>" . ($totalord) . "</td>";
      $response .= "<td>" . ($totaldel) . "</td>";
      $response .= "<td>" . ("{$confirm_rate}%") . "</td>";
      $response .= "<td>" . ("{$delivery_rate}%") . "</td>";
      $response .= "<td class='tmp_capital blue'>" . ("{$capital_per_product} DH") . "</td>";
      $response .= "<td>" . "{$total_delivery_cost} DH" . "</td>";
      $response .= "<td>" . ("{$delivery_cost} DH") . "</td>";
      $response .= "<td>" . ("{$adv} DH") . "</td>";
      $response .= "<td>" . ("{$confirm_fees} DH") . "</td>";
      $response .= "<td class='tmp_expenses red'>" . ("{$expenses_per_product} DH") . "</td>";
      $response .= "<td class='tmp_net green'>" . ("{$net_profit} DH") . "</td>";
      $response .= "<td>" . ("{$net_profit_percentage} DH") . "</td>";
      $response .= "<td>" . ("{$expenses_percentage} DH") . "</td>";
      $response .= "<td>" . ("{$ad_cost_per_delivery} DH") . "</td>";
      $response .= "</tr>";
    }
    return $response;
  } else {
    return "<tr class='alert-warning width-100'><td colspan='17' class='full-width text-center'><strong>No Products</strong></td></tr>";
  }
}


function all_expenses(string $time = null, String $from = null, String $to = null)
{

  $total = 0;
  $expenses = 0;

  if ($time === null) {

    $expenses = getExpenses(); //

  } else {

    if ($time === "range") {
      $expenses = getExpenses($time, $from, $to);
    } else {
      $expenses = getExpenses($time);
    }
  }

  $total += (float) $expenses['delivery'];
  $total += (float) $expenses['confirmation'];
  $total += (float) $expenses['cost'];
  $total += (float) $expenses['ads'];

  return (number_format($total, 0) . " DH");
}
