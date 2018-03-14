<?php 
session_start();
require_once "pdo.php";
if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

if (isset($_POST['cancel'] ) ) {
    header("Location: view.php");
    return;
}

$failure = false;
$output=false;

if (isset($_POST['make']) &&isset($_POST['mileage']) && isset($_POST['year'])){
    if(strlen($_POST['make'])<1){
        $_SESSION["error"] = "Make is required.";
        header( 'Location: add.php' ) ;
        return; 
    }else{
        if(is_numeric($_POST['year'])&&is_numeric($_POST['mileage'])){
			$sql = "INSERT INTO autos(make, year, mileage) VALUES (:make, :year, :mileage)";
    		$stmt = $pdo->prepare($sql);
    		$stmt->execute(array(
        	':make' => $_POST['make'],
        	':year' => $_POST['year'],
        	':mileage' => $_POST['mileage']));
            $_SESSION['success'] = "Record inserted";
            header("Location: view.php");
            return;
		}else{
            $_SESSION["error"] = "Mileage and year must be numeric.";
            header( 'Location: add.php' ) ;
            return; 
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
if ( isset($_SESSION['account']) ) {
    echo "<h1>Tracking Autos for: ";
    echo htmlentities($_SESSION['account']);
    echo "</h1>\n";

}
if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
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
<input type="submit" name= "cancel" value="Cancel"/>
</p>
</form>


</body>
</html>