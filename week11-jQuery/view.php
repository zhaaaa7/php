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

$stmt = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
/*$positionlist=array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){	
	$positionlist[]=htmlentities($row['year']).': '.htmlentities($row['description']);
}*/
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Shijie Zhao View</title>
</head>
<body>

<p>First Name: <?= $firstname ?></p>
<p>Last Name: <?= $lastname ?></p>
<p>Email: <?= $email ?></p>
<p>Headline: </p>
<p><?= $headline ?></p>
<p>Summary: </p>
<p><?= $summary ?></p>
<p>Position</p>
<ul>
	<?php 

		/*for($i=0;$i<count($positionlist);$i++){
			echo '<li>';
			echo $positionlist[$i];
			echo '</li>';
		}*/
		foreach ($positions as $position) {
                echo('<li>'.$position['year'].': '.$position['description'].'</li>');
            };

	?>
		
</ul>

<p><a href="index.php">Done</a></p>

</body>
</html>