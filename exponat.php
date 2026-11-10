<?php
// Pagina exponatului: fișa completă din tabelele exponate, imagini, specificatii, persoane și surse.
require __DIR__ . '/includes/functii.php';

$pdo = db();
$st = $pdo->prepare(
    'SELECT e.*, c.nume AS sectiune, c.slug AS sectiune_slug
       FROM exponate e
       JOIN categorii c ON c.id_categorie = e.id_categorie
      WHERE e.slug = ?'
);
$st->execute([parametru_get('slug')]);
$x = $st->fetch();
if (!$x) {
    pagina_negasita('Exponatul cerut nu există.');
}
$id = $x['id_exponat'];

function randuri(PDO $pdo, string $sql, array $param): array
{
    $st = $pdo->prepare($sql);
    $st->execute($param);
    return $st->fetchAll();
}

$imagini = randuri($pdo, 'SELECT fisier, text_alternativ, credit FROM imagini WHERE id_exponat = ? ORDER BY ordine', [$id]);
$specificatii = randuri($pdo, 'SELECT eticheta, valoare FROM specificatii WHERE id_exponat = ? ORDER BY ordine', [$id]);
$persoane = randuri($pdo,
    'SELECT p.nume, p.an_nastere, p.an_deces, ep.rol
       FROM exponat_persoana ep
       JOIN persoane p ON p.id_persoana = ep.id_persoana
      WHERE ep.id_exponat = ?
      ORDER BY p.nume', [$id]);
$surse = randuri($pdo, 'SELECT titlu, autor, url, an_publicare FROM surse WHERE id_exponat = ? ORDER BY id_sursa', [$id]);

// exponatul anterior și cel următor după an, ca într-un traseu de muzeu
$anterior = randuri($pdo,
    'SELECT titlu, slug, an FROM exponate WHERE (an, id_exponat) < (?, ?) ORDER BY an DESC, id_exponat DESC LIMIT 1',
    [$x['an'], $id])[0] ?? null;
$urmator = randuri($pdo,
    'SELECT titlu, slug, an FROM exponate WHERE (an, id_exponat) > (?, ?) ORDER BY an, id_exponat LIMIT 1',
    [$x['an'], $id])[0] ?? null;

function ani_viata(array $p): string
{
    if ($p['an_nastere'] === null) {
        return '';
    }
    return $p['an_deces'] === null ? ' (n. ' . $p['an_nastere'] . ')' : ' (' . $p['an_nastere'] . '–' . $p['an_deces'] . ')';
}

$principala = $imagini[0] ?? ['fisier' => $x['imagine_principala'], 'text_alternativ' => $x['titlu'], 'credit' => null];

$titlu_pagina = $x['titlu'] . ' · Aripi românești';
$pagina_curenta = 'colectie';
require __DIR__ . '/includes/header.php';
?>
  <main id="continut" class="container pagina">
    <nav class="fir" aria-label="Fir de navigare">
      <ol>
        <li><a href="index.php">Acasă</a></li>
        <li><a href="colectie.php">Colecție</a></li>
        <li><a href="colectie.php?sectiune=<?= e($x['sectiune_slug']) ?>"><?= e($x['sectiune']) ?></a></li>
        <li aria-current="page"><?= e($x['titlu']) ?></li>
      </ol>
    </nav>

    <article>
      <div class="exponat-rand">
        <figure class="galerie">
          <img src="<?= e($principala['fisier']) ?>" alt="<?= e($principala['text_alternativ']) ?>" width="800" height="500">
<?php if ($principala['credit']): ?>
          <figcaption><?= e($principala['credit']) ?></figcaption>
<?php endif; ?>
<?php if (count($imagini) > 1): ?>
          <ul class="miniaturi">
<?php foreach ($imagini as $img): ?>
            <li><a href="<?= e($img['fisier']) ?>"><img src="<?= e($img['fisier']) ?>" alt="<?= e($img['text_alternativ']) ?>" width="800" height="500" loading="lazy"></a></li>
<?php endforeach; ?>
          </ul>
<?php endif; ?>
        </figure>

        <div>
          <p class="eticheta-sectiune"><?= e($x['sectiune']) ?></p>
          <h1><?= e($x['titlu']) ?></h1>
          <p class="meta"><?= e($x['datare']) ?> · <?= e(tip_text($x['tip'])) ?></p>

          <section class="fisa" aria-labelledby="t-fisa">
            <h2 id="t-fisa">Fișa tehnică</h2>
            <dl>
<?php foreach ($specificatii as $s): ?>
              <div><dt><?= e($s['eticheta']) ?></dt><dd><?= e($s['valoare']) ?></dd></div>
<?php endforeach; ?>
            </dl>
          </section>

<?php if ($persoane): ?>
          <section class="persoane" aria-labelledby="t-persoane">
            <h2 id="t-persoane">Persoane legate</h2>
            <ul>
<?php foreach ($persoane as $p): ?>
              <li><?= e($p['nume']) . e(ani_viata($p)) ?> · <?= e($p['rol']) ?></li>
<?php endforeach; ?>
            </ul>
          </section>
<?php endif; ?>
        </div>
      </div>

      <section class="descriere" aria-labelledby="t-despre">
        <h2 id="t-despre">Despre exponat</h2>
<?php foreach (preg_split('/\R{2,}/', $x['descriere'] ?? '') as $paragraf): ?>
        <p><?= e($paragraf) ?></p>
<?php endforeach; ?>
      </section>

<?php if ($surse): ?>
      <section class="surse" aria-labelledby="t-surse">
        <h2 id="t-surse">Surse</h2>
        <ol>
<?php foreach ($surse as $s): ?>
          <li><?= e($s['autor']) ?>, <?= $s['url'] ? '<a href="' . e($s['url']) . '">' . e($s['titlu']) . '</a>' : '<cite>' . e($s['titlu']) . '</cite>' ?><?= $s['an_publicare'] ? ', ' . (int) $s['an_publicare'] : '' ?></li>
<?php endforeach; ?>
        </ol>
      </section>
<?php endif; ?>
    </article>

    <nav class="vecini" aria-label="Exponatul anterior și următor">
<?php if ($anterior): ?>
      <a class="buton-secundar" href="<?= e(url_exponat($anterior['slug'])) ?>" rel="prev">← <?= e($anterior['titlu']) ?> (<?= (int) $anterior['an'] ?>)</a>
<?php endif; ?>
<?php if ($urmator): ?>
      <a class="buton-secundar urmator" href="<?= e(url_exponat($urmator['slug'])) ?>" rel="next"><?= e($urmator['titlu']) ?> (<?= (int) $urmator['an'] ?>) →</a>
<?php endif; ?>
    </nav>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
