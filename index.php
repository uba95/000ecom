<?php
session_start();

$title = 'Homepage';

include 'init.php';?>

<div class="container homepage">
<h1 class='text-center'>Electronics Store <i class="fas fa-laptop"></i></h1>
    <div class="row masonry">
        <?php
        $items = getAll('*','items', 'WHERE Approve = 1', 'ORDER BY ItemID DESC');
        foreach ($items as $item) {?>

            <div class="col-xs-6 col-md-4 col-lg-3 masonry-brick">
                <div class="thumbnail item-box ">
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
                        <p>
                        <?php
                        
                            $desc_string = $item['Description'];
                            echo $desc_string = strlen($desc_string) > 100 ? substr($desc_string, 0, 100) . '...' : $desc_string;
                        
                        ?>
                        </p>
                        <div class="date"><?php echo $item['AddDate']?></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php
include $temps.'footer.php'; 