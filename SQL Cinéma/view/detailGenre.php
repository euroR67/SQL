<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
    $titre_secondaire = $genre["libelle"];
?>

<main>

    <div class="top4">
        <h2><?= $titre_secondaire ?></h2>
            <?php foreach ($requeteFilmParGenre->fetchAll() as $film) { ?>
                <div class="card">
                    <a href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">
                        <img src="public/img/<?= $film["affiche"] ?>" alt="">
                    </a>
                    <div class="titre-date">
                        <h2><?= $film["titre"] ?></h2>
                        <p><?= $film["annee"] ?></p>
                    </div>
                    <div class="duree_note">
                        <p>VOSTFR</p>
                        <div>
                            <p><?= $film["duree"] ?></p>
                            <p><?= $film["note"] ?></p>
                        </div>
                    </div>
                </div> 
            <?php } ?>
    </div>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Films par genre ".$genre["libelle"]."";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>