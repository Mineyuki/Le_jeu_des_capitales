DROP TABLE IF EXISTS score;
DROP TABLE IF EXISTS have_role;
DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS member;

CREATE TABLE member
(
    id_member       INTEGER(10)     AUTO_INCREMENT,
    mail            VARCHAR(255)    NOT NULL,
    password        VARCHAR(255)    NOT NULL,
    sign_in_date    TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_member)
);

CREATE TABLE role
(
    id_role     INTEGER(10)     AUTO_INCREMENT,
    nom         VARCHAR(255)    NOT NULL,
    PRIMARY KEY (id_role)
);

CREATE TABLE have_role
(
    id_member   INTEGER(10),
    id_role     INTEGER(10) DEFAULT '2',
    PRIMARY KEY (id_member, id_role),
    FOREIGN KEY (id_member) REFERENCES member(id_member),
    FOREIGN KEY (id_role) REFERENCES role(id_role)
);

CREATE TABLE score
(
    id_score    INTEGER(10),
    id_member   INTEGER(10),
    point       INTEGER(10) NOT NULL,
    score_date  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_score),
    FOREIGN KEY (id_member) REFERENCES member(id_member)
);

INSERT INTO member (mail, password) VALUES ("admin@admin.fr", "d033e22ae348aeb5660fc2140aec35850c4da997");

INSERT INTO role (nom) VALUES ("administrateur");
INSERT INTO role (nom) VALUES ("utilisateur");

INSERT INTO have_role (id_member, id_role) VALUES (1,1);