-- Aripi românești — schema bazei de date (etapa 1, secțiunea 6)
-- 9 tabele: 7 pentru conținutul expoziției, 2 pentru administrare și contact.
-- MySQL 8 (MAMP), motor InnoDB, utf8mb4 cu colație românească.

SET NAMES utf8mb4;  -- fișierul este UTF-8, indiferent de setarea clientului
CREATE DATABASE IF NOT EXISTS aripi_romanesti
  CHARACTER SET utf8mb4 COLLATE utf8mb4_romanian_ci;
USE aripi_romanesti;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS exponat_persoana, surse, specificatii, imagini, exponate,
                     persoane, categorii, utilizatori, mesaje;
SET FOREIGN_KEY_CHECKS = 1;

-- Cele 4 secțiuni ale expoziției
CREATE TABLE categorii (
  id_categorie INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  nume         VARCHAR(80)      NOT NULL,
  slug         VARCHAR(80)      NOT NULL,
  descriere    TEXT             NULL,
  ordine       TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id_categorie),
  UNIQUE KEY uq_categorii_slug (slug)
) ENGINE=InnoDB;

-- Tabelul central: câte un rând pentru fiecare exponat
CREATE TABLE exponate (
  id_exponat         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_categorie       INT UNSIGNED NOT NULL,
  titlu              VARCHAR(150) NOT NULL,
  slug               VARCHAR(150) NOT NULL,
  an                 SMALLINT     NOT NULL,
  datare             VARCHAR(40)  NULL,
  tip                ENUM('aeronava','document','misiune') NOT NULL,
  rezumat            VARCHAR(300) NULL,
  descriere          TEXT         NULL,
  imagine_principala VARCHAR(255) NULL,
  recomandat         TINYINT(1)   NOT NULL DEFAULT 0,
  creat_la           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizat_la      DATETIME     NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_exponat),
  UNIQUE KEY uq_exponate_slug (slug),
  KEY ix_exponate_an (an),
  CONSTRAINT fk_exponate_categorii FOREIGN KEY (id_categorie)
    REFERENCES categorii (id_categorie) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Galeria fiecărui exponat
CREATE TABLE imagini (
  id_imagine      INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  id_exponat      INT UNSIGNED     NOT NULL,
  fisier          VARCHAR(255)     NOT NULL,
  text_alternativ VARCHAR(255)     NOT NULL,
  credit          VARCHAR(255)     NULL,
  ordine          TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id_imagine),
  CONSTRAINT fk_imagini_exponate FOREIGN KEY (id_exponat)
    REFERENCES exponate (id_exponat) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Fișa tehnică: perechi etichetă–valoare
CREATE TABLE specificatii (
  id_specificatie INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  id_exponat      INT UNSIGNED     NOT NULL,
  eticheta        VARCHAR(60)      NOT NULL,
  valoare         VARCHAR(120)     NOT NULL,
  ordine          TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id_specificatie),
  CONSTRAINT fk_specificatii_exponate FOREIGN KEY (id_exponat)
    REFERENCES exponate (id_exponat) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Pionieri, constructori, piloți și cosmonauți
CREATE TABLE persoane (
  id_persoana INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nume        VARCHAR(120) NOT NULL,
  an_nastere  SMALLINT     NULL,
  an_deces    SMALLINT     NULL,        -- NULL pentru persoane în viață
  biografie   TEXT         NULL,
  imagine     VARCHAR(255) NULL,
  PRIMARY KEY (id_persoana)
) ENGINE=InnoDB;

-- Legătura N:M exponate ↔ persoane
CREATE TABLE exponat_persoana (
  id_exponat  INT UNSIGNED NOT NULL,
  id_persoana INT UNSIGNED NOT NULL,
  rol         VARCHAR(60)  NULL,        -- constructor, pilot, autor, cosmonaut
  PRIMARY KEY (id_exponat, id_persoana),
  CONSTRAINT fk_ep_exponate FOREIGN KEY (id_exponat)
    REFERENCES exponate (id_exponat) ON DELETE CASCADE,
  CONSTRAINT fk_ep_persoane FOREIGN KEY (id_persoana)
    REFERENCES persoane (id_persoana) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Bibliografia afișată la finalul paginii exponatului
CREATE TABLE surse (
  id_sursa     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_exponat   INT UNSIGNED NOT NULL,
  titlu        VARCHAR(255) NOT NULL,
  autor        VARCHAR(150) NULL,
  url          VARCHAR(500) NULL,       -- NULL pentru surse tipărite
  an_publicare SMALLINT     NULL,
  PRIMARY KEY (id_sursa),
  CONSTRAINT fk_surse_exponate FOREIGN KEY (id_exponat)
    REFERENCES exponate (id_exponat) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Conturile din zona de administrare (parola doar ca password_hash())
CREATE TABLE utilizatori (
  id_utilizator INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email         VARCHAR(190) NOT NULL,
  parola_hash   VARCHAR(255) NOT NULL,
  nume          VARCHAR(100) NULL,
  rol           ENUM('admin','editor') NOT NULL DEFAULT 'editor',
  creat_la      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_utilizator),
  UNIQUE KEY uq_utilizatori_email (email)
) ENGINE=InnoDB;

-- Mesajele trimise din formularul de contact
CREATE TABLE mesaje (
  id_mesaj  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nume      VARCHAR(100) NOT NULL,
  email     VARCHAR(190) NOT NULL,
  subiect   VARCHAR(150) NULL,
  mesaj     TEXT         NOT NULL,
  trimis_la DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  citit     TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (id_mesaj)
) ENGINE=InnoDB;
