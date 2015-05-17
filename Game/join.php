<?php
require "db_config.php";
session_start();
if(!isset($_SESSION['name'])){
    header("Location: login.php");
}
$owner=$_POST['owner'];
$name=$_SESSION['name'];
query("UPDATE rooms SET Guest=:name WHERE Owner=:owner",array(":owner"=>$owner,":name"=>$name),$db);
$result=query("SELECT game_id FROM rooms WHERE Guest=:name",array(":name"=>$name),$db);
if(count($result)==1){
    $_SESSION['game_id']=$result[0]['game_id'];
    header("Location: game.php");
}



?>

