<?php 
session_start();

$title = 'Tags';

include 'init.php'?>
<div class="container">
    <div class="row">
        <?php
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            echo '<h1 class="text-center">'.$name.'</h1>';
            
            $tags = getAll('*', 'items', "WHERE find_in_set('$name',Tags) AND Approve = 1", 'ORDER BY ItemID DESC');
            foreach ($tags as $tag) {?>
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo $tag['Currency'] . $tag['Price']?></span>

                        <?php 
                        if (empty($tag['Image'])) {?>
                            <img src="uploads\item_img\item-default.png" alt="">
                        <?php } else { ?>
                            <img src="uploads\item_img\<?php echo $tag['Image'] ?>" alt="">
                        <?php } ?>

                        <div class="caption">
                            <a href="items.php?itemid=<?php echo $tag['ItemID']?>">
                                <h3>
                                    <?php echo $tag['Name']?>
                                </h3>
                            </a>
                            <span><i class='fas fa-plus itog pull-right'></i></span>
                            <p><?php echo $tag['Description']?></p>
                            <div class="date"><?php echo $tag['AddDate']?></div>
                        </div>
                    </div>
                </div>
            <?php }
        } else {

                echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">Sorry No Page ID</div>';
                redirectMe($theMsg);
                echo "</div>";
            }?>
    </div>
</div>
<?php include $temps.'footer.php'?>