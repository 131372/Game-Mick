<?php

require "db_config.php";
session_start();
$name = $_SESSION['name'];
/*if ($name != $_POST['name']) {
    $_SESSION['error'] = "an error has occurrred";
} else {*/
    $value=rand();
    $_SESSION['game_id']=$value;
    query("INSERT INTO rooms(Owner,Guest,game_id) VALUES (:name,0,:value)", array(":name" => $name, ":value" =>$value), $db,0);
    header("Location: room.php");
//}
?>
