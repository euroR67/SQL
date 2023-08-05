<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Formulaire ajout film";
?>

<main>
    
    <h1><?= $titre_secondaire ?></h1>
    <form action="index.php?action=ajouterFilm" method="post" enctype="multipart/form-data">
        <p>
            <label>
                Titre :
                <input type="text" name="name" required>
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
                <input type="number" name="duration" required>
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
                <input type="file" name="name" required>
            </label>
        </p>
        <p>
            <label>
                Synopsis :
                <textarea name="resume" cols="30" rows="10" required></textarea>
            </label>
        </p>
        <p>
            <label>
                Realisateur :
                
            </label>
        <p>
            <input class="ajouter" type="submit" name="submit" value="Ajouter le film">
        </p>
    </form>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Formulaire ajout de film ";
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>