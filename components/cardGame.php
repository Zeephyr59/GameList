<div class="card-game">
    <?php if ($game['poster'] != null) { ?>
        <img src="asset/games/<?php echo $game['poster'] ?>" alt="<?php echo $game['title'] ?>">
    <?php } else { ?>
        <img src="https://thealmanian.com/wp-content/uploads/2019/01/product_image_thumbnail_placeholder.png" alt="<?php echo $game['title'] ?>">
    <?php } ?>
    <div class="card-txt">
        <?php if ($game['score'] != null) { ?>
            <div class="score"><?php echo $game['score'] ?> / 20</div>
        <?php } ?>
        <h2 class="px-15 text-center"><?php echo $game['title'] ?></h2>
        <p class="bold px-15 pt-10">
            <?php if ($game['genres'] != null) {
                echo $game['genres'];
            } else {
                echo 'non catégorisé';
            }
            ?>
        </p>
        <p class="px-15 pt-10 description"><?php echo $game['description'] ?></p>
        <p class="px-15 pt-10 pb-25">
            <?php if ($game['released_at']) { ?>
                <span class="bold">Sortie :</span> <?php $date = new DateTime($game['released_at']);
                                                    echo $date->format('d-m-Y');
                                                } ?>
        </p>
    </div>
    <a class="btn-red" href="single?id=<?php echo $game['id'] ?>">Détail</a>

</div>