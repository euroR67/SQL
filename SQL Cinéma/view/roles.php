<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Liste des rôles";
?>


<main>
    <div class="banniere-casting"></div>
    <div class="detail">
        <div class="film-card">
            <?php foreach ($requeteRoles->fetchAll() as $role) { ?>
                <div class="card">
                    <div class="titre-date">
                        <a href="index.php?action=detailRole&id=<?= $role["id_role"] ?>">
                            <h2><?= $role["role_jouer"] ?></h2>
                        </a>
                    </div>
                </div> 
            <?php } ?>
        </div>
    </div>

</main>


<?php 
    // on stock le titre de la page dans une variable
    $titre = "Liste des roles";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>