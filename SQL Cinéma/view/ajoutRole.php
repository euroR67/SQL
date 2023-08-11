<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Ajouter un rôle";
?>

<main>
    
    <h1><?= $titre_secondaire ?></h1>
    <form action="index.php?action=ajouterRole" method="post" enctype="multipart/form-data">
        <div class="cast-div">
            <button type="button" class="addBtn">Plus de casting</button>
            <p class="casting">
                <label>
                    Rôle :
                    <input type="text" name="role_jouer[]" required>
                </label>
                <br>
                <label>
                    Dans le film :
                    <select name="films[]">
                            <option value="none">None</option>
                        <?php foreach($requeteListFilm->fetchAll() as $film) { ?>
                            <option value="<?= $film["titre"] ?>"><?= $film["titre"] ?></option>
                        <?php } ?>
                    </select>
                </label>
                <br>
                <label>
                    Par l'acteur :
                    <select name="acteurs[]">
                            <option value="none">None</option>
                        <?php foreach($requeteListActeurs->fetchAll() as $acteur) { ?>
                            <option value="<?= $acteur["info_acteur"] ?>"><?= $acteur["info_acteur"] ?></option>
                        <?php } ?>
                    </select>
                </label>
            </p>
        </div>
        <p>
            <input class="ajouter" type="submit" name="submit" value="Ajouter le rôle">
        </p>
    </form>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Ajouter un rôle";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>