<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Détail de l'auteur";
?>


<main>
    <div class="banniere">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="detail">
        <?php $realisateur = $requeteRealisateur->fetch() ?>
        <div class="info">
            <img src="public/img/<?= $realisateur["photo"] ?>" alt="">
            <div class="personne-info">
                <h2><?= $realisateur["info_realisateur"] ?></h2>
                <p>Date de naissance : <i class="uil uil-calendar-alt"></i> <?= $realisateur["date_de_naissance"] ?></p>
                <p>Films réalisés:</p>
                <ul>
                    <?php foreach($requeteFilmsRealisateur->fetchAll() as $film) { ?>
                        <li>
                            <a class="yellow-link" href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">
                                <?= $film["titre"] ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <br><p>Biographie :</p>
                <p><?= $realisateur["biographie"] ?></p>
            </div>
        </div>
    </div>

</main>


<?php 
    // on stock le titre de la page dans une variable
    $titre = "Détail du realisateur " . $realisateur["info_realisateur"];
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>