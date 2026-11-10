<?php
// Colecția: exponatele din baza de date, cu filtru pe secțiune (?sectiune=slug) și sortare după an.
require __DIR__ . '/includes/functii.php';

$pdo = db();
$categorii = $pdo->query('SELECT id_categorie, nume, slug FROM categorii ORDER BY ordine')->fetchAll();

$slug = parametru_get('sectiune');
$sortare = parametru_get('sortare') === 'desc' ? 'desc' : 'asc';

$categorie = null;
foreach ($categorii as $c) {
    if ($c['slug'] === $slug) {
        $categorie = $c;
    }
}
if ($slug !== '' && $categorie === null) {
    pagina_negasita('Secțiunea cerută nu există.');
}

$sql = 'SELECT titlu, slug, datare, tip, imagine_principala FROM exponate'
     . ($categorie ? ' WHERE id_categorie = ?' : '')
     . ' ORDER BY an ' . ($sortare === 'desc' ? 'DESC' : 'ASC') . ', id_exponat';
$st = $pdo->prepare($sql);
$st->execute($categorie ? [$categorie['id_categorie']] : []);
$exponate = $st->fetchAll();

function url_filtru(string $slug, string $sortare): string
{
    $param = array_filter(['sectiune' => $slug, 'sortare' => $sortare === 'desc' ? 'desc' : '']);
    return 'colectie.php' . ($param ? '?' . http_build_query($param) : '');
}

$titlu_pagina = ($categorie ? $categorie['nume'] . ' · ' : '') . 'Colecția · Aripi românești';
$pagina_curenta = 'colectie';
require __DIR__ . '/includes/header.php';
?>
  <main id="continut" class="container pagina">
    <nav class="fir" aria-label="Fir de navigare">
      <ol>
        <li><a href="index.php">Acasă</a></li>
<?php if ($categorie): ?>
        <li><a href="colectie.php">Colecție</a></li>
        <li aria-current="page"><?= e($categorie['nume']) ?></li>
<?php else: ?>
        <li aria-current="page">Colecție</li>
<?php endif; ?>
      </ol>
    </nav>

    <h1>Colecția</h1>
    <p>Exponatele din patru secțiuni, ordonate după an.</p>

    <div class="instrumente">
      <nav class="filtre" aria-label="Filtru pe secțiuni">
        <ul>
          <li><a href="<?= e(url_filtru('', $sortare)) ?>"<?= $categorie ? '' : ' aria-current="page"' ?>>Toate</a></li>
<?php foreach ($categorii as $c): ?>
          <li><a href="<?= e(url_filtru($c['slug'], $sortare)) ?>"<?= $categorie && $categorie['slug'] === $c['slug'] ? ' aria-current="page"' : '' ?>><?= e($c['nume']) ?></a></li>
<?php endforeach; ?>
        </ul>
      </nav>
      <form class="sortare" action="colectie.php" method="get">
<?php if ($categorie): ?>
        <input type="hidden" name="sectiune" value="<?= e($categorie['slug']) ?>">
<?php endif; ?>
        <label for="sortare">Sortare</label>
        <select id="sortare" name="sortare">
          <option value="asc"<?= $sortare === 'asc' ? ' selected' : '' ?>>după an, crescător</option>
          <option value="desc"<?= $sortare === 'desc' ? ' selected' : '' ?>>după an, descrescător</option>
        </select>
        <button class="buton-secundar" type="submit">Aplică</button>
      </form>
    </div>

    <p class="numar"><?= count($exponate) ?> exponate</p>
    <div class="grila">
<?php foreach ($exponate as $x): ?>
      <?= card_exponat($x, 2) ?>

<?php endforeach; ?>
    </div>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
