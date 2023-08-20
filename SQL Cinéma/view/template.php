<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/script.js"></script>
    <title><?= $titre ?></title>
</head>
<body>
    <header>
        <!-- Logo -->
        <div class="logo">
            <a href="index.php?action=listTop4">
                <div>
                    <img src="public/img/logo.png" alt="logo">
                    Movflix
                </div>
            </a>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="bars"><path fill="#fff" d="M3,8H21a1,1,0,0,0,0-2H3A1,1,0,0,0,3,8Zm18,8H3a1,1,0,0,0,0,2H21a1,1,0,0,0,0-2Zm0-5H3a1,1,0,0,0,0,2H21a1,1,0,0,0,0-2Z"></path></svg>
        
        <!-- Menu burger -->
        <nav>
            <!-- Logo du menu burger -->
            <div class="logo_burger">
                <div>
                    <img src="public/img/logo.png" alt="logo">
                    Movflix
                </div>
                <i class="uil uil-times"></i>
            </div>
            <!-- Liste des liens du menu burger -->
            <ul class="menu">
                <li class="menu-item"><a href="index.php?action=listTop4"><i class="uil uil-estate"></i> Accueil</a></li>
                <li class="menu-item" id="sub-li">
                    <a class="sub-button" href="#">
                    <i class="uil uil-book-medical"></i>Ajouter
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="angle-down"><path fill="#e4d804" d="M17,9.17a1,1,0,0,0-1.41,0L12,12.71,8.46,9.17a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42l4.24,4.24a1,1,0,0,0,1.42,0L17,10.59A1,1,0,0,0,17,9.17Z"></path></svg>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="index.php?action=listFilm_ajoutGenre">Ajout Genre</a></li>
                        <li><a href="index.php?action=listRealisateurGenre_ajoutFilm">Ajout Film</a></li>
                        <li><a href="index.php?action=ajouterRealisateur">Ajout Réalisateur</a></li>
                        <li><a href="index.php?action=listFilmRole_ajoutActeur">Ajout Acteur</a></li>
                        <li><a href="index.php?action=listActeurFilm_ajoutRole">Ajout Rôle</a></li>
                    </ul>
                </li>
                <li class="menu-item"><a href="index.php?action=listFilms"><i class="uil uil-film"></i> Films</a></li>
                <li class="menu-item"><a href="index.php?action=listRoles"><i class="uil uil-clapper-board"></i> Rôles</a></li>
                <li class="menu-item"><a href="index.php?action=listActeurs"><i class="uil uil-user"></i> Acteurs</a></li>
                <li class="menu-item"><a href="index.php?action=listRealisateurs"><i class="uil uil-user"></i> Realisateurs</a></li>
                <li class="menu-item"><a href="index.php?action=listGenres"><i class="uil uil-browser"></i> Genres</a></li>
            </ul>
            <div class="social-network">
                <li><i class="uil uil-facebook-f"></i></li>
                <li><i class="uil uil-instagram"></i></li>
                <li><i class="uil uil-twitter"></i></li>
                <li><i class="uil uil-github"></i></li>
                <li><i class="uil uil-youtube"></i></li>
            </div>
        </nav>
        <!-- Fond obscure lorsque le menu burger est ouvert -->
        <div class="overlay"></div>
    </header>

    
    <?= $contenu ?>


    <footer>
        <div class="logo-footer">
            <div>
                <img src="public/img/logo.png" alt="logo">
                Movflix
            </div>
        </div>
            <div class="contact">
                <!-- Affiche une adresse , numéro de téléphone et email avec un icon devant -->
                <div>
                    <i class="uil uil-map-marker"></i>
                    <p>Adresse : 18 rue elan formation, France</p>
                </div>
                <div>
                    <i class="uil uil-phone"></i>
                    <p>Numéro : 06 50 31 30 66</p>
                </div>
                <div>
                    <i class="uil uil-envelope"></i>
                    <p>Email : mchamaev67@gmail.com</p>
                </div>
                <p>© 2023 Movflix, Inc by Mansour Chamaev. All rights reserved.</p>
            </div>
    </footer>
</body>
</html>