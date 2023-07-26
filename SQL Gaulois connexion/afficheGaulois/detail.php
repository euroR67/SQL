<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info gaulois</title>
</head>
<body>
    

<?php
    try {
        $mysqlConnection = new PDO(
            'mysql:host=localhost;dbname=gaulois;charset=utf8',
            'root',
            ''
        );
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $id = $_GET["id_gaulois"];
    $sqlQuery = 'SELECT id_personnage, adresse_personnage, nom_personnage, s.nom_specialite, l.nom_lieu 
                FROM personnage p
                INNER JOIN specialite s ON s.id_specialite = p.id_specialite
                INNER JOIN lieu l ON l.id_lieu = p.id_lieu
                WHERE id_personnage = :id';
    $gauloisStatement = $mysqlConnection->prepare($sqlQuery);
    $gauloisStatement->execute(["id" => $id]);
    $leGaulois = $gauloisStatement->fetch();
    ?>

        <div>
            <h2>Détails du personnage</h2>
            <p><?= $leGaulois['nom_personnage']; ?></p>
            <p><?= $leGaulois['adresse_personnage']; ?></p>
            <p><?= $leGaulois['nom_specialite']; ?></p>
            <p><?= $leGaulois['nom_lieu']; ?></p>
        </div>
</body>
</html>