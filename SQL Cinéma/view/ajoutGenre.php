<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
    $titre_secondaire = "Ajouter un genre";
?>

<main>
    <?php
        // On vérifie si le tableau "errors" est vide ou pas
        if(isset($_SESSION["errors"]) && !empty($_SESSION["errors"])){?>
            <!-- on echo  -->
            <p><?= $_SESSION["errors"][0] ?></p>
            <!-- On réinitialise le tableau "errors" dans la session -->
            <?php
            $_SESSION["errors"]=[];
        } ?>

    <h1><?= $titre_secondaire ?></h1>
    <form action="index.php?action=ajouterGenre" method="post" enctype="multipart/form-data">
        <p>
            <label>
                Genre :
                <input type="text" name="libelle" required>
            </label>
        </p>
        <p>
            <label>
                Films de ce genre :
                <select name="films[]" id="" multiple>
                    <option value="">None</option>    
                    <?php foreach($requeteFilms->fetchAll() as $film) { ?>
                        <option value="<?= $film["titre"] ?>"><?= $film["titre"] ?></option>
                    <?php } ?>
                </select>
            </label>
        </p>
        
        <p>
            <input class="ajouter" type="submit" name="submit" value="Ajouter le genre">
        </p>
    </form>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Ajouter un genre";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>