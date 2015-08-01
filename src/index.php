<?php

include_once 'bootstrap.php';

if (isset($_POST['naam'])) {

    include_once 'Player.php';
    include_once 'dbInterface.php';
    include_once 'dbSetup.php';
    include_once 'Content.php';

    //Maak de naam valid
    $naam = preg_replace('/[^A-Za-z0-9]/is', '', filter_input(INPUT_POST, 'naam', FILTER_SANITIZE_STRING));

    //Niet goed snel terug.
    if (strlen($naam) <= 3) {
        header("Location: index.php");
        exit;
    }

    $p = new Player();
    $p->init($naam);
    $c = new Content();
    $c->setDbInterface(new dbInterface());
    $c->init();
    $c->add($p);

    //$c->dump(); print ":name;$naam;"; print $p->id;	$c->pf->dumpFile(); die();
    //Redirect naar de matchmaking.        
    $script = $_SERVER['PHP_SELF'];
    $len = strrpos($script, '/');
    $src = substr($script, 0, $len + 1) . "src/";
    $src = str_replace('src/src', 'src', $src);

    header("Location: {$src}matchmaking.php?id=" . $p->id);
    exit;
}
?>
<!DOCTYPE html> 
<html> 
    <head> 
        <title>De titel</title> 
    </head> 
    <body> 
        <form action="" method="post">
            Jouw naam: <input type="text" name="naam" value="" /> A-Za-z0-9 geen spaties, minimaal 3 tekens!<br/>
        </form>
    </body> 
</html>