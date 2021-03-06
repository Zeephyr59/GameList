<?php

function getAllGenres(): array
{
    global $db;

    $query = <<<SQL
        SELECT genre.id, genre.name FROM genre
        LEFT JOIN game_genre ON game_genre.genre_id = genre.id
        WHERE game_genre.genre_id IS NOT NULL
        GROUP BY genre.id
        ORDER BY genre.name;
    SQL;

    $stmt = $db->query($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAllPlatforms(): array
{
    global $db;

    $query = <<<SQL
        SELECT platform.id, platform.name FROM platform
        LEFT JOIN game_platform ON game_platform.platform_id = platform.id
        WHERE game_platform.platform_id IS NOT NULL
        GROUP BY platform.id
        ORDER BY platform.name;
    SQL;

    $stmt = $db->query($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function findGames(string $order = NULL, int $limit = NULL, $searchName = NULL, $searchGenre = NULL, $searchPlatform = NULL, $indeGame = null): array
{
    global $db;
    
    $query = <<<SQL
        SELECT game.id, game.title, game.slug, game.released_at, game.description, game.poster, GROUP_CONCAT(DISTINCT genre.name SEPARATOR ' ') as genres, ROUND(AVG(review.score),1) as score FROM game 
        LEFT JOIN game_platform ON game_platform.game_id = game.id
        LEFT JOIN game_genre ON game_genre.game_id = game.id
        LEFT JOIN genre ON game_genre.genre_id = genre.id
        LEFT JOIN review ON review.game_id = game.id 
    SQL;

    $clauses = [];


    if($searchName || $searchName != ''){
        $clauses[] = 'game.title LIKE CONCAT( "%", :title, "%")';
    }

    if($searchGenre || $searchGenre != ''){
        $clauses[] = 'genre.id = :genre';
    }

    if($searchPlatform || $searchPlatform != ''){
        $clauses[] = 'game_platform.platform_id = :platform';
    }

    if($indeGame){
        $clauses[] = 'game.editor_id IS NULL';
    }

    if(count($clauses) > 0){
        $query .= ' WHERE ' . implode(' AND ', $clauses);
    }

    $query .= ' GROUP BY game.id ';
    
    if($order === 'rand')
    {
        $query .= ' ORDER BY RAND() ';
    } else if ($order === 'score')
    {
        $query .= ' ORDER BY score DESC ';
    } else if ($order === 'title')
    {
        $query .= ' ORDER BY game.title ';
    }

    if($limit !== NULL){
        $query .= 'LIMIT :limit';
    }

    

    $stmt = $db->prepare($query);

    if ($limit !== NULL) {
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
    }

    if($searchName){
        $stmt->bindValue('title', $searchName);
    }

    if($searchGenre){
        $stmt->bindValue('genre', $searchGenre, PDO::PARAM_INT);
    }

    if($searchPlatform){
        $stmt->bindValue('platform', $searchPlatform, PDO::PARAM_INT);
    }

    $stmt->execute();

    return $stmt->fetchAll();
}

function findGamesById(int $id): ?array
{
    global $db;

    $query = <<<SQL
        SELECT game.id, game.title, game.poster, game.released_at, game.description, IFNULL(GROUP_CONCAT(DISTINCT genre.name SEPARATOR " - "), "Non connu") AS genres, company_editor.name AS editor, GROUP_CONCAT(DISTINCT company_developer.name SEPARATOR " - ") AS dev, GROUP_CONCAT(DISTINCT platform.name SEPARATOR " - ") AS platforms, ROUND(AVG(review.score),1) as score FROM game
        LEFT JOIN review ON game.id = review.game_id AND review.is_recommanded = 1
        LEFT JOIN game_genre ON game.id = game_genre.game_id
        LEFT JOIN genre ON game_genre.genre_id = genre.id
        LEFT JOIN company company_editor ON game.editor_id = company_editor.id
        LEFT JOIN developer ON game.id = developer.game_id
        LEFT JOIN company company_developer ON developer.company_id = company_developer.id
        LEFT JOIN game_platform ON game.id = game_platform.game_id
        LEFT JOIN platform ON game_platform.platform_id = platform.id
        WHERE game.id = :id
        GROUP BY game.id;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue('id', $id);
    $stmt->execute();

    $game = $stmt->fetch();

    if(!$game){
        return null;
    } else {
        return $game;}
}

function findGamesInLibraryUser(int $userId): ?array
{
    global $db;
    
    $query = <<<SQL
        SELECT game.id, game.title, game.slug, game.released_at, game.description, game.poster, GROUP_CONCAT(genre.name SEPARATOR ' ') as genres, ROUND(AVG(review.score),1) as score FROM game LEFT JOIN game_genre ON game_genre.game_id = game.id
        LEFT JOIN genre ON game_genre.genre_id = genre.id
        LEFT JOIN review ON review.game_id = game.id 
        LEFT JOIN library on library.game_id = game.id
        WHERE library.user_id = :userId
        GROUP BY game.id;
    SQL;

    
    $stmt = $db->prepare($query);
    $stmt->bindValue('userId', $userId);
    $stmt->execute();
    return $stmt->fetchAll();
}

function findReviewsById(int $id): ?array
{
    global $db;

    $query = <<<SQL
        SELECT review.score, review.is_recommanded, review.comment, user.id, user.username from review
        LEFT JOIN user ON user.id = review.user_id     
        WHERE review.game_id = :id LIMIT 5;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue('id', $id);
    $stmt->execute();

    $reviews = $stmt->fetchAll();

    return $reviews;
}

function insertReview(array $review): bool
{
    global $db;

    
    $query = <<<SQL
        INSERT INTO review (game_id, user_id, is_recommanded, score, comment) 
        VALUES (:gameId, :userId, :isRecommanded, :score, :comment);
    SQL;

    $stmt = $db->prepare($query);

    $stmt->bindValue('gameId', $review['gameId'], PDO::PARAM_INT);
    $stmt->bindValue('userId', $review['userId'], PDO::PARAM_INT);
    $stmt->bindValue('isRecommanded', $review['isRecommanded'], PDO::PARAM_INT);
    $stmt->bindValue('score', $review['score'], PDO::PARAM_INT);
    $stmt->bindValue('comment', $review['comment']);

    try {
        $stmt->execute();
        return true;
    } catch (exception $e){
        return false;
    }
}

function insertUser(string $username, string $email, string $password): bool
{
    global $db;
    
    $query = <<<SQL
        INSERT INTO user (username, email, password, roles, created_at) 
        VALUES (:username, :email, :password, 0, NOW());
    SQL;

    $stmt = $db->prepare($query);

    $stmt->bindValue('username', $username);
    $stmt->bindValue('email', $email);
    $stmt->bindValue('password', $password);

    try {
        $stmt->execute();
        return true;
    } catch (exception $e){
        return false;
    }
}

function checkUserReviewedGame(int $gameId, int $userId): bool
{
    global $db;

    $query = <<<SQL
        SELECT count(*) FROM `review` 
        WHERE user_id = :userId && game_id = :gameId;
    SQL;

    $stmt = $db->prepare($query);

    $stmt->bindValue('gameId', $gameId, PDO::PARAM_INT);
    $stmt->bindValue('userId', $userId, PDO::PARAM_INT);

    $stmt->execute();
    
    $result = $stmt->fetchColumn();

    if((int)$result === 0){
        return false;
    }
    
    return true;
    
}

function checkExistingUserEmail(string $email): bool
{
    global $db;

    $query = <<<SQL
        SELECT count(email) as counterEmail FROM user
        WHERE email = :email;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue('email', $email);
    $stmt->execute();

    //Permet de r??cup??r?? directement le r??sultat du de la colonne (ici count) au lieu d'un tableau de r??sultat
    $result = $stmt->fetchColumn();

    if($result == 0){ 
        return false;
    }

    return true;
}

function checkExistingInLibraryUser(int $userId, int $gameId): bool
{
    global $db;

    $query = <<<SQL
        SELECT COUNT(game_id) as counterGame FROM library
        WHERE user_id = :userId AND game_id = :gameId;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue('userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue('gameId', $gameId, PDO::PARAM_INT);
    $stmt->execute();

    //Permet de r??cup??r?? directement le r??sultat du de la colonne (ici count) au lieu d'un tableau de r??sultat
    $result = $stmt->fetchColumn();

    if($result == 0){ 
        return false;
    }

    return true;
}

function insertGameInLibrary(int $userId, int $gameId): bool
{
    global $db;
    
    $query = <<<SQL
        INSERT INTO library (user_id, game_id, status) 
        VALUES (:user_id, :game_id, 0);
    SQL;

    $stmt = $db->prepare($query);

    $stmt->bindValue('user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue('game_id', $gameId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        return true;
    } catch (exception $e){
        return false;
    }
}

function deleteGameInLibrary(int $userId, int $gameId): bool
{
    global $db;
    
    $query = <<<SQL
        DELETE FROM library WHERE user_id = :user_id AND game_id = :game_id;
    SQL;

    $stmt = $db->prepare($query);

    $stmt->bindValue('user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue('game_id', $gameId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        return true;
    } catch (exception $e){
        return false;
    }
}

function findUserByEmail(string $email): ?array
{
    global $db;

    $query = <<<SQL
        SELECT * FROM user WHERE email = :email;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue('email', $email);
    $stmt->execute();

    $user = $stmt->fetch();

    if($user === false){
        return null;
    } else{
        return $user;
    }
}

function findUserById(int $id): ?array
{
    global $db;

    $query = <<<SQL
        SELECT * FROM user
        LEFT JOIN profile ON profile.user_id = user.id 
        WHERE id = :id;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch();

    if($user === false){
        return null;
    } else{
        return $user;
    }
}



// ----- Security -----
function login(int $userId): void
{
    $_SESSION['authenticated'] = true;
    $_SESSION['userId'] = $userId;
}

function logout(): void
{
    $_SESSION['authenticated'] = false;
    unset($_SESSION['userId']);
}

function isLoggedIn(): bool
{
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

function reloadUserFormDatabase(): ?array
{
    if(empty($_SESSION['userId'])){
        return null;
    }

    return findUserById($_SESSION['userId']);
}



// ----- Flash Message -----
function addFlash(string $type, string $message): void
{
    $messages = $_SESSION['messages'] ?? [];

    $messages[] = [
        'type' => $type,
        'content' => $message,
    ];

    $_SESSION['messages'] = $messages;
}

function getFlashMsg(): array
{
    $messages = $_SESSION['messages'] ?? [];
    unset($_SESSION['messages']);
    return $messages;
}



// ----- Utils -----
function getDefaultGamePoster(): string
{
    return 'https://thealmanian.com/wp-content/uploads/2019/01/product_image_thumbnail_placeholder.png';
}

function buildUserPictureName(array $user): string
{
    return md5($user['id'] . '_' . $user['username']);
}

function getUserPicture(array $user, bool $withDefault = true): ?string
{
    $name = buildUserPictureName($user);
    $files = scandir('asset/uploads');

    foreach($files as $file){
        if(strstr($file, $name) !== false){
            return 'asset/uploads/' . $file;
        }
    }
    
    if($withDefault){ return 'asset/profil/default.jpg'; }

    return null;
}


// ----- Form -----
function checkRegisterData(string $username, string $email, string $password, bool $cgu): array
{
    $errors = [];

    //Check Username
    if(strlen($username) < 3 || strlen($username) > 24){
        $errors[] = 'Votre nom d\'utilisateur doit contenir entre 3 et 24 caract??res';
    } 

    if (!ctype_alnum($username))
    {
        $errors[] = 'Votre nom d\'utilisateur doit contenir uniquement des caract??res alphanum??riques';
    }


    //Check Email
    if(empty($email)){
        $errors[] = 'Veuillez saisir votre adresse email';
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Veuillez saisir une adresse email valide';
    }
    else if(checkExistingUserEmail($email)){
        $errors[] = 'Cette adresse email est d??j?? utilis??e';
    }

    
    //Check Password
    if(strlen($password) < 8 || strlen($password) > 30){
        $errors[] = 'Votre mot de passe doit contenir entre 8 et 30 caract??res';
    } 

    $regex = '/(?=.{0,}[a-z])(?=.{0,}[^a-zA-Z0-9])(?=.{0,}\d)/';
    if(preg_match($regex, $password) === 0){
        $errors[] = 'Votre mot de passe doit contenir au moins un chiffre, une lettre et un caract??re sp??cial';
    }


    //Check CGU
    if(!$cgu){
        $errors[] = 'Veuillez accepter les conditions d\'utilisations';
    }

    return $errors;
}
?>