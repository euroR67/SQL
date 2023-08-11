<!-- Début d'enregistrement -->
<?php 
    ob_start();
    
    $titre_secondaire = $roles[0]["role_jouer"];
?>

<main>

        <div class="top4">
            <h2>Le personnage <?= $titre_secondaire ?> à été incarné par les acteurs suivant :</h2>
            <?php foreach($roles as $role) { ?>
                <p style="color: white"><?= $role["acteur"] ?> dans <?= $role["film_joue"] ?></p>
            <?php } ?>
        </div>

</main>

<?php 
    
    // on stock le titre de la page dans une variable
    $titre = "Détail du rôle ".$roles[0]["role_jouer"]."";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>