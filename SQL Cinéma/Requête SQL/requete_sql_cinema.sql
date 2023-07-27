-- Informations d’un film (id_film) : titre, année, durée (au format HH:MM) et réalisateur --
SELECT f.titre, 
	YEAR(f.date_sortie) AS annee_sortie,
	CONCAT(FLOOR(duree_minute / 60), 'h', LPAD(MOD(duree_minute, 60), 2, '0')) AS duree_format_heure_minute,
	CONCAT(p.prenom, ' ', p.nom) AS nom_realisateur
FROM film f
INNER JOIN realisateur r ON r.id_realisateur = f.id_realisateur
INNER JOIN personne p ON r.id_personne = p.id_personne
WHERE f.id_film = 1;

-- Liste des films dont la durée excède 2h15 classés par durée (du + long au + court) --
SELECT f.titre
FROM film f
WHERE duree_minute > 135
ORDER BY duree_minute DESC;

-- Liste des films d’un réalisateur (en précisant l’année de sortie) --
SELECT f.titre, YEAR(f.date_sortie) AS annee_sortie
FROM film f
INNER JOIN realisateur r ON f.id_realisateur = r.id_realisateur
INNER JOIN personne p ON r.id_personne = p.id_personne
WHERE p.nom = 'Verhoeven' AND p.prenom = 'Paul';

-- Nombre de films par genre (classés dans l’ordre décroissant) --
SELECT g.libelle AS genre, COUNT(*) AS nombre_de_films
FROM film f
INNER JOIN contenir c ON f.id_film = c.id_film
INNER JOIN genre g ON c.id_genre = g.id_genre
GROUP BY g.libelle
ORDER BY nombre_de_films DESC;

-- Nombre de films par réalisateur (classés dans l’ordre décroissant) --
SELECT CONCAT(p.prenom, ' ', p.nom) AS nom_realisateur, COUNT(*) AS nombre_de_films
FROM film f
INNER JOIN realisateur r ON r.id_realisateur = f.id_realisateur
INNER JOIN personne p ON p.id_personne = r.id_personne
GROUP BY nom_realisateur
ORDER BY nombre_de_films DESC;

-- Casting d’un film en particulier (id_film) : nom, prénom des acteurs + sexe --
SELECT f.titre, r.role_jouer, CONCAT(p.nom, ' ', p.prenom ,' ', p.sexe) AS info_acteur
FROM film f
INNER JOIN jouer j ON j.id_film = f.id_film
INNER JOIN role r ON r.id_role = j.id_role
INNER JOIN acteur a ON a.id_acteur = j.id_acteur
INNER JOIN personne p ON p.id_personne = a.id_personne
WHERE f.id_film;

-- Films tournés par un acteur en particulier (id_acteur) avec leur rôle et l’année de sortie (du film le plus récent au plus ancien) --
SELECT CONCAT(p.nom, ' ', p.prenom) AS acteur, f.titre, r.role_jouer, YEAR(f.date_sortie) AS annee_sortie
FROM film f
INNER JOIN jouer j ON j.id_film = f.id_film
INNER JOIN role r ON r.id_role = j.id_role
INNER JOIN acteur a ON a.id_acteur = j.id_acteur
INNER JOIN personne p ON p.id_personne = a.id_personne
WHERE a.id_personne = 4;
ORDER BY annee_sortie DESC;

-- Liste des personnes qui sont à la fois acteurs et réalisateurs --
SELECT CONCAT(p.nom, ' ', p.prenom) AS personne
FROM personne p
INNER JOIN acteur a ON a.id_personne = p.id_personne
INNER JOIN realisateur r ON r.id_personne = a.id_personne

-- Liste des films qui ont moins de 5 ans (classés du plus récent au plus ancien) --
SELECT titre
FROM film
WHERE date_sortie >= DATE_SUB(CURRENT_DATE, INTERVAL 5 YEAR)
ORDER BY date_sortie DESC;

-- Nombre d’hommes et de femmes parmi les acteurs --
SELECT p.sexe, COUNT(p.sexe) AS acteur_actrice 
FROM personne p
INNER JOIN acteur a ON a.id_personne = p.id_personne
GROUP BY sexe;

-- Liste des acteurs ayant plus de 50 ans (âge révolu et non révolu) --
SELECT CONCAT(p.nom, ' ', p.prenom) AS acteur
FROM personne p
INNER JOIN acteur a ON a.id_personne = p.id_personne
WHERE p.date_de_naissance <= DATE_SUB(CURRENT_DATE, INTERVAL 50 YEAR)

-- Acteurs ayant joué dans 3 films ou plus --
-- N'affichera rien car je n'est pas encore d'acteurs qui ont jouer plus d'un film
SELECT CONCAT(p.nom, ' ', p.prenom) AS acteur
FROM personne p
INNER JOIN acteur a ON a.id_personne = p.id_personne
INNER JOIN jouer j ON j.id_acteur = a.id_acteur
GROUP BY p.id_personne
HAVING COUNT(j.id_film) >= 3;