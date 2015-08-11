<?php
require "db_config.php";
session_start();
$game_id=$_SESSION['game_id'];
$name=$_SESSION['name'];
$data=$_POST['waarde'];
if($data=="obtain"){
	$result=query("SELECT id FROM games WHERE game_id=:game_id",array(":game_id"=>$game_id),$db);
	if(!empty($result)){
		$ids=array();
		foreach($result as $row){
			array_push($ids,$row['id']);
		}
		$result=query("SELECT move FROM games WHERE id=:max_id",array(":max_id"=>max($ids)),$db);
		foreach($result as $row){
			echo $row['move'];
		}
	}
	else{
		echo "empty";
	}
}
else{
	query("INSERT INTO games(game_id,turn,move) VALUES (:game_id,:name,:move)",array(":game_id"=>$game_id,":name"=>$name,":move"=>json_encode($data)),$db);
}



?>