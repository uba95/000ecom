<?php
session_start();

$title = 'Profile';

include 'init.php';

if (isset($_SESSION['Member'])) {

    $sql = "SELECT * FROM users WHERE Username = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(array($sessionUser));
    $info = $stmt->fetch();

?>
<h1 class="text-center">MY Profile</h1>

<div class="info block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fas fa-user fa-fw"></i>
                        <span>Name:</span> <?php echo $info['Username'] ?>
                    </li>

                    <li>
                        <i class="fas fa-envelope fa-fw"></i>
                        <span>Email:</span> <?php echo $info['Email'] ?>
                    </li>

                    <li>
                        <i class="fas fa-address-card fa-fw"></i>
                        <span>Full Name:</span> <?php echo $info['FullName'] ?>
                    </li>

                    <li>
                        <i class="fas fa-calendar-alt fa-fw"></i>
                        <span>Registration Date:</span> <?php echo $info['Date'] ?>
                    </li>

                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Favorite Category:</span> <?php  ?>
                    </li>
                </ul>
                <a href="" class="btn btn-success"><i class='fas fa-edit iPR'></i>Edit</a>
            </div>
        </div>
    </div>
</div>

<div class="ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div id="myads"class="panel-heading">My Ads</div>
            <div class="panel-body">
                <div class="row">
                    <?php
                    $items = getAll('*', 'items', "WHERE MemberID = {$info['UserID']}", 'ORDER BY ItemID DESC');
                    foreach ($items as $item) {?>

                        <div class="col-sm-6 col-md-3">
                            <div class="thumbnail item-box">
                                <?php if ($item['Approve'] == 0) {?>

                                    <div class='pend'>
                                        <span>Pending</span>    
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>

                                <?php } ?>
                                
                                <span class="price-tag"><?php echo $item['Currency'] . $item['Price']?></span>
                                <?php 
                                if (empty($item['Image'])) {?>
                                    <img src="uploads\item_img\item-default.png" alt="" class="img-responsive">
                                <?php } else { ?>
                                    <img src="uploads\item_img\<?php echo $item['Image'] ?>" alt="" class="img-responsive">
                                <?php } ?>
                                <div class="caption">
                                    <h3>
                                        <a href="items.php?itemid=<?php echo $item['ItemID']?>"><?php echo $item['Name']?></a>
                                    </h3>
                                    <span><i class='fas fa-plus itog pull-right'></i></span>
                                    <p><?php echo $item['Description']?></p>
                                    <div class="date"><?php echo $item['AddDate']?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Comments</div>
            <div class="panel-body">
                <?php
                $comments = getAll('Content', 'comments', "WHERE UserID = {$info['UserID']}");
                foreach ($comments as $comment) {

                    echo '<p>' . $comment['Content'] . '</p>';
                }?>
            </div>
        </div>
    </div>
</div>
<?php 
} else {

    header('Location: index.php');
    exit();
}
?>
<?php include $temps.'footer.php'; ?>