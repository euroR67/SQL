<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Ajouter un réalisateur";
?>

<main>

    <?php if (isset($erreur_message)): ?>
        <p class="error-message"><?= $erreur_message ?></p>
    <?php endif; ?>
    
    <div class="banniere">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="add-container">
        <form action="index.php?action=ajouterRealisateur" method="post" enctype="multipart/form-data">
            <div class="form-element">
                <p>
                    <label>
                        Nom : *
                        <input type="text" name="nom" required>
                    </label>
                </p>
                <p>
                    <label>
                        Prénom : *
                        <input type="text" name="prenom" required>
                    </label>
                </p>
                <p>
                    <label>
                        Date de naissance : *
                        <input type="date" name="date_de_naissance" required>
                    </label>
                </p>
                <p class="sexe">
                    Sexe : *<br>
                    <label>
                        <input type="radio" name="sexe" value="Homme" required> Homme
                    </label><br>
                    <label>
                        <input type="radio" name="sexe" value="Femme" required> Femme
                    </label><br>
                    <label>
                        <input type="radio" name="sexe" value="Hélicoptère de combat" required> Hélicoptère de combat (Apache AH-64)
                    </label>
                </p>
                <p>Photo : *
                    <label class="custom-file-upload">
                        <input type="file" name="photo" class="file" onchange="updateFileName(this)" required>
                        <svg class="upload" xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 512 512">
                            <style>svg{fill:#a3a3a3}</style>
                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM385 231c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-71-71V376c0 13.3-10.7 24-24 24s-24-10.7-24-24V193.9l-71 71c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9L239 119c9.4-9.4 24.6-9.4 33.9 0L385 231z"/>
                        </svg>
                        <span>Choisir un fichier</span>
                    </label>
                    <span id="file-name"></span>
                </p>
                <p>
                    <label>
                        Biographie : *
                        <textarea name="biographie" cols="30" rows="10" required></textarea>
                    </label>
                </p>
                
                <p>
                    <input class="ajouter" type="submit" name="submit" value="Ajouter le réalisateur">
                </p>
            </div>
        </form>
    </div>

</main>

<?php 
    $addRealisateur = 'activeLink';
    // on stock le titre de la page dans une variable
    $titre = "Ajouter un réalisateur";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>