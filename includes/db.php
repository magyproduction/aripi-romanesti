<?php
// Conexiunea la baza de date, comună pentru toate paginile (PDO + MySQL).

function config(): array
{
    static $config = null;
    if ($config === null) {
        $file = __DIR__ . '/config.php';
        if (!is_file($file)) {
            throw new RuntimeException('Lipsește includes/config.php: copiați config.example.php și completați datele bazei.');
        }
        $config = require $file;
    }
    return $config;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $c = config()['db'];
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $c['host'], $c['port'], $c['name'], $c['charset']);
        try {
            $pdo = new PDO($dsn, $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $ex) {
            // excepție nouă, fără lanț: urma excepției PDO ar conține parola
            throw new RuntimeException('Conexiunea la baza de date a eșuat: ' . $ex->getMessage());
        }
    }
    return $pdo;
}

// Escapare pentru afișarea în HTML
function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
