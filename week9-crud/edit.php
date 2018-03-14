<?php
require_once "pdo.php";
require_once "util.php";
session_start();



if ( isset($_POST['make']) && isset($_POST['model'])&& isset($_POST['year']) && isset($_POST['mileage']) ) {

    // Data validation should go here (see add.php)
    if(strlen($_POST['make'])<1||strlen($_POST['model'])<1||strlen($_POST['mileage'])<1||strlen($_POST['year'])<1){
        $_SESSION["error"] = "All values are required.";
        header( 'Location: edit.php?autos_id='.$_POST['autos_id'] ) ;
        return; 
    }else{
        if(is_numeric($_POST['year'])&&is_numeric($_POST['mileage'])){
            $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage=:mileage
            WHERE autos_id = :autos_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':make' => $_POST['make'],
            ':model' => $_POST['model'],
            ':year' => $_POST['year'],
            ':mileage'=>$_POST['mileage'],
            ':autos_id' => $_POST['autos_id']));
            $_SESSION['success'] = 'Record updated';
            header( 'Location: index.php' ) ;
            return;
        }else{
            $_SESSION["error"] = "Mileage and year must be numeric.";
            header( 'Location: edit.php?autos_id='.$_POST['autos_id'] ) ;
            return; 
        }
    }
}

// Guardian should go here (see delete.php)


if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing autos_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shijie Zhao</title>
</head>
<body>
<h2>Edit User</h2>
<?php
    flashMessages();
?>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $make ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $model ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $year ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mileage ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
</body>
</html>

