<?php
require_once "pdo.php";
require_once "util.php";
session_start();
    echo "<h1>Shijie Zhao's Resume Registey</h1>";
    $stmt = $pdo->prepare("SELECT profile_id FROM Profile");
    $stmt->execute();
    $cols=$stmt->fetchColumn();
    if($cols){
        echo('<table border="1">'."\n");
        $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM Profile");       
        flashMessages();
        if ( isset($_SESSION['name']) ) {
        echo "<tr><th>Name</th><th>Headline</th><th>Action</th></tr>"; 
        }else{
        echo "<tr><th>Name</th><th>Headline</th></tr>"; 
        }

    }



    if ( isset($_SESSION['name']) ) {
       while ( $row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo("<tr><td>");
        echo('<a href="view.php?profile_id='.$row["profile_id"].'">');
        echo(htmlentities($row['first_name']).' '.htmlentities($row['last_name']));
        echo("</a>");
        echo("</td><td>");
        echo(htmlentities($row['headline']));
        echo("</td><td>");
        echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
        echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
        echo("</td></tr>\n");
        
    } 

    $html1='<p><a href="logout.php">Logout</a></p><p><a href="add.php">Add New Entry</a></p>';
    }else{
        while ( $row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
        
        echo "<tr><td>";
        echo('<a href="view.php?profile_id='.$row["profile_id"].'">');
        echo(htmlentities($row['first_name']).' '.htmlentities($row['last_name']));
        echo("</a>");
        echo("</td><td>");
        echo(htmlentities($row['headline']));
        echo("</td></tr>\n");
        
    } 

    $html1='<p><a href="login.php">Please log in</a></p>';
}

?>
</table>

<html>
<head>
<title>Shijie Zhao
</title>
</head>
<body>

<?php
flashMessages();
echo $html1;


?>
</body>
</html>