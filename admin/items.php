<?php

session_start();

$title = 'Items';


if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


if ($do == 'Manage') {

    $sql = "SELECT items.*, categories.Name AS CatName, users.Username FROM items 
            INNER JOIN categories ON categories.ID = items.CatID
            INNER JOIN users ON users.UserID = items.MemberID ORDER BY ItemID DESC";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll();
    
    ?>

    <h1 class="">Manage Items</h1>
    <div class="container">
        <a href="items.php?do=Add" class='btn btn-primary m-b'><i class='fas fa-plus iPR'></i>Add Item</a>
        <div class='table-responsive'>
            <table class='table text-center table-bordered main-table'>
                <tr>
                    <td>Item ID</td>
                    <td>Image</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Category</td>
                    <td>Member</td>
                    <td>Add-on Date</td>
                    <td>Control</td>
                </tr>
                
                    <?php foreach ($items as $item) { ?>
                    <tr>
                        <td><?php echo $item['ItemID'] ?></td>
                        <td>
                            <?php
                                if (empty($item['Image'])) {

                                    echo "<img src='..\uploads\item_img\item-default.png' alt='img' class='img-thumbnail img-circle'>";

                                } else {

                                    echo "<img src='..\uploads\item_img\\" . $item['Image'] . "' alt='img' class='img-thumbnail img-circle'>";

                                }
                            ?>
                        </td>
                        <td><?php echo $item['Name'] ?></td>
                        <td><?php echo $item['Description'] ?></td>
                        <td><?php echo $item['Currency'] . $item['Price'] ?></td>
                        <td><?php echo $item['CatName'] ?></td>
                        <td><?php echo $item['Username'] ?></td>
                        <td><?php echo $item['AddDate'] ?></td>
                        <td>
                            <a href="items.php?do=Edit&itemid=<?php echo $item['ItemID'] ?>" class='btn btn-success'><i class='fas fa-edit iPR'></i>Edit</a>
                            <a href="items.php?do=Delete&itemid=<?php echo $item['ItemID'] ?>" class='btn btn-danger confirm'><i class='fas fa-times iPR'></i>Delete</a>
                            <?php if ($item['Approve'] == 0) {

                                echo "<a href='items.php?do=Approve&itemid=" . $item['ItemID'] . "' class='btn btn-info'><i class='fas fa-check iPR'></i>Approve</a>";

                            }?>
                        </td>
                    </tr>
                    <?php } ?>
            </table>
        </div>
    </div>

<?php
} elseif ($do == 'Add') {?>

    <h1 class="">Add Item</h1>
    <div class="container">
        <form class="form-horizontal" action='?do=Insert' method='POST' enctype='multipart/form-data'>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name='name' class="form-control" required>
                </div>
            </div>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name='description' class="form-control" required>
                </div>
            </div>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Price</label>
                <div class="col-sm-6 col-md-4">
                    <input type="text" name='price' class="form-control" required>
                </div>
                <label class="col-sm-1 control-label">Currency</label>
                <div class="col-sm-1 currency">
                <select name="currency" required>
                        <option value="">...</option>
                        <option value="$">$</option>
                        <option value="€">€</option>
                        <option value="£">£</option>
                        <option value="₪">₪</option>
                    </select>
                </div>
            </div>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Country</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name='country' class="form-control" required>
                </div>
            </div>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-10 col-md-6">
                    <select name="status" required>
                        <option value="">...</option>
                        <option value="1">New</option>
                        <option value="2">Like New</option>
                        <option value="3">Used</option>
                        <option value="4">Very Old</option>
                    </select>
                </div>
            </div>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Member</label>
                <div class="col-sm-10 col-md-6">
                    <select name="member" required>
                        <option value="">...</option>
                            <?php 
                            $users = getAll('*', 'users');
                            foreach ($users as $user) {?>
                                <option value="<?php echo $user['UserID'] ?>"><?php echo $user['Username'] ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Category</label>
                <div class="col-sm-10 col-md-6">
                    <select name="category" required>
                        <option value="">...</option>
                            <?php 
                            $cats = getAll('*', 'categories', " WHERE Parent = 0");
                            foreach ($cats as $cat) {?>
                            
                                <option value="<?php echo $cat['ID'] ?>"><?php echo $cat['Name'] ?></option>
                                <?php
                                $scats = getAll('*', 'categories', "WHERE Parent = {$cat['ID']}", 'ORDER BY Ordering ASC');

                                foreach ($scats as $scat) {?>
                                <option
                                value="<?php echo $scat['ID'] ?>"
                                data-text='<strong style="font-weight:bold;font-size:12px">
                                                &nbsp-&nbsp <?php echo $scat['Name'] ?>
                                            </strong>'>
                                </option>
                                <?php } ?> 

                            <?php } ?>    
                    </select>
                </div>
            </div>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Tags</label>
                <div class="col-sm-10 col-md-6">
                <input type="text" name='tag' class="form-control" placeholder="Use Comma ( , ) As A Separetor" data-role="tagsinput">
                </div>
            </div>

            <div class="input-group input-group-lg">
                <label class="col-sm-2 control-label">Item Image</label>
                <div class="col-sm-10 col-md-6 up-img text-center">
                    <span class='form-control btn btn-info'>
                        Upload Your Image (4MB Max) &nbsp
                        <i class="fas fa-upload"></i>
                    </span>
                    <input type="file" name='image' class="form-control">
                </div>
            </div>
            
            <div class="input-group input-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-primary btn-lg" value='Add'>
                </div>
            </div>
        </form>
    </div>

<?php 

} elseif ($do == 'Insert') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        echo '<h1>Insert Item</h1>';
        echo '<div class="container">';
    
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $currency = $_POST['currency'];
        $country = $_POST['country'];
        $status = $_POST['status'];
        $mem = $_POST['member'];
        $cat = $_POST['category'];
        $tag = $_POST['tag'];

        $img        = $_FILES['image'];
        $imgName    = $img['name'];
        $imgType    = $img['type'];
        $imgSize    = $img['size'];
        $imgTmp     = $img['tmp_name'];
        $imgExs     = array('jpeg', 'jpg', 'png');
        $imgEx      = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

        $errors = array();

        if (empty($name)) {

            $errors[] = '<u>Name Can\'t Be <strong>Empty</strong></u>';
        }

        if (empty($desc)) {

            $errors[] = '<u>Description Can\'t Be <strong>Empty</strong></u>';
        }

        if (empty($price)) {

            $errors[] = '<u>Price Can\'t Be <strong>Empty</strong></u>';
        }

        if (empty($currency)) {

            $errors[] = '<u>Currency Can\'t Be <strong>Empty</strong></u>';
        }

        if (empty($country)) {

            $errors[] = '<u>Country Can\'t Be <strong>Empty</strong></u>';
        }

        if ($status == 0) {

            $errors[] = '<u>Status Can\'t Be <strong>Empty</strong></u>';
        }

        if ($mem == 0) {

            $errors[] = '<u>Member Can\'t Be <strong>Empty</strong></u>';
        }

        if ($cat == 0) {

            $errors[] = '<u>Category Can\'t Be <strong>Empty</strong></u>';
        }

        if (!empty($imgName) && !in_array($imgEx, $imgExs)) {

            $errors[] = '<u>This Image Extension Is Not Allowed</u>';
        }

        if ($imgSize > 4*1024*1024) {

            $errors[] = '<u>Maximum Image Size Is 4MB</u>';
        }

        foreach ($errors as $error) {

            echo '<div class="alert alert-danger">' . $error . '</div>';
        }

        if (empty($errors)) {

            if (!empty($imgName)) {

                $imgName = rand(0,1000000000) . '_' . $imgName; 
                move_uploaded_file($imgTmp, '..\uploads\item_img\\' . $imgName);

            }

            $sql = "INSERT INTO items(Name, Description, Price, Currency, CountryMade, Status, AddDate, MemberID, CatID, Tags, Image)
                    VALUES(?, ?, ?, ?, ?, ?, now(), ?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute(array($name, $desc, $price, $currency, $country, $status, $mem, $cat, $tag, $imgName));
            $count = $stmt->rowCount();
            
            $theMsg = '<div class="alert alert-success">' . $count . ' Record Inserted</div>';
            redirectMe($theMsg, 'back');
        }
        

        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Denied</div>';
            redirectMe($theMsg);
            echo "</div>";
        }
    
    echo '</div>';


} elseif ($do == 'Edit') {

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']) : 0;

    $sql = "SELECT * FROM items WHERE ItemID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(array($itemid));
    $item = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) { ?>

        <h1 class="">Edit Item</h1>
        <div class="container">
            <form class="form-horizontal" action='?do=Update' method='POST' enctype='multipart/form-data'>
                <input type="hidden" name = "itemid" value="<?php echo $itemid ?>">
                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name='name' class="form-control" required value="<?php echo $item['Name'] ?>">
                    </div>
                </div>

                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name='description' class="form-control" required value="<?php echo $item['Description'] ?>">
                    </div>
                </div>

                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-6 col-md-4">
                        <input type="text" name='price' class="form-control" required value="<?php echo $item['Price'] ?>">
                    </div>
                    <label class="col-sm-1 control-label">Currency</label>
                    <div class="col-sm-1 currency">
                    <select name="currency" required> 
                            <option value="$" <?php echo $sts = $item['Currency'] == '$' ? 'selected' : '' ?>>$</option>
                            <option value="€" <?php echo $sts = $item['Currency'] == '€' ? 'selected' : '' ?>>€</option>
                            <option value="£" <?php echo $sts = $item['Currency'] == '£' ? 'selected' : '' ?>>£</option>
                            <option value="₪" <?php echo $sts = $item['Currency'] == '₪' ? 'selected' : '' ?>>₪</option>
                        </select>
                    </div>
                </div>

                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name='country' class="form-control" required value="<?php echo $item['CountryMade'] ?>">
                    </div>
                </div>

                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="1" <?php echo $sts = $item['Status'] == 1? 'selected' : '' ?>>New</option>
                            <option value="2" <?php echo $sts = $item['Status'] == 2? 'selected' : '' ?>>Like New</option>
                            <option value="3" <?php echo $sts = $item['Status'] == 3? 'selected' : '' ?>>Used</option>
                            <option value="4" <?php echo $sts = $item['Status'] == 4? 'selected' : '' ?>>Very Old</option>
                        </select>
                    </div>
                </div>

                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">...</option>
                            <?php 
                            
                            $users = getAll('*', 'users');
                            foreach ($users as $user) {?>
                                <option value="<?php echo $user['UserID'] ?>" <?php echo $memSel = $user['UserID'] == $item['MemberID']? 'selected':'' ?>><?php echo $user['Username'] ?></option>
                            <?php } ?>

                        </select>
                    </div>
                </div>

                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category" required>
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


                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name='tag' class="form-control" placeholder="Use Comma ( , ) As A Separetor"
                                data-role="tagsinput" value="<?php echo $item['Tags'] ?>">
                    </div>
                </div>

                <div class="input-group input-group-lg">
                    <label class="col-sm-2 control-label">Item Image</label>
                    <div class="col-sm-10 col-md-6 up-img text-center">
                        <span class='form-control btn btn-info'>
                            Upload Your Image (4MB Max) &nbsp
                            <i class="fas fa-upload"></i>
                        </span>
                        <input type="file" name='image' class="form-control">
                    </div>
                </div>

                <div class="input-group input-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" name='Save' class="btn btn-primary btn-lg" value='Save'>
                    </div>
                </div>
            </form>

            <?php
        
            $sql = "SELECT comments.*, users.Username FROM comments
            INNER JOIN users ON users.UserID = comments.UserID WHERE ItemID = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute(array($itemid));
            $rows = $stmt->fetchAll();
            
            if (!empty($rows)) {
            ?>

                <h1 class="icom">Manage Item Comments</h1>
                <div class='table-responsive'>
                    <table class='table text-center table-bordered main-table'>
                        <tr>
                            <td>Content</td>
                            <td>Username</td>
                            <td>Add-on Date</td>
                            <td>Control</td>
                        </tr>
                        
                            <?php foreach ($rows as $myrow) { ?>
                            <tr>
                                <td><?php echo $myrow['Content'] ?></td>
                                <td><?php echo $myrow['Username'] ?></td>
                                <td><?php echo $myrow['CommentDate'] ?></td>
                                <td>
                                    <a href="comments.php?do=Edit&comid=<?php echo $myrow['CommentID']?>" class='btn btn-success'><i class='fas fa-edit iPR'></i>Edit</a>
                                    <a href="comments.php?do=Delete&comid=<?php echo $myrow['CommentID']?>" class='btn btn-danger confirm'><i class='fas fa-times iPR'></i>Delete</a>
                                    <?php if ($myrow['Status'] == 0 ) {

                                        echo "<a href='comments.php?do=Approve&comid=" . $myrow['CommentID'] . "' class='btn btn-info'><i class='fas fa-check iPR'></i>Approve</a>";
                                    } ?>
                                </td>
                            </tr>
                            <?php } ?>

                    </table>
                </div>
            <?php } ?>
        </div>

    <?php

        } else {

            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Sorry No ID</div>';
            redirectMe($theMsg);
            echo "</div>";
    
        }


} elseif ($do == 'Update') {

    echo '<h1>Update Item</h1>';
    echo '<div class="container">';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['itemid'];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $currency = $_POST['currency'];
        $country = $_POST['country'];
        $status = $_POST['status'];
        $mem = $_POST['member'];
        $cat = $_POST['category'];
        $tag = $_POST['tag'];

        $img        = $_FILES['image'];
        $imgName    = $img['name'];
        $imgType    = $img['type'];
        $imgSize    = $img['size'];
        $imgTmp     = $img['tmp_name'];
        $imgExs     = array('jpeg', 'jpg', 'png');
        $imgEx      = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

        $item = getOne('*', 'items', "WHERE ItemID = $id");

        $errors = array();

        if (empty($name)) {

            $errors[] = '<u>Name Can\'t Be <strong>Empty</strong></u>';
        }

        if (empty($desc)) {

            $errors[] = '<u>Description Can\'t Be <strong>Empty</strong></u>';
        }

        if (empty($price)) {

            $errors[] = '<u>Price Can\'t Be <strong>Empty</strong></u>';
        }

        if (empty($currency)) {

            $errors[] = '<u>Currency Can\'t Be <strong>Empty</strong></u>';
        }

        if (empty($country)) {

            $errors[] = '<u>Country Can\'t Be <strong>Empty</strong></u>';
        }

        if ($status == 0) {

            $errors[] = '<u>Status Can\'t Be <strong>Empty</strong></u>';
        }

        if ($mem == 0) {

            $errors[] = '<u>Member Can\'t Be <strong>Empty</strong></u>';
        }

        if ($cat == 0) {

            $errors[] = '<u>Category Can\'t Be <strong>Empty</strong></u>';
        }

        if (!empty($imgName) && !in_array($imgEx, $imgExs)) {

            $errors[] = '<u>This Image Extension Is Not Allowed</u>';
        }

        if ($imgSize > 4*1024*1024) {

            $errors[] = '<u>Maximum Image Size Is 4MB</u>';
        }

        foreach ($errors as $error) {

            echo '<div class="alert alert-danger">' . $error . '</div>';
        }

        if (empty($errors)) {

            if (!empty($imgName)) {

                $imgName = rand(0,1000000000) . '_' . $imgName; 
                move_uploaded_file($imgTmp, '..\uploads\item_img\\' . $imgName);
                
            } else {

                $imgName = $item['Image'];
            }

            $sql = "UPDATE items SET Name = ?, Description = ?, Price = ?, Currency = ?, CountryMade = ?,
                                     Status = ?, MemberID = ?, CatID = ?, Tags = ?, Image = ? WHERE ItemID = ? ";
            $stmt = $connect->prepare($sql);
            $stmt->execute(array($name, $desc, $price, $currency, $country, $status, $mem, $cat, $tag, $imgName, $id ));
            $count = $stmt->rowCount();
            $theMsg = '<div class="alert alert-success">' . $count . ' Record Updated</div>';
            redirectMe($theMsg, 'back');
        }

    } else {

        
        echo "<div class='container'>";
        $theMsg = '<div class="alert alert-danger">Denied</div>';
        redirectMe($theMsg);
        echo "</div>";

    }
    
    echo '</div>';


} elseif ($do == 'Delete') {

    echo '<h1>Delete Item</h1>';
    echo '<div class="container">';


        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']) : 0;

        $check = checkIfExitInDb('ItemID', 'items', $itemid);

        if ($check > 0) {

            $sql = "DELETE FROM items WHERE ItemID = ? LIMIT 1 ";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(1, $itemid);
            $stmt->execute();

            $theMsg = '<div class="alert alert-success">' . $check . ' Record Deleted</div>';
            redirectMe($theMsg, 'back');
        } else {

            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Denied</div>';
            redirectMe($theMsg);
            echo "</div>";
    
    
        }
        
        echo '</div>';


} elseif ($do == 'Approve') {

    echo '<h1>Approve Item</h1>';
    echo '<div class="container">';


        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']) : 0;

        $check = checkIfExitInDb('ItemID', 'items', $itemid);

        if ($check > 0) {

            $sql = "UPDATE items SET Approve = 1 WHERE ItemID = ? ";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(1, $itemid);
            $stmt->execute();

            $theMsg = '<div class="alert alert-success">' . $check . ' Record Updated</div>';
            redirectMe($theMsg, 'back');
        } else {

            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Denied</div>';
            redirectMe($theMsg);
            echo "</div>";
    
    
        }
        
        echo '</div>';


}

    include $temps.'footer.php';

} else {

    header('Location: index.php');
    exit();

}

