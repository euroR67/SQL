<?php
    
    use Controller\CinemaController;
    // permet de charger les classes automatiquement
    spl_autoload_register(function ($class_name) {
        // on inclue la classe correspondante au paramètre passé
        include $class_name . '.php';
    });

    // on stock cinemaController dans une variable
    $ctrlCinema = new CinemaController();
    $id = (isset($_GET["id"])) ? $_GET["id"] : null;
    // on récupère l'action passée dans l'URL
    if(isset($_GET["action"])) {
        // en fonction de l'action on appelle la méthode du controller
        switch ($_GET["action"]) {
            case "listTop4" : $ctrlCinema->listTop4(); break;
            case "listFilms" : $ctrlCinema->listFilms(); break;
            case "detailFilm" : $ctrlCinema->detailFilm($id); break;
            case "listActeurs" : $ctrlCinema->listActeurs(); break;
            case "detailActeur" : $ctrlCinema->detailActeur($id); break;
            case "ajouterGenre" : $ctrlCinema->ajouterGenre(); break;
            case "filmsParGenre" : $ctrlCinema->filmsParGenre($id); break;
            case "listGenres" : $ctrlCinema->listGenres(); break;
            case "detailGenre" : $ctrlCinema->detailGenre($id); break;
            case "listRoles" : $ctrlCinema->listRoles(); break;
            case "detailRole" : $ctrlCinema->detailRole($id); break;
        }
    }
    
?>