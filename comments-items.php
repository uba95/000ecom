<?php
include 'admin/pdo.php';
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }     
@session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $userid = isset($_SESSION['uid']) ? $_SESSION['uid'] : '';
        $itemid = isset($_SESSION['iid']) ? $_SESSION['iid'] : '';
        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

        $errors = array();
        $errors[] = empty($comment) ? '<strong>Comment Can\'t Be empty</strong>' : '';
        $errors[] = strlen($comment) < 4 && !empty($comment) ? '<strong>Comment Can\'t Be Less Than 4 Characters</strong>' : '';
        $errors = array_filter($errors);
        if (empty($errors)) {
            $sql = "INSERT INTO comments(Content, Status, CommentDate, ItemID, UserID) VALUES (?, 0, NOW(), ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute(array($comment, $itemid, $userid));
            unset($_POST);
            echo $success = $stmt ? '<strong><div class="alert alert-success error">Comment Added Successfully, Wait For Approve</div></strong>' : 'Error';
        } else {
            
            foreach ($errors as $error) {

                echo '<div class="alert alert-danger error">' . $error . '</div>';
            }
        }
    }
?>
