<?php
require "db_config.php";
session_start();
$name=$_SESSION['name'];
$game_id=$_SESSION['game_id'];
$turn=$_SESSION['turn'];
if ($turn == "you") {
    $result = query("SELECT id FROM games WHERE game_id=:game", array(":game" => $game_id), $db);
        $array = array();
        if(count($result)>0) {
            foreach ($result as $row) {
                array_push($array, $row['id']);
            }
            $max = max($array);
            $result = query("SELECT id,move FROM games WHERE game_id=:game and id=:max",array(":game"=>$game_id,":max"=>$max),$db);
            foreach($result as $row){
                if($row['id']==$max){
                    $value=$row['move']+1;
                    break;
                }
            }
            query("INSERT INTO games (game_id,turn,move) VALUES (:game,:name,:value)",array(":game"=>$game_id,":name"=>$name,":value"=>$value),$db);
        }
        else{
            query("INSERT INTO games (game_id,turn,move) VALUES (:game,:name,1)",array(":game"=>$game_id,":name"=>$name),$db);
        }
        $_SESSION['turn']="opponent";
}
?>
