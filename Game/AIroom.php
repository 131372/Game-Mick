<!DOCTYPE html>
<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="JS/tests.js"></script>
        <script type="text/javascript" src="JS/game.js"></script>
		<script type="text/javascript" src="JS/AIGame.js"></script>
        </script>

        <style>

            .tile1{
                <!--border:solid black 2px;-->
				background:transparent;
                width:48px;
                height:48px;
                display:block;
                float:left;
                text-align:center;
                top: 50%;
				z-index:2;
				position:absolute;
            }

            span{
                vertical-align: middle;
                display:inline-block;
                height:100%;
				z-index:3;
            }

            .tile2{
                <!--border:solid black 2px;-->
                width:48px;
                height:48px;
                display:block;
				z-index:2;
				position:absolute;
            }

            button{
                width:200px;
            }
        </style>
        <!-- span doesn't work :( (yet)--->
    </head> 
    <body>
        <!-- <div id="pieces2" class="all" style="height:20px"></div> -->
        <?php
	    $left=17;
	    $top=39;
        for ($i = 1; $i < 11; $i++) {
            $value = chr(65 + $i);
            for ($i1 = 1; $i1 < 11; $i1++) {
                $id1 = "x" . $i1 . "y" . $i;
                $id2 = $id1 . "b";
				$style="left:".$left."px;top:".$top."px";
                echo "<div class='tile1 all' id='$id1' style='$style'><span id='$id2'></span></div>";
				$left+=48;
            }
            echo "<div class='tile2 all'></div>";
            //create the game board
			$top+=48;
			$left=17;
        }
        ?>
		
		<div><img src="Images/Classic_board.jpg" width="500px" height="500px" style="position:absolute;top:30px;z-index:0;"></img></div>
		<img id="redflag1" src="Images/redflag.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="redbomb1" src="Images/redbomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="redbomb2" src="Images/redbomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="redbomb3" src="Images/redbomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="redbomb4" src="Images/redbomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="redbomb5" src="Images/redbomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="redbomb6" src="Images/redbomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red11" src="Images/red1.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red21" src="Images/red2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red22" src="Images/red2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red23" src="Images/red2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red24" src="Images/red2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red25" src="Images/red2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red26" src="Images/red2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red27" src="Images/red2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red28" src="Images/red2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red31" src="Images/red3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red32" src="Images/red3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red33" src="Images/red3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red34" src="Images/red3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red35" src="Images/red3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red41" src="Images/red4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red42" src="Images/red4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red43" src="Images/red4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red44" src="Images/red4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red51" src="Images/red5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red52" src="Images/red5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red53" src="Images/red5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red54" src="Images/red5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red61" src="Images/red6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red62" src="Images/red6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red63" src="Images/red6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red64" src="Images/red6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red71" src="Images/red7.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red72" src="Images/red7.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red73" src="Images/red7.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red81" src="Images/red8.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red82" src="Images/red8.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red91" src="Images/red9.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="red101" src="Images/red10.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		
		<img id="blueflag1" src="Images/blueflag.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="bluebomb1" src="Images/bluebomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="bluebomb2" src="Images/bluebomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="bluebomb3" src="Images/bluebomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="bluebomb4" src="Images/bluebomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="bluebomb5" src="Images/bluebomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="bluebomb6" src="Images/bluebomb.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue11" src="Images/blue1.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue21" src="Images/blue2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue22" src="Images/blue2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue23" src="Images/blue2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue24" src="Images/blue2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue25" src="Images/blue2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue26" src="Images/blue2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue27" src="Images/blue2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue28" src="Images/blue2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue31" src="Images/blue3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue32" src="Images/blue3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue33" src="Images/blue3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue34" src="Images/blue3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue35" src="Images/blue3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue41" src="Images/blue4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue42" src="Images/blue4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue43" src="Images/blue4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue44" src="Images/blue4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue51" src="Images/blue5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue52" src="Images/blue5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue53" src="Images/blue5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue54" src="Images/blue5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue61" src="Images/blue6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue62" src="Images/blue6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue63" src="Images/blue6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue64" src="Images/blue6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue71" src="Images/blue7.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue72" src="Images/blue7.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue73" src="Images/blue7.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue81" src="Images/blue8.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue82" src="Images/blue8.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue91" src="Images/blue9.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="blue101" src="Images/blue10.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		
		<img id="selected1" src="Images/selected1.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected2" src="Images/selected2.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected3" src="Images/selected3.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected4" src="Images/selected4.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected5" src="Images/selected5.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected6" src="Images/selected6.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected7" src="Images/selected7.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected8" src="Images/selected8.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected9" src="Images/selected9.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		<img id="selected10" src="Images/selected10.jpg" width="48px" height="48px" style="display:none;position:absolute;"></img>
		
		<?php
		for($i=1;$i<41;$i++){
			echo "<img id='redhidden$i' src='Images/redhidden.jpg' width='48px' height='48px' style='display:none;position:absolute;'></img>";
			echo "<img id='bluehidden$i' src='Images/bluehidden.jpg' width='48px' height='48px' style='display:none;position:absolute;'></img>";
		}
		?>
		
        <div id="information" style="display:none">
            <span id="owner"><?php echo $owner; ?></span>
            <span id="name"><?php echo $name; ?></span>
        </div>
        
       <!-- <div id="pieces1" class="all" style="top:530px;position:absolute;"></div> -->
        
        <div id="dummy"></div>
        <div id='si' style='top:550px;position:absolute;' class="all">
            <button onclick="game.typeChange('f')">place flag</button>
            <span id="remainingf">remaining:1</span><br>
            <button onclick="game.typeChange('b')">place bomb</button>
            <span id="remainingb">remaining:6</span><br>
            <button onclick="game.typeChange('1')">place spy(1)</button>
            <span id="remaining1">remaining:1</span><br>
            <button onclick="game.typeChange('2')">place scout(2)</button>
            <span id="remaining2">remaining:8</span><br>
            <button onclick="game.typeChange('3')">place miner(3)</button>
            <span id="remaining3">remaining:5</span><br>
            <button onclick="game.typeChange('4')">place sergeant(4)</button>
            <span id="remaining4">remaining:4</span><br>
            <button onclick="game.typeChange('5')">place lieutenant(5)</button>
            <span id="remaining5">remaining:4</span><br>
            <button onclick="game.typeChange('6')">place captain(6)</button>
            <span id="remaining6">remaining:4</span><br>
            <button onclick="game.typeChange('7')">place major(7)</button>
            <span id="remaining7">remaining:3</span><br>
            <button onclick="game.typeChange('8')">place colonel(8)</button>
            <span id="remaining8">remaining:2</span><br>
            <button onclick="game.typeChange('9')">place general(9)</button>
            <span id="remaining9">remaining:1</span><br>
            <button onclick="game.typeChange('10')">place marshal(10)</button>
            <span id="remaining10">remaining:1</span><br>
            <button onclick="game.endSetup()">end setup</button>
        </div>
        <div id="win"></div>
    </body>
</html>

