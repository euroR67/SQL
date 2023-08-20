<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Liste des réalisateurs";
?>


<main>
    <!-- Section des top 4 films -->
    <div class="banniere-realisateur">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="top4">
        <div class="personne-container">
            <?php foreach ($requeteRealisateurs->fetchAll() as $realisateur) { ?>
                <div class="personne-card">
                    <a href="index.php?action=detailRealisateur&id=<?= $realisateur["id_realisateur"] ?>">
                        <img src="public/img/<?= $realisateur["photo"] ?>" alt="">
                    </a>
                    <div class="nom-personne">
                        <h2><?= $realisateur["info_realisateur"] ?></h2>
                    </div>
                </div> 
            <?php } ?>
        </div>
    </div>

</main>


<?php 
    // on stock le titre de la page dans une variable
    $titre = "Liste des réalisateurs";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>