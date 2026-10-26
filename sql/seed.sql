-- Aripi românești — date inițiale: cele 4 secțiuni, cele 12 exponate și persoanele legate.
-- Fișele complete (descriere, imagini, specificații, surse) se adaugă în etapele următoare.
SET NAMES utf8mb4;  -- fișierul este UTF-8, indiferent de setarea clientului
USE aripi_romanesti;

INSERT INTO categorii (id_categorie, nume, slug, descriere, ordine) VALUES
  (1, 'Pionierii zborului', 'pionierii-zborului', 'Primele aparate de zbor create de români.', 1),
  (2, 'Rachete și cosmos',  'rachete-si-cosmos',  'De la teoria rachetei la primul român în spațiu.', 2),
  (3, 'Aviație militară',   'aviatie-militara',   'Avioane proiectate și construite în România.', 3),
  (4, 'Transport aerian',   'transport-aerian',   'Elicoptere și avioane de pasageri produse sub licență.', 4);

INSERT INTO exponate (id_exponat, id_categorie, titlu, slug, an, datare, tip, rezumat, recomandat) VALUES
  ( 1, 2, 'Manuscrisul lui Conrad Haas', 'manuscrisul-haas', 1529, 'sec. XVI, Sibiu', 'document',
       'Una dintre primele descrieri ale rachetei în trepte.', 0),
  ( 2, 1, 'Vuia I', 'vuia-i', 1906, '1906', 'aeronava',
       'Aparat care a decolat de pe sol prin mijloace proprii, la Montesson.', 1),
  ( 3, 1, 'Coandă-1910', 'coanda-1910', 1910, '1910', 'aeronava',
       'Aparat cu propulsie fără elice, prezentat la Salonul de la Paris.', 0),
  ( 4, 1, 'Vlaicu I', 'vlaicu-i', 1910, '1910', 'aeronava',
       'Primul zbor al unui avion construit de Aurel Vlaicu, la Cotroceni.', 0),
  ( 5, 2, '„Die Rakete zu den Planetenräumen”', 'die-rakete-oberth', 1923, '1923', 'document',
       'Lucrarea lui Hermann Oberth care fundamentează teoria zborului spațial.', 0),
  ( 6, 3, 'IAR-37', 'iar-37', 1937, '1937', 'aeronava',
       'Avion de recunoaștere și bombardament ușor, IAR Brașov.', 0),
  ( 7, 3, 'IAR-80', 'iar-80', 1939, '1939', 'aeronava',
       'Avion de vânătoare proiectat și construit în România.', 1),
  ( 8, 3, 'IAR-93 Vultur', 'iar-93-vultur', 1974, '1974', 'aeronava',
       'Avion de atac la sol, program comun româno-iugoslav.', 0),
  ( 9, 4, 'IAR-330 Puma', 'iar-330-puma', 1975, '1975', 'aeronava',
       'Elicopter produs la Brașov sub licență Aérospatiale.', 0),
  (10, 2, 'Soiuz 40', 'soiuz-40', 1981, '1981', 'misiune',
       'Misiunea primului român în spațiu, Dumitru Prunariu.', 1),
  (11, 4, 'Rombac 1-11', 'rombac-1-11', 1982, '1982', 'aeronava',
       'Avion de pasageri produs la București sub licență BAC One-Eleven.', 0),
  (12, 3, 'IAR-99 Șoim', 'iar-99-soim', 1985, '1985', 'aeronava',
       'Avion de antrenament și atac ușor, Avioane Craiova.', 0);

INSERT INTO persoane (id_persoana, nume, an_nastere, an_deces) VALUES
  (1, 'Conrad Haas',      1509, 1576),
  (2, 'Traian Vuia',      1872, 1950),
  (3, 'Henri Coandă',     1886, 1972),
  (4, 'Aurel Vlaicu',     1882, 1913),
  (5, 'Hermann Oberth',   1894, 1989),
  (6, 'Dumitru Prunariu', 1952, NULL);

INSERT INTO exponat_persoana (id_exponat, id_persoana, rol) VALUES
  ( 1, 1, 'autor'),
  ( 2, 2, 'constructor și pilot'),
  ( 3, 3, 'constructor'),
  ( 4, 4, 'constructor și pilot'),
  ( 5, 5, 'autor'),
  (10, 6, 'cosmonaut');
