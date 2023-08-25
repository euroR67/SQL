<?php
    use Controller\FilmsController;
    use Controller\ActeursController;
    use Controller\RealisateursController;
    use Controller\GenresController;
    use Controller\RolesController;
    // permet de charger les classes automatiquement
    spl_autoload_register(function ($class_name) {
        // on inclue la classe correspondante au paramètre passé
        include $class_name . '.php';
    });
    session_start();
    // on stock cinemaController dans une variable
    $ctrlFilms = new FilmsController();
    $ctrlActeurs = new ActeursController();
    $ctrlRealisateurs = new RealisateursController();
    $ctrlGenres = new GenresController();
    $ctrlRoles = new RolesController();
    $id = (isset($_GET["id"])) ? $_GET["id"] : null;
    // on récupère l'action passée dans l'URL
    if(isset($_GET["action"])) {
        // en fonction de l'action on appelle la méthode du controller
        switch ($_GET["action"]) {
            // appel au fonction pour afficher liste des 4 films les plus récent
            case "listTop4" : $ctrlFilms->listTop4(); break;
            // appel au fonction pour afficher liste des films et detail d'un film
            case "listFilms" : $ctrlFilms->listFilms(); break;
            case "detailFilm" : $ctrlFilms->detailFilm($id); break;
            // appel au fonction pour afficher liste des acteurs et detail d'un acteur
            case "listActeurs" : $ctrlActeurs->listActeurs(); break;
            case "detailActeur" : $ctrlActeurs->detailActeur($id); break;
            // appel au fonction pour afficher liste des realisateurs et detail d'un realisateur
            case "listRealisateurs" : $ctrlRealisateurs->listRealisateurs(); break;
            case "detailRealisateur" : $ctrlRealisateurs->detailRealisateur($id); break;
            // appel au fonction pour afficher liste des films par genre
            case "filmsParGenre" : $ctrlGenres->filmsParGenre($id); break;
            // appel au fonction pour afficher liste des genres et detail d'un genre
            case "listGenres" : $ctrlGenres->listGenres(); break;
            case "detailGenre" : $ctrlGenres->detailGenre($id); break;
            // appel au fonction pour afficher liste des roles et detail d'un role
            case "listRoles" : $ctrlRoles->listRoles(); break;
            case "detailRole" : $ctrlRoles->detailRole($id); break;
            // appel au fonction pour l'ajout de genre
            case "ajouterGenre" : $ctrlGenres->ajouterGenre(); break;
            case "listFilm_ajoutGenre" : $ctrlGenres->listFilm_ajoutGenre(); break;
            // appel au fonction pour l'ajout de film
            case "ajouterFilm" : $ctrlFilms->ajouterFilm(); break;
            case "listRealisateurGenre_ajoutFilm" : $ctrlFilms->listRealisateurGenre_ajoutFilm(); break;
            // appel au fonction pour l'ajout de réalisateur
            case "ajouterRealisateur" : $ctrlRealisateurs->ajouterRealisateur(); break;
            // appel au fonction pour l'ajout d'acteur
            case "ajouterActeur" : $ctrlActeurs->ajouterActeur(); break;
            case "listFilmRole_ajoutActeur" : $ctrlActeurs->listFilmRole_ajoutActeur(); break;
            // appel au fonction pour l'ajout de role
            case "ajouterRole" : $ctrlRoles->ajouterRole(); break;
            case "listActeurFilm_ajoutRole" : $ctrlRoles->listActeurFilm_ajoutRole(); break;
        }
    }
    
?>