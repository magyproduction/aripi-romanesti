<?php
// Pagina „Hello” (etapa 2): verifică conexiunea la baza de date și afișează datele inițiale.
// În etapele următoare devine pagina Acasă din harta site-ului.
require __DIR__ . '/includes/db.php';

$eroare = null;
try {
    $pdo = db();

    $server = $pdo->query(
        "SELECT VERSION() AS versiune, DATABASE() AS baza, @@collation_database AS colatie"
    )->fetch();

    $nr_tabele = (int) $pdo->query(
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()"
    )->fetchColumn();

    $sectiuni = $pdo->query(
        "SELECT c.nume, c.slug, COUNT(e.id_exponat) AS nr_exponate
           FROM categorii c
           LEFT JOIN exponate e ON e.id_categorie = c.id_categorie
          GROUP BY c.id_categorie, c.nume, c.slug, c.ordine
          ORDER BY c.ordine"
    )->fetchAll();

    $exponate = $pdo->query(
        "SELECT e.titlu, e.datare, e.tip, e.recomandat, c.nume AS sectiune
           FROM exponate e
           JOIN categorii c ON c.id_categorie = e.id_categorie
          ORDER BY e.an, e.id_exponat"
    )->fetchAll();
} catch (Throwable $ex) {
    $eroare = $ex;
}

$debug = false;
try {
    $debug = (bool) (config()['debug'] ?? false);
} catch (Throwable) {
}

$tipuri = ['aeronava' => 'aeronavă', 'document' => 'document', 'misiune' => 'misiune'];
$titlu_pagina = 'Hello · Aripi românești';
require __DIR__ . '/includes/header.php';
?>
  <main class="container">
    <section class="hello">
      <h1>Hello, Aripi românești!</h1>
      <p class="subtitlu">12 repere ale zborului, de la rachetele lui Conrad Haas la zborul cosmic al lui Dumitru Prunariu.</p>
    </section>

<?php if ($eroare): ?>
    <section class="stare stare-eroare" role="alert">
      <h2>Conexiunea la baza de date a eșuat</h2>
      <p>Verificați că serverul MariaDB/MySQL rulează, că baza <code>aripi_romanesti</code> a fost importată
        din <code>sql/schema.sql</code> și <code>sql/seed.sql</code> și că datele din <code>includes/config.php</code> sunt corecte.</p>
<?php if ($debug): ?>
      <p><code><?= e($eroare->getMessage()) ?></code></p>
<?php endif; ?>
    </section>
<?php else: ?>
    <section class="stare stare-ok" aria-labelledby="titlu-stare">
      <h2 id="titlu-stare">Conectat la baza de date</h2>
      <dl class="fisa">
        <div><dt>Server</dt><dd><?= e($server['versiune']) ?></dd></div>
        <div><dt>Baza de date</dt><dd><?= e($server['baza']) ?></dd></div>
        <div><dt>Colație</dt><dd><?= e($server['colatie']) ?></dd></div>
        <div><dt>Tabele</dt><dd><?= $nr_tabele ?></dd></div>
        <div><dt>PHP</dt><dd><?= e(PHP_VERSION) ?></dd></div>
      </dl>
    </section>

    <section aria-labelledby="titlu-sectiuni">
      <h2 id="titlu-sectiuni">Secțiunile expoziției</h2>
      <ul class="sectiuni">
<?php foreach ($sectiuni as $s): ?>
        <li><strong><?= e($s['nume']) ?></strong><span><?= (int) $s['nr_exponate'] ?> exponate</span></li>
<?php endforeach; ?>
      </ul>
    </section>

    <section aria-labelledby="titlu-exponate">
      <h2 id="titlu-exponate">Exponatele, citite din tabelul <code>exponate</code></h2>
      <div class="tabel-scroll" tabindex="0" role="region" aria-labelledby="titlu-exponate">
        <table>
          <caption><?= count($exponate) ?> exponate, ordonate după an</caption>
          <thead>
            <tr><th scope="col">Nr.</th><th scope="col">Exponat</th><th scope="col">Datare</th><th scope="col">Secțiune</th><th scope="col">Tip</th></tr>
          </thead>
          <tbody>
<?php foreach ($exponate as $i => $x): ?>
            <tr>
              <td class="nr"><?= $i + 1 ?></td>
              <th scope="row"><?= e($x['titlu']) ?><?= $x['recomandat'] ? ' <span class="recomandat">recomandat</span>' : '' ?></th>
              <td class="mono"><?= e($x['datare']) ?></td>
              <td><?= e($x['sectiune']) ?></td>
              <td><?= e($tipuri[$x['tip']] ?? $x['tip']) ?></td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
<?php endif; ?>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
