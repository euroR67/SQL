<?php 
    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 
    // on crée une classe CinemaController
    class CinemaController {

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
                            SELECT titre,
                            YEAR(date_sortie) AS annee,
                            CONCAT(FLOOR(duree_minute / 60), 'h', LPAD(MOD(duree_minute, 60), 2, '0')) AS duree,
                            note,
                            affiche,id_film
                            FROM film");

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
                            r.role_jouer,
                            re.id_realisateur
                            FROM film f
                            INNER JOIN jouer j ON j.id_film = f.id_film
                            INNER JOIN role r ON r.id_role = j.id_role
                            INNER JOIN realisateur re ON re.id_realisateur = f.id_realisateur
                            INNER JOIN personne p ON p.id_personne = re.id_personne
                            WHERE f.id_film = :id;
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

        // ================= méthode pour afficher la liste des réalisateurs =================
        public function listRealisateurs() {
            $pdo = Connect::seConnecter();
            $requeteRealisateurs = $pdo->query("
                            SELECT CONCAT(p.prenom, ' ', p.nom) AS info_realisateur,
                            p.photo,
                            r.id_realisateur
                            FROM personne p
                            INNER JOIN realisateur r ON r.id_personne = p.id_personne
            ");
            require "view/realisateurs.php";
        }

        // ================= méthode pour afficher les détails d'un réalisateur =================
        public function detailRealisateur($id) {
            $pdo = Connect::seConnecter();
            $requeteRealisateur = $pdo->prepare("
                            SELECT CONCAT(p.prenom, ' ', p.nom) AS info_realisateur,
                            p.photo,
                            DATE_FORMAT(p.date_de_naissance, '%d/%m/%Y') AS date_de_naissance,
                            p.biographie,
                            r.id_realisateur,
                            GROUP_CONCAT(f.titre) AS films_realiser
                            FROM personne p
                            INNER JOIN realisateur r ON r.id_personne = p.id_personne
                            LEFT JOIN film f ON f.id_realisateur = r.id_realisateur
                            WHERE r.id_realisateur = :id
                            GROUP BY r.id_realisateur
            ");
            $requeteRealisateur->execute(["id" => $id]);

            $requeteFilmsRealisateur = $pdo->prepare("
                            SELECT f.id_film, f.titre
                            FROM film f
                            INNER JOIN realisateur r ON r.id_realisateur = f.id_realisateur
                            WHERE f.id_realisateur = :id
            ");
            $requeteFilmsRealisateur->execute(["id" => $id]);

            require "view/detailRealisateur.php";
        }

        // ================= méthode pour afficher la liste des réalisateurs =================
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

        // ================= méthode pour afficher les détails d'un acteur =================
        public function detailActeur($id) {
            $pdo = Connect::seConnecter();
            $requeteActeur = $pdo->prepare("
                            SELECT CONCAT(p.prenom, ' ', p.nom) AS info_acteur,
                            p.photo,
                            DATE_FORMAT(p.date_de_naissance, '%d/%m/%Y') AS date_de_naissance,
                            p.biographie,
                            a.id_acteur,
                            GROUP_CONCAT(f.titre) AS films_jouer
                            FROM personne p
                            INNER JOIN acteur a ON a.id_personne = p.id_personne
                            LEFT JOIN jouer j ON j.id_acteur = a.id_acteur
                            LEFT JOIN film f ON f.id_film = j.id_film
                            WHERE a.id_acteur = :id
                            GROUP BY a.id_acteur
            ");
            $requeteActeur->execute(["id" => $id]);

            $requeteFilmsActeur = $pdo->prepare("
                            SELECT f.id_film, f.titre
                            FROM film f
                            INNER JOIN jouer j ON j.id_film = f.id_film
                            INNER JOIN acteur a ON a.id_acteur = j.id_acteur
                            WHERE a.id_acteur = :id
            ");
            $requeteFilmsActeur->execute(["id" => $id]);

            require "view/detailActeur.php";
        }

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
                            SELECT f.id_film,
                            f.titre,
                            YEAR(date_sortie) AS annee,
                            CONCAT(FLOOR(duree_minute / 60), 'h', LPAD(MOD(duree_minute, 60), 2, '0')) AS duree,
                            f.affiche,
                            f.note
                            FROM FILM f
                            INNER JOIN contenir c ON f.id_film = c.id_film
                            WHERE c.id_genre = :id;
            ");
            $requeteFilmParGenre->execute(["id" => $id]);

            require "view/detailGenre.php";
        }

        // ================= méthode pour afficher la liste des personnages/rôles =================
        public function listRoles() {
            $pdo = Connect::seConnecter();
            $requeteRoles = $pdo->query("
                            SELECT r.id_role, r.role_jouer
                            FROM role r
            ");
            require "view/roles.php";
        }

        // ================= méthode pour afficher les détails d'un rôle =================
        public function detailRole($id) {
            $pdo = Connect::seConnecter();
            // on prépare la requête qui va nous permettre de récupérer les infos du role
            $requeteRole = $pdo->prepare("
                            SELECT CONCAT(p.nom, ' ' , p.prenom) AS acteur, f.titre AS film_joue,
                            p.id_personne,
                            a.id_acteur,
                            r.id_role, r.role_jouer,
                            f.id_film
                            FROM PERSONNE p
                            INNER JOIN ACTEUR a ON p.id_personne = a.id_personne
                            INNER JOIN JOUER j ON a.id_acteur = j.id_acteur
                            INNER JOIN FILM f ON j.id_film = f.id_film
                            INNER JOIN ROLE r ON j.id_role = r.id_role
                            WHERE j.id_role = :id;
                            ");
            $requeteRole2 = $pdo->prepare("
                            SELECT  role.role_jouer
                            FROM role 
                            WHERE id_role = :id;
                            ");
            $requeteRole->execute(["id" => $id]);             
            $requeteRole2->execute(["id" => $id]);
            
            require "view/detailRole.php";
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
                    header("Location:index.php?action=listGenres");
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
                    header("Location:index.php?action=listGenres");
                }
                // Sinon on affiche un message d'erreur
                else {
                    session_start();
                    $_SESSION["errors"][] = "Le champ du nom du genre ne peut pas être vide.";
                    header("Location:index.php?action=listFilm_ajoutGenre");
                    exit();
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
                $maxSize = 40000000;
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
                        header("Location:index.php?action=listFilms");
                    }
                } else {
                    if (!in_array($extension, $extensions)) {
                        $erreur_message = "L'extension du fichier n'est pas valide. Les extensions acceptées sont : " . implode(", ", $extensions);
                    } elseif ($size > $maxSize) {
                        $erreur_message = "Le fichier est trop volumineux. La taille maximale autorisée est " . ($maxSize / 1000) . " Ko.";
                    } elseif ($error !== 0) {
                        $erreur_message = "Une erreur s'est produite lors du téléchargement du fichier.";
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

        // méthode pour ajouter un personnage qui est un réalisateur
        public function ajouterRealisateur() {
            if(isset($_POST["submit"])){
                $pdo = Connect::seConnecter();

                $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $date_de_naissance = filter_input(INPUT_POST, "date_de_naissance", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $sexe = filter_input(INPUT_POST, "sexe", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $biographie = filter_input(INPUT_POST, "biographie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
                // Vérification extension et erreurs de l'affiche
                $tmpName = $_FILES["photo"]["tmp_name"];
                $img_name = $_FILES["photo"]["name"];
                $size = $_FILES["photo"]["size"];
                $error = $_FILES["photo"]["error"];
                $type = $_FILES["photo"]["type"];
        
                $tabExtension = explode('.', $img_name); // Sépare le nom du fichier et son extension
                $extension = strtolower(end($tabExtension)); // Stock l'extension
        
                //Tableau des extensions acceptées
                $extensions = ['jpg', 'png', 'jpeg', 'gif'];
                // Taille maximale acceptée (en bytes)
                $maxSize = 40000000;

                if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
                    $uniqueName = uniqid('', true);
                    $file = $uniqueName . "." . $extension;
                    // Importer l'image chargée dans le dossier public/img
                    move_uploaded_file($tmpName, 'public/img/' . $file);

                    if ($nom && $prenom && $date_de_naissance && $sexe && $biographie && $file) {
                        $requeteAjoutRealisateur = $pdo->prepare("
                            INSERT INTO personne(nom, prenom, date_de_naissance, sexe, photo, biographie)
                            VALUES (:nom, :prenom, :date_de_naissance, :sexe, :photo, :biographie)
                        ");
                        // on exécute la requête en passant les valeurs en paramètre
                        $requeteAjoutRealisateur->execute([
                            'nom' => $nom,
                            'prenom' => $prenom,
                            'date_de_naissance' => $date_de_naissance,
                            'sexe' => $sexe,
                            'photo' => "$img_name",
                            'biographie' => $biographie
                        ]);

                        $newPersonId = $pdo->lastInsertId();
                        // on récupère les films sélectionnés
                        
                        $requeteAjoutRealisateur2 = $pdo->prepare("
                                    INSERT INTO realisateur (id_personne)
                                    VALUES (:id_personne)
                                    ");
                        $requeteAjoutRealisateur2->execute([
                            "id_personne" => $newPersonId
                        ]);
                        
                        $newId = $pdo->lastInsertId();
                        header("Location:index.php?action=listRealisateurs");
                    }
                } else {
                    if (!in_array($extension, $extensions)) {
                        $erreur_message = "L'extension du fichier n'est pas valide. Les extensions acceptées sont : " . implode(", ", $extensions);
                    } elseif ($size > $maxSize) {
                        $erreur_message = "Le fichier est trop volumineux. La taille maximale autorisée est " . ($maxSize / 1000) . " Ko.";
                    } elseif ($error !== 0) {
                        $erreur_message = "Une erreur s'est produite lors du téléchargement du fichier.";
                    }
                }
            }
            require "view/ajoutRealisateur.php";
        }

        public function ajouterActeur() {
            if(isset($_POST["submit"])){
                $pdo = Connect::seConnecter();
                
                $erreur_message = "";

                $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $date_de_naissance = filter_input(INPUT_POST, "date_de_naissance", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $sexe = filter_input(INPUT_POST, "sexe", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $biographie = filter_input(INPUT_POST, "biographie", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
                // Vérification et traitement de l'image chargée
                $tmpName = $_FILES['photo']['tmp_name'];
                $img_name = $_FILES['photo']['name'];
                $size = $_FILES['photo']['size'];
                $error = $_FILES['photo']['error'];
                
                $extensions = ['jpg', 'png', 'jpeg', 'gif'];
                $maxSize = 40000000;
        
                $tabExtension = explode('.', $img_name);
                $extension = strtolower(end($tabExtension));
                
                if (in_array($extension, $extensions) && $error == 0 && $size <= $maxSize) {
                    $uniqueName = uniqid('', true);
                    $file = $uniqueName . "." . $extension;
                    move_uploaded_file($tmpName, 'public/img/' . $file);

                    if ($nom && $prenom && $date_de_naissance && $sexe && $biographie && $file) {
                        // Insère le personnage dans la table personne
                        $requeteAjoutActeur = $pdo->prepare("
                            INSERT INTO personne(nom, prenom, date_de_naissance, sexe, photo, biographie)
                            VALUES (:nom, :prenom, :date_de_naissance, :sexe, :photo, :biographie)
                        ");
                        $requeteAjoutActeur->execute([
                            'nom' => $nom,
                            'prenom' => $prenom,
                            'date_de_naissance' => $date_de_naissance,
                            'sexe' => $sexe,
                            'photo' => $img_name,
                            'biographie' => $biographie
                        ]);
            
                        // Récupère l'id du personnage inséré
                        $newPersonId = $pdo->lastInsertId();
                        
                        // Insère le personnage dans la table acteur
                        $requeteAjoutActeur2 = $pdo->prepare("
                            INSERT INTO acteur (id_personne)
                            VALUES (:id_personne)
                        ");
                        $requeteAjoutActeur2->execute([
                            "id_personne" => $newPersonId
                        ]);
                        
                        // Insérer les relations dans la table jouer
                        foreach($_POST['films'] as $index => $film){
                            $role = $_POST['roles'][$index];
                            if($film !== 'none' && $role !== 'none') {
                                $requeteAjoutJouer = $pdo->prepare("
                                    INSERT INTO jouer(id_acteur, id_film, id_role)
                                    VALUES (
                                        (SELECT a.id_acteur FROM acteur a WHERE id_personne =
                                            (SELECT p.id_personne FROM personne p WHERE CONCAT(p.prenom,' ', p.nom) = :acteur)),
                                        (SELECT id_film FROM film WHERE titre = :film),
                                        (SELECT id_role FROM role WHERE role_jouer = :role)
                                    )
                                ");
                                $requeteAjoutJouer->execute([
                                    "acteur" => $prenom . ' ' . $nom,
                                    "film" => $film,
                                    "role" => $role
                                ]);
                            }
                        }
                    }
                    
                    header("Location:index.php?action=listActeurs");
                } else {
                    // Gestion des erreurs liées à l'image
                    if (!in_array($extension, $extensions)) {
                        $erreur_message = "L'extension du fichier n'est pas valide. Les extensions acceptées sont : " . implode(", ", $extensions);
                    } elseif ($size > $maxSize) {
                        $erreur_message = "Le fichier est trop volumineux. La taille maximale autorisée est " . ($maxSize / 1000) . " Ko.";
                    } elseif ($error !== 0) {
                        $erreur_message = "Une erreur s'est produite lors du téléchargement du fichier.";
                    }
                }
            }
            require "view/ajouterActeur.php";
        }
        
        

        // méthode pour afficher la liste des films et rôles pour l'ajout de l'acteur
        public function listFilmRole_ajoutActeur(){
            $pdo = Connect::seConnecter();
            // Reqûete pour récupérer les films
            $requeteListFilm = $pdo->prepare("
                            SELECT titre
                            FROM film
            ");
            $requeteListFilm->execute();

            // Reqûete pour récupérer les rôles
            $requeteListRoles = $pdo->prepare("
                            SELECT role_jouer
                            FROM role
            ");
            $requeteListRoles->execute();
            require "view/ajouterActeur.php";
        }

        // méthode pour ajouter un rôle
        public function ajouterRole() {
            if(isset($_POST["submit"])){
                $pdo = Connect::seConnecter();

                $erreur_message = "";

                foreach ($_POST['role_jouer'] as $index => $role_jouer) {
                    // Nettoyage de l'entrée du rôle
                    $role_jouer = filter_var($role_jouer, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    // Si le champ rôle est renseigner on procède a l'ajout du rôle
                    if($role_jouer) {
                        // Insère le nouveau rôle dans la table role
                        $requeteAjoutRole = $pdo->prepare("
                        INSERT INTO role(role_jouer)
                        VALUES (:role_jouer)
                        ");
                        $requeteAjoutRole->execute([
                            'role_jouer' => $role_jouer
                        ]);
                        // Récupère le nouvel ID de rôle
                        $newRoleId = $pdo->lastInsertId();

                        // Filtrage et nettoyage des valeurs d'acteur et de film
                        $acteur = filter_var($_POST['acteurs'][$index], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $film = filter_var($_POST['films'][$index], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        
                        // Vérification des valeurs d'acteur et de film
                        if ($acteur !== 'none' && $film !== 'none') {
                            // Requête pour insérer la relation dans la table jouer
                            $requeteAjoutJouer = $pdo->prepare("
                                INSERT INTO jouer(id_acteur, id_film, id_role)
                                VALUES ((SELECT a.id_acteur FROM acteur a WHERE id_personne =
                                        (SELECT p.id_personne FROM personne p WHERE CONCAT(p.prenom,' ', p.nom) = :acteur)),
                                        (SELECT id_film FROM film WHERE titre = :film),
                                        :id_role)
                            ");
                            
                            // On exécute la requête en passant les valeurs en paramètre
                            $requeteAjoutJouer->execute([
                                "acteur" => $acteur,
                                "film" => $film,
                                "id_role" => $newRoleId // Utilisation du nouvel ID de rôle
                            ]);
                        }
                        header("Location:index.php?action=listRoles");
                    } else {
                        // Si le champ rôle n'est pas renseigné on renvoi un message d'erreur
                        $erreur_message = "Veuillez renseigner le nom du rôle";
                    }
                }
            }
        
            require "view/ajoutRole.php";
        }

        // méthode pour afficher la liste des acteurs et films pour l'ajout de rôle
        public function listActeurFilm_ajoutRole() {
            $pdo = Connect::seConnecter();
            // Reqûete pour récupérer les acteurs
            $requeteListActeurs = $pdo->prepare("
                            SELECT CONCAT(p.prenom, ' ', p.nom) AS info_acteur
                            FROM personne p
                            INNER JOIN acteur a ON a.id_personne = p.id_personne
            ");
            $requeteListActeurs->execute();

            // Reqûete pour récupérer les films
            $requeteListFilm = $pdo->prepare("
                            SELECT titre
                            FROM film
            ");
            $requeteListFilm->execute();
            require "view/ajoutRole.php";
        }
    }
?>