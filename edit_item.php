<?php
ob_start();
session_start();

$title = 'Edit Ad';

include 'init.php';

if (isset($_SESSION['Member'])) {

    $do = isset($_GET['do']) ? $_GET['do'] : 'edit';

    if ($do == 'edit') {

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
         $item = getOne('*', 'items', "WHERE ItemID = {$itemid}");
         $count = getOne('*', 'items', "WHERE ItemID = {$itemid}" , '', 'count');

        if ($count > 0 && $item['MemberID'] == $_SESSION['uid']) {

            ?>
            <h1 class="text-center">Edit Ad</h1>

            <div class="newad block">
                <div class="container">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Edit Ad</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <form class="form-horizontal" action='?do=update' method='POST' enctype='multipart/form-data'>
                                        <input type="hidden" name='itemid' value='<?php echo $itemid ?>'>
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input type="text" name='name' class="form-control live" data-live='.live-name'
                                                        pattern='.{5,30}' title='Name Must Be Between 5-20 Characters'   value = '<?php echo $item['Name'] ?>' >
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input type="text" name='description' class="form-control live" data-live='.live-desc'
                                                        pattern='.{10,60}' title='Description Must Be Between 10-50 Characters'   value = '<?php echo $item['Description'] ?>'>
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Price</label>
                                            <div class="col-sm-5 col-md-4">
                                                <input type="text" name='price' class="form-control live" data-live='.live-price'
                                                        pattern='.{1,10}' title='Price Must Be Between 1-10 Digitis'   value = '<?php echo $item['Price'] ?>'>
                                            </div>
                                            <label class="col-sm-3 control-label">Currency</label>
                                            <div class="col-sm-2 currency">
                                            <select name="currency" class='live' data-live='.live-currency' required> 
                                                    <option value="$" <?php echo $sts = $item['Currency'] == '$' ? 'selected' : '' ?>>$</option>
                                                    <option value="€" <?php echo $sts = $item['Currency'] == '€' ? 'selected' : '' ?>>€</option>
                                                    <option value="£" <?php echo $sts = $item['Currency'] == '£' ? 'selected' : '' ?>>£</option>
                                                    <option value="₪" <?php echo $sts = $item['Currency'] == '₪' ? 'selected' : '' ?>>₪</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Country</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input type="text" name='country' class="form-control"
                                                        pattern='.{2,20}' title='Country Must Be Between 2-20 Characters'   value = '<?php echo $item['CountryMade'] ?>' >
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-10 col-md-9">
                                                <select name="status"  >
                                                    <option value="0" <?php echo $sel = $item['Status'] == 0 ? 'selected' : ''; ?> >...</option>
                                                    <option value="1" <?php echo $sel = $item['Status'] == 1 ? 'selected' : ''; ?> >New</option>
                                                    <option value="2" <?php echo $sel = $item['Status'] == 2 ? 'selected' : ''; ?> >Like New</option>
                                                    <option value="3" <?php echo $sel = $item['Status'] == 3 ? 'selected' : ''; ?> >Used</option>
                                                    <option value="4" <?php echo $sel = $item['Status'] == 4 ? 'selected' : ''; ?> >Very Old</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Category</label>
                                            <div class="col-sm-10 col-md-9"   >
                                                <select name="category">
                                                    <option value="">...</option>
                                                    <?php 
                                                    $cats = getAll('*', 'categories', " WHERE Parent = 0");
                                                    foreach ($cats as $cat) {?>
                                                    
                                                        <option value="<?php echo $cat['ID'] ?>"
                                                        
                                                        <?php echo $catSel = $cat['ID'] == $item['CatID']? 'selected':'' ?>>
                                                            <?php echo $cat['Name'] ?>
                                                        </option>
                                                        <?php
                                                        $scats = getAll('*', 'categories', "WHERE Parent = {$cat['ID']}", 'ORDER BY Ordering ASC');

                                                        foreach ($scats as $scat) {?>
                                                        <option
                                                        value="<?php echo $scat['ID'] ?>"
                                                        <?php echo $catSel = $scat['ID'] == $item['CatID']? 'selected':'' ?>
                                                        data-text='<strong style="font-weight:bold;font-size:12px">
                                                                        &nbsp-&nbsp <?php echo $scat['Name'] ?>
                                                                    </strong>'>
                                                        </option>
                                                        <?php } ?> 

                                                    <?php } ?>    
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Tags</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input type="text" name='tag' class="form-control" placeholder="Use Comma ( , ) As A Separetor" data-role="tagsinput" value = '<?php echo $item['Tags'] ?>'>
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Personal Image</label>
                                            <div class="col-sm-10 col-md-9 up-img text-center">
                                                <span class='form-control btn btn-info'>
                                                    Upload Your Image (4MB Max) &nbsp
                                                    <i class="fas fa-upload"></i>
                                                </span>
                                                <input type="file" name='image' class="form-control">

                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <input type="submit" class="btn btn-primary" value='Save'>
                                                <a href="?do=delete&itemid=<?php echo $item['ItemID']?>" class="confirm btn btn-danger">
                                                    <i class="fas fa-times iPR"></i>Delete Ad
                                                </a>

                                            </div>
                                        </div>

                                    </form>

                                </div>
                                <div class="col-md-offset-1 col-md-3">
                                    <div class="thumbnail item-box live-prev">
                                            <span class="price-tag">
                                                <span class='live-currency'><?php echo $item['Currency'] ?></span>
                                                <span class='live-price'><?php echo $item['Price'] ?></span>
                                            </span>
                                            <?php 
                                            if (empty($item['Image'])) {?>
                                                <img src="uploads\item_img\item-default.png" alt="" class="img-responsive">
                                            <?php } else { ?>
                                                <img src="uploads\item_img\<?php echo $item['Image'] ?>" alt="" class="img-responsive">
                                            <?php } ?>
                                        <div class="caption">
                                            <h3>
                                                <span class='live-name'><?php echo $item['Name'] ?></span>
                                            </h3>
                                            <span><i class='fas fa-minus itog pull-right'></i></span>
                                            <p class='live-desc'><?php echo $item['Description'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php

        } else {

            echo "<div class='container errors'>";
            $theMsg = '<div class="alert alert-danger">Sorry No Item ID</div>';
            redirectMe($theMsg);
            echo "</div>";

        }


    } elseif ($do == 'update') {

        echo '<h1 class="text-center">Update Item</h1>';
        echo '<div class="container">';    

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = filter_var($_POST['itemid'], FILTER_SANITIZE_NUMBER_INT);
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $currency = filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tag = filter_var($_POST['tag'], FILTER_SANITIZE_STRING);

            $img        = $_FILES{'image'};
            $imgName    = $img['name'];
            $imgType    = $img['type'];
            $imgSize    = $img['size'];
            $imgTmp     = $img['tmp_name'];
            $imgExs     = array('jpeg', 'jpg', 'png');
            $imgEx      = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

            $item = getOne('*', 'items', "WHERE ItemID = $id");

            $errors = array();

            $errors[] = empty($name) ? "<strong>Name Can't Be  Empty </strong>" : '';
            $errors[] = strlen($name) < 5 && strlen($name) !== 0 ? " <strong>Name Can't Be Less Than  5 Characters </strong>" : '';
            $errors[] = strlen($name) > 30 ? " <strong>Name  Can't Be More Than  20 Characters </strong>" : '';
            
            $errors[] = empty($desc) ? " <strong>Description  Can't Be  Empty </strong>" : '';
            $errors[] = strlen($desc) < 10 && strlen($desc) !== 0 ? " <strong>Description  Can't Be Less Than  10 Characters</strong> " : '';
            $errors[] = strlen($desc) > 60 ? "<strong> Description  Can't Be More Than  50 Characters </strong>" : '';
            
            $errors[] = empty($price) ? " <strong>Price  Can't Be  Empty </strong>" : '';
            $errors[] = strlen($price) < 1 && strlen($desc) !== 0 ? " <strong>Price  Can't Be Less Than  1 Digits </strong>" : '';
            $errors[] = strlen($price) > 10 ? " <strong>Price  Can't Be More Than  10 Digits </strong>" : '';

            $errors[] = empty($currency) ? " <strong>Currency  Can't Be  Empty </strong>" : '';

            $errors[] = empty($country) ? " <strong>Country  Can't Be  Empty </strong>" : '';
            $errors[] = strlen($country) < 2 && strlen($country) !== 0 ? "<strong> Country  Can't Be Less Than  2 Characters </strong>" : '';
            $errors[] = strlen($country) > 20 ? " <strong>Country  Can't Be More Than  20 Characters </strong>" : '';
            
            $errors[] = empty($status) ? "<strong> Status  Can't Be  Empty</strong> " : '';

            $errors[] = empty($category) ? " <strong>Category  Can't Be  Empty</strong> " : '';

            $errors[] = !empty($imgName) && !in_array($imgEx, $imgExs) ? " <strong>This Image Extension Is Not Allowed</strong> " : '';
            $errors[] = $imgSize > 4*1024*1024 ? " <strong>Maximum Image Size Is 4MB</strong> " : '';


            $errors = array_filter($errors);

            if (!empty($errors)) {

                foreach ($errors as $error) {

                    echo '<div class="errors"><div class="alert alert-danger text-center">' . $error . '</div></div>';
                }
            }
            

            if (empty($errors)) {

                if (!empty($imgName)) {

                    $imgName = rand(0,1000000000) . '_' . $imgName; 
                    move_uploaded_file($imgTmp, 'uploads\item_img\\' . $imgName);

                } else {
                    
                    $imgName = $item['Image'];
                }
    
                $sql = "UPDATE items SET Name = ?, Description = ?, Price = ?, Currency = ?, CountryMade = ?, Status = ?, MemberID = ?,
                        CatID = ?, Tags = ?, Image = ? WHERE ItemID = ?";

                $stmt = $connect->prepare($sql);
                $stmt->execute(array($name, $desc, $price, $currency, $country, $status, $_SESSION['uid'], $category, $tag, $imgName, $id));

                if ($stmt) {
                    
                    $theMsg = '<div class="alert alert-success">
                                    <i class="fas fa-check"></i> &nbsp Item Is Updated
                                </div>';
                    redirectMe($theMsg, 'profile.php#myads', 4, 'To Your Ads');

                }
                
            }
            
            echo '</div>';

        } else {

            echo 'no';
        }


    } elseif ($do == 'delete') {

        echo '<h1 class="text-center">Delete Item</h1>';
        echo '<div class="container">';


            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']) : 0;
            $sql = "SELECT * FROM items WHERE ItemID = {$itemid}";
            $stmt = $connect->prepare($sql);
            $stmt->execute();
            $item = $stmt->fetch();    

            $check = checkIfExitInDb('ItemID', 'items', $itemid);

            if ($check > 0 && $item['MemberID'] == $_SESSION['uid']) {

                $sql = "DELETE FROM items WHERE ItemID = ? LIMIT 1 ";
                $stmt = $connect->prepare($sql);
                $stmt->bindParam(1, $itemid);
                $stmt->execute();

                $theMsg = '<div class="alert alert-success"> Item Is Deleted </div>';
                redirectMe($theMsg, 'profile.php#myads', 4, 'To Your Ads');
            } else {

                echo "<div class='container errors'>";
                $theMsg = '<div class="alert alert-danger">Sorry No Item ID</div>';
                redirectMe($theMsg);
                echo "</div>";
        
        
            }
            
            echo '</div>';
    }

} else {

    header('Location: index.php');
    exit();
}

ob_end_flush();
include $temps.'footer.php';