DROP TABLE possede_role CASCADE;
DROP TABLE member CASCADE;
DROP TABLE role CASCADE;

CREATE TABLE member
(
    id_member   NUMBER(10);
    mail        VARCHAR2(255);
    password    VARCHAR2(255);
    CONSTRAINT pk_member PRIMARY KEY (id_member);
    CONSTRAINT nn_member_mail CHECK (mail IS NOT NULL);
    CONSTRAINT nn_member_password CHECK (password IS NOT NULL)
);

CREATE TABLE role
{
    id_role     NUMBER(10);
    nom         VARCHAR2(255);
    CONSTRAINT pk_role PRIMARY KEY (id_role);
    CONSTRAINT nn_role_nom CHECK (nom IS NOT NULL)
};

CREATE TABLE possede_role
{
    id_member   NUMBER(10);
    id_role     NUMBER(10);
    CONSTRAINT pk_possede_role PRIMARY KEY (id_member, id_role);
    CONSTRAINT fk_possede_role_id_member_member FOREIGN KEY (id_member) REFERENCES member;
    CONSTRAINT fk_possde_role_id_role_role FOREIGN KEY (id_role) REFERENCES role
};

INSERT INTO TABLE (mail, password) VALUES ("admin@admin.fr", "d033e22ae348aeb5660fc2140aec35850c4da997");