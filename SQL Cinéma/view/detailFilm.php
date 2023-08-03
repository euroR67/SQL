<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
?>


<main>
    <!-- Section des top 4 films -->
    <div class="detail">
        <?php $film=$requeteFilm->fetch() ?>
        <img src="public/img/<?= $film["affiche"] ?>" alt="Word War Z">
        <div class="info">
            <h2><?= $film["titre"] ?></h2>
            <p>Réalisateur : <?= $film["info_realisateur"] ?></p>
            <?php foreach ($requeteCasting->fetchAll() as $casting) { ?>
            <p>Acteur : <?= $casting["info_acteur"] ?> incarne le rôle de <?= $casting["role_jouer"] ?></p> 
            <?php } ?>
            <?php foreach ($requeteGenre->fetchAll() as $genre) { ?>
            <p>Genre : <?= $genre["libelle"] ?></p>
            <?php } ?>
            <p>Date de sortie en France : <?= $film["date_sortie"] ?></p>
            <p>Durée : <?= $film["duree"] ?></p><br>
            <p>Synopsis :</p><br>
            <p><?= $film["synopsis"] ?></p>
        </div>
    </div>

</main>


<?php 
    // on stock le titre de la page dans une variable
    $titre = "Détail du film " . $film["titre"];
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>