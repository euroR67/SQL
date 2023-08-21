<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
?>

<main>
    <!-- Section des top 4 films -->
    <div class="detail">
        <?php $film = $requeteFilm->fetch() ?>
        
        <div class="info">
            <img src="public/img/<?= $film["affiche"] ?>" alt="">
            <h2><?= $film["titre"] ?></h2>
            <p>Réalisateur :
                <a class="yellow-link" href="index.php?action=detailRealisateur&id=<?=$film["id_realisateur"]?>">
                    <?= $film["info_realisateur"] ?>
                </a>
            </p>
            
            <?php if ($requeteCasting->rowCount() > 0): ?>
                <p>Casting :<br> <?php foreach ($requeteCasting->fetchAll() as $casting) { ?>
                    <?php if ($casting["info_acteur"] && $casting["role_jouer"]) : ?>
                        <a class="yellow-link" href="index.php?action=detailActeur&id=<?= $casting["id_acteur"] ?>">
                            <?= $casting["info_acteur"] ?>
                        </a>
                        incarne
                        <a class="yellow-link" href="index.php?action=detailRole&id=<?= $casting["id_role"] ?>">
                            <?= $casting["role_jouer"] ?>
                        </a><br>
                    <?php endif; ?>
                <?php } ?>
                </p>
            <?php else: ?>
                <p>Casting : Aucun casting n'a été sélectionné pour ce film.</p>
            <?php endif; ?>

            <p>Genres :
                <?php foreach ($requeteGenre->fetchAll() as $filmGenres) { ?>
                    <a class="yellow-link custom-class" href="index.php?action=detailGenre&id=<?= $filmGenres["id_genre"] ?>">
                        <?= $filmGenres["genres"] ?>
                    </a>
                <?php } ?>
            </p>

            <p>Date de sortie en France : <i class="uil uil-calendar-alt"></i> <?= $film["date_sortie"] ?></p>
            <p>Durée : <i class="uil uil-clock"></i> <?= $film["duree"] ?></p><br>
            <p>Synopsis :</p>
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