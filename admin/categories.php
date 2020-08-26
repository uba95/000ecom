<?php

session_start();

$title = 'Categories';


if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


if ($do == 'Manage') {

    $sortArray = array('ASC','DESC');
    $sort = isset($_GET['sort']) && in_array($_GET['sort'], $sortArray)? $_GET['sort'] : 'ASC';

    
    $sql = "SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $cats = $stmt->fetchAll();?>

    <h1 >Manage Categories</h1>
    <div class="container categories">
    <a href="?do=Add" class='btn btn-primary m-b'><i class='fas fa-plus iPR'></i>Add Category</a>
        <div class="panel panel-default">
            <div class="panel-heading">
            <i class="fas fa-cog fa-fw"></i>
                Manage Categories
                <div class="option pull-right">
                    Ordering:
                    <a href="?sort=ASC" class="<?php if ($sort == 'ASC') {echo 'active';} ?>"><i class='fas fa-chevron-circle-up'></i></a>
                    <a href="?sort=DESC" class="<?php if ($sort == 'DESC') {echo 'active';} ?>"><i class='fas fa-chevron-circle-down'></i></a>
                </div>
                <div class="option view pull-right">
                    View:
                    <span data-view='full'>Full</span>
                    <span class='active' data-view='classic'>Classic</span>
                </div>
            </div>
            <div class="panel-body">
            
                <?php
                    foreach ($cats as $cat) {?>
                        <div class='cat'>
                        <div class="hidden-buttons">
                            <a href="?do=Edit&catid=<?php echo $cat['ID']?>" class="btn btn-success"><i class="fas fa-edit iPR"></i>Edit</a>
                            <a href="?do=Delete&catid=<?php echo $cat['ID']?>" class="confirm btn btn-danger"><i class="fas fa-times iPR"></i>Delete</a>
                            </div>
                            <h3><?php echo $cat['Name']?></h3>
                            <div class="full-view">
                                <p><?php echo $desc1 = $cat['Description'] == ''? 'No Description': $cat['Description'] ?></p>
                                
                                <?php
                                $subs = getAll('*', 'categories', "WHERE Parent = {$cat['ID']}");
                                if (!empty($subs)) {?>
                                <h4>Sub Categories:</h4>
                                <ul>
                                    <?php foreach ($subs as $sub) { ?>
                                    <li><a href="?do=Edit&catid=<?php echo $sub['ID'] ?>"><?php echo $sub['Name'] ?></a></li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>

                                <span class='visibility'><?php echo $vis1 = $cat['Visibility'] == 1? 'Hidden': '' ?></span>
                                <span class='commenting'><?php echo $com1 = $cat['Allow_Comment'] == 1? 'Comments Disabled': '' ?></span>
                                <span class='advs'><?php echo $ads1 = $cat['Allow_Ads'] == 1? 'Ads Disabled': '' ?></span>
                            </div>
                        </div>

                        <hr>
                    
            <?php } ?>
            
            </div>
        </div>
    </div>
    <?php
} elseif ($do == 'Add') {?>

    <h1 class="">Add Category</h1>
    <div class="container">
        <form class="form-horizontal" action='?do=Insert' method='POST'>
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name='name' class="form-control" autocomplete='off' required='required'>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name='description' class="form-control">
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Ordering</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name='ordering' class="form-control">
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Sub-category</label>
                <div class="col-sm-10 col-md-6">
                    <?php 
                    $cats = getAll('*', 'categories', 'WHERE Parent = 0');
                    ?>
                    <select name="parent">
                        <option value="0">None</option>
                        <?php foreach ($cats as $cat) {?>
                        <option value="<?php echo $cat['ID'] ?>"><?php echo $cat['Name'] ?></option>
                        <?php } ?>
                    </select>
                    
                    
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Visible</label>
                <div class="col-sm-10 col-md-6">
                    <input type="radio" id='visYes' name='visible' value='0' checked>
                    <label for="visYes">Yes</label>
                </div>
                <div class="col-sm-10 col-md-6">
                    <input type="radio" id='visNo' name='visible' value='1'>
                    <label for="visNo">No</label>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Commenting</label>
                <div class="col-sm-10 col-md-6">
                    <input type="radio" id='comYes' name='commenting' value='0' checked>
                    <label for="comYes">Yes</label>
                </div>
                <div class="col-sm-10 col-md-6">
                    <input type="radio" id='comNo' name='commenting' value='1'>
                    <label for="comNo">No</label>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Ads</label>
                <div class="col-sm-10 col-md-6">
                    <input type="radio" id='adsYes' name='ads' value='0' checked>
                    <label for="adsYes">Yes</label>
                </div>
                <div class="col-sm-10 col-md-6">
                    <input type="radio" id='adsNo' name='ads' value='1'>
                    <label for="adsNo">No</label>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-primary btn-lg" value='Add'>
                </div>
            </div>
        </form>
    </div>

<?php 
} elseif ($do == 'Insert') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        echo '<h1>Insert Category</h1>';
        echo '<div class="container">';

        $name = $_POST['name'];
        $desc = $_POST['description'];
        $order = $_POST['ordering'];
        $parent = $_POST['parent'];
        $vis = $_POST['visible'];
        $com = $_POST['commenting'];
        $ads = $_POST['ads'];

        $check = checkIfExitInDb('Name', 'categories', $name);

        if ($check == 1) {

            $theMsg = '<div class="alert alert-danger">Sorry, This Username Is Exit</div>';
            redirectMe($theMsg);

        } else {

            $sql = "INSERT INTO categories(Name, Description, Ordering, Parent, Visibility, Allow_Comment, Allow_Ads)
                    VALUES(?, ?, ?, ?, ?, ?,?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute(array($name, $desc, $order, $parent,$vis, $com, $ads));
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

    $catid = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']) : 0;
    
    $sql = "SELECT * FROM categories WHERE ID = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute(array($catid));
    $cat = $stmt->fetch();
    $count = $stmt->rowCount();
    
        if ($count > 0) { ?>
    
            <h1 class="">Edit Category</h1>
            <div class="container">
                <form class="form-horizontal" action='?do=Update' method='POST'>
                    <input type="hidden" name='catid' value='<?php echo $catid ?>'>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name='name' class="form-control" required='required' value= <?php echo $cat['Name'] ?>>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name='description' class="form-control" value= '<?php echo $cat['Description'] ?>'>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name='ordering' class="form-control" value= <?php echo $cat['Ordering'] ?>>
                        </div>
                    </div>

                    <div class="form-group form-gruop-lg">
                        <label class="col-sm-2 control-label">Sub-category</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                $scats = getAll('*', 'categories', 'WHERE Parent = 0');
                                foreach ($scats as $scat) {?>

                                    <option
                                        value="<?php echo $scat['ID'] ?>"
                                        <?php echo $selected = $scat['ID'] == $cat['Parent'] ? 'selected' : '' ?>
                                    >
                                        <?php echo $scat['Name'] ?>
                                    </option>

                                <?php } ?>
                                
                            </select>
                        </div>

                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visible</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="radio" id='visYes' name='visible' value='0' <?php echo $valVis = $cat['Visibility'] == 0? 'checked':'' ?> >
                            <label for="visYes">Yes</label>
                        </div>
                        <div class="col-sm-10 col-md-6">
                            <input type="radio" id='visNo' name='visible' value='1' <?php echo $valVis = $cat['Visibility'] == 1? 'checked':'' ?>>
                            <label for="visNo">No</label>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="radio" id='comYes' name='commenting' value='0' <?php echo $valCom = $cat['Allow_Comment'] == 0? 'checked':'' ?>>
                            <label for="comYes">Yes</label>
                        </div>
                        <div class="col-sm-10 col-md-6">
                            <input type="radio" id='comNo' name='commenting' value='1' <?php echo $valCom = $cat['Allow_Comment'] == 1? 'checked':'' ?>>
                            <label for="comNo">No</label>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="radio" id='adsYes' name='ads' value='0' <?php echo $valAds = $cat['Allow_Ads'] == 0? 'checked':'' ?>>
                            <label for="adsYes">Yes</label>
                        </div>
                        <div class="col-sm-10 col-md-6">
                            <input type="radio" id='adsNo' name='ads' value='1' <?php echo $valAds = $cat['Allow_Ads'] == 1? 'checked':'' ?>>
                            <label for="adsNo">No</label>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary " value='Save'>
                            <a href="?do=Delete&catid=<?php echo $cat['ID']?>" class="confirm btn btn-danger">
                                <i class="fas fa-times iPR"></i>Delete Category
                            </a>
                        </div>
                    </div>

                </form>
            </div>
<?php
        }

} elseif ($do == 'Update') {

    echo '<h1>Update category</h1>';
    echo '<div class="container">';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['catid'];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $ordering = $_POST['ordering'];
        $parent = $_POST['parent'];
        $visible = $_POST['visible'];
        $commenting = $_POST['commenting'];
        $ads = $_POST['ads'];

        $sql = "UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Parent = ?,Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(array($name, $desc, $ordering, $parent,$visible, $commenting, $ads, $id));
        $count = $stmt->rowCount();
        
        $theMsg = '<div class="alert alert-success">' . $count . ' Record Updated</div>';
        redirectMe($theMsg, 'back');
    
        

    } else {

        
        echo "<div class='container'>";
        $theMsg = '<div class="alert alert-danger">Denied</div>';
        redirectMe($theMsg);
        echo "</div>";

    }
    
    echo '</div>';


} elseif ($do == 'Delete') {

    echo '<h1>Delete Category</h1>';
    echo '<div class="container">';


        $catid = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']) : 0;

        $check = checkIfExitInDb('ID', 'categories', $catid);

        if ($check > 0) {

            $sql = "DELETE FROM categories WHERE ID = ? LIMIT 1 ";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(1, $catid);
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

}

    include $temps.'footer.php';

} else {

    header('Location: index.php');
    exit();

}

