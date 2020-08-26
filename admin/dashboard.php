<?php

session_start();

$title = 'Dashboard';

if (isset($_SESSION['Username'])) {

    include 'init.php';
    $limitMem = 5;
    $latestMem = latestItems('*', 'users', 'UserID', $limitMem);
    $limitItm = 5;
    $latestItm = latestItems('*', 'items', 'ItemID', $limitItm);
    $limitCom = 5;

    ?>

    <div class="home-stats">
        <div class="container text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-mem">
                    <i class="fas fa-users"></i>
                    <div class="info">
                        Total Members
                        <span><a href="members.php"><?php echo countItems('UserID','users'); ?></a></span>
                    </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat st-pen">
                    <i class="fas fa-user-plus"></i>
                    <div class="info">
                        Pending Members
                        <span><a href="members.php?do=Manage&page=Pending"><?php echo checkIfExitInDb('RegStatus','users', 0); ?></a></span>
                    </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat st-itm">
                    <i class="fas fa-tag"></i>
                    <div class="info">
                        Total Items
                        <span><a href="items.php"><?php echo countItems('ItemID','items'); ?></a></span>
                    </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat st-com">
                    <i class="fas fa-comment"></i>
                    <div class="info">
                        Total Comments
                        <span><a href="comments.php"><?php echo countItems('CommentID','comments'); ?></a></span>
                    </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fas fa-users"></i>
                            Latest <?php echo $limitMem ?> Registered Members
                            <i class="fas fa-minus pull-right itog"></i>
                        </div>
                        
                        <div class="panel-body">
                            <ul class='list-unstyled latestMem'>
                                <?php foreach ($latestMem as $user) {?>
                                    <li>
                                            <?php echo $user['Username']?>
                                            <a href="members.php?do=Edit&userid=<?php echo $user['UserID']?>">
                                                <span class='btn btn-success pull-right'>
                                                    <i class='fas fa-edit iPR'></i>
                                                    Edit
                                                </span>
                                            </a>
                                            <?php if ($user['RegStatus'] == 0 ) {

                                            echo "<a href='members.php?do=Activate&userid="
                                            . $user['UserID'] .
                                            "' class='btn btn-info pull-right'><i class='fas fa-check iPR'></i>Activate</a>";
                                            } ?>

                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fas fa-tag"></i>
                            Latest <?php echo $limitItm ?> Added Items
                            <i class="fas fa-minus pull-right itog"></i>
                        </div>
                        <div class="panel-body">
                            <ul class='list-unstyled latestMem'>
                                <?php foreach ($latestItm as $item) {?>
                                    <li>
                                            <?php echo $item['Name']?>
                                            <a href="items.php?do=Edit&itemid=<?php echo $item['ItemID']?>">
                                                <span class='btn btn-success pull-right'>
                                                    <i class='fas fa-edit iPR'></i>
                                                    Edit
                                                </span>
                                            </a>
                                            <?php if ($item['Approve'] == 0 ) {

                                            echo "<a href='items.php?do=Approve&itemid="
                                            . $item['ItemID'] .
                                            "' class='btn btn-info pull-right'><i class='fas fa-check iPR'></i>Approve</a>";
                                            } ?>

                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fas fa-comment"></i>
                            Latest <?php echo $limitCom ?> Added Comment
                            <i class="fas fa-minus pull-right itog"></i>
                        </div>
                        <div class="panel-body">
                            <?php 
                            $sql = "SELECT comments.*, users.Username FROM comments
                                    INNER JOIN users ON users.UserID = comments.UserID 
                                    ORDER BY CommentID DESC LIMIT $limitCom";
                            $stmt = $connect->prepare($sql);
                            $stmt->execute();
                            $comments = $stmt->fetchAll();
                        
                            foreach ($comments as $comment) {?>

                                <div class="comment-box">
                                    <span class="mname"><?php echo $comment['Username'] ?></span>
                                    <p class="mcom"><?php echo $comment['Content'] ?></p>
                                </div>


                            <?php } ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <?php
    include $temps.'footer.php';
} else {

    header('Location: index.php');
    exit();
}