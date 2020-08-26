<?php

function lang($pharse) {

    static $lang = array(

        'HOME_PAGE' => 'Homepage',
    );

    return $lang[$pharse];
}