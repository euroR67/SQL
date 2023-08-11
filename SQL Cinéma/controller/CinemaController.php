<?php 
    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 

    // on crée une classe CinemaController
    class CinemaController {

        // méthode pour afficher la page d'accueil avec les 4 films les plus récents
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

        // méthode pour afficher les détails d'un film
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
            // requête pour récupérer le casting du film
            $requeteCasting = $pdo->prepare("
                            SELECT r.role_jouer, GROUP_CONCAT(CONCAT(p.prenom, ' ', p.nom)) AS info_acteur
                            FROM film f
                            INNER JOIN jouer j ON j.id_film = f.id_film
                            INNER JOIN role r ON r.id_role = j.id_role
                            INNER JOIN acteur a ON a.id_acteur = j.id_acteur
                            INNER JOIN personne p ON p.id_personne = a.id_personne
                            WHERE f.id_film = :id
                            GROUP BY r.role_jouer;
            ");
            // on exécute la requête casting en passant l'id en paramètre
            $requeteCasting->execute(["id" => $id]);

            // on prépare la requête qui va nous permettre de récupérer les genres du film
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

        // méthode pour afficher la liste des réalisateurs
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

        // méthode pour afficher les détails d'un réalisateur
        public function detailRealisateur($id) {
            $pdo = Connect::seConnecter();
            $requeteRealisateur = $pdo->prepare("
                            SELECT CONCAT(p.prenom, ' ', p.nom) AS info_realisateur,
                            p.photo,
                            DATE_FORMAT(p.date_de_naissance, '%d/%m/%Y') AS date_de_naissance,
                            p.biographie,
                            r.id_realisateur,
                            GROUP_CONCAT(f.titre SEPARATOR ', ') AS films_realiser
                            FROM personne p
                            INNER JOIN realisateur r ON r.id_personne = p.id_personne
                            LEFT JOIN film f ON f.id_realisateur = r.id_realisateur
                            WHERE r.id_realisateur = :id;
                            GROUP BY r.id_realisateur
            ");
            $requeteRealisateur->execute(["id" => $id]);
            require "view/detailRealisateur.php";
        }

        // méthode pour afficher la liste des réalisateurs
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

        // méthode pour afficher les détails d'un acteur
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

        // méthode pour afficher les films par genre
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
        
        // méthode pour afficher la liste des genres
        public function listGenres() {
            $pdo = Connect::seConnecter();
            $requeteGenres = $pdo->query("
                            SELECT g.id_genre, g.libelle
                            FROM genre g
            ");
            require "view/genres.php";
        }

        // méthode pour afficher les détails d'un genre
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

        // méthode pour afficher la liste des personnages/rôles
        public function listRoles() {
            $pdo = Connect::seConnecter();
            $requeteRoles = $pdo->query("
                            SELECT r.id_role, r.role_jouer
                            FROM role r
            ");
            require "view/roles.php";
        }

        // méthode pour afficher les détails d'un rôle
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
            $requeteRole->execute(["id" => $id]);
            $roles = $requeteRole->fetchAll();
            require "view/detailRole.php";
        }

        // méthode pour ajouter un genre
        public function ajouterGenre() {
            if(isset($_POST["submit"])){
                $pdo = Connect::seConnecter();
                $requeteAjoutGenre = $pdo->prepare("
                            INSERT INTO genre(libelle)
                            VALUES (:name)
                ");
                $requeteAjoutGenre->execute([
                    'name' => $_POST['name']
                ]);
                // on récupère les films sélectionnés
                foreach($_POST['films'] as $film){
                    $requeteAjoutGenre2 = $pdo->prepare("
                    INSERT INTO contenir (id_film, id_genre)
                    SELECT
                        (SELECT id_film FROM film WHERE titre = :film),
                        (SELECT id_genre FROM genre WHERE libelle = :genre);
                    ");
                $requeteAjoutGenre2->execute(["film" => $film, "genre" => $_POST['name']]);
                }
                $newId = $pdo->lastInsertId();
                header("Location:index.php?action=listGenres");
            }
        
            require "view/ajoutGenre.php";
        }

        // méthode pour afficher la liste des films pour l'ajout de genre
        public function listFilm_ajoutGenre() {
            $pdo = Connect::seConnecter();
            $requeteFilms = $pdo->prepare("
                            SELECT titre
                            FROM film
            ");
            $requeteFilms->execute();
            require "view/ajoutGenre.php";
        }

        // méthode pour ajouter un film
        public function ajouterFilm() {
            
            if(isset($_POST["submit"])){
                $pdo = Connect::seConnecter();
                // Importer l'image chargée dans le dossier public/img
                move_uploaded_file($_FILES['affiche']['tmp_name'], 'public/img/'.$_FILES['affiche']['name']);
                $requeteAjoutFilm = $pdo->prepare("
                            INSERT INTO film(titre, date_sortie, duree_minute, note, affiche, synopsis, id_realisateur)
                            VALUES (:titre, :date_sortie, :duree_minute, :note, :affiche, :synopsis, (SELECT r.id_realisateur FROM realisateur r WHERE id_personne =
                            (SELECT p.id_personne FROM personne p WHERE CONCAT(p.prenom,' ', p.nom) = :realisateur)))
                ");
                // on exécute la requête en passant les valeurs en paramètre
                $requeteAjoutFilm->execute([
                    'titre' => $_POST['titre'],
                    'date_sortie' => $_POST['date_sortie'],
                    'duree_minute' => $_POST['duree_minute'],
                    'note' => $_POST['note'],
                    'affiche' => $_FILES["affiche"]["name"],
                    'synopsis' => $_POST['synopsis'],
                    'realisateur' => $_POST['realisateur']
                ]);
                // on récupère les genres sélectionnés
                foreach($_POST['genres'] as $genre){
                    $requeteAjoutContenir = $pdo->prepare("
                    INSERT INTO contenir (id_film, id_genre)
                    SELECT
                        (SELECT id_film FROM film WHERE titre = :titre),
                        (SELECT id_genre FROM genre WHERE libelle = :genre);
                    ");
                $requeteAjoutContenir->execute([
                    "titre" => $_POST['titre'],
                    "genre" => $genre]);
                }
                
                // Insérer les relations dans la table jouer
                foreach($_POST['acteurs'] as $index=>$acteur){
                $requeteAjoutJouer = $pdo->prepare("
                            INSERT INTO jouer(id_acteur, id_film, id_role)
                            VALUES ((SELECT a.id_acteur FROM acteur a WHERE id_personne =
                            (SELECT p.id_personne FROM personne p WHERE CONCAT(p.prenom,' ', p.nom) = :acteur)),
                            (SELECT id_film FROM film WHERE titre = :titre),
                            (SELECT id_role FROM role WHERE role_jouer = :role))
                            ");
                $requeteAjoutJouer->execute([
                    "acteur" => $acteur,
                    "titre" => $_POST['titre'], 
                    "role" => $_POST['roles'][$index]
                ]);
                }
                $newId = $pdo->lastInsertId();
                header("Location:index.php?action=listFilms");
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
                // Importer l'image chargée dans le dossier public/img
                move_uploaded_file($_FILES['photo']['tmp_name'], 'public/img/'.$_FILES['photo']['name']);
                $requeteAjoutRealisateur = $pdo->prepare("
                            INSERT INTO personne(nom, prenom, date_de_naissance, sexe, photo, biographie)
                            VALUES (:nom, :prenom, :date_de_naissance, :sexe, :photo, :biographie)
                ");
                // on exécute la requête en passant les valeurs en paramètre
                $requeteAjoutRealisateur->execute([
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'date_de_naissance' => $_POST['date_de_naissance'],
                    'sexe' => $_POST['sexe'],
                    'photo' => $_FILES["photo"]["name"],
                    'biographie' => $_POST['biographie']
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

        
            require "view/ajoutRealisateur.php";
        }

        public function ajouterActeur() {
            if(isset($_POST["submit"])){
                $pdo = Connect::seConnecter();
                // Importer l'image chargée dans le dossier public/img
                move_uploaded_file($_FILES['photo']['tmp_name'], 'public/img/'.$_FILES['photo']['name']);
                // requête pour insérer le personnage dans la table personne
                $requeteAjoutActeur = $pdo->prepare("
                            INSERT INTO personne(nom, prenom, date_de_naissance, sexe, photo, biographie)
                            VALUES (:nom, :prenom, :date_de_naissance, :sexe, :photo, :biographie)
                ");
                // on exécute la requête en passant les valeurs en paramètre
                $requeteAjoutActeur->execute([
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'date_de_naissance' => $_POST['date_de_naissance'],
                    'sexe' => $_POST['sexe'],
                    'photo' => $_FILES["photo"]["name"],
                    'biographie' => $_POST['biographie']
                ]);

                // on récupère l'id du personnage inséré
                $newPersonId = $pdo->lastInsertId();
                
                // on insère le personnage dans la table acteur
                $requeteAjoutActeur2 = $pdo->prepare("
                            INSERT INTO acteur (id_personne)
                            VALUES (:id_personne)
                            ");
                $requeteAjoutActeur2->execute([
                    "id_personne" => $newPersonId // on passe l'id du personnage inséré en paramètre
                ]);
                
                // Insérer les relations dans la table jouer
                foreach($_POST['films'] as $index=>$film){
                    $acteur = $_POST['prenom'].' '.$_POST['nom'];
                    $role = $_POST['roles'][$index];
                    // requête pour insérer les relations dans la table jouer
                    $requeteAjoutJouer = $pdo->prepare("
                            INSERT INTO jouer(id_acteur, id_film, id_role)
                            VALUES ((SELECT a.id_acteur FROM acteur a WHERE id_personne =
                            (SELECT p.id_personne FROM personne p WHERE CONCAT(p.prenom,' ', p.nom) = :acteur)),
                            (SELECT id_film FROM film WHERE titre = :film),
                            (SELECT id_role FROM role WHERE role_jouer = :role))
                            ");
                    // on exécute la requête en passant les valeurs en paramètre
                    $requeteAjoutJouer->execute([
                        "acteur" => $acteur,
                        "film" => $film, // on passe l'index du film en paramètre
                        "role" => $role // on passe l'index du rôle en paramètre
                    ]);
                }
                
                $newId = $pdo->lastInsertId();
                header("Location:index.php?action=listActeurs");
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
                // on insère le nouveau rôle dans la table role
                $requeteAjoutRole = $pdo->prepare("
                            INSERT INTO role(role_jouer)
                            VALUES (:role_jouer)
                ");
                $requeteAjoutRole->execute([
                    'role_jouer' => $_POST['role_jouer']
                ]);
                $newId = $pdo->lastInsertId();
                header("Location:index.php?action=listRoles");
            }

            foreach($_POST['films'] as $index=>$film){
                $acteur = $_POST['prenom'].' '.$_POST['nom'];
                $role = $_POST['roles'][$index];
                // requête pour insérer les relations dans la table jouer
                $requeteAjoutJouer = $pdo->prepare("
                        INSERT INTO jouer(id_acteur, id_film, id_role)
                        VALUES ((SELECT a.id_acteur FROM acteur a WHERE id_personne =
                        (SELECT p.id_personne FROM personne p WHERE CONCAT(p.prenom,' ', p.nom) = :acteur)),
                        (SELECT id_film FROM film WHERE titre = :film),
                        (SELECT id_role FROM role WHERE role_jouer = :role))
                        ");
                // on exécute la requête en passant les valeurs en paramètre
                $requeteAjoutJouer->execute([
                    "acteur" => $acteur,
                    "film" => $film, // on passe l'index du film en paramètre
                    "role" => $role // on passe l'index du rôle en paramètre
                ]);
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