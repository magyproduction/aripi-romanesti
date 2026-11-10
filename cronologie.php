<?php
// Cronologia: exponatele așezate pe axa timpului.
require __DIR__ . '/includes/functii.php';

$exponate = db()->query(
    'SELECT e.titlu, e.slug, e.datare, e.rezumat, c.nume AS sectiune
       FROM exponate e
       JOIN categorii c ON c.id_categorie = e.id_categorie
      ORDER BY e.an, e.id_exponat'
)->fetchAll();

$titlu_pagina = 'Cronologie · Aripi românești';
$pagina_curenta = 'cronologie';
require __DIR__ . '/includes/header.php';
?>
  <main id="continut" class="container pagina">
    <h1>Cronologie</h1>
    <p>Cele <?= count($exponate) ?> exponate, de la cel mai vechi la cel mai nou.</p>
    <ol class="cronologie">
<?php foreach ($exponate as $x): ?>
      <li>
        <span class="an"><?= e($x['datare']) ?></span>
        <h2><a href="<?= e(url_exponat($x['slug'])) ?>"><?= e($x['titlu']) ?></a></h2>
        <p><?= e($x['rezumat']) ?> · <?= e($x['sectiune']) ?></p>
      </li>
<?php endforeach; ?>
    </ol>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
