-- REQUETE SQL NUMERO 1 --
SELECT nom_lieu
FROM lieu
WHERE nom_lieu LIKE '%um';


-- REQUETE SQL NUMERO 2 --
SELECT nom_lieu, COUNT(personnage.nom_personnage)
FROM personnage
INNER JOIN lieu ON lieu.id_lieu = personnage.id_lieu
GROUP BY nom_lieu
ORDER BY COUNT(personnage.nom_personnage) DESC;


-- REQUETE SQL NUMERO 3 --
SELECT nom_personnage, specialite.nom_specialite, adresse_personnage, lieu.nom_lieu
FROM personnage
INNER JOIN specialite ON specialite.id_specialite = personnage.id_specialite
INNER JOIN lieu ON lieu.id_lieu = personnage.id_lieu
ORDER BY lieu.nom_lieu, personnage.nom_personnage;


-- REQUETE SQL NUMERO 4 --
SELECT nom_specialite, COUNT(personnage.id_personnage)
FROM specialite
INNER JOIN personnage ON personnage.id_specialite = specialite.id_specialite
GROUP BY nom_specialite
ORDER BY COUNT(personnage.id_personnage) DESC;


-- REQUETE SQL NUMERO 5 --
SELECT nom_bataille , DATE_FORMAT(date_bataille, '%d/%m/%Y'), lieu.nom_lieu
FROM bataille
INNER JOIN lieu ON lieu.id_lieu = bataille.id_lieu
ORDER BY date_bataille DESC;


-- REQUETE SQL 6 --
SELECT nom_potion , (composer.qte * ingredient.cout_ingredient)
FROM potion
INNER JOIN composer ON composer.id_potion = potion.id_potion
INNER JOIN ingredient ON ingredient.id_ingredient = composer.id_ingredient
ORDER BY (composer.qte * ingredient.cout_ingredient) DESC;


-- REQUETE SQL 7 --
SELECT ingredient.nom_ingredient, ingredient.cout_ingredient , composer.qte
FROM potion
INNER JOIN composer ON composer.id_potion = potion.id_potion
INNER JOIN ingredient ON ingredient.id_ingredient = composer.id_ingredient
WHERE potion.nom_potion = 'Santé';


-- REQUETE SQL 8 --
SELECT nom_personnage, SUM(prendre_casque.qte)
FROM personnage
INNER JOIN prendre_casque ON prendre_casque.id_personnage = personnage.id_personnage
INNER JOIN bataille ON bataille.id_bataille = prendre_casque.id_bataille
WHERE bataille.nom_bataille = 'Bataille du village gaulois'
GROUP BY personnage.id_personnage
HAVING SUM(prendre_casque.qte) >= ALL(
	SELECT SUM(prendre_casque.qte)
	 FROM prendre_casque
	 INNER JOIN bataille ON bataille.id_bataille = prendre_casque.id_bataille
	 WHERE bataille.nom_bataille = 'Bataille du village gaulois'
	 GROUP BY prendre_casque.id_personnage)


-- REQUETE SQL 9 --
SELECT nom_personnage , boire.dose_boire
FROM personnage
INNER JOIN boire ON boire.id_personnage = personnage.id_personnage
ORDER BY boire.dose_boire DESC;


-- REQUETE SQL 10 --
SELECT nom_bataille, SUM(prendre_casque.qte)
FROM bataille
INNER JOIN prendre_casque ON prendre_casque.id_bataille = bataille.id_bataille
GROUP BY bataille.id_bataille
HAVING SUM(prendre_casque.qte) >= ALL(
	SELECT SUM(prendre_casque.qte)
		FROM prendre_casque
		INNER JOIN bataille ON bataille.id_bataille = prendre_casque.id_bataille
		GROUP BY prendre_casque.id_bataille)


-- REQUETE SQL 11 --
SELECT nom_type_casque , COUNT(casque.id_type_casque), SUM(casque.cout_casque)
FROM type_casque
INNER JOIN casque ON casque.id_type_casque = type_casque.id_type_casque
GROUP BY type_casque.id_type_casque
ORDER BY COUNT(casque.id_type_casque) DESC;


-- REQUETE SQL 12 --
SELECT nom_potion
FROM potion
INNER JOIN composer ON composer.id_potion = potion.id_potion
INNER JOIN ingredient ON ingredient.id_ingredient = composer.id_ingredient
WHERE ingredient.nom_ingredient = 'Poisson frais';

		
-- REQUETE SQL 13 --
SELECT lieu.nom_lieu , COUNT(personnage.nom_personnage)
FROM personnage
INNER JOIN lieu ON lieu.id_lieu = personnage.id_lieu 
GROUP BY lieu.nom_lieu 
HAVING NOT lieu.nom_lieu = 'Village gaulois' AND COUNT(personnage.nom_personnage) = (
  SELECT MAX(count)
	  	FROM ( SELECT COUNT(personnage.nom_personnage) as count FROM personnage
		INNER JOIN lieu ON lieu.id_lieu = personnage.id_lieu 
		WHERE NOT lieu.nom_lieu = 'Village gaulois'
		GROUP BY lieu.nom_lieu ) AS total ) 
ORDER BY COUNT(personnage.nom_personnage) DESC;


-- REQUETE SQL 14 --
SELECT nom_personnage
FROM personnage
LEFT JOIN boire ON boire.id_personnage = personnage.id_personnage
WHERE boire.id_potion IS NULL;


-- REQUETE SQL 15 --
SELECT nom_personnage
FROM personnage
INNER JOIN autoriser_boire ON autoriser_boire.id_personnage = personnage.id_personnage
INNER JOIN potion ON potion.id_potion = autoriser_boire.id_potion
WHERE NOT potion.nom_potion = 'Magique';


-- REQUETE SQL A. --
INSERT INTO personnage (nom_personnage, id_specialite , adresse_personnage , id_lieu ) 
VALUES ('Champdeblix' ,
	(SELECT id_specialite FROM specialite WHERE nom_specialite = 'Agriculteur') ,
 	'Ferme hantassion' ,
	(SELECT id_lieu FROM lieu WHERE nom_lieu = 'Rotomagus') ) ;
	

-- REQUETE SQL B. --
INSERT INTO autoriser_boire (id_potion , id_personnage)
VALUES ((SELECT id_potion FROM potion WHERE nom_potion = 'Magique') ,
 		  (SELECT id_personnage FROM personnage WHERE nom_personnage = 'Bonemine')) ;
 		  
 		  
-- REQUETE SQL C.
DELETE FROM casque 
WHERE id_type_casque = (SELECT id_type_casque FROM type_casque WHERE nom_type_casque = 'Grec')
AND id_casque NOT IN (
    SELECT casque.id_casque FROM type_casque
    INNER JOIN prendre_casque pc ON casque.id_casque = pc.id_casque
);


-- REQUETE SQL D.
UPDATE personnage
SET adresse_personnage = 'prison', id_lieu = (SELECT id_lieu FROM lieu WHERE nom_lieu = 'Condate')
WHERE nom_personnage = 'Zérozérosix';


-- REQUETE SQL E. --
DELETE FROM composer
WHERE id_potion = (SELECT id_potion FROM potion WHERE nom_potion = 'Soupe')
AND id_ingredient = (SELECT id_ingredient FROM ingredient WHERE nom_ingredient = 'Persil');


-- REQUETE SQL F. --
UPDATE prendre_casque
SET id_casque = (SELECT id_casque FROM casque WHERE nom_casque = 'Weisenau') , qte = 42
WHERE id_personnage = (SELECT id_personnage FROM personnage WHERE nom_personnage = 'Obélix')
AND id_bataille = (SELECT id_bataille FROM bataille WHERE nom_bataille = 'Attaque de la banque postale');

