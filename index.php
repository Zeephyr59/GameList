<?php include 'components/init.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'components/head.php'; ?>

<?php
$bestGames = findGames('rand', 3);


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $name = htmlspecialchars($_GET['searchByName']) ?? null;
    $genre = htmlspecialchars($_GET['searchByGenre']) ?? null;
    $platform = htmlspecialchars($_GET['searchByPlatform']) ?? null;
    $indeGame = $_GET['indeGame'] ?? null;

    $filterGames = findGames('title', null, $name, $genre, $platform, $indeGame);
}
?>

<body>
    <?php include 'components/header.php'; ?>
    <main class="container bg-white p-25 flex-column align-items-center">
        <!-- 
            - Ajouter un système de filtre des jeux (utilisable ensemble ou non)
            - Recherche par nom (contenant)
            - Filtre par genre (déroulante multiple)
            - Case à cocher pour les jeux indépendants
            - Filtre par plateform (déroulante unique)    
        -->

        <form method="GET" class="searchGame">
            <div class="form-field">
                <label for="searchByName">Titre :</label>
                <input type="text" name="searchByName" id="searchByName" placeholder="Titre du jeux">
            </div>

            <div class="form-field">
                <label for="searchByGenre">Genre :</label>
                <select type="searchByGenre" name="searchByGenre" id="searchByGenre">
                    <option value="">Tous</option>
                    <?php foreach(getAllGenres() as $genre) { ?>
                    <option value="<?php echo $genre['id'] ?>"><?php echo $genre['name'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-field">
                <label for="searchByPlatform">Plateforme :</label>
                <select type="searchByPlatform" name="searchByPlatform" id="searchByPlatform">
                    <option value="">Tous</option>
                    <?php foreach(getAllPlatforms() as $platform) { ?>
                    <option value="<?php echo $platform['id'] ?>"><?php echo $platform['name'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-field">
                <input type="checkbox" name="indeGame" id="indeGame" <?php echo !empty($cgu) ? 'checked' : '' ?>>
                <label for="indeGame">Jeux Indépendants</label>
            </div>

            <button class="btn-red">Search</button>
        </form>

        <h2 class="pt-25">GameList - Votre bibliothèque de Jeux Vidéos</h2>
        <p class="pt-25">Lorem ipsum dolor sit amet consectetur adipisicing elit. Et libero facere at ipsum est
            sequi esse fuga
            eum? Corrupti vitae nulla debitis iste deserunt aperiam assumenda labore ipsa nostrum a! Lorem, ipsum
            dolor sit amet consectetur adipisicing elit. Voluptatibus laboriosam rem est animi! Nihil qui fugiat
            quisquam enim repudiandae. Itaque aliquid nihil veritatis quam beatae omnis qui temporibus ipsam
            facilis.</p>

        <?php if(!empty($filterGames)) { ?>
        <div class="flex-row justify-space-around wrap">
            <?php
            foreach ($filterGames as $game) {
                include 'components/cardGame.php';
            }
            ?>
        </div>
        <?php } else { ?>
        <div>
            <p class="flash-error">Aucun jeux ne correspond à votre recherche</p>
        </div>
        <?php } ?>


        <h2 class="pt-25">Les mieux notés</h2>
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