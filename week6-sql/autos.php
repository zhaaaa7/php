<?php 
require_once "pdo.php";

if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

if (isset($_POST['logout'] ) ) {
    header("Location: index.php");
    return;
}


$failure = false;
$output=false;

if (isset($_POST['make']) &&isset($_POST['mileage']) && isset($_POST['year'])){
    if(strlen($_POST['make'])<1){
	    $failure="Make is required";
    }else{
        if(is_numeric($_POST['year'])&&is_numeric($_POST['mileage'])){
			$sql = "INSERT INTO autos(make, year, mileage) VALUES (:make, :year, :mileage)";
    		$stmt = $pdo->prepare($sql);
    		$stmt->execute(array(
        	':make' => $_POST['make'],
        	':year' => $_POST['year'],
        	':mileage' => $_POST['mileage']));
        	$failure="Record inserted";
		}else{
			$failure='Mileage and year must be numeric';
	   }
    }
}

?>


<html>
<head>
    
	<title>Shijie Zhao</title>
</head>

<body>
<?php
if ( isset($_REQUEST['name']) ) {
    echo "<h1>Tracking Autos for: ";
    echo htmlentities($_REQUEST['name']);
    echo "</h1>\n";

}
if ($failure=='Record inserted') {

    echo('<p style="color: green;">'.$failure."</p>\n");
}else{
    echo('<p style="color: red;">'.$failure."</p>\n");
}
?>

<form method="POST">
<p>Make:
<input type="text" name="make" size="40"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<p>
<input type="submit" name="Add" value="Add"/>
<input type="submit" name= "logout" value="Logout"/>
</p>
</form>
<h1>Automobiles</h1>

<?php

$stmt = $pdo->query("SELECT year, make, mileage FROM autos");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {

    echo "<ul><li>";
	echo htmlentities($row['year']).' '.htmlentities($row['make']).' / '.htmlentities($row['mileage']);
    echo "</li></ul>";
}
?>

</body>
</html>