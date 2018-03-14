<?php
require_once "pdo.php";
require_once "util.php";
session_start();
    $stmt = $pdo->query("SELECT * FROM Profile");
    $profiles=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    



<!DOCTYPE html>
<head>
<title>Shijie Zhao index
</title>
<?php require_once('head.php');?>
</head>
<body>

<h1>Shijie Zhao's Resume Registry</h1>
<?php
flashMessages();



if(count($profiles)>0){
    echo'<table border="1">'."\n";
    echo '<tr><th>Name</th><th>Headline</th>'; 
    if(isset($_SESSION['user_id'])){
        echo '<th>Action</th>';
    }
    echo '</tr>';
        
    foreach ($profiles as $profile) {
        echo("<tr><td>");
        echo('<a href="view.php?profile_id='.$profile["profile_id"].'">');
        echo(htmlentities($profile['first_name']).' '.htmlentities($profile['last_name']));
        echo("</a>");
        echo("</td><td>");
        echo(htmlentities($profile['headline']));
        echo("</td>");
        if(isset($_SESSION['user_id'])){
            echo "<td>";
            if($_SESSION['user_id']==$profile['user_id']){
                echo('<a href="edit.php?profile_id='.$profile['profile_id'].'">Edit</a> / ');
                echo('<a href="delete.php?profile_id='.$profile['profile_id'].'">Delete</a>');
            }
            
            echo("</td>");
        }
        echo("</tr>");
           
    }
    echo("</table>");


    }else{
        echo '<p>No Rows Found</p>';
    }
if(isset($_SESSION['user_id'])){
    echo '<p><a href="logout.php">Logout</a></p>'."\n";
}else{
    echo '<p><a href="login.php">Please log in</a></p>'."\n";
}

if(isset($_SESSION['user_id'])){
    echo '<p><a href="add.php">Add New Entry</a></p>';
}




?>
</table>
</body>
</html>