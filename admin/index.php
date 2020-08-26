<?php

session_start();

$noNavbar= '';
$title = 'Login';

if (isset($_SESSION['Username'])) {

    header('Location: dashboard.php');
}

include 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $hpass = sha1($pass);

    $sql = "SELECT Username, Password, UserID FROM users 
            WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1 ";
    $stmt = $connect->prepare($sql);
    $stmt->execute(array($user, $hpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) {
        
        $_SESSION['Username'] = $user;
        $_SESSION['ID'] = $row['UserID'];
        header('Location: dashboard.php');
        exit();
    }
}

?>

<form class='login' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST'>
    <h1 class='text-center'>Admin Login</h1>
    <div class="text-center"><i class="fas fa-user-tie fa-5x"></i></div>
    <input class="form-control" type="text" name='user' placeholder='Username' autocomplete='off'>
    <input class="form-control" type="password" name='pass' placeholder='Password' autocomplete='new-password'>
    <input class="btn btn-primary btn-block" type="submit" value='Login'>

</form>

<?php include $temps.'footer.php'; ?>