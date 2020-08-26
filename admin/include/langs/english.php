<?php

function lang($pharse) {

    static $lang = array(

        'HOME_ADMIN' => 'Home',
        'CATEGORIES' => 'Categories',
        'ITEMS' => 'Items',
        'MEMBERS' => 'Members',
        'COMMENTS' => 'Comments',
        'MIAN_SITE' => 'Visit Shop',
    );

    return $lang[$pharse];
}