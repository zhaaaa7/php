<?php
session_start();
require_once "util.php";
require_once "pdo.php";
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
                $check = hash('md5', $salt.$_POST['pass']);
                $stmt = $pdo->prepare('SELECT user_id, name FROM users
                        WHERE email = :em AND password = :pw');
                $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ( $row !== false ) {
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['user_id'] = $row['user_id'];
                        // Redirect the browser to index.php
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
//flashMessages();
?>
<form method="post">
<label for="who">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="pass">Password</label>
<input type="password" name="pass" id="pass"><br/>
<input type="submit" value="Log In" onclick="return doValidate();">
<input type="submit" name="cancel" value="Cancel">
</form>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('pass').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
</body>
