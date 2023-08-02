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
                            affiche
                            FROM film");

            // on relie la vue qui nous intéresse(située dans le dossier view)
            require "view/films.php";

        }

        // méthode pour afficher les détails d'un acteur
        public function detailFilm($id) {

            $pdo = Connect::seConnecter();
            // on prépare la requête
            $requeteFilm = $pdo->prepare("
                    SELECT  f.titre,
                    f.affiche,
                    f.date_sortie,
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
                    WHERE f.id_film;
            ");
            // on exécute la requête en passant l'id en paramètre
            $requete->execute(["id" => $id]);

            require "view/detailActeur.php";

        }
    }

?>