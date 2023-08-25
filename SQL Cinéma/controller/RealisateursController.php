<?php 
    // catégoriser virtuellement (dans un espace de nom la classe en question)
    namespace Controller;
    // Accéder à la classe Connect située dans le namespace "Model"
    use Model\Connect; 

    class RealisateursController {
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
                            SELECT f.id_film, f.titre, YEAR(f.date_sortie) AS annee
                            FROM film f
                            INNER JOIN realisateur r ON r.id_realisateur = f.id_realisateur
                            WHERE f.id_realisateur = :id
                            ORDER BY f.date_sortie DESC;
            ");
            $requeteFilmsRealisateur->execute(["id" => $id]);

            require "view/detailRealisateur.php";
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
                $extensions = ['jpg', 'png', 'jpeg', 'gif', 'wepb'];
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
                        
                        $_SESSION["success"][] = "Le réalisateur a été ajouté avec succès.";
                        header("Location:index.php?action=ajouterRealisateur"); die;
                    }
                } else {
                    if (!in_array($extension, $extensions)) {
                        $_SESSION["errors"][] = "L'extension du fichier n'est pas valide. Les extensions acceptées sont : " . implode(", ", $extensions);
                        header("Location:index.php?action=ajouterRealisateur"); die;
                    } elseif ($size > $maxSize) {
                        $_SESSION["errors"][] = "Le fichier est trop volumineux. La taille maximale autorisée est " . ($maxSize / 1000) . " Ko.";
                        header("Location:index.php?action=ajouterRealisateur"); die;
                    } elseif ($error !== 0) {
                        $_SESSION["errors"][] = "Une erreur s'est produite lors du téléchargement du fichier.";
                        header("Location:index.php?action=ajouterRealisateur"); die;
                    }
                }
            }
            require "view/ajoutRealisateur.php";
        }
    }
?>