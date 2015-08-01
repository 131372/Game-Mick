<?php

function redirect($gameId, $playerNr, $naam, $id, $mp) {
	$appl = "example/game.php";
	$header = "Location: $appl?gameId=$gameId&player=$playerNr&naam=$naam&id=$id&mp=$mp";
        
	header($header);
}
			