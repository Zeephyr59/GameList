<?php

function findGames(string $order = NULL, int $limit = NULL): array
{
    global $db;
    
    $query = <<<SQL
        SELECT game.id, game.title, game.slug, game.released_at, game.description, game.poster, GROUP_CONCAT(genre.name SEPARATOR ' ') as genres, ROUND(AVG(review.score),1) as score FROM game 
        LEFT JOIN game_genre ON game_genre.game_id = game.id
        LEFT JOIN genre ON game_genre.genre_id = genre.id
        LEFT JOIN review ON review.game_id = game.id 
        GROUP BY game.id
    SQL;

    if($order === 'rand')
    {
        $query .= ' ORDER BY RAND() ';
    } 

    if ($order === 'score')
    {
        $query .= ' ORDER BY score DESC ';
    }

    if($limit !== NULL){
        $query .= 'LIMIT :limit';
    }

    $stmt = $db->prepare($query);

    if ($limit !== NULL) {
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
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

function findReviewsById(int $id): ?array
{
    global $db;

    $query = <<<SQL
        SELECT review.score, review.is_recommanded, review.comment, user.id, user.username, profile.picture from review
        LEFT JOIN user ON user.id = review.user_id
        LEFT JOIN profile ON profile.user_id = user.id        
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
?>