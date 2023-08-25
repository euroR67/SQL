<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Ajouter un acteur";
?>

<main>
    <div class="banniere">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="add-container">
        <?php 
            if(isset($_SESSION["errors"]) && !empty($_SESSION["errors"])){?>
                <!-- on echo  -->
                <p class="errors">
                <svg xmlns="http://www.w3.org/2000/svg" height="1.25em" viewBox="0 0 512 512">
                    <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                </svg>
                    <?= $_SESSION["errors"][0] ?></p>
                <!-- On réinitialise le tableau "errors" dans la session -->
                <?php
                $_SESSION["errors"]=[];
            }
            if(isset($_SESSION["success"]) && !empty($_SESSION["success"])){?>
                <!-- on echo  -->
                <p class="success">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1.25em" viewBox="0 0 512 512">
                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                    </svg>
                    <?= $_SESSION["success"][0] ?>
                </p>
                <!-- On réinitialise le tableau "errors" dans la session -->
                <?php
                $_SESSION["success"]=[];
            }
        ?>
        <form action="index.php?action=ajouterActeur" method="post" enctype="multipart/form-data">
            <div class="separation-container">
                <div class="form-separation">
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
                            Biographie :
                            <textarea name="biographie" cols="30" rows="10" required></textarea>
                        </label>
                    </p>
                </div>
                <div class="form-separation2">
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
                    <div class="cast-div">
                        <div class="add_casting">
                        <label for="">Casting</label>
                            <button type="button" class="addBtn">
                                <svg class="add_role" xmlns="http://www.w3.org/2000/svg" height="0.875em" viewBox="0 0 512 512">
                                    <style>svg{fill:#a3a3a3}</style>
                                    <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                                </svg> de casting
                            </button>
                        </div>
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
                        <input class="ajouter" type="submit" name="submit" value="Ajouter le acteur">
                    </p>
                </div>
            </div>
        </form>
    </div>

</main>

<?php 
    $addActeur = 'activeLink';
    // on stock le titre de la page dans une variable
    $titre = "Ajouter un acteur";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>