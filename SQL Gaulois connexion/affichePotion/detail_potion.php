<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info potion</title>
</head>
<body>
    

<?php

    // on se connecte à la base de données
    try {
        $mysqlConnection = new PDO(
            'mysql:host=localhost;dbname=gaulois;charset=utf8',
            'root',
            ''
        );
        // on active les erreurs PDO
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    // on récupère l'id de la potion dans l'url depuis le fichier liste_potion.php
    $id = $_GET["id_potion"];
    
    // on stock les requêtes dans des variables

    // on récupère les infos de la potion
    $sqlQuery = 'SELECT nom_potion
                FROM potion p
                WHERE p.id_potion = :id';

    // on récupère les infos des ingrédients de la potion
    $sqlIngredients = 'SELECT i.nom_ingredient, i.cout_ingredient, c.qte
                FROM potion p
                INNER JOIN composer c ON c.id_potion = p.id_potion
                INNER JOIN ingredient i ON i.id_ingredient = c.id_ingredient
                WHERE p.id_potion = :id';
    
    // on exécute la requête pour récupérer les infos de la potion
    $potionStatement = $mysqlConnection->prepare($sqlQuery); // on prépare la requête
    $potionStatement->execute(["id" => $id]); // on l'exécute en lui donnant l'id de la potion
    $potion = $potionStatement->fetch(); // on récupère les infos de la potion

    // on exécute la requête pour récupérer les ingrédients
    $ingredientStatement = $mysqlConnection->prepare($sqlIngredients);
    $ingredientStatement->execute(["id" => $id]);
    $ingredients = $ingredientStatement->fetchAll();
    ?>
        <!-- on affiche les infos de la potion -->
        <div>
            <h2>Détails de la potion</h2>
            <!-- on affiche le nom de la potion -->
            <h3><?= $potion["nom_potion"] ?></h3>
            <!-- on affiche les ingrédients de la potion a l'aide d'une boucle foreach car il y en a plusieurs -->
            <?php foreach ($ingredients as $ingredient) {?>
                <p><?= $ingredient['nom_ingredient']; ?></p>
                <p><?= $ingredient['cout_ingredient']; ?></p>
                <p><?= $ingredient['qte']; ?></p>
            <?php } ?>
        </div>
</body>
</html>