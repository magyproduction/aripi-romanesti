<?php
// Copiați acest fișier ca includes/config.php (config.php nu intră în Git).
// Valorile de mai jos sunt cele implicite din MAMP.
return [
    'db' => [
        'host'    => '127.0.0.1',
        'port'    => 8889,
        'name'    => 'aripi_romanesti',
        'user'    => 'root',
        'pass'    => 'root',
        'charset' => 'utf8mb4',
    ],
    // true doar pe calculatorul de dezvoltare: afișează mesajul exact al erorii
    'debug' => true,
];
