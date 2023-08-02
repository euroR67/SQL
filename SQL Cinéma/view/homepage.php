<?php 

    ob_start();
    session_start();
?>
    <main>
        <!-- Section de présentation -->
        <div class="decouverte">
            <h3>Movflix</h3>
            <p>Découvre les <span>films</span> du moment,<br>
            actualités, présentations,<br>
            & plus encore.</p>
            <a href="films.php">Découvrir</a>
        </div>

        <!-- Section des top 4 films -->
        <div class="top4">
            <h2>LES FILMS DU MOMENT</h2>
            <div class="film-card">
                <?php foreach ($requete->fetchAll() as $film) { ?>
                
                <div class="card">
                    <a href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">
                        <img src="public/img/<?= $film["affiche"] ?>" alt="Word War Z">
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
        </div>

    </main>


<?php 

    $titre = "Accueil";
    $titre_secondaire = "LES FILMS DU MOMENT";
    $contenu = ob_get_clean();
    require "template.php"
?>