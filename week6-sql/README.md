## MySQL

Assignment specification: https://www.wa4e.com/assn/autosdb/

Demo: http://sjzhao.byethost33.com/week6/


## Notes
1. Strings  are not object in php 
2. Two new operators 
* '::'
* '->' method/attributes, not '.' because it is used for concatenation.
3. grant all: give the application authentication
Add a firewall : localhost/127.0.0.1
```php
		GRANT ALL ON misc.* TO 'fred'@'localhost' IDENTIFIED BY 'zap';
		GRANT ALL ON misc.* TO 'fred'@'127.0.0.1' IDENTIFIED BY ‘zap’;
```
Set username & password
4. 
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    print_r($row);
}

PDO::FETCH_ASSOC  from the pdo class get the row in the form of what is stated
When there is no more data, the $row is set to false and the loop ends
What is happening: fetch—row—check, assignment statement also have a value
$y=($x=2);
echo(“y=$y”);


6. 
if ( isset($_POST['name']) && isset($_POST['email'])&&isset($_POST['password']))
If it is a _GET, skip it
$sql = "INSERT INTO users (name, email, password VALUES (:name, :email, :password)”;
Placeholders
echo(“<pre>\n”.$sql.”\n</pre>\n");--to avoid html injection
Prepare: check the accuracy


7. user2.php: get and then post
8.user2del.php: $sql="DELETE FROM users WHERE user_id = :zip”
The placeholder can be anything as long as it matched the array key.

9.sql injection: you are always in.

   $sql = "SELECT name FROM users
        WHERE email = '$e'
        AND password = '$p'";

10. PDO prepared statement:
	$sql = "SELECT name FROM users
         WHERE email = :em AND password = :pw";

When the statement is executed, the placeholders get replaced with the actual strings and everything is automatically escaped! ———— PDO do everything for you to avoid sql injection


11.
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
Catch error
$stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
$stmt->execute(array(":pizza" => $_GET[‘user_id']));

Exception: catch the error and quits

Control what users see: 
try {
    $stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
    $stmt->execute(array(":pizza" => $_GET['user_id']));
} catch (Exception $ex ) {
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
}
