<?php include 'components/init.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'components/head.php'; ?>

<?php


if(!isLoggedIn()){
    header('Location: http://localhost/Formation_Amigraf/D%C3%A9veloppement/PHP/GameList/');
}

$games = findGamesInLibraryUser($connectedUser['id']);
?>

<body>
    <?php include 'components/header.php'; ?>
    <main class="container bg-white p-25 flex-column align-items-center">
        <h2 class="mt-20">GameList - Ma Liste</h2>


        <div class="flex-row justify-space-around wrap">
            <?php
            foreach ($games as $game) {
                include 'components/cardGame.php';
            }
            ?>
        </div>


    </main>
    <?php include 'components/footer.php'; ?>

</body>

</html>