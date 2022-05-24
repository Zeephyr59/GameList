<?php 
include 'components/init.php';
include 'components/head.php'; 

logout();
addFlash('success', 'Vous êtes bien déconnecté');
header('Location: http://localhost/Formation_Amigraf/D%C3%A9veloppement/PHP/GameList/');
die();