<?php include 'components/init.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'components/head.php'; ?>

<?php
$recommandedGames = findGames('rand', 3);
$bestGames = findGames('score', 3);
?>

<body>
    <?php include 'components/header.php'; ?>
    <main class="container bg-white p-25 flex-column align-items-center">
        <h2 class="mt-20">GameList - Votre bibliothèque de Jeux Vidéos</h2>
        <p class="mt-20">Lorem ipsum dolor sit amet consectetur adipisicing elit. Et libero facere at ipsum est
            sequi esse fuga
            eum? Corrupti vitae nulla debitis iste deserunt aperiam assumenda labore ipsa nostrum a! Lorem, ipsum
            dolor sit amet consectetur adipisicing elit. Voluptatibus laboriosam rem est animi! Nihil qui fugiat
            quisquam enim repudiandae. Itaque aliquid nihil veritatis quam beatae omnis qui temporibus ipsam
            facilis.</p>

        <div class="flex-row justify-space-around wrap">
            <?php
            foreach ($recommandedGames as $game) {
                include 'components/cardGame.php';
            }
            ?>
        </div>

        <h2 class="mt-20">Les mieux notés</h2>
        <div class="flex-row justify-space-around wrap">
            <?php
            foreach ($bestGames as $game) {
                include 'components/cardGame.php';
            }
            ?>
        </div>
    </main>
    <?php include 'components/footer.php'; ?>

</body>

</html>