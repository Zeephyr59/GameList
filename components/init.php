<?php 
        session_start();

        $db = new PDO('mysql:dbname=amigraf_game_list;host=localhost', 'admin', 'admin', array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        )); 
?>