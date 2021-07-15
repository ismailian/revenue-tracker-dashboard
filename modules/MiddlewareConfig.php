<?php

// guard properties:
if (isset($_SESSION['USER'])) {
  $userAccess = $db->getOne(ACCESS, ['user_id' => $_SESSION['USER']->id]);

  $pagesAllowed = [];

  ($userAccess->index_page)      ? array_push($pagesAllowed, 'index')      : null;
  ($userAccess->settings_page)   ? array_push($pagesAllowed, 'settings')   : null;
  ($userAccess->products_page)   ? array_push($pagesAllowed, 'products')   : null;
  ($userAccess->calculator_page) ? array_push($pagesAllowed, 'calculator') : null;
  ($userAccess->reports_page)    ? array_push($pagesAllowed, 'reports')    : null;
  ($userAccess->fees_page)       ? array_push($pagesAllowed, 'fees')       : null;

  $guard->restrict($_SESSION['USER']->username,  array_merge($pagesAllowed, ['insight', 'ajax', 'functions']), ($pagesAllowed[0])); //

} else { // guest account

  $guard->restrict('guest',  ['login', 'ajax'], ('login')); //

}
