<!-- Début d'enregistrement -->
<?php ob_start();?>


<main>
    <!-- Section des top 4 films -->
    <div class="top4">
        <h2><?= $titre_secondaire ?></h2>
        <div class="film-card">
            <?php foreach ($requete->fetchAll() as $film) { ?>
                <div class="card">
                    <img src="public/img/<?= $film["affiche"] ?>" alt="Word War Z">
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
    // on stock le titre de la page dans une variable
    $titre = "Détail du film " . $film["titre"];
    // on stock le titre secondaire de la page dans une variable
    $titre_secondaire = "Liste des films";
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>