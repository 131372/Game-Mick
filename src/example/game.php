<?php
include_once './DBIF.php';

$gameId = filter_input(INPUT_GET, 'gameId');

$db = new DBIF();
$db->setGameId($gameId);    // info for this game
//
//Speler 1 is al is online geweest!
$player = filter_input(INPUT_GET, 'player');
//
//
//Defineer speler 1 en speler 2
if (!$db->isStarted()) {
    
    if ($db->isPlayerKnown($player) === false) {
        $naam = htmlspecialchars(filter_input(INPUT_GET, 'naam'));  // from URL
        $db->setPlayer($player);
        $db->setPlayersName($player, $naam);
    }
}
// either one or two players connected
//Alles is gezet, starten dus
if ($db->playersRegistered()) {
    $db->setBeurt(1);   // can also be random
    $db->initScores();
    $db->setStarted();
}
?>
<!DOCTYPE html> 
<html>
    <head> 
        <title>The Game</title> 
        <link rel="stylesheet" href="inc/style.css" type="text/css" /> 
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

        <?php
        include_once 'inc/setup.php';
        ?>
        <script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <script type="text/javascript">
            mp.beurt = "<?php echo $db->getbeurt($player); ?>";
            mp.gameId = <?php echo $gameId; ?>;
            mp.speler = "<?php echo intval($player) == 1 ? 1 : 2; ?>";
        </script>

    </head> 
    <body id='body'> 
        <div id="request"></div>
        <a href="info.php?gameId=<?php echo $gameId; ?>" target="_blank">SHOW database for this game</a>
        <div id="ServerDebug"></div>
        <div id="current">
            Jij, <span id="speler"><?php echo $db->getPlayersName($player); ?></span>, bent<br/><br/>
            <?php
// for tic tac toe
            echo "<img src='inc/" . ($player == 1 ? 'X' : 'O') . ".png' alt='' />";
            ?>
        </div>
        <div id="main"> 
            <div id="field"> 
            </div> 
        </div>
        <div id='overlay'></div>

    </body> 
</html> 