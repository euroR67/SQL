<?php 
    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 

    // on crée une classe CinemaController
    class CinemaController {

        // méthode pour afficher la liste des films
        public function listFilms() {

            // on se connecte à la base de données
            $pdo = Connect::seConnecter();
            // requête pour récupérer les films
            $requete = $pdo->query("
                SELECT titre, annee_sortie
                FROM film;
            ");

            // on relie la vue qui nous intéresse(située dans le dossier view)
            require "view/listFilms.php";

        }

        // méthode pour afficher les détails d'un acteur
        public function detailActeur($id) {

            $pdo = Connect::seConnecter();
            // on prépare la requête
            $requete = $pdo->prepare("
                SELECT *
                FROM acteur
                WHERE id_acteur = :id;
            ");
            // on exécute la requête en passant l'id en paramètre
            $requete->execute(["id" => $id]);

            require "view/detailActeur.php";

        }
    }

?>