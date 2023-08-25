<?php 

    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 
        // on crée une classe CinemaController
        class RolesController {
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
                                SELECT CONCAT(p.prenom, ' ' , p.nom) AS acteur, f.titre AS film_joue,
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
                            $_SESSION["success"][] = "Le rôle a été ajouté avec succès. ";
                            header("Location:index.php?action=listActeurFilm_ajoutRole");;die;
                        } else {
                            // Si le champ rôle n'est pas renseigné on renvoi un message d'erreur
                            $_SESSION["errors"][] = "Veuillez renseigner le nom du rôle";
                            header("Location:index.php?action=listActeurFilm_ajoutRole");;die;
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