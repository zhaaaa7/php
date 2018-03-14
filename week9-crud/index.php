<?php
require_once "pdo.php";
require_once "util.php";
session_start();



if ( isset($_SESSION['name']) ) {
    echo "<h1>Welcome to Automobiles Datebase</h1>";
    $stmt = $pdo->prepare("SELECT make FROM autos");
    $stmt->execute();
    $cols=$stmt->fetchColumn();
    if(!$cols){
        echo "No rows found";
    }

    else{
        echo('<table border="1">'."\n");
        $stmt = $pdo->query("SELECT make, model, year, mileage, autos_id FROM autos");
        
        flashMessages();
        echo "<tr><th>Make</th><th>Model</th><th>Year</th><th>Mileage</th><th>Action</td></tr>";

    
    while ( $row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
        
        echo "<tr><td>";
        echo(htmlentities($row['make']));
        echo("</td><td>");
        echo(htmlentities($row['model']));
        echo("</td><td>");
        echo(htmlentities($row['year']));
        echo("</td><td>");
        echo(htmlentities($row['mileage']));
        echo("</td><td>");
        echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
        echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
        echo("</td></tr>\n");
        
    } 
}

    $html1='<p><a href="add.php">Add New Entry</a></p><p><a href="logout.php">Logout</a></p>';
}else{
    $html1='<h1>Welcome to Automobiles Datebase</h1><p><a href="login.php">Please log in</a></p><p>Attempt to go to<a href="add.php"> add data</a> without logging in.</p>';
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

echo $html1;


?>



</body>
</html>