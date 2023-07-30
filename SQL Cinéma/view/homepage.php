<?php 

    ob_start();
    session_start();

?>

<?php 
    $titre = "Accueil";
    $titre_secondaire = "LES FILMS DU MOMENT";
    $contenu = ob_get_clean();
    require "template.php"
?>