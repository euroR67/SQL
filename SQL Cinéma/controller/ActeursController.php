<?php 

    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 
        // on crée une classe CinemaController
        class ActeursController {
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
                                SELECT f.id_film, f.titre, YEAR(f.date_sortie) AS annee
                                FROM film f
                                INNER JOIN jouer j ON j.id_film = f.id_film
                                INNER JOIN acteur a ON a.id_acteur = j.id_acteur
                                WHERE a.id_acteur = :id
                                ORDER BY f.date_sortie DESC;
                ");
                $requeteFilmsActeur->execute(["id" => $id]);

                require "view/detailActeur.php";
            }

            // Méthode pour ajouter un acteur et les relation casting
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
                    
                    $extensions = ['jpg', 'png', 'jpeg', 'gif', 'webp'];
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
                        $_SESSION["success"][] = "L'acteur/actrice a été ajouté avec succès.";
                        header("Location:index.php?action=listFilmRole_ajoutActeur"); die;
                    } else {
                        // Gestion des erreurs liées à l'image
                        if (!in_array($extension, $extensions)) {
                            $_SESSION["errors"][] = "L'extension du fichier n'est pas valide. Les extensions acceptées sont : " . implode(", ", $extensions);
                            header("Location:index.php?action=listFilmRole_ajoutActeur"); die;
                        } elseif ($size > $maxSize) {
                            $_SESSION["errors"][] = "Le fichier est trop volumineux. La taille maximale autorisée est " . ($maxSize / 1000) . " Ko.";
                            header("Location:index.php?action=listFilmRole_ajoutActeur"); die;
                        } elseif ($error !== 0) {
                            $_SESSION["errors"][] = "Une erreur s'est produite lors du téléchargement du fichier.";
                            header("Location:index.php?action=listFilmRole_ajoutActeur"); die;
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
        }

?>