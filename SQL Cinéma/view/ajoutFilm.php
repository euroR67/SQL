<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
    $titre_secondaire = "Ajouter un film";
?>

<main>
   
    <?php
        if(isset($_SESSION["errors"]) && !empty($_SESSION["errors"])) { ?>
            <!-- on echo  -->
            <p><?= $_SESSION["errors"][0] ?></p>
            <!-- On réinitialise le tableau "errors" dans la session -->
            <?php
        } ?>
    <h1><?= $titre_secondaire ?></h1>
    <form action="index.php?action=ajouterFilm" method="post" enctype="multipart/form-data">
        <p>
            <label>
                Titre :
                <input type="text" name="titre" required>
            </label>
        </p>
        <p>
            <label>
                Date de sortie :
                <input type="date" name="date_sortie" required>
            </label>
        </p>
        <p>
            <label>
                Durée :
                <input type="number" name="duree_minute" required>
            </label>
        </p>
        <p>
            <label>
                Note :
                <input type="number" name="note" required>
            </label>
        </p>
        <p>
            <label>
                Affiche :
                <input type="file" name="affiche" required>
            </label>
        </p>
        <p>
            <label>
                Synopsis :
                <textarea name="synopsis" cols="30" rows="10" required></textarea>
            </label>
        </p>
        <p>
            <label>
                Réalisateur :
                <select name="realisateur" required>
                    <?php foreach($requeteListRealisateur->fetchAll() as $realisateur) { ?>
                        <option value="<?= $realisateur["info_realisateur"] ?>"><?= $realisateur["info_realisateur"] ?></option>
                    <?php } ?>
                </select>
            </label>
        </p>
        <div class="cast-div">
            <button type="button" class="addBtn">Plus de rôle</button>
            <p class="casting">
                <label>
                    Acteurs :
                    <select name="acteurs[]">
                            <option value="none">None</option>
                        <?php foreach($requeteListActeurs->fetchAll() as $acteur) { ?>
                            <option value="<?= $acteur["info_acteur"] ?>"><?= $acteur["info_acteur"] ?></option>
                        <?php } ?>
                    </select>
                </label>
                <br>
                <label>
                    Rôles :
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
            <label>
                Genre :
                <select name="genres[]" required multiple>
                    <?php foreach($requeteListGenre->fetchAll() as $genre) { 
                      ?>
                        <option value="<?= $genre["libelle"] ?>"><?= $genre["libelle"] ?></option>
                    <?php } ?>
                </select>
            </label>
        </p>
        
        <p>
            <input class="ajouter" type="submit" name="submit" value="Ajouter le film">
        </p>
    </form>

</main>

<?php 
    $_SESSION["errors"]=[];
    // on stock le titre de la page dans une variable
    $titre = "Ajouter un film";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>