<?php
if (!isset($D)) $D = '';    // for debugging
/*
print <<<EOI
<script type='text/javascript' src='//code.jquery.com/jquery-2.1.4.min.js'></script>
        
<script type='text/javascript' src='${D}inc/common/veld.js'></script>
<script type='text/javascript' src='${D}inc/common/bord.js'></script>
<script type='text/javascript' src='${D}inc/common/logic.js'></script>
<script type='text/javascript' src='${D}inc/common/bordAction.js'></script>
<script type='text/javascript' src='${D}inc/common/zet.js'></script>

<script type='text/javascript' src='${D}inc/game/game.js'></script>
<script type='text/javascript' src='${D}inc/game/gameVeld.js'></script>
<script type='text/javascript' src='${D}inc/game/gameBord.js'></script>
<script type='text/javascript' src='${D}inc/game/gameLogic.js'></script>

<script type='text/javascript' src='${D}inc/multiPlayer.js'></script>
<script type='text/javascript' src='${D}inc/singlePlayer.js'></script>
EOI;

if (! isset($testing)) {
    print "<script type='text/javascript' src='${D}inc/game/gameRun.js'></script>";
    print "<script type='text/javascript' src='${D}inc/multiplayerRun.js'></script>";
}
*/

print <<<EOI
<script type='text/javascript' src='//code.jquery.com/jquery-2.1.4.min.js'></script>
        
<script type="text/javascript" src="../../../Game/JS/game.js"></script>
EOI;
