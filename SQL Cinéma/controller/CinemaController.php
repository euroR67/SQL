<?php 
    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 

    // on crée une classe CinemaController
    class CinemaController {

        public function listTop4() {
            $pdo = Connect::seConnecter();

            $requete = $pdo->query("
                            SELECT titre,
                            YEAR(date_sortie) AS annee,
                            CONCAT(FLOOR(duree_minute / 60), 'h', LPAD(MOD(duree_minute, 60), 2, '0')) AS duree,
                            note,
                            id_film,
                            affiche
                            FROM film
                            ORDER BY YEAR(date_sortie) DESC
                            LIMIT 4");
            require "view/homepage.php";
        }
        // méthode pour afficher la liste des films
        public function listFilms() {

            // on se connecte à la base de données
            $pdo = Connect::seConnecter();
            // requête pour récupérer les films
            $requete = $pdo->query("
                            SELECT titre,
                            YEAR(date_sortie) AS annee,
                            CONCAT(FLOOR(duree_minute / 60), 'h', LPAD(MOD(duree_minute, 60), 2, '0')) AS duree,
                            note,
                            affiche,id_film
                            FROM film");

            // on relie la vue qui nous intéresse(située dans le dossier view)
            require "view/films.php";

        }

        // méthode pour afficher les détails d'un acteur
        public function detailFilm($id) {

            $pdo = Connect::seConnecter();
            // on prépare la requête
            // Requête pour récuperer infos d'un film
            $requeteFilm = $pdo->prepare("
                    SELECT  f.titre,
                    f.affiche,
                    DATE_FORMAT(f.date_sortie, '%d/%m/%Y') AS date_sortie,
                    CONCAT(FLOOR(f.duree_minute / 60), 'h', LPAD(MOD(f.duree_minute, 60), 2, '0')) AS duree,
                    f.note,
                    f.synopsis,
                    CONCAT(p.nom, ' ', p.prenom, ' ') AS info_realisateur,
                    r.role_jouer
                    FROM film f
                    INNER JOIN jouer j ON j.id_film = f.id_film
                    INNER JOIN role r ON r.id_role = j.id_role
                    INNER JOIN realisateur re ON re.id_realisateur = f.id_realisateur
                    INNER JOIN personne p ON p.id_personne = re.id_personne
                    WHERE f.id_film = :id;
            ");
            // on exécute la requête film en passant l'id en paramètre
            $requeteFilm->execute(["id" => $id]);

            $requeteCasting = $pdo->prepare("
                    SELECT r.role_jouer, CONCAT(p.prenom, ' ', p.nom ,' ') AS info_acteur
                    FROM film f
                    INNER JOIN jouer j ON j.id_film = f.id_film
                    INNER JOIN role r ON r.id_role = j.id_role
                    INNER JOIN acteur a ON a.id_acteur = j.id_acteur
                    INNER JOIN personne p ON p.id_personne = a.id_personne
                    WHERE f.id_film= :id;
            ");
            // on exécute la requête casting en passant l'id en paramètre
            $requeteCasting->execute(["id" => $id]);

            $requeteGenre = $pdo->prepare("
                    SELECT f.id_film,
                    GROUP_CONCAT(g.libelle SEPARATOR ', ') AS libelle
                    FROM film f
                    INNER JOIN contenir c ON f.id_film = c.id_film
                    INNER JOIN genre g ON c.id_genre = g.id_genre
                    WHERE f.id_film= :id;
                    GROUP BY f.id_film;
            ");
            // on exécute la requête genre en passant l'id en paramètre
            $requeteGenre->execute(["id" => $id]);

            require "view/detailFilm.php";

        }

        public function listActeurs() {
            $pdo = Connect::seConnecter();
            $requeteActeurs = $pdo->query("
                            SELECT CONCAT(p.prenom, ' ', p.nom ,' ') AS info_acteur,
                            p.photo,
                            a.id_acteur
                            FROM personne p
                            INNER JOIN acteur a ON a.id_personne = p.id_personne
            ");
            require "view/acteurs.php";
        }

        public function detailActeur($id) {
            $pdo = Connect::seConnecter();
            $requeteActeur = $pdo->prepare("
                            SELECT CONCAT(p.prenom, ' ', p.nom) AS info_acteur,
                            p.photo,
                            DATE_FORMAT(p.date_de_naissance, '%d/%m/%Y') AS date_de_naissance,
                            p.biographie,
                            a.id_acteur,
                            GROUP_CONCAT(f.titre SEPARATOR ', ') AS films_jouer
                            FROM personne p
                            INNER JOIN acteur a ON a.id_personne = p.id_personne
                            LEFT JOIN jouer j ON j.id_acteur = a.id_acteur
                            LEFT JOIN film f ON f.id_film = j.id_film
                            WHERE a.id_acteur = :id;
                            GROUP BY a.id_acteur
            ");
            $requeteActeur->execute(["id" => $id]);
            require "view/detailActeur.php";
        }
    }

?>