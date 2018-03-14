<?php 
session_start();
require_once "pdo.php";
require_once "util.php";
if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

if (isset($_POST['cancel'] ) ) {
    header("Location: index.php");
    return;
}


if (isset($_POST['make']) &&isset($_POST['model']) &&isset($_POST['mileage'])&& isset($_POST['year'])){
    if(strlen($_POST['make'])<1||strlen($_POST['model'])<1||strlen($_POST['mileage'])<1||strlen($_POST['year'])<1){
        $_SESSION["error"] = "All values are required.";
        header( 'Location: add.php' ) ;
        return; 
    }else{
        if(is_numeric($_POST['year'])&&is_numeric($_POST['mileage'])){
			$sql = "INSERT INTO autos(make, model, year, mileage) VALUES (:make, :model, :year, :mileage)";
    		$stmt = $pdo->prepare($sql);
    		$stmt->execute(array(
        	':make' => $_POST['make'],
            ':model' => $_POST['model'],
        	':year' => $_POST['year'],
        	':mileage' => $_POST['mileage']));
            $_SESSION['success'] = "Record added";
            header("Location: index.php");
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
<p>Make:
<input type="text" name="make" size="40"></p>
<p>Model:
<input type="text" name="model" size="40"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<p>
<input type="submit" name="Add" value="Save"/>
<input type="submit" name= "cancel" value="Cancel"/>
</p>
</form>


</body>
</html>