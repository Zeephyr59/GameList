<?php include 'components/init.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'components/head.php'; ?>

<?php
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = findUserByEmail($email);
        if($user === null){
            $errors[] = 'Informations incorrectes';
        }

        else if(password_verify($password, $user['password'])){
            login($user['id']);
            addFlash('success', 'Vous êtes bien connecté');
            header('Location: http://localhost/Formation_Amigraf/D%C3%A9veloppement/PHP/GameList/');
            die();
        } else {
            $errors[]  = 'Informations incorrectes';
        }
    }
?>

<body>
    <?php include 'components/header.php'; ?>
    <main class="container bg-white p-25 flex-column align-items-center">
        <h2 class="py-15">GameList - Connexion</h2>

        <div>
            <?php foreach ($errors as $error) { ?>
            <p class="flash-error"><?php echo $error ?></p>
            <?php } ?>
        </div>
        <form action="" method="POST">
            <div class="form-field pb-10">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value=<?php echo $email ?? ''; ?>>
            </div>

            <div class="form-field pb-10">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
            </div>

            <button class="btn-red">Connexion</button>
        </form>

    </main>
    <?php include 'components/footer.php'; ?>

</body>

</html>