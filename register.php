<?php include 'components/init.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'components/head.php'; ?>

<?php 
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $username = htmlspecialchars($_POST['username']) ?? '';
        $email = htmlspecialchars($_POST['email']) ?? '';
        $password = htmlspecialchars($_POST['password']) ?? '';
        $cgu = isset($_POST['cgu']);

        $errors = checkRegisterData($username, $email, $password, $cgu);

        if(count($errors) === 0){
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            if(insertUser($username, $email, $hashed)){
                addFlash('success', 'Votre compte a bien été créé');
                header('Location: http://localhost/Formation_Amigraf/D%C3%A9veloppement/PHP/GameList/login.php');
                die();
            }else{
                $errors[] = 'Une erreur est survenue, veuillez réessayer ultérieurement.';
            }
        }
    }
?>

<body>
    <?php include 'components/header.php'; ?>
    <main class="container bg-white p-25 flex-column align-items-center">
        <h2 class="py-15">Rejoignez la communauté</h2>

        <div>
            <?php foreach ($errors as $error) { ?>
            <p class="flash-error"><?php echo $error ?></p>
            <?php } ?>
        </div>
        <form action="" method="POST">
            <div class="form-field pb-10">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" value=<?php echo $username ?? ''; ?>>
            </div>

            <div class="form-field pb-10">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value=<?php echo $email ?? ''; ?>>
            </div>

            <div class="form-field pb-10">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="form-field pb-10">
                <input type="checkbox" name="cgu" id="cgu" <?php echo !empty($cgu) ? 'checked' : '' ?>>
                <label for="username">J'accepte les conditions d'utilisations</label>
            </div>

            <button class="btn-red">S'inscrire</button>
        </form>


    </main>
    <?php include 'components/footer.php'; ?>

</body>

</html>