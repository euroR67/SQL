<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
?>


<main>
    
    <div class="detail">
        <?php $realisateur = $requeteRealisateur->fetch() ?>
        <img src="public/img/<?= $realisateur["photo"] ?>" alt="">
        <div class="info">
            <h2><?= $realisateur["info_realisateur"] ?></h2>
            <p>Date de naissance : <?= $realisateur["date_de_naissance"] ?></p>
            <p>Films réalisés: 
                <?php foreach($requeteFilmsRealisateur->fetchAll() as $film) { ?>
                    <a class="yellow-link" href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">
                        <?= $film["titre"] ?>
                    </a>,
                <?php } ?>
            </p>
            <br><p>Biographie :</p><br>
            <p><?= $realisateur["biographie"] ?></p>
        </div>
    </div>

</main>


<?php 
    // on stock le titre de la page dans une variable
    $titre = "Détail de l'realisateur " . $realisateur["info_realisateur"];
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>