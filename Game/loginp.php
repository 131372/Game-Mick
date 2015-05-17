<?php

session_start();
require "db_config.php";
if (isset($_POST['name'])) {
    $name = $_POST["name"];
    $_SESSION['name'] = $name;
    header('Location: lobby.php');
    echo $_SESSION['name'];
}
else{
    header("Location: login.php");
}
?>
