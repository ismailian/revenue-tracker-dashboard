<?php

session_start();
session_regenerate_id();

// set timezone:
date_default_timezone_set('GMT+0');

/**
 * This file is necessary to all other file/pages,
 * it should and must be required, for it manages
 * third-party modules and functions holder file.
 * as well as our connection to database.
 */




# -------------------------------------
# ---------- [ CONSTANTS ] ------------
define("ROOT",     $_SERVER['DOCUMENT_ROOT'] . "/");
define('BASE_URL', "http://" . $_SERVER['HTTP_HOST'] . "/");
define('MODULES',  ROOT . 'modules/');
define('INCLUDES', ROOT . 'includes/');


# -------------------------------------
# ---------- [ FILES ] ----------------
define('HEADER',      INCLUDES . 'header.php');
define('SIDEBAR',     INCLUDES . 'side_bar.php');
define('FOOTER',      INCLUDES . 'footer.php');
define('MAIN_JS',     INCLUDES . 'main.js.php');
define('INDEX_JS',    INCLUDES . 'index_js.php');
define('PRODUCTS_JS', INCLUDES . 'products_js.php');
define('FEES_JS',     INCLUDES . 'fees_js.php');
define('REPORTS_JS',  INCLUDES . 'reports_js.php');
# -------------------------------------


/**
 * All third-party modules/files need to be imported here.
 */
# import database manager:
require_once(MODULES . 'Connector.php');
require_once(MODULES . 'Middleware.php');


/**
 * All third-party modules/files need to be Initiated here.
 */

# Initiate database manager instance:
$db = new Connector(
	'localhost',
	'root',
	'',
	'revenue_tracker'
);


// $con = mysqli_connect("localhost", "root", "", "revenue_tracker");

// if (!$con) {
//     die("Connection Failure");
// }


# Instantiate a middleware instance:
$guard = new Middleware('USER', 'username');

// modules' properties:
$db->returnObj = TRUE;

// Functions And Ajax file:
require_once(MODULES . 'MiddlewareConfig.php');
require_once(MODULES . 'helpers.php');
require_once(MODULES . 'functions.php');
require_once(ROOT    . 'api/ajax.php');
require_once(ROOT    . 'api/insight.php');

// for debugging:
//$guard->_access();

// run the Middleware:
$guard->watch();
