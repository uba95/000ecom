<?php
session_start();
if (isset($_SESSION['Username'])) {

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    include 'pdo.php';
    include 'include/funcs/functions.php';

    if ($do === 'insert') {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // echo '<h1>Insert Member</h1>';
            // echo '<div class="container">';
        
            $user = $_POST['user'];
            $email = $_POST['email'];
            $fullname = $_POST['full'];
            $pass = $_POST['password'];
            $hpass = sha1($pass);

            $img        = $_FILES['image'];
            $imgName    = $img['name'];
            $imgType    = $img['type'];
            $imgSize    = $img['size'];
            $imgTmp     = $img['tmp_name'];
            $imgExs     = array('jpeg', 'jpg', 'png');
            $imgEx      = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
            // $imgEx      = strtolower(end(explode('.', $imgName)));
            
            $errors = array();

            if (empty($user) || strlen($user) < 4 || strlen($user) > 15) {

                $errors[] = '<u>Username Can\'t Be <strong>Empty</strong></u> Or <u>Less Than <strong>4</strong> Characters</u> Or <u>More Than <strong>15</strong> Characters</u>';
            }

            if (empty($pass)) {

                $errors[] = '<u>Password Can\'t Be <strong>Empty</strong></u>';
            }

            if (empty($email)) {

                $errors[] = '<u>Email Can\'t Be <strong>Empty</strong></u>';
            }

            if (empty($fullname)) {

                $errors[] = '<u>Full Name Can\'t Be <strong>Empty</strong></u>';
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

                $check = checkIfExitInDb('Username', 'users', $user);

                if ($check == 1) {

                    echo '<div class="alert alert-danger">Sorry, This Username Is Exit</div>';
                } else {

                    if (!empty($imgName)) {
                        $imgName = rand(0,1000000000) . '_' . $imgName; 
                        move_uploaded_file($imgTmp, '..\uploads\user_img\\' . $imgName);
                    }
            
                    $sql = "INSERT INTO users(Username, Password, Email, FullName, RegStatus, Date, Image)
                            VALUES(:user, :pass, :email, :fullname, 1, now(), :image)";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute(array('user'=>$user, 'pass'=>$hpass, 'email'=>$email, 'fullname'=>$fullname, 'image'=>$imgName));
                    $count = $stmt->rowCount();
                    
                    echo '<div class="alert alert-success">' . $count . ' Record Inserted</div>';
                }
            }

        } else {
            echo '<div class="alert alert-danger">Denied</div>';

        }

    }


    elseif ($do == 'update') {
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $id = $_POST['userid'];
            $user = $_POST['user'];
            $email = $_POST['email'];
            $fullname = $_POST['full'];
            $pass = empty($_POST['newpassword']) ?  $_POST['oldpassword'] : sha1($_POST['newpassword']);
    
            $img        = $_FILES['image'];
            $imgName    = $img['name'];
            $imgType    = $img['type'];
            $imgSize    = $img['size'];
            $imgTmp     = $img['tmp_name'];
            $imgExs     = array('jpeg', 'jpg', 'png');
            $imgEx      = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    
            $errors = array();
    
            if (empty(trim($user)) || strlen($user) < 4 || strlen($user) > 15) {
    
                $errors[] = '<u>Username Can\'t Be <strong>Empty</strong></u> Or <u>Less Than <strong>4</strong> Characters</u> Or <u>More Than <strong>15</strong> Characters</u>';
            }
    
            if (empty($email)) {
    
                $errors[] = '<u>Email Can\'t Be <strong>Empty</strong></u>';
            }
    
            if (empty(trim($fullname))) {
    
                $errors[] = '<u>Full Name Can\'t Be <strong>Empty</strong></u>';
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
                
                $sql = "SELECT * FROM users WHERE Username = ? AND UserID != ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute(array($user, $id));
                $check = $stmt->rowCount();
    
                if ($check == 1) {
    
                    echo '<div class="alert alert-danger">Sorry, This Username Is Exit</div>';
    
                } else {
    
                    if (!empty($imgName)) {
                        
                        $imgName = rand(0,1000000000) . '_' . $imgName; 
                        move_uploaded_file($imgTmp, '..\uploads\user_img\\' . $imgName);
    
                    } else {
    
                        $member = getOne('*', 'users', "WHERE UserID = $id");
                        $imgName = $member['Image'];
                    }
        
                    $sql = "UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ?, Image = ? WHERE UserID = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute(array($user, $email, $fullname, $pass, $imgName, $id));
                    $count = $stmt->rowCount();
                    
                    echo '<div class="alert alert-success">' . $count . ' Record Updated</div>';
                    }
            }
    
    
        } else {
    
            
            echo "<div class='container'>";
            echo'<div class="alert alert-danger">Denied</div>';
            echo "</div>";
    
        }
        
        echo '</div>';
    
    } 
    
    elseif ($do === 'delete') {
        
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid'])? intval($_GET['userid']) : 0;

        $check = checkIfExitInDb('UserID', 'users', $userid);

        if ($check > 0) {

            $sql = "DELETE FROM users WHERE UserID = ? LIMIT 1 ";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(1, $userid);
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

    elseif ($do == 'activate') {
        
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid'])? intval($_GET['userid']) : 0;

        $check = checkIfExitInDb('UserID', 'users', $userid);

        if ($check > 0) {

            $sql = "UPDATE users SET RegStatus = 1 WHERE UserID = ? ";
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(1, $userid);
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

}