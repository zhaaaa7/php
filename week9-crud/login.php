<?php
session_start();
require_once "util.php";

if(isset($_POST["cancel"])){
    header("Location:index.php");
    return;
}

    if ( isset($_POST["email"]) && isset($_POST["pass"]) ) {
        unset($_SESSION["name"]);  // Logout current user
        if ( strlen($_POST['pass'] )<1 ||strlen($_POST['email'])<1) {
            $_SESSION["error"] = "User Name and password are required.";
            header( 'Location: login.php' ) ;
            return;   
        }else{
            if (!strpos($_POST['email'], '@')){
            $_SESSION["error"] = "Email must have an at-sign (@)";
            header( 'Location: login.php' ) ;
            return;
            }else{
                $salt = 'XyZzy12*_';
                $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
                $check = hash('md5', $salt.$_POST['pass']);
                if ( $check == $stored_hash ) {
                    $_SESSION['name'] = $_POST['email'];
                    error_log("Login success ".$_POST['email']);
                    header("Location: index.php");
                    return;
                }else{
                    $_SESSION["error"] = "Incorrect password";
                    error_log("Login fail ".$_POST['email']." $check");
                    header( 'Location: login.php' ) ;
                    return;
                }
        }}
}
?>
<html>
<head>
    <title>Shijie Zhao</title>
</head>
<body>
<h1>Please Log In</h1>
<?php
flashMessages();
?>
<form method="post">
<label for="who">User Name</label>
<input type="text" name="email" id="who"><br/>
<label for="pass">Password</label>
<input type="text" name="pass" id="pass"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
</body>
