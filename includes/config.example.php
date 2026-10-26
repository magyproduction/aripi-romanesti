<?php
// Copiați acest fișier ca includes/config.php și completați datele bazei locale.
// config.php este în .gitignore: parolele nu ajung în depozitul Git.
return [
    'db' => [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'aripi_romanesti',
        'user'    => 'root',   // XAMPP: root, fără parolă
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],
    // true doar pe calculatorul de dezvoltare: afișează mesajul exact al erorii
    'debug' => true,
];
