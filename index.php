<?php
// Acasă: titlul expoziției, cele 4 secțiuni, 3 exponate recomandate și un fragment din cronologie.
require __DIR__ . '/includes/functii.php';

$pdo = db();
$sectiuni = $pdo->query(
    'SELECT c.nume, c.slug, COUNT(e.id_exponat) AS nr_exponate
       FROM categorii c
       LEFT JOIN exponate e ON e.id_categorie = c.id_categorie
      GROUP BY c.id_categorie, c.nume, c.slug, c.ordine
      ORDER BY c.ordine'
)->fetchAll();
$recomandate = $pdo->query(
    'SELECT titlu, slug, datare, tip, imagine_principala FROM exponate WHERE recomandat = 1 ORDER BY an LIMIT 3'
)->fetchAll();
$cronologie = $pdo->query('SELECT titlu, slug, an FROM exponate ORDER BY an, id_exponat')->fetchAll();

$titlu_pagina = 'Aripi românești — 12 repere ale zborului';
$pagina_curenta = 'acasa';
require __DIR__ . '/includes/header.php';
?>
  <main id="continut">
    <section class="erou">
      <div class="container erou-rand">
        <div>
          <h1>Aripi românești</h1>
          <p class="erou-text">12 repere ale zborului, de la rachetele lui Conrad Haas la zborul cosmic al lui Dumitru Prunariu.</p>
          <a class="buton" href="colectie.php">Intră în expoziție</a>
        </div>
        <img src="img/exponate/iar-80.svg" alt="Ilustrație de test: avionul IAR-80, 1939" width="800" height="500">
      </div>
    </section>

    <div class="container">
      <section aria-labelledby="t-sectiuni">
        <h2 id="t-sectiuni">Secțiunile expoziției</h2>
        <ul class="sectiuni">
<?php foreach ($sectiuni as $s): ?>
          <li><a href="colectie.php?sectiune=<?= e($s['slug']) ?>"><strong><?= e($s['nume']) ?></strong><?= (int) $s['nr_exponate'] ?> exponate</a></li>
<?php endforeach; ?>
        </ul>
      </section>

      <section aria-labelledby="t-recomandate">
        <h2 id="t-recomandate">Exponate recomandate</h2>
        <div class="grila grila-plina">
<?php foreach ($recomandate as $x): ?>
          <?= card_exponat($x) ?>

<?php endforeach; ?>
        </div>
      </section>

      <section aria-labelledby="t-cronologie">
        <div class="titlu-rand">
          <h2 id="t-cronologie">Fragment din cronologie</h2>
          <a href="cronologie.php">Vezi cronologia →</a>
        </div>
        <div class="axa-scroll">
          <ol class="axa">
<?php foreach ($cronologie as $x): ?>
            <li><a href="<?= e(url_exponat($x['slug'])) ?>"><?= (int) $x['an'] ?><span class="ascuns">: <?= e($x['titlu']) ?></span></a></li>
<?php endforeach; ?>
          </ol>
        </div>
      </section>
    </div>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
