<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Affichage Gaulois</title>
        <style>
            * {
                margin : 0;
                padding : 0;
                box-sizing: border-box;
                font-family: sans-serif;
            }
            table, td {
                border: 1px solid gray;
                padding: 3px;
            }
        </style>
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
    
    $sqlQuery = 'SELECT id_personnage, nom_personnage, s.nom_specialite, l.nom_lieu 
                FROM personnage p
                INNER JOIN specialite s ON s.id_specialite = p.id_specialite
                INNER JOIN lieu l ON l.id_lieu = p.id_lieu';
    $gauloisStatement = $mysqlConnection->prepare($sqlQuery);
    $gauloisStatement->execute();
    $allGaulois = $gauloisStatement->fetchAll();
    ?>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Specialité</th>
                    <th>Ville</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allGaulois as $gaulois) { ?>
                    <tr>
                        <td><a href="detail.php?id_gaulois=<?= $gaulois["id_personnage"] ?>"><?= $gaulois['nom_personnage']; ?></a></td>
                        <td><?= $gaulois['nom_specialite']; ?></td>
                        <td><?= $gaulois['nom_lieu']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </body>
</html>