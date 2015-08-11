<!DOCTYPE html>
<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="JS/game.js">

        </script>
		
		<style>
		
		.tile1{
			border:solid black 2px;
			width:40px;
			height:40px;
			display:block;
			float:left;
			text-align:center;
			top: 50%;
		}
		
		span{
			vertical-align: middle;
			display:inline-block;
			height:100%;
		}
		
		.tile2{
			border:solid black 2px;
			width:40px;
			height:40px;
			display:block;
		}
		
		button{
			width:200px;
		}
		</style>
		<!-- span doesn't work :( (yet)--->
    </head> 
    <body>
        <?php
        require "db_config.php";
        session_start();
        if (!isset($_SESSION['name'])) {
            header("Location: login.php");
        }
        $name = $_SESSION['name'];
		$owner= $_SESSION['owner'];
        $game_id = $_SESSION['game_id'];
        $result = query("SELECT id FROM games WHERE game_id=:game", array(":game" => $game_id), $db);
        $array = array();
        if (count($result) > 0) {
            foreach ($result as $row) {
                array_push($array, $row['id']);
            }
            $max = max($array);
            $result = query("SELECT id,turn,move FROM games WHERE game_id=:game and id=:max", array(":game" => $game_id, ":max" => $max), $db);
            foreach ($result as $row) {
                if ($row['id'] == $max) {
                    $value = $row['move'];
                    if ($row['turn'] == $name) {
                        echo "It is your opponent's turn<br>";// . $value;
                        $_SESSION['turn'] = "opponent";
                        break;
                    } else {
                        echo "It is your turn<br>";// . $value;
                        $_SESSION['turn'] = "you";
                        break;
                    }
                }
            }
        } else {
            echo "0";
            $_SESSION['turn'] = "you";
        }
		echo "<br>";
		for($i=1;$i<11;$i++){
			$value=chr(65+$i);
			for($i1=1;$i1<11;$i1++){
				$id1="x".$i1."y".$i;
				$id2=$id1."b";
				echo "<div class='tile1' id='$id1'><span id='$id2'></span></div>";
			}
			echo "<div class='tile2'></div>";
			//create the game board
		}
        ?>

		<div id="information" style="display:none">
			<span id="owner"><?php echo $owner;?></span>
			<span id="name"><?php echo $name;?></span>
		</div>
		
        <div id="dummy"></div>
		<?php
			if($name==$owner){
				echo "<div id='si'>";
			}
			else{
				echo "<div id='si' style='display:none'>";
			}		//hide the setup interface unless this player is the owner of this room and is thus supposed to setup his side of the board first
		?>
			<button onclick="boardSetup.typeChange('f')">place flag</button>
			<span id="remainingf">remaining:1</span><br>
			<button onclick="boardSetup.typeChange('b')">place bomb</button>
			<span id="remainingb">remaining:6</span><br>
			<button onclick="boardSetup.typeChange('1')">place spy(1)</button>
			<span id="remaining1">remaining:1</span><br>
			<button onclick="boardSetup.typeChange('2')">place scout(2)</button>
			<span id="remaining2">remaining:8</span><br>
			<button onclick="boardSetup.typeChange('3')">place miner(3)</button>
			<span id="remaining3">remaining:5</span><br>
			<button onclick="boardSetup.typeChange('4')">place sergeant(4)</button>
			<span id="remaining4">remaining:4</span><br>
			<button onclick="boardSetup.typeChange('5')">place lieutenant(5)</button>
			<span id="remaining5">remaining:4</span><br>
			<button onclick="boardSetup.typeChange('6')">place captain(6)</button>
			<span id="remaining6">remaining:4</span><br>
			<button onclick="boardSetup.typeChange('7')">place major(7)</button>
			<span id="remaining7">remaining:3</span><br>
			<button onclick="boardSetup.typeChange('8')">place colonel(8)</button>
			<span id="remaining8">remaining:2</span><br>
			<button onclick="boardSetup.typeChange('9')">place general(9)</button>
			<span id="remaining9">remaining:1</span><br>
			<button onclick="boardSetup.typeChange('10')">place marshal(10)</button>
			<span id="remaining10">remaining:1</span><br>
			<button onclick="boardSetup.endSetup()">end setup</button>
		</div>
		<button onclick="examine(boardSetup.gameState)">examine</button>
		<button onclick="send()">send</button>
		<button onclick="obtain()">obtain</button>
    </body>
</html>

