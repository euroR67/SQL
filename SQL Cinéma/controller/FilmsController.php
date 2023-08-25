<?php

    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 
        // on crée une classe CinemaController
        class FilmsController {
            // ================= méthode pour afficher la page d'accueil avec les 4 films les plus récents =================
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
            // ================= méthode pour afficher la liste des films =================
            public function listFilms() {
                // on se connecte à la base de données
                $pdo = Connect::seConnecter();
                // requête pour récupérer les films
                $requete = $pdo->query("
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
                                ORDER BY f.date_sortie DESC;
                            ");

                // on relie la vue qui nous intéresse(située dans le dossier view)
                require "view/films.php";

            }

            // ================= méthode pour afficher les détails d'un film =================
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
                                re.id_realisateur
                                FROM film f
                                INNER JOIN realisateur re ON re.id_realisateur = f.id_realisateur
                                INNER JOIN personne p ON p.id_personne = re.id_personne
                                WHERE f.id_film = :id
                ");
                // on exécute la requête film en passant l'id en paramètre
                $requeteFilm->execute(["id" => $id]);
                // requête pour récupérer le casting du film
                $requeteCasting = $pdo->prepare("
                                SELECT r.role_jouer,
                                CONCAT(p.prenom, ' ', p.nom) AS info_acteur,
                                a.id_acteur,
                                j.id_role
                                FROM film f
                                INNER JOIN jouer j ON j.id_film = f.id_film
                                INNER JOIN role r ON r.id_role = j.id_role
                                INNER JOIN acteur a ON a.id_acteur = j.id_acteur
                                INNER JOIN personne p ON p.id_personne = a.id_personne
                                WHERE f.id_film = :id;
                ");
                // on exécute la requête casting en passant l'id en paramètre
                $requeteCasting->execute(["id" => $id]);

                // on prépare la requête qui va nous permettre de récupérer les genres du film
                $requeteGenre = $pdo->prepare("
                                SELECT f.id_film, g.id_genre,
                                GROUP_CONCAT(g.libelle SEPARATOR ', ') AS genres
                                FROM film f
                                INNER JOIN contenir c ON f.id_film = c.id_film
                                INNER JOIN genre g ON c.id_genre = g.id_genre
                                WHERE f.id_film = :id
                                GROUP BY g.id_genre
                            ");
                // on exécute la requête genre en passant l'id en paramètre
                $requeteGenre->execute(["id" => $id]);

                require "view/detailFilm.php";

            }

            // ================= méthode pour ajouter un film =================
        public function ajouterFilm() {
            if(isset($_POST["submit"])){
                $pdo = Connect::seConnecter();
                
                $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $date_sortie = filter_input(INPUT_POST, "date_sortie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $duree_minute = filter_input(INPUT_POST, "duree_minute", FILTER_VALIDATE_INT);
                $note = filter_input(INPUT_POST, "note", FILTER_VALIDATE_INT);
                $synopsis = filter_input(INPUT_POST, "synopsis", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $realisateur = filter_input(INPUT_POST, "realisateur", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
                // Vérification extension et erreurs de l'affiche
                $tmpName = $_FILES["affiche"]["tmp_name"];
                $img_name = $_FILES["affiche"]["name"];
                $size = $_FILES["affiche"]["size"];
                $error = $_FILES["affiche"]["error"];
                $type = $_FILES["affiche"]["type"];
        
                $tabExtension = explode('.', $img_name); // Sépare le nom du fichier et son extension
                $extension = strtolower(end($tabExtension)); // Stock l'extension
        
                //Tableau des extensions acceptées
                $extensions = ['jpg', 'png', 'jpeg', 'gif'];
                // Taille maximale acceptée (en bytes)
                $maxSize = 5000000;
                // Applique le filtre de sanitize sur chaque élément du tableau genre
                $TableCheck = false;
        
                // Vérifie si le tableau de Genres est défini et non vide
                if(isset($_POST["genres"]) && !empty($_POST["genres"])) {
                    // Parcourt chaque élément du tableau Genres
                    foreach($_POST["genres"] as $index=>$genre) {
                        // Filtrage avec sanitize sur chaque élément du tableau Genres
                        $genre = filter_input(INPUT_POST,"genres[$index]", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    }
        
                    // Vérifie si un genre ou plusieurs genres sont sélectionnés
                    if (!empty(array_filter($_POST["genres"]))) {
                        $TableCheck = true;
                    }
                }
        
                if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
                    $uniqueName = uniqid('', true);
                    $file = $uniqueName . "." . $extension;
                    move_uploaded_file($tmpName, 'public/img/' . $file);
        
                    if ($titre && $date_sortie && $duree_minute && $note && $synopsis && $realisateur && $TableCheck && $file) {
                        $requeteAjoutFilm = $pdo->prepare("
                            INSERT INTO film(titre, date_sortie, duree_minute, note, affiche, synopsis, id_realisateur)
                            VALUES (:titre, :date_sortie, :duree_minute, :note, :affiche, :synopsis, (SELECT r.id_realisateur FROM realisateur r WHERE id_personne =
                            (SELECT p.id_personne FROM personne p WHERE CONCAT(p.prenom,' ', p.nom) = :realisateur)))
                        ");
                        // on exécute la requête en passant les valeurs en paramètre
                        $requeteAjoutFilm->execute([
                            'titre' => $titre,
                            'date_sortie' => $date_sortie,
                            'duree_minute' => $duree_minute,
                            'note' => $note,
                            'affiche' => "$img_name",
                            'synopsis' => $synopsis,
                            'realisateur' => $realisateur
                        ]);
                        // on récupère les genres sélectionnés pour insérer dans la table contenir
                        foreach($_POST['genres'] as $genre){
                            $requeteAjoutContenir = $pdo->prepare("
                            INSERT INTO contenir (id_film, id_genre)
                            SELECT
                                (SELECT id_film FROM film WHERE titre = :titre),
                                (SELECT id_genre FROM genre WHERE libelle = :genre);
                            ");
                            $requeteAjoutContenir->execute([
                                "titre" => $titre,
                                "genre" => $genre
                            ]);
                        }
                        // Insérer les relations dans la table jouer
                        foreach($_POST['acteurs'] as $index=>$acteur){
                            $role = $_POST['roles'][$index];
                            // Vérification des valeurs d'acteur et de film avant d'insérer dans la table jouer
                            if($acteur !== 'none' && $role !== 'none') {
                                $requeteAjoutJouer = $pdo->prepare("
                                    INSERT INTO jouer(id_acteur, id_film, id_role)
                                    VALUES ((SELECT a.id_acteur FROM acteur a WHERE id_personne =
                                    (SELECT p.id_personne FROM personne p WHERE CONCAT(p.prenom,' ', p.nom) = :acteur)),
                                    (SELECT id_film FROM film WHERE titre = :titre),
                                    (SELECT id_role FROM role WHERE role_jouer = :role))
                                    ");
                                $requeteAjoutJouer->execute([
                                    "acteur" => $acteur,
                                    "titre" => $titre, 
                                    "role" => $role
                                ]);
                            }
                        }
                        $_SESSION["success"][] = "Le film a été ajouté avec succès.";
                        header("Location:index.php?action=listRealisateurGenre_ajoutFilm");die;
                    }
                } else {
                    if (!in_array($extension, $extensions)) {
                        $_SESSION["errors"][] = "L'extension du fichier n'est pas valide. Les extensions acceptées sont : " . implode(", ", $extensions);
                        header("Location:index.php?action=listRealisateurGenre_ajoutFilm");die;
                    } elseif ($size > $maxSize) {
                        $_SESSION["errors"][] = "Le fichier est trop volumineux. La taille maximale autorisée est " . ($maxSize / 1000) . " Ko.";
                        header("Location:index.php?action=listRealisateurGenre_ajoutFilm");die;
                    } elseif ($error !== 0) {
                        $_SESSION["errors"][] = "Une erreur s'est produite lors du téléchargement du fichier.";
                        header("Location:index.php?action=listRealisateurGenre_ajoutFilm");die;
                    }
                }

                $newId = $pdo->lastInsertId();
            }
            require "view/ajoutFilm.php";
        }
        
            
            // méthode pour afficher la liste des réalisateurs pour l'ajout de film
            public function listRealisateurGenre_ajoutFilm() {
                $pdo = Connect::seConnecter();
                // Reqûete pour récupérer les réalisateurs
                $requeteListRealisateur = $pdo->prepare("
                                SELECT CONCAT(p.prenom, ' ', p.nom) AS info_realisateur
                                FROM personne p
                                INNER JOIN realisateur r ON r.id_personne = p.id_personne
                ");
                $requeteListRealisateur->execute();

                // Reqûete pour récupérer les genres
                $requeteListGenre = $pdo->prepare("
                                SELECT libelle
                                FROM genre
                ");
                $requeteListGenre->execute();

                // Reqûete pour récupérer les acteurs
                $requeteListActeurs = $pdo->prepare("
                                SELECT CONCAT(p.prenom, ' ', p.nom) AS info_acteur
                                FROM personne p
                                INNER JOIN acteur a ON a.id_personne = p.id_personne
                ");
                $requeteListActeurs->execute();

                // Reqûete pour récupérer les rôles
                $requeteListRoles = $pdo->prepare("
                                SELECT role_jouer
                                FROM role
                ");
                $requeteListRoles->execute();
            
                // ajouter les entrées de casting dans la table jouer

                require "view/ajoutFilm.php";
            }
        }

?>