<?php

session_start();

$title = 'Comments';


if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


if ($do == 'Manage') { 
    
    $sql = "SELECT comments.*, items.Name AS Item_Name, users.Username FROM comments
            INNER JOIN items ON items.ItemID = comments.ItemID
            INNER JOIN users ON users.UserID = comments.UserID";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    
    ?>

    <h1 class="">Manage Comments</h1>
    <div class="container">
        <div class='table-responsive'>
            <table class='table text-center table-bordered main-table'>
                <tr>
                    <td>ID</td>
                    <td>Content</td>
                    <td>Item Name</td>
                    <td>Username</td>
                    <td>Add-on Date</td>
                    <td>Control</td>
                </tr>
                
                    <?php foreach ($rows as $myrow) { ?>
                    <tr>
                        <td><?php echo $myrow['CommentID'] ?></td>
                        <td><?php echo $myrow['Content'] ?></td>
                        <td><?php echo $myrow['Item_Name'] ?></td>
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
    </div>
     
<?php

} elseif ($do == 'Edit') {

$comid = isset($_GET['comid']) && is_numeric($_GET['comid'])? intval($_GET['comid']) : 0;

$sql = "SELECT * FROM comments WHERE CommentID = ?";
$stmt = $connect->prepare($sql);
$stmt->execute(array($comid));
$row = $stmt->fetch();
$count = $stmt->rowCount();

    if ($count > 0) { ?>

        <h1 class="">Edit Comment</h1>
        <div class="container">
            <form class="form-horizontal" action='?do=Update' method='POST'>
                <input type="hidden" name='comid' value='<?php echo $comid ?>'>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Content</label>
                    <div class="col-sm-10 col-md-6">
                        <textarea class='form-control' name="comment" cols="20" rows="10"><?php echo $row['Content'] ?></textarea>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" name='Save' class="btn btn-primary btn-lg" value='Save'>
                    </div>
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

 } elseif ($do == 'Update') {

    echo '<h1>Update Comment</h1>';
    echo '<div class="container">';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['comid'];
        $content = $_POST['comment'];

            $sql = "UPDATE comments SET Content = ? WHERE CommentID = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute(array($content, $id));
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

    echo '<h1>Delete Comment</h1>';
    echo '<div class="container">';


        $comid = isset($_GET['comid']) && is_numeric($_GET['comid'])? intval($_GET['comid']) : 0;

        $check = checkIfExitInDb('CommentID', 'comments', $comid);

        if ($check > 0) {

            $sql = "DELETE FROM comments WHERE CommentID = ? LIMIT 1 ";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(1, $comid);
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

    echo '<h1>Approve Comments</h1>';
    echo '<div class="container">';


        $comid = isset($_GET['comid']) && is_numeric($_GET['comid'])? intval($_GET['comid']) : 0;

        $check = checkIfExitInDb('CommentID', 'comments', $comid);

        if ($check > 0) {

            $sql = "UPDATE comments SET Status = 1 WHERE CommentID = ? ";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(1, $comid);
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