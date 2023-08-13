<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
?>

<main>
    <!-- Section des top 4 films -->
    <div class="detail">
        <?php $film=$requeteFilm->fetch() ?>
        <img src="public/img/<?= $film["affiche"] ?>" alt="">
        <div class="info">
            <h2><?= $film["titre"] ?></h2>
            <p>Réalisateur :
                <a class="yellow-link" href="index.php?action=detailRealisateur&id=<?=$film["id_realisateur"]?>">
                    <?= $film["info_realisateur"] ?>
                </a>
            </p>
            <p>Casting : <?php foreach ($requeteCasting->fetchAll() as $casting) { ?>
                <a class="yellow-link" href="index.php?action=detailActeur&id=<?= $casting["id_acteur"] ?>">
                    <?= $casting["info_acteur"] ?>
                </a>
                incarne le rôle de
                <a class="yellow-link" href="index.php?action=detailRole&id=<?= $casting["id_role"] ?>">
                    <?= $casting["role_jouer"] ?>
                </a><br>
            <?php } ?>
            </p>

            <p>Genres :
                <?php foreach ($requeteGenre->fetchAll() as $filmGenres) { ?>
                    <a class="yellow-link" href="index.php?action=detailGenre&id=<?= $filmGenres["id_genre"] ?>">
                        <?= $filmGenres["genres"] ?>
                    </a>,
                <?php } ?>
            </p>

            <p>Date de sortie en France : <?= $film["date_sortie"] ?></p>
            <p>Durée : <?= $film["duree"] ?></p><br>
            <p>Synopsis :</p><br>
            <p><?= $film["synopsis"] ?></p>
        </div>
    </div>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Détail du film " . $film["titre"];
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>