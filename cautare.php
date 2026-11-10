<?php
// Căutare: exponatele după titlu sau an (cautare.php?q=…).
require __DIR__ . '/includes/functii.php';

$q = parametru_get('q');
$rezultate = [];
if ($q !== '') {
    $st = db()->prepare(
        "SELECT titlu, slug, datare, tip, imagine_principala FROM exponate
          WHERE titlu LIKE ? ESCAPE '!' OR CAST(an AS CHAR) = ?
          ORDER BY an, id_exponat"
    );
    $st->execute(['%' . addcslashes($q, '%_!') . '%', $q]);
    $rezultate = $st->fetchAll();
}

$titlu_pagina = 'Căutare · Aripi românești';
require __DIR__ . '/includes/header.php';
?>
  <main id="continut" class="container pagina">
    <h1>Căutare</h1>
    <form class="sortare" action="cautare.php" method="get" role="search">
      <label for="q">Titlu sau an</label>
      <input id="q" name="q" type="search" value="<?= e($q) ?>">
      <button class="buton" type="submit">Caută</button>
    </form>

<?php if ($q === ''): ?>
    <p class="numar">Scrieți un titlu (de exemplu „IAR”) sau un an (de exemplu 1910).</p>
<?php else: ?>
    <p class="numar"><?= count($rezultate) ?> rezultate pentru „<?= e($q) ?>”</p>
    <div class="grila">
<?php foreach ($rezultate as $x): ?>
      <?= card_exponat($x, 2) ?>

<?php endforeach; ?>
    </div>
<?php endif; ?>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
