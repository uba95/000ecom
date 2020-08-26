<?php
session_start();

$title = 'Items';

include 'init.php';
$approve = 'AND Approve = 1';
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
$_SESSION['iid'] = $itemid;
if (isset($_SESSION['Member'])) {
    $item = getOne('*', 'items', " WHERE itemID = {$itemid}");
    $approve = $item['MemberID'] == $_SESSION['uid'] ? '' : $approve; 
}
$sql = "SELECT items.*, categories.Name AS Cat_Name, categories.Parent, users.Username AS Member_Name FROM items
        INNER JOIN categories ON categories.ID = items.CatID
        INNER JOIN users ON users.UserID = Items.MemberID
        WHERE itemID = ? $approve";

$stmt = $connect->prepare($sql);
$stmt->execute(array($itemid));
$count = $stmt->rowCount();
$item = $stmt->fetch();

if ($count > 0) {

?>
<h1 class="text-center"><?php echo $item['Name']?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3 thumbnail item-img">
            <?php 
            if (empty($item['Image'])) {?>
                <img src="uploads\item_img\item-default.png" alt="" class="img-responsive img-thumbnail center-block">
            <?php } else { ?>
                <img src="uploads\item_img\<?php echo $item['Image'] ?>" alt="" class="img-responsive img-thumbnail center-block">
            <?php } ?>
        </div>
        <div class="col-md-9 item-info">
            <h2>
            <?php 
            echo $item['Name'];
            if (isset($_SESSION['Member'])) {
                if ($item['MemberID'] == $_SESSION['uid']) {?>

                    <a href="edit_item.php?do=edit&itemid=<?php echo $itemid ?>" class='btn btn-success'><i class='fas fa-edit iPR'></i>Edit</a>

                <?php } 
                
            }?>
            </h2>

            <p><?php echo $item['Description']?></p>
            <ul class="list-unstyled">
                <li>
                    <i class="fas fa-calendar-alt fa-fw"></i>
                    <span>Add-on Date:</span><?php echo $item['AddDate']?>
                </li>
                <li>
                    <i class="fas fa-dollar-sign fa-fw"></i>
                    <span>Price:</span> <?php echo $item['Currency'] . $item['Price']?>
                </li>
                <li>
                    <i class="fas fa-globe fa-fw"></i>
                    <span>Made In:</span> <?php echo $item['CountryMade']?>
                </li>
                <li>
                    <i class="fas fa-exclamation-triangle fa-fw"></i>
                    <span>Status:</span> 
                    <?php echo $sts = $item['Status'] == 1? 'New' : '' ?>
                    <?php echo $sts = $item['Status'] == 2? 'Like New' : '' ?>
                    <?php echo $sts = $item['Status'] == 3? 'Used' : '' ?>
                    <?php echo $sts = $item['Status'] == 4? 'Very Old' : '' ?>
                </li>

                <?php 
                if ($item['Parent'] == 0) {?>
                <li>
                    <i class="fas fa-project-diagram fa-fw"></i>
                    <span>Category:</span> <a href="categories.php?pageid=<?php echo $item['CatID']?>"><?php echo $item['Cat_Name']?></a>
                </li>
                <?php 
                } else { ?>
                <li>
                    <i class="fas fa-project-diagram fa-fw"></i>
                    <span>Category:</span>
                    <a href="categories.php?pageid=<?php echo $item['Parent']?>">
                        <?php 
                        $parent = getOne('*', 'categories', "WHERE ID = {$item['Parent']}");
                        echo  $parent['Name'];
                        ?>
                    </a>
                </li>
                <li>
                    <i class="fas fa-stream fa-fw"></i>
                    <span>Sub-category:</span> <a href="categories.php?pageid=<?php echo $item['CatID']?>"><?php echo $item['Cat_Name']?></a>
                </li>
                <?php
                } ?>
                <li>
                    <i class="fas fa-user fa-fw"></i>
                    <span>Added By:</span> <?php echo $item['Member_Name']?>
                </li>
                <li>
                    <i class="fas fa-tags fa-fw"></i>
                    <span>Tags:</span>
                    <?php
                    $tags = explode(",", $item['Tags']);
                        foreach ($tags as $tag) {
                            if (!empty($tag)) {
                            echo "<a class='btn btn-info' href='tags.php?name={$tag}'>" . $tag . '</a> ';
                            }
                        }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <hr class="custom-hr">
    <?php if (isset($_SESSION['Member'])) {?>
        <div class="row">
        <div class="col-md-offset-3">
            <div class="add-comment">
                <h3>Add Comment</h3>
                <form action="comments-items.php" id="add_comment_form" method='POST'>
                    <textarea name="comment"></textarea>
                    <!-- <input type="submit" id="add_comment_submit" class="btn btn-primary" value="Add Comment"> -->
                    <button type="submit" id="add_comment_submit" class="btn btn-primary">Add Comment</button>
                    <div id="show_comment"></div>
                </form>
                <?php include 'comments-items.php'; ?>
            </div>
        </div>
    </div>
    <?php } else {

            echo 'You Need To <a href="login.php?sign=in">Login</a> To Add Comment';
        } ?>

    <hr class="custom-hr">
    <?php

    $sql = "SELECT comments.*, users.Username FROM comments
            INNER JOIN users ON users.UserID = comments.UserID
            WHERE ItemID = ? AND Status = 1 ORDER BY CommentID DESC";
    $stmt = $connect->prepare($sql);
    $stmt->execute(array($itemid));
    $comments = $stmt->fetchAll();

    foreach ($comments as $comment) {?>
        <div class="comment-box">
            <div class="row">
                <div class="col-sm-2 text-center">
                    <img src="http://placehold.it/260x300" alt="" class="img-responsive img-thumbnail center-block img-circle">
                    <?php echo $comment['Username'] ?>
                </div>
                <div class="col-sm-6">
                    <p class="lead">
                        <?php echo $comment['Content'] ?>
                    </p>
                </div>
            </div>
    </div>
    <hr class="custom-hr">
    <?php } ?>
    
    <?php 

} else {

    echo '<div class="errors3"><div class="alert alert-danger text-center"> No Item ID Or Item Is Not Approved Yet </div></div>';
}?>

</div>


<?php include $temps.'footer.php'; ?>