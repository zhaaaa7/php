<?php
require_once "pdo.php";
require_once "util.php";
session_start();

if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

if (isset($_POST['cancel'] ) ) {
    header("Location: index.php");
    return;
}

if (isset($_POST['first_name']) &&isset($_POST['last_name']) &&isset($_POST['email'])&& isset($_POST['headline'])&& isset($_POST['summary'])){

    // Data validation should go here (see add.php)
    if(strlen($_POST['first_name'])<1||strlen($_POST['last_name'])<1||strlen($_POST['email'])<1||strlen($_POST['headline'])<1||strlen($_POST['summary'])<1){
        $_SESSION["error"] = "All values are required.";
        header( 'Location: edit.php?profile_id='.$_POST['profile_id'] ) ;
        return; 
    }else{
        if (!strpos($_POST['email'], '@')){
            $_SESSION["error"] = "Email address must contain @";
            header( 'Location: edit.php?profile_id='.$_POST['profile_id'] ) ;
            return;

        }else{

            $sql = "UPDATE Profile SET first_name = :fn,
            last_name = :ln, email = :em, headline=:he, summary=:su
            WHERE profile_id = :pid";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'],
                ':pid'=>$_POST['profile_id'])
            );
            $_SESSION['success'] = 'Record updated';
            header( 'Location: index.php' ) ;
            return;
        }
    }
}
// Guardian should go here (see delete.php)


if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

$firstname = htmlentities($row['first_name']);
$lastname = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary=htmlentities($row['summary']);
$profile_id = $row['profile_id'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Shijie Zhao Edit</title>
</head>
<body>

<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Editing Profile for: ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}
flashMessages();
?>

<form method="POST">
<p>First Name:
<input type="text" name="first_name" value="<?= $firstname ?>"></p>
<p>Last Name:
<input type="text" name="last_name" value="<?= $lastname ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $email ?>"></p>
<p>Headline:
<input type="text" name="headline" value="<?= $headline ?>"></p>
<p>Summary:
</p>
<textarea name="summary" rows="6" cols="30"><?= $summary ?></textarea>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<p>
<input type="submit" name="save" value="Save"/>
<input type="submit" name= "cancel" value="Cancel"/>
</p>
</form>

</body>
</html>

