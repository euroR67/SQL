<?php 
    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 
        // on crée une classe CinemaController
        class GenresController {
            // ================= méthode pour afficher les films par genre =================
            public function filmsParGenre($id){
                $pdo = Connect::seConnecter();
                $requeteGenre = $pdo->prepare("
                                SELECT f.id_film, f.titre, f.date_sortie, f.duree_minute, f.affiche, f.note, f.synopsis
                                FROM FILM f
                                INNER JOIN contenir c ON f.id_film = c.id_film
                                WHERE c.id_genre = :id;
                ");
                $requeteGenre->execute(["id" => $id]);
                require "view/filmsParGenre.php";
            }
            // ================= méthode pour afficher la liste des genres =================
            public function listGenres() {
                $pdo = Connect::seConnecter();
                $requeteGenres = $pdo->query("
                                SELECT g.id_genre, g.libelle
                                FROM genre g
                ");
                require "view/genres.php";
            }

            // ================= méthode pour afficher les détails d'un genre =================
            public function detailGenre($id) {

                $pdo = Connect::seConnecter();
                // on prépare la requête qui va nous permettre de récupérer les infos du genre
                $requeteGenre = $pdo->prepare("
                                SELECT g.id_genre, g.libelle
                                FROM genre g
                                WHERE g.id_genre = :id;
                ");
                $requeteGenre->execute(["id" => $id]);
                $genre = $requeteGenre->fetch();
                // on prépare la requête qui va nous permettre de récupérer les films du genre
                $requeteFilmParGenre = $pdo->prepare("
                                SELECT
                                f.titre,
                                YEAR(f.date_sortie) AS annee,
                                CONCAT(FLOOR(f.duree_minute / 60), 'h', LPAD(MOD(f.duree_minute, 60), 2, '0')) AS duree,
                                f.note,
                                f.affiche,
                                f.id_film,
                                CONCAT(r.nom, ' ', r.prenom) AS info_realisateur,
                                (
                                    SELECT GROUP_CONCAT(p1.nom, ' ', p1.prenom)
                                    FROM personne p1
                                    JOIN acteur a1 ON p1.id_personne = a1.id_personne
                                    JOIN jouer j1 ON a1.id_acteur = j1.id_acteur
                                    WHERE j1.id_film = f.id_film        
                                ) AS acteurs,
                                (
                                    SELECT GROUP_CONCAT(a1.id_acteur)
                                    FROM personne p1
                                    JOIN acteur a1 ON p1.id_personne = a1.id_personne
                                    JOIN jouer j1 ON a1.id_acteur = j1.id_acteur
                                    WHERE j1.id_film = f.id_film
                                ) AS acteurs_ids,
                                (
                                    SELECT GROUP_CONCAT(g1.libelle SEPARATOR ', ')
                                    FROM genre g1
                                    JOIN contenir c1 ON g1.id_genre = c1.id_genre
                                    WHERE c1.id_film = f.id_film
                                ) AS genres,
                                (
                                    SELECT GROUP_CONCAT(c1.id_genre)
                                    FROM contenir c1
                                    WHERE c1.id_film = f.id_film
                                ) AS genres_ids,
                                rd.id_realisateur
                                FROM film AS f
                                JOIN realisateur AS rd ON f.id_realisateur = rd.id_realisateur
                                JOIN personne AS r ON rd.id_personne = r.id_personne
                                GROUP BY f.id_film
                                HAVING FIND_IN_SET(:id, genres_ids)
                                ORDER BY f.date_sortie DESC;
                ");
                $requeteFilmParGenre->execute(["id" => $id]);

                require "view/detailGenre.php";
            }
            // ================= méthode pour ajouter un genre =================
            public function ajouterGenre() {
                if(isset($_POST["submit"])){
                    $pdo = Connect::seConnecter();

                    $libelle = filter_input(INPUT_POST,"libelle", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $TableCheck = false;
                    
                    // Vérifie si le tableau de Films est défini et non vide
                    if(isset($_POST["films"]) && !empty($_POST["films"])) {
                        // Parcourt chaque élément du tableau Films
                        foreach ($_POST["films"] as $index=>$film ) {
                            // Applique le filtre de sanitize sur chaque élément du tableau Films
                            $film = filter_input(INPUT_POST,"films[$index]", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        }
                        // Vérifie si un film ou plusieurs films sont selectionner ou aucun film n'est selectionner
                        foreach ($_POST["films"] as $film) {
                            // Vérifie si un est film selectionner ou pas
                            if (empty($film)) {
                                $TableCheck = false; // si aucun film selectionner = false
                                break; // interruption de la boucle si toutes les valeurs sont vide
                            }
                            // Sinon vérifie si au moin un film a été selectionner
                            else {
                                $TableCheck = true; // si un ou plusieurs film selectionner = true
                            }
                        }
                    }
                    // Si le champ libelle n'est pas vide et au moin 1 film a été selectionner on insère le genre et le/les films
                    if($libelle && $TableCheck) {
                        $requeteAjoutGenre = $pdo->prepare("
                                INSERT INTO genre(libelle)
                                VALUES (:libelle)
                        ");
                        $requeteAjoutGenre->execute([
                            'libelle' => $_POST['libelle']
                        ]);
                        // on récupère les films sélectionnés et insère dans la table contenir
                        foreach($_POST['films'] as $film){
                            $requeteAjoutGenre2 = $pdo->prepare("
                            INSERT INTO contenir (id_film, id_genre)
                            SELECT
                                (SELECT id_film FROM film WHERE titre = :film),
                                (SELECT id_genre FROM genre WHERE libelle = :genre);
                            ");
                            $requeteAjoutGenre2->execute([
                                "film" => $film,
                                "genre" => $_POST['libelle']
                            ]);
                        }
                        $_SESSION["success"][] = "Le genre a été ajouté avec succès.";
                        header("Location:index.php?action=listFilm_ajoutGenre");die;
                    } 
                    // Si le champ libelle n'est pas vide et que aucun film n'a été selectionner on insère que le genre dans la table 
                    else if($libelle && !$TableCheck) {
                        $requeteAjoutGenre = $pdo->prepare("
                                INSERT INTO genre(libelle)
                                VALUES (:libelle)
                        ");
                        $requeteAjoutGenre->execute([
                            'libelle' => $_POST['libelle']
                        ]);
                        $_SESSION["success"][] = "Le genre a été ajouté avec succès.";
                        header("Location:index.php?action=listFilm_ajoutGenre");die;
                    }
                    // Sinon on affiche un message d'erreur
                    else {
                        $_SESSION["errors"][] = "Le champ du nom du genre ne peut pas être vide.";
                        header("Location:index.php?action=listFilm_ajoutGenre");die;
                    }
                }
            
            }
            // méthode pour afficher la liste des films pour l'ajout de genre
            public function listFilm_ajoutGenre() {
                $pdo = Connect::seConnecter();
                $requeteFilms = $pdo->query("
                                SELECT titre
                                FROM film
                ");
                require "view/ajoutGenre.php";
            }
        }
?>