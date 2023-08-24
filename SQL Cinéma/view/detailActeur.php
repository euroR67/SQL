<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Détail de l'acteur/actrice";
?>

<main>
    <div class="banniere">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="detail">
        <?php $acteur = $requeteActeur->fetch() ?>
        <div class="info">
            <img src="public/img/<?= $acteur["photo"] ?>" alt="<?= $acteur["info_acteur"] ?>">
            <div class="personne-info">
                <h2><?= $acteur["info_acteur"] ?></h2>
                <p>Date de naissance : <i class="uil uil-calendar-alt"></i> <?= $acteur["date_de_naissance"] ?></p>
                <p>Films :</p>
                <ul>
                    <?php foreach($requeteFilmsActeur->fetchAll() as $film) { ?>
                        <li>
                            <a class="yellow-link" href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">
                                <?= $film["titre"] ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <p>Biographie :</p>
                <p><?= $acteur["biographie"] ?></p>
            </div>
        </div>
    </div>

</main>

<?php 
    $pageActeur = 'activeLink';
    // on stock le titre de la page dans une variable
    $titre = "Détail de l'acteur " . $acteur["info_acteur"];
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>