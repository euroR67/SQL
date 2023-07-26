<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Affichage Potion</title>
        <style>
            * {
                margin : 0;
                padding : 0;
                box-sizing: border-box;
                font-family: sans-serif;
            }
        </style>
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
            
            // on stock les requêtes dans des variables
            $sqlQuery = 'SELECT id_potion, nom_potion FROM potion';

            // on exécute la requête pour récupérer les infos de la potion
            $potionStatement = $mysqlConnection->query($sqlQuery); // on prépare la requête
            $potionStatement->execute(); // on l'exécute sans lui donner d'id
            $allPotion = $potionStatement->fetchAll(); // on récupère la liste des potions
            ?>
                    <!-- on affiche les infos de la potion a l'aide d'une boucle foreach car il y en a plusieurs -->
                    <?php foreach ($allPotion as $potion) { ?>
                        <p><a href="detail_potion.php?id_potion=<?= $potion["id_potion"] ?>"><?= $potion['nom_potion']; ?></a></p>
                    <?php } ?>
    </body>
</html>