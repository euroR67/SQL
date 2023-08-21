<!-- Début d'enregistrement -->
<?php 
    ob_start();
    $titre_secondaire = "Liste des films";
?>

<main>
    <div class="banniere">
        <h2><?= $titre_secondaire ?></h2>
    </div>
    <div class="detail">
        <?php foreach ($requete->fetchAll() as $film) { ?>
            <div class="card">
                <a href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">
                    <img src="public/img/<?= $film["affiche"] ?>" alt="<?= $film["titre"] ?>">
                </a>
                <div class="film-info">
                    <div class="titre-date">
                        <h2><a href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>"><?= $film["titre"] ?></a></h2>
                    </div>
                    <div class="duree_note">
                        <div>
                            <p><?= $film["annee"] ?> |
                            <i class="uil uil-clock"></i> <?= $film["duree"] ?> |
                            <i class="uil uil-thumbs-up"></i> <?= $film["note"] ?>/5 |
                            <?php
                                $genresArray = explode(',', $film["genres_ids"]);
                                $genresLibelleArray = explode(',', $film["genres"]); // Convertir la chaîne en un tableau de libellés de genres
                                
                                foreach ($genresArray as $key => $genreId) {
                                    $genreLibelle = $genresLibelleArray[$key]; // Récupérer le libellé du genre correspondant
                                    
                                    echo '<a class="yellow-link" href="index.php?action=detailGenre&id=' . $genreId . '">' . $genreLibelle . '</a>';
                                    
                                    if ($key < count($genresArray) - 1) {
                                        echo ', '; // Ajouter une virgule sauf pour le dernier genre
                                    }
                                }?>
                            </p>
                            <p>De <a class="yellow-link" href="index.php?action=detailRealisateur&id=<?=$film["id_realisateur"]?>"><?= $film["info_realisateur"] ?></a></p>
                            <p>Avec
                                <?php
                                $acteursArray = !empty($film["acteurs_ids"]) ? explode(',', $film["acteurs_ids"]) : [];
                                $acteurNomPrenomArray = !empty($film["acteurs"]) ? explode(',', $film["acteurs"]) : []; // Convertir la chaîne en un tableau de noms/prénoms d'acteurs
                                
                                if (!empty($acteursArray)) {
                                    foreach ($acteursArray as $key => $acteurId) {
                                        $acteurNomPrenom = $acteurNomPrenomArray[$key]; // Récupérer le nom/prénom de l'acteur correspondant
                                        
                                        echo '<a class="yellow-link" href="index.php?action=detailActeur&id=' . $acteurId . '">' . $acteurNomPrenom . '</a>';
                                        
                                        if ($key < count($acteursArray) - 1) {
                                            echo ', '; // Ajouter une virgule sauf pour le dernier acteur
                                        }
                                    }
                                } else {
                                    echo 'Aucun acteur n\'est défini pour ce film.';
                                }
                                ?>
                            </p>

                        </div>
                        <a class="plus" href="index.php?action=detailFilm&id=<?= $film["id_film"] ?>">VOIR PLUS</a>
                    </div>
                </div>
            </div> 
        <?php } ?>
    </div>

</main>

<?php 
    // on stock le titre de la page dans une variable
    $titre = "Liste des films";
    // fin d'enregistrement
    // on stock le contenu de la page dans une variable
    $contenu = ob_get_clean();
    // Permet l'injection du contenu dans le template "template.php"
    require "view/template.php"
?>