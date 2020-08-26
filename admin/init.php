<?php

include 'pdo.php';

$temps = 'include/temps/';
$funcs = 'include/funcs/';
$css = 'layout/css/';
$js= 'layout/js/';
$langs='include/langs/';

include $langs.'english.php';
include $funcs.'functions.php';
include $temps.'header.php';

if (!isset($noNavbar)) {

    include $temps . 'navbar.php';
}