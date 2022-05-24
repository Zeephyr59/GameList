<?php include 'components/init.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'components/head.php'; ?>

<?php

$id = (int) $_GET['id'] ?? 0;
$game = findGamesById($id);

if (!$game) {
    header('Location: http://localhost/Formation_Amigraf/D%C3%A9veloppement/PHP/GameList/');
}

$reviews = findReviewsById($id);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review = [];

    $review['isRecommanded'] = isset($_POST['is_recommanded']);

    if (strlen($_POST['comment']) > 10 && strlen($_POST['comment']) < 600) {
        $review['comment'] = htmlspecialchars($_POST['comment']);
    } else {
        $errors[] = 'Votre commentaire doit contenir entre 10 et 600 caractères';
    }

    if ($_POST['score']) {
        $review['score'] = $_POST['score'];
    } else {
        $review['score'] = null;
    }

    if (count($errors) === 0) {
        $review['gameId'] = $game['id'];
        //TODO - Récupérer l'ID de l'utilisateur connecté
        $review['userId'] = 4;

        if (!insertReview($review)) {
            $errors[] = 'Une erreur inconnu est survenue';
        } else {
            addFlash('success', 'Votre commentaire a bien été publié');
        }
    }
}

?>

<body>
    <?php include 'components/header.php'; ?>

    <main>
        <div class="game-preview">
            <?php if ($game['poster'] != null) { ?>
            <img src="asset/games/<?php echo $game['poster'] ?>" alt="<?php echo $game['title'] ?>">
            <?php } else { ?>
            <img src="https://thealmanian.com/wp-content/uploads/2019/01/product_image_thumbnail_placeholder.png"
                alt="<?php echo $game['title'] ?>">
            <?php } ?>
            <div class="game-preview__details">
                <div></div>
                <h1><?php echo $game['title']; ?></h1>
                <ul>
                    <li>
                        <i class="fa-solid fa-users-gear"></i>
                        <?php if ($game['editor'] != null or $game['dev'] != null) {
                            echo $game['editor'] . ' / ' . $game['dev'];
                        } else {
                            echo 'À venir';
                        } ?>
                    </li>
                    <?php if ($game['score'] != null) { ?>
                    <li>
                        <i class="fa-solid fa-star"></i>
                        <?php echo $game['score'] ?> / 20
                    </li>
                    <?php } ?>
                    <li>
                        <i class="fa-solid fa-calendar-days"></i>
                        <?php if ($game['released_at'] != null) {
                            $date = new DateTime($game['released_at']);
                            echo $date->format('d-m-Y');
                        } else {
                            echo 'À venir';
                        } ?>
                    </li>
                </ul>
            </div>
        </div>

        <div class="game-details container px-15">
            <div>
                <p class="pt-10 px-5">
                    <i class="fa-solid fa-tag"></i>
                    <?php echo $game['genres']; ?>
                </p>
                <p class="pt-10 px-5">
                    <i class="fa-solid fa-gamepad"></i>
                    <?php echo $game['platforms']; ?>
                </p>
            </div>
            <p class="pt-25">
                <?php echo $game['description']; ?>
            </p>

            <h3 class="pt-25">Commentaires :</h3>
            <?php if (!empty($reviews)) { ?>
            <?php foreach ($reviews as $review) { ?>
            <div class="comments">
                <div class="comment">
                    <div class="left">
                        <img src="asset/profil/<?php echo $review['picture'] ?>" alt="profil picture">
                        <?php if ($review['score'] != null) { ?>
                        <p class="pt-10 bold underline"><?php echo $review['score'] ?></p>
                        <p class="bold">20</p>
                        <?php } ?>
                    </div>
                    <div class="right">
                        <p class="bold"><?php echo $review['username'] ?></p>
                        <p class="pt-10"><?php echo $review['comment'] ?></p>
                    </div>
                </div>
            </div>
            <?php }
            }

            if (!checkUserReviewedGame($game['id'], 4)) { ?>
            <form id="commentary" action="" method="POST">

                <?php foreach ($errors as $error) { ?>
                <p class="flash-error"><?php echo $error ?></p>
                <?php } ?>
                </ul>
                <div class="pb-10">
                    <input type="checkbox" name='is_recommanded'
                        <?php echo isset($_POST['is_recommanded']) ? 'checked' : '' ?>>
                    <label for="is_recommended">Recommandez-vous ce jeu ?</label>
                </div>
                <div class="pb-10">
                    <input type="number" name='score' min="0" max="20" value="<?php echo $_POST['score'] ?? '' ?>">
                    <label for="score">/ 20</label>
                </div>
                <label for="comment">Votre commentaire :</label>
                <textarea rows="3" name='comment'><?php echo $_POST['comment'] ?? ''; ?></textarea>

                <button class="btn-red">Publiez</button>
            </form>
            <?php } ?>
        </div>
    </main>
    <?php include 'components/footer.php'; ?>

</body>

</html>