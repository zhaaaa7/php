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

if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt=$pdo->prepare('SELECT * FROM Profile WHERE profile_id=:prof AND user_id=:uid');
$stmt->execute(array(":prof" => $_GET['profile_id'], ":uid"=>$_SESSION['user_id']));
$profile= $stmt->fetch(PDO::FETCH_ASSOC);
if ( $profile=== false ) {
    $_SESSION['error'] = 'Could not load profile';
    header( 'Location: index.php' ) ;
    return;
}


$positions=loadPos($pdo,$_GET['profile_id']);
$schools=loadEdu($pdo,$_GET['profile_id']);

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

    $msg=validateEdu();
    if(is_string($msg)){
        $_SESSION['error']=$msg;
        header( "Location: edit.php?profile_id=".$_POST['profile_id']) ;
        return; 
    }  
  
    $stmt = $pdo->prepare('UPDATE Profile SET first_name = :fn, last_name = :ln, email = :em, headline=:he, summary=:su WHERE profile_id = :pid');
    $stmt->execute(array(
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':pid'=>$_POST['profile_id'])
        );



    // Clear out the old position entries
    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

    // Insert the position entries
    insertPositions($pdo,$_REQUEST['profile_id']);
    
    $stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
        
    insertEducations($pdo,$_REQUEST['profile_id']);

    
    $_SESSION['success'] = 'Profile updated';
    header( 'Location: index.php' ) ;
    return;
}




?>



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
<input type="text" name="first_name" value="<?= htmlentities($profile['first_name']); ?>"></p>
<p>Last Name:
<input type="text" name="last_name" value="<?= htmlentities($profile['last_name']);  ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= htmlentities($profile['email']); ?>"></p>
<p>Headline:
<input type="text" name="headline" value="<?= htmlentities($profile['headline']);  ?>"></p>
<p>Summary:
</p>
<textarea name="summary" rows="6" cols="30"><?= htmlentities($profile['summary']);  ?></textarea>
<input type="hidden" name="profile_id" value="<?= htmlentities($_REQUEST['profile_id']); ?>">




<?php
$countEdu=0;
echo('<p>Education: <input type="button" id="addEdu" value="+">'."\n");
echo('<div id="edu_fields">'."\n");
if(count($schools)>0){
    foreach ($schools as $school) {
        $countEdu++;
        echo('<div id="edu'.$countEdu.'">');
        echo('<p>Year: <input type="text" name="edu_year'.$countEdu.'" value="'.htmlentities($school['year']).'"/>
    <input type="button" value="-" onclick="$(\'#edu'.$countEdu.'\').remove();return false;">
</p>

<p>School: <input type="text" name="edu_school'.$countEdu.'" class="school" value="'.htmlentities($school['name']).'" />
</p>');
        echo("</div>");
    }
}
echo("</div></p>");

$countPos=0;
echo('<p>Position: <input type="button" id="addPos" value="+">'."\n");
echo('<div id="position_fields">'."\n");
if(count($positions)>0){
    foreach ($positions as $position) {
        $countPos++;
        echo('<div id="position'.$countEdu.'">');
        echo('<p>Year: <input type="text" name="year'.$countPos.'" value="'.htmlentities($position['year']).'"/>
    <input type="button" value="-" onclick="$(\'#position'.$countPos.'\').remove();return false;">
</p>

 <textarea name="desc'.$countPos.'" rows="8" cols="50"> '.htmlentities($position['description']).' </textarea>');
        echo("</div>");
    }
}
echo("</div></p>");
?>
<p>
<input type="submit" name="save" value="Save"/>
<input type="submit" name= "cancel" value="Cancel"/>
</p>
</form>



<script>
    countPos=<?= $countPos ?>;
    countEdu=<?= $countEdu ?>;

    $(document).ready(function(){
        window.console && console.log('Document ready called');
    
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
    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });

    });

    });

</script>
<!--HTML with substitution hot spots-->


</body>
</html>

