<header class="bg-dark">
    <nav class="navbar container">
        <a href="#" class="logo"><img src="asset/logo.png" alt="logo GameList">
            <h1>GameList</h1>
        </a>
        <input type="checkbox" id="toggler">
        <label for="toggler"><i class="fa-solid fa-bars"></i></label>
        <div class="menu">
            <ul class="list">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="librairy.php">Bibliothèque</a></li>
                <li><a href="#">Ma liste</a></li>
            </ul>
            <ul class="list">
                <?php if(isLoggedIn()) { ?>
                <li class="pt-25-sm"><a href="logout.php">Déconnexion</a></li>
                <li><img src="asset/profil/<?php echo $connectedUser['picture'] ?>" alt="profil picture"></li>
                <?php } else { ?>
                <li class="pt-25-sm"><a href="register.php">Inscription</a></li>
                <li><a href="login.php">Connexion</a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <?php 
            foreach (getFlashMsg('success') as $flash)
            {
                echo '<p class="container pt-10 flash flash-'.$flash['type'].'">' . $flash['content'] . '</p>';
            };
        ?>
</header>