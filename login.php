<?php 
ob_start();
session_start();

$title = 'Login';

if (isset($_SESSION['Member'])) {

    header('Location: index.php');
}

include 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hpass = sha1($pass);

        $sql = "SELECT UserID, Username, Password FROM users 
                WHERE Username = ? AND Password = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(array($user, $hpass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) {
            
            $_SESSION['Member'] = $user;
            $_SESSION['uid'] = $get['UserID'];
            echo 'yes';
            header('Location: index.php');
            exit();
        }
    } else {

        $errors =  array();
        $user   =  $_POST['username'];
        $pass1  =  $_POST['password'];
        $pass2  =  $_POST['password2'];
        $email  =  $_POST['email'];
        $hpass1 =  sha1($pass1);
        $hpass2 =  sha1($pass2);


        if (isset($user)) {

            $s_user = filter_var($user, FILTER_SANITIZE_STRING);
            $errors[] = empty($s_user) ? "<strong>Username</strong> Can't Be <strong>Empty</strong>" : '';
            $errors[] = strlen($s_user) < 3 && strlen($s_user) !== 0 ? "<strong>Username</strong> Can't Be Less Than <strong>3 Characters</strong>" : '';
            $errors[] = strlen($s_user) > 10 ? "<strong>Username</strong> Can't Be More Than <strong>10 Characters</strong>" : '';    
        }

        if (isset($pass1) && isset($pass2)){

            $errors[] = empty($pass1)? "<strong>Password</strong> Can't Be <strong>Empty</strong>" : '';
            $errors[] = strlen($pass1) < 3 && strlen($pass1) !== 0 ? "<strong>Password</strong> Can't Be Less Than <strong>3 Characters</strong>" : '';
            $errors[] = strlen($pass1) > 10? "<strong>Password</strong> Can't Be More Than <strong>10 Characters</strong>" : '';    
            $errors[] = $hpass1 !== $hpass2? "There Is <strong>No Match</strong>  Between <strong>Passwords</strong>" : '';    
        }

        if (isset($email)) {

            $s_email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $v_email = filter_var($s_email, FILTER_VALIDATE_EMAIL);
            $errors[] = $v_email != true ? 'This <strong>Email</strong> Is Not<strong> Valid</strong>': '';
        }
        
        $errors =  array_filter($errors);

        if (empty($errors)) {

            $check = checkIfExitInDb('Username', 'users', $user);

            if ($check == 1) {

                $errors[] = 'Sorry, This <strong>Username</strong> Is Exit';

            } else {

                $sql = "INSERT INTO users(Username, Password, Email, RegStatus, Date)
                        VALUES(:user, :pass, :email, 0, now())";
                $stmt = $connect->prepare($sql);
                $stmt->execute(array('user'=>$user, 'pass'=>$hpass1, 'email'=>$email));
                
                $theMsg = '<div class="alert alert-success"> Wlecome <strong>' . $user . '</strong>,<br> Your Are Now A Member Of Our Site</div>';
            }
        }


        
    }
}
?>

        <?php
        $sign = isset($_GET['sign']) ? $_GET['sign'] : 'in';
        
        if ($sign == "in") { ?>

    <div class="container login-page">
            <h1 class="text-center">
                <a href="login.php?sign=in"><span class="l-selected" data-class=".login">Login</span></a> | <a href="login.php?sign=up"><span data-class=".signup">Sign Up</span></a>        
            </h1>
            <div class="text-center ilog">
                <i class="fas fa-user fa-5x"></i>
            </div>
            <form action="login.php?sign=in" method='POST' class="login">
                <input type="text" class="form-control" name="username" autocomplete="off" placeholder="Type Your Username" required>
                <input type="password" class="form-control" name="password" autocomplete="new-password" placeholder="Type Your Password" required>
                <input type="submit" class="btn btn-primary btn-block" value="Login" name='login'>
            </form>
            <!-- <div class='errors text-center'>
            </div> -->
    </div>
        <?php } 
        
        elseif ($sign == "up") { ?>

    <div class="container login-page">
            <h1 class="text-center">
                <a href="login.php?sign=in"><span data-class=".login">Login</span></a> | <a href="login.php?sign=up"><span class="s-selected" data-class=".signup">Sign Up</span></a>        
            </h1>
            <div class="text-center ilog">
                <i class="fas fa-user-plus fa-5x"></i>
            </div>
            <form action="login.php?sign=up" method='POST' class="signup" >
                <input type="text" class="form-control" name="username" autocomplete="off"
                       placeholder="Type Your Username" pattern='.{3,10}' title='Username Must Be Between 3-10 Characters' required >
                <input type="password" class="form-control" name="password" autocomplete="new-password"
                       placeholder="Type Your Password" minlength='3' required >
                <input type="password" class="form-control" name="password2" autocomplete="new-password"
                       placeholder="Type Your Password Again" minlength='3' required >
                <input type="email" class="form-control" name="email" placeholder="Type Your Email" required >
                <input type="submit" class="btn btn-success btn-block" value="Sign Up" name='signup'>
            </form>
    </div>
        <?php } ?>
        
    <div class="errors">
        
    <?php
        if(!empty($errors)) {

            foreach ($errors as $error) {

                echo '<div class="alert alert-danger text-center">' . $error . '</div>';
            }
        
        }

        if (isset($theMsg)) {
            echo $theMsg;
        }

    ?>
        
    </div>

<?php
ob_end_flush();
include $temps.'footer.php'?>