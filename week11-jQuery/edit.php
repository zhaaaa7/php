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
    $msg=validateProfile();
    if(is_string($msg)){
        $_SESSION['error']=$msg;
        header( "Location: edit.php?profile_id=".$_POST['profile_id']) ;
        return; 
    }
   
    $msg=validatePos();
    if(is_string($msg)){
        $_SESSION['error']=$msg;
        header( "Location: edit.php?profile_id=".$_POST['profile_id']) ;
        return; 
    }   

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



    // Clear out the old position entries
    $stmt = $pdo->prepare('DELETE FROM Position
        WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

    // Insert the position entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }
        $_SESSION['success'] = 'Record updated';
        header( 'Location: index.php' ) ;
        return;
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


$stmt = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$positionlist=array();
$positioncount=0;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){   
    $positionlist[$positioncount]['year']=htmlentities($row['year']);
    $positionlist[$positioncount]['description']=htmlentities($row['description']);
    $positionlist[$positioncount]['rank']=htmlentities($row['rank']);
    $positioncount++;
}
#var_dump($positionlist);

#print_r($positionlist[0]['year']);
?>
<script>
    var jpositionlist=<?php echo json_encode($positionlist); ?>;

</script>


<!DOCTYPE html>
<html>
<head>
    <title>Shijie Zhao Edit</title>
    <?php require_once "head.php";?>
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

Position:<input type="submit" id="addPos" value="+">
<div id="position_fields">




</div>
</p>
<p>
<input type="submit" name="save" value="Save"/>
<input type="submit" name= "cancel" value="Cancel"/>
</p>
</form>
<script>

    $(document).ready(function(){
        window.console && console.log('Document ready called');
    countPos=0;
    $.each(jpositionlist,function(index,value){ 
        countPos++;
        console.log(index + " : " + value);
        jpl=[];
        $.each(value, function(index2, value2) {
                console.log(index2 + " : " + value2);  
        jpl.push(value2);
        }); 

        console.log(jpl[0],jpl[1],jpl[2]);
        $('#position_fields').append(
            '<div id="position'+jpl[2]+'"> \
            <p>Year: <input type="text" name="year'+jpl[2]+'" value="'+jpl[0]+'" />\
            <input type="button" value="-" \
                onclick="$(\'#position'+jpl[2]+'\').remove();countPos--;return false;"></p>\
            <textarea name="desc'+jpl[2]+'" row="8" cols="50">'+jpl[1]+'</textarea>\
            </div>');
    console.log("the list have"+countPos);
    });
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" />\
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p>\
            <textarea name="desc'+countPos+'" row="8" cols="50"></textarea>\
            </div>');
        });
    });

</script>

</body>
</html>

