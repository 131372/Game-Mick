<!DOCTYPE html> 
<html>
    <head> 
        <title>example game tests</title> 
         <?php
        $D = "../";
        $testing = true;
        include_once '../inc/setup.php';
        ?>
        <?php
        include_once 'loadJasmine.php';
        ?>

        <script type="text/javascript" src="js/testHelpers.js"></script>
        <script type="text/javascript" src="js/toBeTypeOf.js"></script>

       

        <script type="text/javascript">
            var beurt = 1;
            var speler1 = "Speler1";
            var speler2 = "Speler2";
            var speler = 1;
        </script>
        <?php
        foreach (array("Bord", "GameBord", "GameVeld", "Zet") as $f) {
            print "<script type='text/javascript' src='test$f.js'></script>";
        }
        ?>

    </head> 
    <body> 
    </body> 
</html> 