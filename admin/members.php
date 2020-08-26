<?php

session_start();

$title = 'Members';


if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

if ($do == 'manage') { 
    
    $query = isset($_GET['page']) && $_GET['page'] == 'Pending'? 'AND RegStatus = 0' : '';
    $sql = "SELECT * FROM users WHERE GroupID != 1 $query";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    
    ?>

    <div id="manage">
        <h1 class="">Manage Members</h1>
        <div class="container">
            <a href="members.php?do=Add" class='btn btn-primary m-b'><i class='fas fa-plus iPR'></i>Add Member</a>
            <div class='table-responsive'>
                <table class='table text-center table-bordered main-table'>
                    <tr>
                        <td>ID</td>
                        <td>Image</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Register Date</td>
                        <td>Control</td>
                    </tr>
                    
                        <?php foreach ($rows as $myrow) { ?>
                        <tr>
                            <td><?php echo $myrow['UserID'] ?></td>
                            <td>
                                <?php
                                    if (empty($myrow['Image'])) {

                                        echo "<img src='..\uploads\user_img\default.png' alt='img' class='img-thumbnail img-circle'>";

                                    } else {

                                        echo "<img src='..\uploads\user_img\\" . $myrow['Image'] . "' alt='img' class='img-thumbnail img-circle'>";

                                    }
                                ?>
                            </td>
                            <td><?php echo $myrow['Username'] ?></td>
                            <td><?php echo $myrow['Email'] ?></td>
                            <td><?php echo $myrow['FullName'] ?></td>
                            <td><?php echo $myrow['Date'] ?></td>
                            <td>
                                <a id="edit-m"  href="members.php?do=edit&userid=<?php echo $myrow['UserID']?>" class='btn btn-success'><i class='fas fa-edit iPR'></i>Edit</a>
                                <a id="<?php  echo $myrow['UserID'] ?>" data-id="<?php  echo $myrow['UserID'] ?>" href="crud-member.php?do=delete&userid=<?php echo $myrow['UserID']?>" class='btn btn-danger confirm delete-m'><i class='fas fa-times iPR'></i>Delete</a>
                                <?php if ($myrow['RegStatus'] == 0 ) {

                                    echo "<a id='{$myrow['UserID']}' data-id='{$myrow['UserID']}' href='crud-member.php?do=activate&userid=" . $myrow['UserID'] . "' class='btn btn-info activate-m'><i class='fas fa-check iPR'></i>Activate</a>";
                                } ?>
                            </td>
                        </tr>
                        <?php } ?>

                </table>
            </div>
        </div>
    </div>
     
<?php }
elseif ($do == 'Add') { ?>

    <h1 class="">Add Member</h1>
    <div class="container">
        <form class="form-horizontal" action='crud-member.php?do=insert' method='POST' id="am-form" enctype='multipart/form-data'>
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name='user' class="form-control" autocomplete='off' required='required'>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10 col-md-6">
                    <input type="password" name='password' class="form-control" autocomplete='new-password' required='required'>
                    <i class='show-pass fas fa-eye fa-2x'></i>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10 col-md-6">
                    <input type="email" name='email' class="form-control" required='required'>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Full Name</label>
                <div class="col-sm-10 col-md-6">
                    <input type="text" name='full' class="form-control" required='required'>
                </div>
            </div>

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Personal Image</label>
                <div class="col-sm-10 col-md-6 up-img text-center">
                    <span class='form-control btn btn-info'>
                        Upload Your Image (4MB Max) &nbsp
                        <i class="fas fa-upload"></i>
                    </span>
                    <input type="file" name='image' class="form-control">
                </div>
            </div>

            <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary btn-lg">Add</button>
                </div>
            </div>

            <div class="col-sm-10 col-md-6 col-md-offset-2" style="padding: 0">
                <div id="show-m"></div>
            </div>
        </form>
    </div>
    
<?php }


elseif ($do == 'edit') {

$userid = isset($_GET['userid']) && is_numeric($_GET['userid'])? intval($_GET['userid']) : 0;

$sql = "SELECT * FROM users WHERE UserID = ? LIMIT 1 ";
$stmt = $connect->prepare($sql);
$stmt->execute(array($userid));
$row = $stmt->fetch();
$count = $stmt->rowCount();

    if ($count > 0) { ?>

        <h1 class="">Edit Member</h1>
        <div class="container">
            <form id="em-form" class="form-horizontal" action='crud-member.php?do=update' method='POST' enctype='multipart/form-data'>
                <input type="hidden" name='userid' value='<?php echo $userid ?>'>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name='user' class="form-control" value='<?php echo $row['Username'] ?>' autocomplete='off' required='required'>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="hidden" name='oldpassword' value='<?php echo $row['Password'] ?>'>
                        <input type="password" name='newpassword' class="form-control" autocomplete='new-password' placeholder="Leave It Empty If Don't Want To Change It">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name='email' value='<?php echo $row['Email'] ?>' class="form-control" required='required'>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name='full' value='<?php echo $row['FullName'] ?>' class="form-control" required='required'>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Personal Image</label>
                    <div class="col-sm-10 col-md-6 up-img text-center">
                        <span class='form-control btn btn-info'>
                            Upload Your Image (4MB Max) &nbsp
                            <i class="fas fa-upload"></i>
                        </span>
                        <input type="file" name='image' class="form-control">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                    </div>
                </div>

                <div class="col-sm-10 col-md-6 col-md-offset-2" style="padding: 0">
                    <div id="show-em"></div>
                </div>

            </form>
        </div>

        <?php

        } else {

            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Sorry No ID</div>';
            redirectMe($theMsg);
            echo "</div>";
    
        }

 } 

    include $temps.'footer.php';
} else {

    header('Location: index.php');
    exit();
}