<!DOCTYPE html>
<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>

            function worker() {
                $("body").load("game.php");
            }

            setTimeout(worker, 1000);


            function increase() {
                $("#dummy").load("increase.php");
            }

            function decrease() {
                $("#dummy").load("decrease.php");
            }

            function end() {
                $("#dummy").load("endgame.php");
                window.location.href = 'lobby.php';
            }
        </script>
    </head> 
    <body>
        <?php
        require "db_config.php";
        session_start();
        if (!isset($_SESSION['name'])) {
            header("Location: login.php");
        }
        $name = $_SESSION['name'];
        $game_id = $_SESSION['game_id'];
        $result = query("SELECT id FROM games WHERE game_id=:game", array(":game" => $game_id), $db);
        $array = array();
        if (count($result) > 0) {
            foreach ($result as $row) {
                array_push($array, $row['id']);
            }
            $max = max($array);
            $result = query("SELECT id,turn,move FROM games WHERE game_id=:game and id=:max", array(":game" => $game_id, ":max" => $max), $db);
            foreach ($result as $row) {
                if ($row['id'] == $max) {
                    $value = $row['move'];
                    if ($row['turn'] == $name) {
                        echo "It is your opponent's turn<br>" . $value;
                        $_SESSION['turn'] = "opponent";
                        break;
                    } else {
                        echo "It is your turn<br>" . $value;
                        $_SESSION['turn'] = "you";
                        break;
                    }
                }
            }
        } else {
            echo "0";
            $_SESSION['turn'] = "you";
        }
        ?>

        <div id="dummy"></div>
        <button onclick="increase()">increase</button>
        <button onclick="decrease()">decrease</button>
        <button onclick="end()">end game</button>
    </body>
</html>

