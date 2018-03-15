## Notes

1. Cookies are written in the server, but live in the browser, and belongs to you
2. All the session cookies go away when the browser is closed.
3. 
```php
setcookie('zap', '42', time()+3600)
```

4. sessions are little bit of data on the server, it is a random large number that is difficult to guess, stored in a temporary folder on disk
5. To know which session goes into which browser ---— use cookies to select
6. Unlike GET and Post, Session goes from request to request, but get cleaned after 20 minutes stopping browsing
7. pick a number: session id, set a session cookie, store on the disk
```php
 session_start(); // all the value gone
```
8. double-posting
pop-up : “Do you want to send back again” comes from the browser, which means the browser is sending another post.To avoid double-posting, send date from post to get
9. Having a session is not the same as being logged in.
10. Flash: use unset session to ensure it show once
11. logout
```php
<?php
    session_start();
    session_destroy();
    header("Location: app.php”);
 ```
