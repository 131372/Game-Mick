<?php

//$Ipath = get_include_path();
//set_include_path($Ipath . PATH_SEPARATOR . "./");


include_once 'Player.php';
include_once 'Content.php';


$p1 = new Player(); $p1->init('player1');
$p2 = new Player(); $p2->init('player2');
$p3 = new Player(); $p3->init('player3');
$c = new Content(false);

$c->add($p1);
$c->add($p2);
$c->add($p3);

$d = new Content();
print "<pre>";
print_r($d);
