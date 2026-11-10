<?php
// Funcții comune pentru paginile site-ului.
require_once __DIR__ . '/db.php';

// Orice eroare neprinsă afișează o pagină simplă, nu mesajul brut al PHP.
set_exception_handler(function (Throwable $ex): void {
    http_response_code(500);
    $debug = false;
    try {
        $debug = (bool) (config()['debug'] ?? false);
    } catch (Throwable) {
    }
    echo '<!DOCTYPE html><html lang="ro"><head><meta charset="utf-8"><title>Eroare · Aripi românești</title></head><body>'
        . '<h1>Pagina nu poate fi afișată</h1><p>Verificați dacă serverele MAMP sunt pornite și datele din includes/config.php.</p>'
        . ($debug ? '<p><code>' . e($ex->getMessage()) . '</code></p>' : '')
        . '</body></html>';
});

function parametru_get(string $nume): string
{
    $valoare = $_GET[$nume] ?? '';
    return is_string($valoare) ? trim($valoare) : '';
}

function tip_text(string $tip): string
{
    return ['aeronava' => 'aeronavă', 'document' => 'document', 'misiune' => 'misiune'][$tip] ?? $tip;
}

function url_exponat(string $slug): string
{
    return 'exponat.php?slug=' . rawurlencode($slug);
}

// Cardul unui exponat (Acasă, Colecție, Căutare). Imaginea are alt gol: titlul de sub ea spune același lucru.
// $nivel = nivelul titlului: 2 sub h1 (Colecție, Căutare), 3 sub un h2 (Acasă).
function card_exponat(array $x, int $nivel = 3): string
{
    return '<article class="card"><a href="' . e(url_exponat($x['slug'])) . '">'
        . '<img src="' . e($x['imagine_principala']) . '" alt="" width="800" height="500" loading="lazy">'
        . "<h$nivel class=\"card-titlu\">" . e($x['titlu']) . "</h$nivel>"
        . '<p class="card-meta">' . e($x['datare']) . ' · ' . e(tip_text($x['tip'])) . '</p>'
        . '</a></article>';
}

function pagina_negasita(string $mesaj): never
{
    http_response_code(404);
    $titlu_pagina = 'Pagină negăsită · Aripi românești';
    require __DIR__ . '/header.php';
    echo '<main id="continut" class="container pagina"><h1>Pagină negăsită</h1><p>' . e($mesaj)
        . '</p><p><a href="colectie.php">Înapoi la colecție</a></p></main>';
    require __DIR__ . '/footer.php';
    exit;
}
