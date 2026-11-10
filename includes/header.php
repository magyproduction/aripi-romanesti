<?php
/** @var string $titlu_pagina */
/** @var string $pagina_curenta */
$titlu_pagina = $titlu_pagina ?? 'Aripi românești';
$pagina_curenta = $pagina_curenta ?? '';
$meniu = [
    'acasa'      => ['index.php', 'Acasă'],
    'colectie'   => ['colectie.php', 'Colecție'],
    'cronologie' => ['cronologie.php', 'Cronologie'],
    'despre'     => ['despre.php', 'Despre'],
    'contact'    => ['contact.php', 'Contact'],
];
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titlu_pagina) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&amp;family=IBM+Plex+Mono&amp;family=IBM+Plex+Sans:wght@400;600&amp;display=swap">
  <link rel="stylesheet" href="css/style.css">
  <script>document.documentElement.classList.add('js');</script>
  <script src="js/meniu.js" defer></script>
</head>
<body>
  <a class="sari" href="#continut">Sari la conținut</a>
  <header class="antet">
    <div class="container antet-rand">
      <a class="logo" href="index.php">Aripi românești</a>
      <button class="buton-meniu" type="button" aria-expanded="false" aria-controls="meniu">Meniu</button>
      <nav id="meniu" class="meniu" aria-label="Meniul principal">
        <ul>
<?php foreach ($meniu as $cheie => [$url, $text]): ?>
          <li><a href="<?= $url ?>"<?= $cheie === $pagina_curenta ? ' aria-current="page"' : '' ?>><?= $text ?></a></li>
<?php endforeach; ?>
        </ul>
        <form class="cautare-antet" action="cautare.php" method="get" role="search">
          <label class="ascuns" for="q-antet">Caută în expoziție</label>
          <input id="q-antet" name="q" type="search" placeholder="Caută…" value="<?= e(parametru_get('q')) ?>">
          <button class="ascuns" type="submit">Caută</button>
        </form>
      </nav>
    </div>
  </header>
