<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['name'])) {
    header("Location: lobby.php");
}
?>
<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>
        </script>
    </head> 
    <body>
        <form action="loginp.php" method="POST">
            Naam:<input type="text" name="name"></input>
            <input type="submit" value="Login"></input>
        </form>
    </body>
</html>
