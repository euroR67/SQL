<?php 
    ob_start();
?>
    <main>
        <!-- Section de présentation -->
        <div class="decouverte">
            <div class="present">
                <h3>Movflix</h3>
                <p>Découvre les <span>films</span> du moment,<br>
                actualités, présentations,<br>
                & plus encore.</p>
                <a href="index.php?action=listFilms">Découvrir</a>
            </div>
        </div>

        <!-- Section des top 4 films -->
        <div class="top4">
            <h2>LES FILMS DU MOMENT</h2>
            <div class="card-container">
            <?php foreach ($requete->fetchAll() as $film) { ?>
                <div class="card-top4">
                    <a href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">
                        <figure>
                            <img src="public/img/<?= $film["affiche"] ?>" alt="<?= $film["titre"] ?>">
                        </figure>
                    </a>
                    <div class="top4-titre-date">
                        <h2><?= $film["titre"] ?></h2>
                        <p><?= $film["annee"] ?></p>
                    </div>
                    <div class="top4-duree_note">
                        <p>VOSTFR</p>
                        <div>
                            <p><i class="uil uil-clock"></i> <?= $film["duree"] ?></p>
                            <p><i class="uil uil-thumbs-up"></i> <?= $film["note"] ?>.0</p>
                        </div>
                    </div>
                </div> 
            <?php } ?>
            </div>
        </div>
        <div class="see-more">
            <a href="index.php?action=listFilms">Découvrir tout les films</a>
        </div>

    </main>


<?php 

    $titre = "Accueil";
    $titre_secondaire = "LES FILMS DU MOMENT";
    $contenu = ob_get_clean();
    require "template.php"
?>