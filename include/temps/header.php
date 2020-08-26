<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel='stylesheet' href='<?php echo $css?>bootstrap.min.css'>
    <link rel='stylesheet' href='<?php echo $css?>all.css'>
    <link rel='stylesheet' href='<?php echo $css?>jquery-ui.css'>
    <link rel='stylesheet' href='<?php echo $css?>jquery.selectBoxIt.css'>
    <link rel='stylesheet' href='<?php echo $css?>bootstrap-tagsinput.css'>
    <link rel='stylesheet' href='<?php echo $css?>frontend.css'>
    <title><?php getTitle() ?></title>
</head>
<body>
    <div class="upper-bar">
        <div class="container">
            <?php if (isset($_SESSION['Member'])) {?>
                <?php
                    // $userImg = getAll('*', 'users', "WHERE UserID = {$_SESSION['uid']}");
                     $userImg = getOne('*', 'users', "WHERE UserID = {$_SESSION['uid']}");    

                    if (empty($userImg['Image'])) {

                        echo "<img src='uploads\user_img\default.png' alt='img' class='my-image img-thumbnail img-circle'>";
                    } else {

                        echo "<img src='uploads\user_img\\" . $userImg['Image'] . "' alt='img' class='my-image img-thumbnail img-circle'>";

                    }
                ?>

                <!-- <img src="http://placehold.it/260x300" alt="" class="my-image  img-thumbnail img-circle"> -->
                <div class="btn-group my-info">
                    
                    <span class="btn btn-default dropdown-toggle" data-toggle='dropdown'>
                        <?php echo $sessionUser; ?>
                        <span class="caret"></span>
                    </span>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="newad.php">+ New AD</a></li>
                        <li><a href="profile.php#myads">My Ads</a></li>
                    </ul>
                    
                </div>
                <?php

            } else {?> 
            <a href='login.php?sign=up'><span class="pull-right btn btn-default log">Sign up</span></a>
            <span class="pull-right">&nbsp  &nbsp</span>
            <a href='login.php?sign=in'><span class="pull-right btn btn-default log">Login</span></a>
            <?php } ?>
            
            <?php if (isset($_SESSION['Member'])) {?>
            <a href="logout.php" class='pull-right btn btn-default log'>Logout</a>
            <?php } ?>
        </div>
    </div>
    <nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php"><?php echo lang('HOME_PAGE') ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="app-nav">
        <ul class="nav navbar-nav navbar-right">
            <?php 
            $cats = getAll('*', 'categories', 'WHERE Parent = 0', 'ORDER BY ID ASC');
            foreach ($cats as $cat) {?>
            <li class="dropdown">
                <a href="categories.php?pageid=<?php echo $cat['ID'] ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo $cat['Name'] ?>
                </a>
                <ul class="dropdown-menu">
                    <li>
                            <?php
                            $scats = getAll('*', 'categories', "WHERE Parent = {$cat['ID']}", 'ORDER BY Ordering ASC');
                            foreach ($scats as $scat) {

                                echo '<a href="categories.php?pageid=' .  $scat['ID'] . '">' . $scat['Name'] . '</a>';

                            }?>
                    </li>
                </ul>

            </li>
            <?php } ?>
        </ul>
        
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
    </nav> 
