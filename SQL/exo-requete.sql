/*
1 - Sortir l'ensemble des tags 
*/
	SELECT tag.name FROM tag;

/*
2 - Sortir les sociétés ayant édités au moins un jeu depuis 1er Janvier 2020
*/
	SELECT company.name, game.title FROM company 
    INNER JOIN game ON game.editor_id = company.id
    WHERE game.released_at > '2020-01-01'
    GROUP BY company.id

/*
3 - Calculer le nombre de jeux par genre et ainsi que le pourcentage que cela représente
*/
    SELECT 
        genre.name, 
        COUNT(game.id) AS counterGame,
        ROUND(COUNT(game.id) * 100 / (SELECT COUNT(game.id) FROM game,),1) AS share
    FROM genre
    LEFT JOIN game_genre ON game_genre.genre_id = genre.id
    LEFT JOIN game ON game_genre.game_id = game.id
    GROUP BY genre.id
    ORDER BY counterGame DESC


/*
4 - Lister les sociétés n'ayant pas participer au développement d'un jeu
*/
	SELECT company.name FROM company
	LEFT JOIN developer ON developer.company_id = company.id
	WHERE developer.company_id IS NULL


/*
5 - Sortir la licence la plus populaire
*/
    SELECT licence.name, COUNT(game.id) AS nbrGame FROM licence
    LEFT JOIN game ON game.licence_id = licence.id
    GROUP BY licence.name 
    ORDER BY nbrGame DESC
    LIMIT 1

/*
6 - Bonus : Sortir pour chaque plateforme le genre le plus populaire
*/
    SELECT platform.name, genre.name FROM platform
    LEFT JOIN game_platform gp on platform.id = gp.platform_id
    LEFT JOIN game g on gp.game_id = g.id
    LEFT JOIN game_genre gg on g.id = gg.game_id
    LEFT JOIN genre on gg.genre_id = genre.id
    WHERE genre.id = (
        SELECT genre.id FROM platform p2
        LEFT JOIN game_platform gp on p2.id = gp.platform_id
        LEFT JOIN game g on gp.game_id = g.id
        LEFT JOIN game_genre gg on g.id = gg.game_id	
        LEFT JOIN genre on gg.genre_id = genre.id
        WHERE platform.id = p2.id
        GROUP BY platform.id, genre.id
        ORDER BY COUNT(genre.id) DESC
        LIMIT 1
    )
    GROUP BY platform.id



/*
7 - Insérer le jeu : Counter Strike Global Offensive & Connecter avec les plateformes, genres et tags nécessaire
*/

INSERT INTO company (name, slug) VALUES ('Valve', 'valve');

INSERT INTO licence (name, slug) VALUES ('Counter-Strike', 'counter-strike');

INSERT INTO game (title, slug, released_at, editor_id, licence_id) VALUES ('Counter-Strike : Global Offensive', 'counter-strike-global-offensive', '2012-08-21', 12, 8);

INSERT INTO developer (game_id, company_id) VALUES (8, 12);

INSERT INTO platform (name, slug, icon) VALUES ('Playstation 3', 'ps3', 'fa-brands fa-playstation'), ('Xbox 360', 'xbox-360', 'fa-brands fa-xbox');

INSERT INTO game_platform (game_id, platform_id) VALUES (8, 1), (8, 6), (8, 7);

INSERT INTO game_genre (game_id, genre_id) VALUES (8, 3), (8, 6);

INSERT INTO game_tag (game_id, tag_id) VALUES (8, 1);

/*
8 - Suppression des jeux sans éditeurs
*/

/* Table 'Developer', 'game_platform', 'game_genre' passer la clé étrangère 'game_id' en CASCADE */
DELETE FROM game WHERE editor_id IS NULL;

/*
9 - Suppression des plateformes sans jeux associés
*/

/* On supprime tout les ID qui ne sont pas selectionné et on sélectionne les platform déjà associé qui seront donc gardé */
DELETE FROM platform WHERE id NOT IN (
    SELECT platform_id FROM game_platform GROUP BY platform_id
)

/*
10 - Supprimer les société qui n'ont pas édités ou développés de jeux
*/

DELETE FROM company WHERE 
    id NOT IN (SELECT editor_id FROM game WHERE editor_id IS NOT NULL GROUP BY editor_id)
    AND id NOT IN (SELECT company_id FROM developer GROUP BY company_id)

/*
11 - Mettre à jour la date de sortie des jeux qui n'ont pas de licence à aujourd'hui
*/

UPDATE game SET released_at = now() WHERE game.licence_id IS NULL