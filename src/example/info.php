<?php
include_once '../dbInit.php';
include_once '../dbConnection.php';
include_once 'DBIF.php';

$gameId = filter_input(INPUT_GET, 'gameId');
$db = new DBIF();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Afluistertool</title>
        <meta http-equiv="refresh" content="3" />
    </head>
    <body>gameID <?php echo gameId; ?><br>
        <?php
        echo "<table border=1>";
        foreach ($db->toArray() AS $var => $value) {
            echo "<tr><td><b>$var</b><td><pre>";
            var_dump($value);
            echo ">/pre></td></tr>";
        }
        echo "</table>";
        ?>
    </body>
</html>