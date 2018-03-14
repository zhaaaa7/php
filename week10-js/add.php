<?php 
session_start();
require_once "pdo.php";
require_once "util.php";

if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

if (isset($_POST['cancel'] ) ) {
    header("Location: index.php");
    return;
}

if (isset($_POST['first_name']) &&isset($_POST['last_name']) &&isset($_POST['email'])&& isset($_POST['headline'])&& isset($_POST['summary'])){
    if(strlen($_POST['first_name'])<1||strlen($_POST['last_name'])<1||strlen($_POST['email'])<1||strlen($_POST['headline'])<1||strlen($_POST['summary'])<1){
        $_SESSION["error"] = "All values are required";
        header( 'Location: add.php' ) ;
        return; 
    }else{
        if (!strpos($_POST['email'], '@')){
            $_SESSION["error"] = "Email address must contain @";
            header( 'Location: add.php' ) ;
            return;
        }else{
			$stmt = $pdo->prepare('INSERT INTO Profile(user_id, first_name, last_name, email, headline, summary) 
                   VALUES ( :uid, :fn, :ln, :em, :he, :su)');
            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
            );
            $_SESSION['success'] = "Profile added";
            header("Location: index.php");
            return;
		}
    }
}

?>


<html>
<head>
    
	<title>Shijie Zhao add page</title>
</head>

<body>
<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Tracking Autos for: ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}
flashMessages();

?>

<form method="POST">
<p>First Name:
<input type="text" name="first_name" size="40"></p>
<p>Last Name:
<input type="text" name="last_name" size="40"></p>
<p>Email:
<input type="text" name="email"></p>
<p>Headline:
<input type="text" name="headline"></p>
<p>Summary:
</p>
<textarea name="summary" rows="6" cols="30"></textarea>
<p>
<input type="submit" name="add" value="Add"/>
<input type="submit" name= "cancel" value="Cancel"/>
</p>
</form>


</body>
</html>