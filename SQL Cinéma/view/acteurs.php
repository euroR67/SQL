<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
    $titre_secondaire = "Liste des acteurs";
?>


<main>
    <!-- Section des top 4 films -->
    <div class="top4">
        <h2><?= $titre_secondaire ?></h2>
        <div class="film-card">
            <?php foreach ($requeteActeurs->fetchAll() as $acteur) { ?>
                <div class="card">
                    <a href="index.php?action=detailActeur&id=<?= $acteur["id_acteur"] ?>">
                        <img src="public/img/<?= $acteur["photo"] ?>" alt="">
                    </a>
                    <div class="titre-date">
                        <h2><?= $acteur["info_acteur"] ?></h2>
                    </div>
                </div> 
            <?php } ?>
        </div>
    </div>

</main>


<?php 
    // on stock le titre de la page dans une variable
    $titre = "Liste des acteurs";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>