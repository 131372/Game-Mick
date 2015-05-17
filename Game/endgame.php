<?php
require "db_config.php";
session_start();
$game_id=$_SESSION['game_id'];
query("DELETE FROM games WHERE game_id=:game",array(":game"=>$game_id),$db);
query("DELETE FROM rooms WHERE game_id=:game",array(":game"=>$game_id),$db);
?>

