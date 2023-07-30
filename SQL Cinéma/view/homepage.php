<?php 

    ob_start();
    session_start();
?>
    <main>
        <!-- Section de présentation -->
        <div class="decouverte">
            <h3>Movflix</h3>
            <p>Découvre les <span>films</span> du moment,<br>
            actualités, présentations,<br>
            & plus encore.</p>
            <a href="films.php">Découvrir</a>
        </div>

        <!-- Section des top 4 films -->


    </main>


<?php 
    $titre = "Accueil";
    $titre_secondaire = "LES FILMS DU MOMENT";
    $contenu = ob_get_clean();
    require "template.php"
?>