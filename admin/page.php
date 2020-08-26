<?php

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


if ($do == 'Manage') {

    echo 'hi Manage ';
    echo '<a href="?do=Add">Add New Categories</a>';
}
elseif ($do == 'Add') {

    echo 'hi Add';
}
elseif ($do == 'Insert') {

    echo 'hi Insert';
}
else  {

    echo 'No Page';
}

