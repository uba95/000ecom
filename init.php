<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'admin/pdo.php';

$sessionUser = isset($_SESSION['Member'])? $_SESSION['Member'] : '';

$temps = 'include/temps/';
$funcs = 'include/funcs/';
$css = 'layout/css/';
$js= 'layout/js/';
$langs='include/langs/';

include $langs.'english.php';
include $funcs.'functions.php';
include $temps.'header.php';
