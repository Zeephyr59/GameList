<?php include 'components/init.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'components/head.php'; ?>

<?php
    if(!isLoggedIn()){
        header('Location: http://localhost/Formation_Amigraf/D%C3%A9veloppement/PHP/GameList/');
    }

    $errors = [];

    if (isset($_FILES['picture'])){
        if($_FILES['picture']['error'] === UPLOAD_ERR_OK){//UPLOAD_ERR_OK = 0
            
            $maxSize = 2000000; //2 Mo
            if($_FILES['picture']['size'] < $maxSize){

                $allowedMimeTypes = ['image/jpeg', 'image/png'];
                if(in_array($_FILES['picture']['type'], $allowedMimeTypes)){
                    
                    $oldPicture = getUserPicture($connectedUser, false);
                    if($oldPicture !== null){
                        unlink($oldPicture);
                    }

                    $explodedName = explode('.', $_FILES['picture']['name']);
                    $fileExt = strtolower(end($explodedName));

                    $name = buildUserPictureName($connectedUser);
                    $path = 'asset/uploads/' . $name . '.' . $fileExt;

                    move_uploaded_file($_FILES['picture']['tmp_name'], $path);

                    addFlash('success', 'Votre image a bien été téléchargée');
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    die();

                } else{
                    $errors[] = 'Votre image doit être au format jpeg ou png';
                }
            } else {
                $errors[] = 'Votre image ne doit pas dépasser 2MO';
            }
        }
        else if($_FILES['picture']['error'] === UPLOAD_ERR_NO_FILE){
            $errors[] = 'Veuillez sélectionner un fichier';
        }else{
            $errors[] = 'Une erreur est survenue, veuillez réessayer';
        }
    } 
?>

<body>
    <?php include 'components/header.php'; ?>
    <main class="container bg-white p-25 flex-column align-items-center">
        <h2 class="mt-20">Profil</h2>
        <div>
            <?php foreach ($errors as $error) { ?>
            <p class="flash-error"><?php echo $error ?></p>
            <?php } ?>
        </div>

        <form class="profil" method="post" enctype="multipart/form-data">
            <div class="form-field pb-10">
                <label for="picture">
                    <img src="<?php echo getUserPicture($connectedUser); ?>"
                        alt="<?php echo $connectedUser['username']; ?>">
                </label>
                <input type="file" name="picture" id="picture" accept="image/jpeg,image/png">
            </div>
            <button class="btn-red">Uploader</button>
        </form>

    </main>
    <?php include 'components/footer.php'; ?>

</body>

</html>