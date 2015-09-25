<?php

require "db_config.php";
session_start();
$game_id = $_SESSION['game_id'];
$name = $_SESSION['name'];
$data = $_POST['waarde'];
if ($data == "obtain") {
    $result = query("SELECT id FROM games WHERE game_id=:game_id", array(":game_id" => $game_id), $db);
    if (!empty($result)) {
        $ids = array();
        foreach ($result as $row) {
            array_push($ids, $row['id']);
        }
        $result = query("SELECT move,turn FROM games WHERE id=:max_id", array(":max_id" => max($ids)), $db);		//find the last move made in this game
        foreach ($result as $row) {
            $val = "\"" . $row['turn'] . "\"";
            echo "[" . $row['move'];
            echo ",$val]";
        }			//echo it together with which player made that move
    } else {
        echo "empty";
    }
} else if ($data == "update") {
	$result = query("SELECT id FROM games WHERE game_id=:game_id",array(":game_id" => $game_id),$db);
	if (!empty($result)) {
        $ids = array();
        foreach ($result as $row) {
            array_push($ids, $row['id']);
        }
        $result = query("SELECT move,turn FROM games WHERE id=:max_id", array(":max_id" => max($ids)), $db);
        foreach ($result as $row) {
            if($row['move']=="end"){
				echo "end";
				exit;
			}
        }
    } else {
        echo "empty";
		exit;
    }			//if the game hasn't ended
    $result = query("SELECT id FROM games WHERE game_id=:game_id and turn=:name", array(":game_id" => $game_id, ":name" => $name), $db);
    if (!empty($result)) {
        $ids = array();
        foreach ($result as $row) {
            array_push($ids, $row['id']);
        }
        $result = query("SELECT move,turn FROM games WHERE id=:max_id", array(":max_id" => max($ids)), $db);
        foreach ($result as $row) {
            echo $row['move'];
        }
    } else {
        echo "empty";
    }
} else if(strstr($data,"end")){
	$winner=str_replace("end","",$data);
    query("DELETE FROM games WHERE game_id=:game_id",array(":game_id"=>$game_id),$db);
    query("INSERT INTO games(game_id,turn,move) VALUES (:game_id,:name,:move)",array(":game_id" => $game_id, ":name" => $name, ":move" =>json_encode(array("end",$winner))),$db);
}					//end the game
else if($data=="destroy"){
	query("DELETE FROM games WHERE game_id=:game_id",array(":game_id"=>$game_id),$db);
}					//destroy all data
else {
    query("INSERT INTO games(game_id,turn,move) VALUES (:game_id,:name,:move)", array(":game_id" => $game_id, ":name" => $name, ":move" => $data), $db);
}					//insert move
?>