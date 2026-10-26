# Aripi românești — 12 repere ale zborului

Expoziție virtuală despre aviația și cosmonautica românească, de la manuscrisul lui Conrad Haas
(sec. XVI) până la IAR-99 Șoim (1985). Proiect pentru disciplina **Web Design**, master, anul I.

Conceptul complet (colecția, cele 12 exponate, harta site-ului, wireframe-uri, paleta, fonturile
și schema bazei de date) este în [`docs/concept-etapa1.pdf`](docs/concept-etapa1.pdf).

## Stadiul proiectului

| Etapă | Conținut | Stare |
|---|---|---|
| 1 | Colecția și titlul, cele 12 exponate, hartă, wireframe-uri, paletă și fonturi, schema bazei | gata |
| 2 | Platforma, mediul offline, depozitul Git, pagina „Hello” conectată la bază | gata |
| 3+ | Paginile din harta site-ului: colecție, exponat, cronologie, căutare, contact, administrare | urmează |

## Platforma aleasă: PHP simplu (fără framework)

- Site-ul are puține pagini (5 în meniu, pagina exponatului și administrarea), cu adrese simple
  (`colectie.php?sectiune=…`, `exponat.php?slug=…`), deja stabilite în harta din etapa 1.
- Fiecare pagină se scrie direct în PHP: se vede exact ce interogare SQL produce ce bucată de HTML,
  fără rutare, ORM sau Composer de învățat în paralel.
- Elementele comune (header, footer, conexiunea la bază) stau în `includes/` și se includ cu `require`.
- Acces la bază prin **PDO** cu interogări pregătite (protecție la SQL injection), parole cu `password_hash()`.
- Rulează pe orice găzduire cu PHP 8 și MySQL/MariaDB, fără configurare specială.

WordPress ar fi impus teme și pluginuri peste o structură de date proprie (fișă tehnică, persoane, surse),
iar Laravel/CodeIgniter ar fi adus mai mult cod-cadru decât cod propriu pentru un site de această mărime.

## Mediul de lucru offline

| | Folosit la dezvoltare (macOS) | Echivalent Windows |
|---|---|---|
| Server web | serverul integrat PHP: `php -S localhost:8000` | Apache din XAMPP |
| PHP | PHP 8.5 (Homebrew) | PHP 8.x din XAMPP |
| Baza de date | MariaDB (Homebrew), serviciu local pe portul 3306 | MariaDB din XAMPP |
| Administrare bază | linia de comandă `mariadb` | phpMyAdmin |
| Editor | Visual Studio Code | Visual Studio Code |
| Versionare | Git | Git |

## Instalare și pornire

### macOS (Homebrew)

```bash
brew install php mariadb
brew services start mariadb
git clone <adresa-depozitului> aripi-romanesti && cd aripi-romanesti

# baza de date: schema (9 tabele) + datele inițiale
# (Homebrew dă acces de administrator utilizatorului macOS care a instalat MariaDB)
mariadb < sql/schema.sql
mariadb < sql/seed.sql

# utilizator propriu pentru aplicație (alegeți o parolă)
mariadb -e "CREATE USER 'aripi_app'@'localhost' IDENTIFIED BY 'parola-aleasa';
                 GRANT SELECT, INSERT, UPDATE, DELETE ON aripi_romanesti.* TO 'aripi_app'@'localhost';"

cp includes/config.example.php includes/config.php   # completați user/parolă
php -S localhost:8000
```

Pagina „Hello” se deschide la <http://localhost:8000/>.

### Windows (XAMPP)

1. Instalați XAMPP (PHP 8.x) și porniți **Apache** și **MySQL** din panoul de control.
2. Copiați folderul proiectului în `C:\xampp\htdocs\aripi-romanesti`.
3. În phpMyAdmin (<http://localhost/phpmyadmin>) → *Import*: întâi `sql/schema.sql`, apoi `sql/seed.sql`.
4. Copiați `includes/config.example.php` ca `includes/config.php` (implicit XAMPP: `root`, fără parolă).
5. Deschideți <http://localhost/aripi-romanesti/>.

## Pagina „Hello”

`index.php` se conectează la baza `aripi_romanesti` și afișează:

- versiunea serverului, numele bazei, colația (`utf8mb4_romanian_ci`), numărul de tabele (9) și versiunea PHP;
- cele 4 secțiuni ale expoziției, cu numărul de exponate din fiecare (`categorii` + `COUNT` pe `exponate`);
- cele 12 exponate, citite din tabelul `exponate` și ordonate după an.

Dacă baza nu răspunde, pagina afișează ce trebuie verificat în loc de o eroare PHP.

![Pagina Hello](docs/hello.png)

## Structura

```text
aripi-romanesti/
├── index.php                 pagina „Hello” (devine pagina Acasă)
├── css/style.css             paleta și tipografia din etapa 1
├── includes/
│   ├── config.example.php    model de configurare (config.php nu intră în Git)
│   ├── db.php                conexiunea PDO și funcția e() pentru escapare
│   ├── header.php
│   └── footer.php
├── sql/
│   ├── schema.sql            cele 9 tabele, InnoDB, utf8mb4_romanian_ci
│   └── seed.sql              4 secțiuni, 12 exponate, 6 persoane
└── docs/
    ├── concept-etapa1.pdf
    └── hello.png
```

## Baza de date

`aripi_romanesti`, 9 tabele: `categorii`, `exponate`, `imagini`, `specificatii`, `persoane`,
`exponat_persoana`, `surse` (conținutul expoziției) și `utilizatori`, `mesaje` (administrare și contact).
`exponate` este tabelul central; legătura cu persoanele este N:M prin `exponat_persoana`.

Ambele fișiere SQL încep cu `SET NAMES utf8mb4`: fără el, un client configurat implicit pe latin1
importă „ș” și „ț” ca „È™” și „È›”. Conexiunea PDO cere și ea `charset=utf8mb4`.

## Git

- `includes/config.php` este în `.gitignore`: parolele bazei nu ajung în depozit.
- Schema și datele inițiale sunt versionate în `sql/`, deci baza se poate reface oricând.
- Mesajele de commit descriu ce s-a schimbat, câte un commit pentru fiecare pas.
