<!DOCTYPE html>
<html>
    <head>
        <style>
            form{
                display:inline;
            }
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>

          setTimeout(function worker() {
               $("body").load("lobby.php");
                },1000)

        </script>
        <?php
        session_start();
        if (!isset($_SESSION['name'])) {
            header("Location: login.php");
        }
        $name = $_SESSION['name'];
        if (isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        }
        ?>
    </head> 
    <body>
        <form action="lobbyp.php" method="post">
           <!-- <input type="hidden" name="name"  value="<?php //echo $name  ?>">  -->
            <input type="submit" value="create room">
        </form>
        <br>
        <?php
        require "db_config.php";
        $result = query("SELECT * FROM rooms", array(), $db);
        echo "<table>";
        foreach ($result as $row) {
            $owner = $row['Owner'];
            echo "<tr><td>".$owner . "</td><td><form action='join.php' method='post'><input type='hidden' name='owner' value='$owner'><input type='submit' value='join'></form></td></tr>";
        }
        echo "</table>";
        ?>
		<form action="logout.php" method="post">
			<input type="submit" value="logout">
		</form>
		<form action="AIroom.php" method="post">
			<input type="submit" value="AI game">
		</form>
    </body>
	
    <?php
    ?>
</html>
<!-- jasmine (unit testen javascript)
jquery
JSON

ajax post get