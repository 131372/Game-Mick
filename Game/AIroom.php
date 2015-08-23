<!DOCTYPE html>
<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="JS/AIgame.js">

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
        <div id="pieces2" class="all"></div>
        <?php
        require "db_config.php";
        session_start();
        if (!isset($_SESSION['name'])) {
            header("Location: login.php");
        }
        $name = $_SESSION['name'];
        for ($i = 1; $i < 11; $i++) {
            $value = chr(65 + $i);
            for ($i1 = 1; $i1 < 11; $i1++) {
                $id1 = "x" . $i1 . "y" . $i;
                $id2 = $id1 . "b";
                echo "<div class='tile1 all' id='$id1'><span id='$id2'></span></div>";
            }
            echo "<div class='tile2 all'></div>";
            //create the game board
        }
        ?>


        
        <div id="pieces1" class="all"></div>
        
        <div id="dummy"></div>
        <div id='si' class="all">
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

