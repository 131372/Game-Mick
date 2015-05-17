<!DOCTYPE html>
<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>

            setTimeout(function worker() {
               $("body").load("room.php");
                },1000);

        </script>
    </head> 
    <body>
        <?php

        function terminate() {
            header("Location:game.php");
        }

        require "db_config.php";
        session_start();
        if (!isset($_SESSION['name'])) {
            header("Location: login.php");
        }
        $name = $_SESSION['name'];
        $result = query("SELECT Guest FROM rooms WHERE Owner=:name", array(":name" => $name), $db);
        foreach($result as $row){
            if($row[0]!=="0"){
                header("Location: game.php");
            }
        }
        ?>
        waiting for another player to join you
    </body>
</html>

