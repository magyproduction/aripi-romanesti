<?php
// Pagina „Hello”: verifică conexiunea la baza de date aripi_romanesti.
require __DIR__ . '/includes/db.php';

$eroare = null;
try {
    $pdo = db();
    $versiune = $pdo->query('SELECT VERSION()')->fetchColumn();
    $sectiuni = $pdo->query(
        'SELECT c.nume, COUNT(e.id_exponat) AS nr_exponate
           FROM categorii c
           LEFT JOIN exponate e ON e.id_categorie = c.id_categorie
          GROUP BY c.id_categorie, c.nume, c.ordine
          ORDER BY c.ordine'
    )->fetchAll();
} catch (Throwable $ex) {
    $eroare = $ex;
}

$debug = false;
try {
    $debug = (bool) (config()['debug'] ?? false);
} catch (Throwable) {
}

$titlu_pagina = 'Hello · Aripi românești';
require __DIR__ . '/includes/header.php';
?>
  <main class="container">
    <h1>Hello, Aripi românești!</h1>

<?php if ($eroare): ?>
    <p class="stare stare-eroare">Nu mă pot conecta la baza de date. Verifică dacă MariaDB/MySQL rulează și datele din <code>includes/config.php</code>.</p>
<?php if ($debug): ?>
    <p><code><?= e($eroare->getMessage()) ?></code></p>
<?php endif; ?>
<?php else: ?>
    <p class="stare stare-ok">Conectat la baza de date <code>aripi_romanesti</code> (<?= e($versiune) ?>, PHP <?= e(PHP_VERSION) ?>).</p>

    <h2>Secțiunile expoziției, citite din baza de date</h2>
    <ul>
<?php foreach ($sectiuni as $s): ?>
      <li><?= e($s['nume']) ?>: <?= (int) $s['nr_exponate'] ?> exponate</li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
