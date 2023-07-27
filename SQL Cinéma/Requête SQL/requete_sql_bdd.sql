CREATE TABLE PERSONNE(
   id_personne INT NOT NULL AUTO_INCREMENT,
   nom VARCHAR(50) NOT NULL,
   prenom VARCHAR(50),
   sexe VARCHAR(50) NOT NULL,
   date_de_naissance DATE NOT NULL,
   PRIMARY KEY(id_personne)
);

 

 

CREATE TABLE GENRE(
   id_genre INT  NOT NULL AUTO_INCREMENT,
   libelle VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_genre)
);

 

 

CREATE TABLE ACTEUR(
   id_acteur INT  NOT NULL AUTO_INCREMENT,
   id_personne INT NOT NULL,
   PRIMARY KEY(id_acteur),
   UNIQUE(id_personne),
   FOREIGN KEY(id_personne) REFERENCES PERSONNE(id_personne)
);

 

 

CREATE TABLE REALISATEUR(
   id_realisateur INT  NOT NULL AUTO_INCREMENT,
   id_personne INT NOT NULL,
   PRIMARY KEY(id_realisateur),
   UNIQUE(id_personne),
   FOREIGN KEY(id_personne) REFERENCES PERSONNE(id_personne)
);

 

 

CREATE TABLE ROLE(
   id_role INT  NOT NULL AUTO_INCREMENT,
   role_jouer VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_role)
);

 

 

CREATE TABLE FILM(
   id_film INT  NOT NULL AUTO_INCREMENT,
   titre VARCHAR(50) NOT NULL,
   date_sortie DATE NOT NULL,
   duree_minute INT NOT NULL,
   affiche VARCHAR(255),
   note INT NOT NULL,
   synopsis VARCHAR(50),
   id_realisateur INT NOT NULL,
   PRIMARY KEY(id_film),
   FOREIGN KEY(id_realisateur) REFERENCES REALISATEUR(id_realisateur)
);

 

 

CREATE TABLE jouer(
   id_film INT,
   id_acteur INT,
   id_role INT,
   PRIMARY KEY(id_film, id_acteur, id_role),
   FOREIGN KEY(id_film) REFERENCES FILM(id_film),
   FOREIGN KEY(id_acteur) REFERENCES ACTEUR(id_acteur),
   FOREIGN KEY(id_role) REFERENCES ROLE(id_role)
);

 

 

CREATE TABLE contenir(
   id_film INT,
   id_genre INT,
   PRIMARY KEY(id_film, id_genre),
   FOREIGN KEY(id_film) REFERENCES FILM(id_film),
   FOREIGN KEY(id_genre) REFERENCES GENRE(id_genre)
);