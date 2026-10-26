# Aripi românești

Proiect Web Design: expoziția virtuală „Aripi românești — 12 repere ale zborului”.

## 1. Platforma aleasă

PHP, fără framework, cu baza de date MySQL. Site-ul are puține pagini, iar fiecare pagină
citește direct din bază, deci nu este nevoie de WordPress, Laravel sau CodeIgniter.

## 2. Mediul offline

MAMP 7.4, instalat pe macOS:

- Apache 2.4.66, portul 8888
- MySQL 8.0.44, portul 8889
- PHP 8.3.30

Proiectul este în `/Applications/MAMP/htdocs/aripi-romanesti`.

## 3. Depozitul Git

https://github.com/magyproduction/aripi-romanesti

`.gitignore` exclude `includes/config.php`, fișierul cu parola bazei de date.

## 4. Pagina „Hello”

`index.php` se conectează la baza `aripi_romanesti` și afișează secțiunile expoziției citite din tabelele `categorii` și `exponate`.

![Pagina Hello](docs/hello.png)

## Pornire

1. Copiază folderul proiectului în `/Applications/MAMP/htdocs` și pornește serverele din MAMP (Start).
2. În phpMyAdmin (http://localhost:8888/phpMyAdmin5/) importă `sql/schema.sql`, apoi `sql/seed.sql`.
3. Copiază `includes/config.example.php` ca `includes/config.php` (conține deja valorile implicite MAMP).
4. Deschide http://localhost:8888/aripi-romanesti/.
