<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Ajouter un réalisateur";
?>

<main>

    <?php if (isset($erreur_message)): ?>
        <p class="error-message"><?= $erreur_message ?></p>
    <?php endif; ?>
    
    <h1><?= $titre_secondaire ?></h1>
    <form action="index.php?action=ajouterRealisateur" method="post" enctype="multipart/form-data">
        <p>
            <label>
                Nom :
                <input type="text" name="nom" required>
            </label>
        </p>
        <p>
            <label>
                Prénom :
                <input type="text" name="prenom" required>
            </label>
        </p>
        <p>
            <label>
                Date de naissance :
                <input type="date" name="date_de_naissance" required>
            </label>
        </p>
        <p>
            <label>
                Sexe :
                <input type="text" name="sexe" required>
            </label>
        </p>
        <p>
            <label>
                Photo :
                <input type="file" name="photo" required>
            </label>
        </p>
        <p>
            <label>
                Biographie :
                <textarea name="biographie" cols="30" rows="10" required></textarea>
            </label>
        </p>
        
        <p>
            <input class="ajouter" type="submit" name="submit" value="Ajouter le réalisateur">
        </p>
    </form>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Ajouter un réalisateur";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>