## MySQL

Assignment specification: https://www.wa4e.com/assn/autosdb/

Demo: http://sjzhao.byethost33.com/week6/


## Notes
1. Strings  are not object in php 
2. Two new operators 
* '::'
* '->' method/attributes, not '.' because it is used for concatenation.
3. grant all: give the application authentication
Add a firewall : localhost/127.0.0.1, Set username & password
```php
GRANT ALL ON misc.* TO 'fred'@'localhost' IDENTIFIED BY 'zap';
GRANT ALL ON misc.* TO 'fred'@'127.0.0.1' IDENTIFIED BY ‘zap’;
```

4. 
```php
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    print_r($row);
}
```

PDO::FETCH_ASSOC comes from the pdo class, is to get the row in the form of what is stated.
When there is no more data, the $row is set to false and the loop ends
What is happening: fetch—row—check, and assign it to $row

assignment statement also have a value

```php
$y=($x=2);
echo(“y=$y”);
```

6. 
```php
if ( isset($_POST['name']) && isset($_POST['email'])&&isset($_POST['password']))
```
If it is a _GET, skip it

```php
$sql = "INSERT INTO users (name, email, password VALUES (:name, :email, :password)”;
```
Placeholders

```php
echo(“<pre>\n”.$sql.”\n</pre>\n");
```
to avoid html injection

```php
#stm->prepare ..
```
check the accuracy


7. user2.php: get and then post
8. user2del.php: $sql="DELETE FROM users WHERE user_id = :zip”
The placeholder can be anything as long as it matched the array key.

9.sql injection: you are always in.
```
$sql = "SELECT name FROM users WHERE email = '$e' AND password = '$p'";
```
 PDO prepared statement:
```
$sql = "SELECT name FROM users WHERE email = :em AND password = :pw";
```
When the statement is executed, the placeholders get replaced with the actual strings and everything is automatically escaped! ———— PDO do everything for you to avoid sql injection


11. try ... catch
```php
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
Catch error
$stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
$stmt->execute(array(":pizza" => $_GET[‘user_id']));
```
Exception catches the error and quits, so you can control what users see: 
```php
try {
    $stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
    $stmt->execute(array(":pizza" => $_GET['user_id']));
} catch (Exception $ex ) {
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
}
```
