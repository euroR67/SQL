<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Ajouter un rôle";
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
    <div class="banniere">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="add-container">
        <form action="index.php?action=ajouterRole" method="post" enctype="multipart/form-data">
            <div class="form-element">
                <div class="cast-div">
                    <label>
                        Rôle :
                        <input type="text" name="role_jouer[]" >
                    </label>
                    <div class="add_casting">
                        <label for="">Casting</label>
                        <button type="button" class="addBtn">
                            <svg class="add_role" xmlns="http://www.w3.org/2000/svg" height="0.875em" viewBox="0 0 512 512">
                                <style>svg{fill:#a3a3a3}</style>
                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                            </svg>
                            de casting
                        </button>
                    </div>
                    <p class="casting">
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
            </div>
        </form>
    </div>

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