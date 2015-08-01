<?php

/**
 * once the person has entered a players name, this file is requested.
 * The file is updated every 3 seconds (see refresh below).
 * 
 * It request the common shared file of names and offers other players
 * Once joined, a redirect is issued and the game can beging
 */
//include_once 'commonHeader.php';
//echo "<head><title>MatchMaking</title></head>";
echo "<head><meta http-equiv='refresh' content='3' /></head>";

include_once 'Player.php';
include_once 'dbInterface.php';
include_once 'dbSetup.php';
include_once "Content.php";
include_once 'redirect.php';


$content = new Content();   // get the content and methods on playersFile (with names)
$content->setDbInterface(new dbInterface());
$content->init();
$content->sanitize();       // drop old entries in file

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING); // request from ...

$t = $content->isPlayer($id);
//print_r($content->players);
if ($t === false) { // que, Should be in players file. Not so    
    die("Ongeldige player ($id), ga naar <a href='index.php'>deze pagina</a> om een nieuwe aan te vragen.");
}

//Haal de speler die wij willen joinen op
$joinerSha = filter_input(INPUT_GET, 'join');   // if on JOIN clicked

if ($joinerSha != '') { // yes join was given. 
    // $content->dump();
    $joinerId = $content->getIdFromSha($joinerSha); // who is this (based on sha)
    foreach ($content->players as $plid => $playerInfo) {
        // found the person and he/she does not participate in a game
        if ($joinerSha == $playerInfo->sha && $playerInfo->gameId == '') {
            // register (make gameId,...)
            $content->joinGamer($joinerId);   // register person joined

            $gameId = $content->getGameId($joinerId);
            // joiner has now a gameId, get it and attach to me
            $content->setGameId($id, $gameId);  // same session/game ID
            $naam = $content->getName($id);
            $content->schrijf();
//print_r($gameId); die();
            //Speler 1
            redirect($gameId, 1, $naam, $id,true);   // start game
            die("Joining game...");
        }
    }
}

//Kijk of jij iets kan joinen (met speler 2) (ander heeft op join gedrukt)
$player = $content->players[$content->findId($id)];
//print_r($player);
if ($player->gameId != '') {  //Ja, je moet iets joinen; ander heeft gameId bij jou ingevoerd
    $gameId = $content->getGameId($id);
    $naam = $content->getName($id);
    $joinerId = $content->getIdFromGameId($id, $gameId);

    redirect($gameId, 2, $naam, $id, true);
    die("Joining game..");
}

// not joining a game (direct or via join). show potential joiners

$zelf = $content->getPlayer($id);
echo "<script>document.title='{$zelf->name}'</script>";
echo "<b>Zichtbaar als: <span id=me>" . $zelf->name . "</span></b> (<a id=again href='index.php'>verander</a>)<hr/>";


if (count($content->players) == 0) {
    echo "<i>Er zijn geen andere spelers in de lobby</i>.";
} else {
    print "Andere spelers in de lobby:<br/><br/>";

    //Toon alle levende sessies
    echo "<table>";
    $i = 0;
    //print_r($content->sortedPlayers());
    foreach ($content->sortedPlayers() as $session => $info) {
        if ($info->id == $id) {
            continue;   // not joining with yourself
        }
        $i++;
        echo "<tr>
			<td>$i.</td>
			<td><span id=joiner$i>" . $info->name . "</span></td>
			<td><a id=join$i href='" . $_SERVER['REQUEST_URI'] . "&join=" . $info->sha . "'>JOIN</a></td>
		</tr>";
    }
    echo "</table>";
    // jij joint, jij begint het spel
}
