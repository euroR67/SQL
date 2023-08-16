<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Ajouter un acteur";
?>

<main>
    <h1><?= $titre_secondaire ?></h1>

    <?php if (isset($erreur_message)): ?>
        <p class="error-message"><?= $erreur_message ?></p>
    <?php endif; ?>
    
    <form action="index.php?action=ajouterActeur" method="post" enctype="multipart/form-data">
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
        <div class="cast-div">
            <button type="button" class="addBtn">Plus de casting</button>
            <p class="casting">
                <label>
                    A jouer dans le film :
                    <select name="films[]">
                            <option value="none">None</option>  
                        <?php foreach($requeteListFilm->fetchAll() as $film) { ?>
                            <option value="<?= $film["titre"] ?>"><?= $film["titre"] ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label>
                    A jouer le rôle de :
                    <select name="roles[]">
                            <option value="none">None</option>
                        <?php foreach($requeteListRoles->fetchAll() as $role) { ?>
                            <option value="<?= $role["role_jouer"] ?>"><?= $role["role_jouer"] ?></option>
                        <?php } ?>
                    </select>
                </label>
            </p>
        </div>
        <p>
            <input class="ajouter" type="submit" name="submit" value="Ajouter le acteur">
        </p>
    </form>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Ajouter un acteur";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>