<!-- Début d'enregistrement -->
<?php 
    ob_start();
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
        } 
        if(isset($_SESSION["success"]) && !empty($_SESSION["success"])){?>
            <!-- on echo  -->
            <p><?= $_SESSION["success"][0] ?></p>
            <!-- On réinitialise le tableau "errors" dans la session -->
            <?php
            $_SESSION["success"]=[];
        } 
    ?>
    <div class="banniere">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="add-container">
        <form action="index.php?action=ajouterGenre" method="post" enctype="multipart/form-data">
            <div class="form-element">
                <p>
                    <label>
                        Genre :
                        <input type="text" name="libelle" >
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
            </div>
        </form>
    </div>

</main>

<?php 
    $addGenre = 'activeLink';
    // on stock le titre de la page dans une variable
    $titre = "Ajouter un genre";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>