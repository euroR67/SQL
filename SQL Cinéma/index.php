<?php
    
    use Controller\CinemaController;
    // permet de charger les classes automatiquement
    spl_autoload_register(function ($class_name) {
        // on inclue la classe correspondante au paramètre passé
        include $class_name . '.php';
    });
    session_start();
    // on stock cinemaController dans une variable
    $ctrlCinema = new CinemaController();
    $id = (isset($_GET["id"])) ? $_GET["id"] : null;
    // on récupère l'action passée dans l'URL
    if(isset($_GET["action"])) {
        // en fonction de l'action on appelle la méthode du controller
        switch ($_GET["action"]) {
            // appel au fonction pour afficher liste des 4 films les plus récent
            case "listTop4" : $ctrlCinema->listTop4(); break;
            // appel au fonction pour afficher liste des films et detail d'un film
            case "listFilms" : $ctrlCinema->listFilms(); break;
            case "detailFilm" : $ctrlCinema->detailFilm($id); break;
            // appel au fonction pour afficher liste des acteurs et detail d'un acteur
            case "listActeurs" : $ctrlCinema->listActeurs(); break;
            case "detailActeur" : $ctrlCinema->detailActeur($id); break;
            // appel au fonction pour afficher liste des realisateurs et detail d'un realisateur
            case "listRealisateurs" : $ctrlCinema->listRealisateurs(); break;
            case "detailRealisateur" : $ctrlCinema->detailRealisateur($id); break;
            // appel au fonction pour afficher liste des films par genre
            case "filmsParGenre" : $ctrlCinema->filmsParGenre($id); break;
            // appel au fonction pour afficher liste des genres et detail d'un genre
            case "listGenres" : $ctrlCinema->listGenres(); break;
            case "detailGenre" : $ctrlCinema->detailGenre($id); break;
            // appel au fonction pour afficher liste des roles et detail d'un role
            case "listRoles" : $ctrlCinema->listRoles(); break;
            case "detailRole" : $ctrlCinema->detailRole($id); break;
            // appel au fonction pour l'ajout de genre
            case "ajouterGenre" : $ctrlCinema->ajouterGenre(); break;
            case "listFilm_ajoutGenre" : $ctrlCinema->listFilm_ajoutGenre(); break;
            // appel au fonction pour l'ajout de film
            case "ajouterFilm" : $ctrlCinema->ajouterFilm(); break;
            case "listRealisateurGenre_ajoutFilm" : $ctrlCinema->listRealisateurGenre_ajoutFilm(); break;
            // appel au fonction pour l'ajout de réalisateur
            case "ajouterRealisateur" : $ctrlCinema->ajouterRealisateur(); break;
            // appel au fonction pour l'ajout d'acteur
            case "ajouterActeur" : $ctrlCinema->ajouterActeur(); break;
            case "listFilmRole_ajoutActeur" : $ctrlCinema->listFilmRole_ajoutActeur(); break;
            // appel au fonction pour l'ajout de role
            case "ajouterRole" : $ctrlCinema->ajouterRole(); break;
            case "listActeurFilm_ajoutRole" : $ctrlCinema->listActeurFilm_ajoutRole(); break;
        }
    }
    
?>