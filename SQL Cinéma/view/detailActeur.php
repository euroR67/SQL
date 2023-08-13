<!-- Début d'enregistrement -->
<?php 
    ob_start();
    session_start();
?>

<main>
    
    <div class="detail">
        <?php $acteur = $requeteActeur->fetch() ?>
        <img src="public/img/<?= $acteur["photo"] ?>" alt="">
        <div class="info">
            <h2><?= $acteur["info_acteur"] ?></h2>
            <p>Date de naissance : <?= $acteur["date_de_naissance"] ?></p>
            <p>Films : 
                <?php foreach($requeteFilmsActeur->fetchAll() AS $film) { ?>
                    <a class="yellow-link" href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">
                        <?= $film["titre"] ?>
                    </a>,
                <?php } ?>
            </p>
            <br><p>Biographie :</p><br>
            <p><?= $acteur["biographie"] ?></p>
        </div>
    </div>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Détail de l'acteur " . $acteur["info_acteur"];
    // on stock le titre secondaire de la page dans une variable
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>