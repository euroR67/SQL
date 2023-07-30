<!-- Début d'enregistrement -->
<?php ob_start();?>





<?php 
    // on stock le titre de la page dans une variable
    $titre = "Liste des films";
    // on stock le titre secondaire de la page dans une variable
    $titre_secondaire = "Liste des films";
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>