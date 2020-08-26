<?php 
session_start();

$title = 'Categories';

include 'init.php'?>
<div class="container">
    <div class="row">
        <?php
        if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
            $pageid = intval($_GET['pageid']);
            $items = getAll('items.*, categories.Parent', 'items',
                            "INNER JOIN categories ON categories.ID = items.CatID
                            WHERE Approve = 1 AND CatID = {$pageid} OR Parent = {$pageid}
                             ", 'ORDER BY ItemID DESC');
            $cat = getOne('*', 'categories', "WHERE ID = {$pageid}");
            echo '<h1 class="text-center">'.$cat['Name'].'</h1>';
            foreach ($items as $item) {?>

                <div class="col-xs-6 col-md-4 col-lg-3">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo $item['Currency'] . $item['Price']?></span>
                        
                        <?php 
                        if (empty($item['Image'])) {?>
                            <img src="uploads\item_img\item-default.png" alt="">
                        <?php } else { ?>
                            <img src="uploads\item_img\<?php echo $item['Image'] ?>" alt="">
                        <?php } ?>

                        <div class="caption">
                            <a href="items.php?itemid=<?php echo $item['ItemID']?>">
                                <h3>
                                    <?php echo $item['Name']?>
                                </h3>
                            </a>
                            <span><i class='fas fa-plus itog pull-right'></i></span>
                            <p><?php echo $item['Description']?></p>
                            <div class="date"><?php echo $item['AddDate']?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else {

                echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">Sorry No Page ID</div>';
                redirectMe($theMsg);
                echo "</div>";
            }?>
    </div>
</div>
<?php include $temps.'footer.php'?>