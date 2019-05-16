/*
 * Suppression des tables existants
 */
DROP TABLE IF EXISTS from_game;
DROP TABLE IF EXISTS have_role;
DROP TABLE IF EXISTS game;
DROP TABLE IF EXISTS score;
DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS member;

/* Creation table des membres */
CREATE TABLE member
(
    id_member       INTEGER(10)     AUTO_INCREMENT,
    mail            VARCHAR(255)    NOT NULL,
    password        VARCHAR(255)    NOT NULL,
    pseudo          VARCHAR(255)    NOT NULL,
    sign_in_date    TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_member)
);

/* Creation table des privileges */
CREATE TABLE role
(
    id_role     INTEGER(10)     AUTO_INCREMENT,
    nom         VARCHAR(255)    NOT NULL,
    PRIMARY KEY (id_role)
);

/* Creation table des privileges des membres */
CREATE TABLE have_role
(
    id_member   INTEGER(10),
    id_role     INTEGER(10) DEFAULT '2',
    PRIMARY KEY (id_member, id_role),
    FOREIGN KEY (id_member) REFERENCES member(id_member),
    FOREIGN KEY (id_role) REFERENCES role(id_role)
);

/* Creation table des scores */
CREATE TABLE score
(
    id_score    INTEGER(10)     AUTO_INCREMENT,
    id_member   INTEGER(10),
    point       INTEGER(10)     NOT NULL,
    score_date  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_score),
    FOREIGN KEY (id_member) REFERENCES member(id_member)
);

/* Creation table des jeux */
CREATE TABLE game
(
    id_game     INTEGER(10)     AUTO_INCREMENT,
    name_game   VARCHAR(255)    NOT NULL,
    PRIMARY KEY (id_game)
);

/* Creation table de lien avec les jeux et les scores */
CREATE TABLE from_game
(
    id_score    INTEGER(10),
    id_game     INTEGER(10),
    PRIMARY KEY (id_score, id_game),
    FOREIGN KEY (id_score) REFERENCES score(id_score),
    FOREIGN KEY (id_game) REFERENCES game(id_game)
);

/*
 * Insertion valeur par defaut
 */
INSERT INTO member (mail, password, pseudo) VALUES ("admin@admin.fr", "d033e22ae348aeb5660fc2140aec35850c4da997", "admin@admin.fr");

INSERT INTO role (nom) VALUES ("administrateur");
INSERT INTO role (nom) VALUES ("utilisateur");

INSERT INTO have_role (id_member, id_role) VALUES (1,1);

INSERT INTO game (name_game) VALUES ("Capitale");
INSERT INTO game (name_game) VALUES ("Pays");