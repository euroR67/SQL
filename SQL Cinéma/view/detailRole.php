<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $roles = $requeteRole->fetchAll();
    $nom_role = $requeteRole2->fetch();
    $titre_secondaire = $nom_role[0];
?>

<main>
    <div class="detail">
        <h2>Le rôle de <span class="yellow-link"><?= $titre_secondaire ?></span> à été incarné par le/les acteurs/actrices suivant :</h2>
        <div class="casting-container">
            <?php if ($roles) { ?>
                <?php foreach($roles as $role) { ?>
                    <p class="casts" style="color: white">
                        <a class="yellow-link" href="index.php?action=detailActeur&id=<?= $role["id_acteur"] ?>">
                            <?= $role["acteur"] ?>
                        </a>
                        dans 
                        <a class="yellow-link" href="index.php?action=detailFilm&id=<?= $role["id_film"] ?>">
                            <?= $role["film_joue"] ?>
                        </a>
                    </p>
                <?php } ?>
            <?php } else { ?>
                <p style="color: white">Aucun acteur n'a encore incarné le rôle <?= $titre_secondaire ?></p>
            <?php } ?>
        </div>
    </div>
</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = $nom_role[0];
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>