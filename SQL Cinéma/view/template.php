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
            <a href="/SQL%20Cinéma/index.php?action=listTop4">
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
            <ul>
                <li><a href="/SQL%20Cinéma/index.php?action=listTop4">Accueil</a></li>
                <li><a href="/SQL%20Cinéma/index.php?action=listFilms">Films</a></li>
                <li><a href="/SQL%20Cinéma/index.php?action=listRoles">Roles</a></li>
                <li><a href="/SQL%20Cinéma/index.php?action=listActeurs">Acteurs</a></li>
                <li><a href="/SQL%20Cinéma/index.php?action=listRealisateurs">Realisateurs</a></li>
                <li><a href="/SQL%20Cinéma/index.php?action=ajouter">Ajouter</a></li>
            </ul>
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