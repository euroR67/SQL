<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Liste des genres";
?>


<main>
    <div class="banniere-genres">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="detail">
        <div class="genre-container">
            <?php foreach ($requeteGenres->fetchAll() as $genre) { ?>
                <div class="genre-card">
                    <a href="index.php?action=detailGenre&id=<?= $genre["id_genre"] ?>">
                        <h2><?= $genre["libelle"] ?></h2>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

</main>


<?php 
    // on stock le titre de la page dans une variable
    $titre = "Liste des genres";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>