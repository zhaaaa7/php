<?php
require_once "pdo.php";
session_start();
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



?>
<!DOCTYPE html>
<html>
<head>
	<title>Shijie Zhao View</title>
</head>
<body>

<p>First Name: <?= $firstname ?></p>
<p>First Name: <?= $lastname ?></p>
<p>First Name: <?= $email ?></p>
<p>First Name: </p>
<p><?= $headline ?></p>
<p>First Name: </p>
<p><?= $summary ?></p>

<p><a href="index.php">Done</a></p>

</body>
</html>