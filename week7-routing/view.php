<?php 
session_start();
require_once "pdo.php";
if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

?>


<html>
<head>
    
	<title>Shijie Zhao</title>
</head>

<body>
<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Tracking Autos for: ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>
<h1>Automobiles</h1>


<?php

$stmt = $pdo->query("SELECT year, make, mileage FROM autos");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {

    echo "<ul><li>";
	echo htmlentities($row['year']).' '.htmlentities($row['make']).' / '.htmlentities($row['mileage']);
    echo "</li></ul>";
}
?>
<p>
<a href="add.php">Add New |</a>
<a href="logout.php"> Logout</a>
</p>

</body>
</html>