<?php

function rangeCapital($id)
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

            // fees:
            $confirm_fee = ($product)->confirmation_fee;
            $delivery_fee = ($product)->delivery_fee;

            $result = ($sale * $totaldel - ($totaldel * $cost + $adv + $totaldel * $delivery_fee + $confirm_fee * $totaldel));
            $total_net_per_date += ($result > 0) ? $result : 0;
        }
    }
    return $total_net_per_date;
}

// parse Capital:
function parseCapital(array $dates = null)
{
    global $db;
    $capital = 0; // the resultant total capital of all given dates.

    if (!empty($dates) and !is_null($dates)) {
        foreach ($dates as $dateId) {

            $total_cp_per_date = 0; // net profit per date
            $products = $db->getMany(CALCULATOR, ['date_id' => $dateId->id]);

            if (!is_null($products) && $db->count > 0) {
                foreach ($products as $product) {
                    $totaldel = ($product)->total_delivered;
                    $sale = ($product)->sale_price;

                    $result = ($sale * $totaldel);
                    $total_cp_per_date += ($result > 0) ? $result : 0;
                }
            }

            // append current date profit to the total capital:
            $capital += $total_cp_per_date;
        }
    }
    return $capital;
}

// Product Expenses:
function ProductExpense($date)
{
    global $db;
    $data = [
        "orders" => 0,
        "cost" => 0,
        "ads" => 0,
        "confirmed" => 0,
        "delivered" => 0,
        "delivery_fee" => 0,
        "confirmation_fee" => 0
    ];

    if (!empty($date)) {
        foreach ($date as $dateId) {
            $all = $db->getMany(CALCULATOR, ['date_id' => $dateId->id]);
            if (!empty($all)) {
                foreach ($all as $purchase) {
                    $mainProduct = $db->getOne(PRODUCTS, ['id' => $purchase->product_id], ['cost_price']);
                    $data["orders"]    += (int) $purchase->all_orders;
                    $data["ads"]       += (float) $purchase->Ads;
                    $data["confirmed"] += (int) $purchase->total_products;
                    $data["delivered"] += (int) $purchase->total_delivered;

                    // a-fix
                    $data["cost"]      += (float) $mainProduct->cost_price * (int) $purchase->total_delivered;

                    // fees:
                    $confirm_fee = ($purchase)->confirmation_fee;
                    $delivery_fee = ($purchase)->delivery_fee;

                    $data["confirmation_fee"] += (float) $confirm_fee * (int) $purchase->total_delivered; // || total_products
                    $data["delivery_fee"] += (float) $delivery_fee * (int) $purchase->total_delivered;
                }
            }
        }
    }
    return $data;
}


// Get Date Range:
function dateRange($rangeFrom, $rangeTo)
{
    global $db; // golbalize db variable:
    $rangeFrom = new DateTime($rangeFrom);
    $rangeTo = new DateTime($rangeTo);

    $rangeFrom = $rangeFrom->format('Y-m-d');
    $rangeTo = $rangeTo->format('Y-m-d');

    $datesIds = $db->getMany(DATES, [
        'date' => $rangeFrom,
        'dates.date' => $rangeTo,
    ], ['id', 'date'], ['date' => '>=', 'dates.date' => '<=']);
    if ($db->count > 0) {
        return $datesIds;
    }
}


// returns this months reports:
function this_month()
{
    global $db; // golbalize db variable:
    $thisMonth = array(
        "start" => date('Y-m-d', strtotime('first day of this month', strtotime(date('Y-m-d')))),
        "end" => date('Y-m-d', strtotime('last day of this month', strtotime(date('Y-m-d'))))
    );
    $datesIds = $db->getMany(DATES, [
        'date' => $thisMonth['start'],
        'dates.date' => $thisMonth['end']
    ], ['id', 'date'], ['date' => '>=', 'dates.date' => '<=']);

    if ($db->count > 0) {
        return $datesIds;
    }
}

// returns last months reports:
function last_month()
{
    global $db; // golbalize db variable:
    $thisMonth = array(
        "start" => date('Y-m-d', strtotime('first day of last month', strtotime(date('Y-m-d')))),
        "end" => date('Y-m-d', strtotime('last day of last month', strtotime(date('Y-m-d'))))
    );
    $datesIds = $db->getMany(DATES, [
        'date' => $thisMonth['start'],
        'dates.date' => $thisMonth['end']
    ], ['id', 'date'], ['date' => '>=', 'dates.date' => '<=']);

    if ($db->count > 0) {
        return $datesIds;
    }
}

// returns last months reports:
function last_30_days()
{
    global $db; // golbalize db variable:
    $thisMonth = array(
        "start" => date('Y-m-d', strtotime('today', strtotime(date('Y-m-d')))),
        "end" => date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))))
    );
    $datesIds = $db->getMany(DATES, [
        'date' => $thisMonth['start'],
        'dates.date' => $thisMonth['end']
    ], ['id', 'date'], ['date' => '<=', 'dates.date' => '>=']);
    if ($db->count > 0) {
        return $datesIds;
    }
}

// returns this year's reports:
function this_year()
{
    global $db; // golbalize db variable:
    $thisYear = date('Y') . '-01-01';
    $nextYear = ((int) date('Y') + 1) . '-01-01';
    $datesIds = $db->getMany(DATES, [
        'date' => $thisYear,
        'dates.date' => $nextYear,
    ], ['id', 'date'], ['date' => '>=', 'dates.date' => '<=']);
    if ($db->count > 0) {
        return $datesIds;
    }
}


// returns today's reports:
function today()
{
    global $db; // golbalize db variable:
    $today = new DateTime("Today");
    $datesIds = $db->getMany(DATES, ['date' => $today->format('Y-m-d')], ['id', 'date']);
    if ($db->count > 0) {
        return $datesIds;
    }
}

// returns yesterday's reports:
function yesterday()
{
    global $db; // golbalize db variable:
    $yesterday = new DateTime("Yesterday");
    $datesIds = $db->getMany(DATES, ['date' => $yesterday->format('Y-m-d')], ['id', 'date']);
    if ($db->count > 0) {
        return $datesIds;
    }
}

// returns last_week's reports:
function last_week()
{
    global $db; // golbalize db variable:
    $weekStart = new DateTime("this week");
    $weekEnd = new DateTime("last week");
    $datesIds = $db->getMany(DATES, [
        'date' => $weekStart->format('Y-m-d'),
        'dates.date' => $weekEnd->format('Y-m-d'),
    ], ['id', 'date'], ['date' => '<=', 'dates.date' => '>=']);
    if ($db->count > 0) {
        return $datesIds;
    }
}


// die and dump function:
function dd(String $message)
{
    echo json_encode([
        'status' => 'error',
        'message' => $message,
    ]);
    exit();
}
