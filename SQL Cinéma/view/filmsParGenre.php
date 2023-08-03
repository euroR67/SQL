<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
    $titre_secondaire = "Formulaire ajout genre";
?>

<main>
    
    

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Films par genre ".$genre["libelle"];
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>