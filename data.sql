DROP TABLE member CASCADE;

CREATE TABLE member
(
    id_member   NUMBER(10);
    mail        VARCHAR2(255);
    password    VARCHAR2(255);
    CONSTRAINT pk_member PRIMARY KEY (id_member);
    CONSTRAINT nn_member_mail CHECK (mail IS NOT NULL);
    CONSTRAINT nn_member_password CHECK (password IS NOT NULL)
);