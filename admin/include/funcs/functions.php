<?php

function getAll($column, $table, $where = NULL, $order = NULL) {

    global $connect;
    $sql4 = "SELECT $column FROM $table $where $order";
    $stmt4 = $connect->prepare($sql4);
    $stmt4->execute();
    $all = $stmt4->fetchAll();
    
    return $all;
}


function getOne($column, $table, $where = NULL, $order = NULL, $one = 'fetch') {

    global $connect;
    $sql0 = "SELECT $column FROM $table $where $order";
    $stmt0 = $connect->prepare($sql0);
    $stmt0->execute();
    $item0 = $stmt0->fetch();

    if ($one == 'fetch') {
        return $item0;


    } else {

        $count0 = $stmt0->rowCount();    
        return $count0;
    }
    
}
/* Page Title */

function getTitle() {

    global $title;

    if (isset($title)) {

        echo $title;

    } else {

        echo 'Default';
    }
}


/* Redirect To */

function redirectMe($theMsg, $url= null,$sec = 3) {

    if ($url = null) {

        $url = 'index.php';
        $link = 'HomePage';

    } else {

        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';

        } else {
            
            $url = 'index.php';
            $link = 'HomePage';

        }
        
    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Be Redirected To $link In $sec Seconds</div>";
    header("refresh:$sec;url=$url");
    exit();
}


/* Check If Exit In Database */

function checkIfExitInDb($select, $from, $value) {

    global $connect;

    $mysql = "SELECT $select FROM $from WHERE $select = ?";
    $mystmt = $connect->prepare($mysql);
    $mystmt->execute(array($value));
    $count = $mystmt->rowCount();
    
    return $count;
    
}

/* Count Items In The Table */

function countItems($item, $table) {

    global $connect;

    $sql2 = "SELECT COUNT($item) FROM $table";
    $stmt2 = $connect->prepare($sql2);
    $stmt2->execute();
    return $stmt2->fetchColumn();

}

/* Shaw Latest Items */

function latestItems($select, $table, $order, $limit) {

    global $connect;

    $sql3 = "SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit";
    $stmt3 = $connect->prepare($sql3);
    $stmt3->execute();
    $rows = $stmt3->fetchAll();
    return $rows;

}