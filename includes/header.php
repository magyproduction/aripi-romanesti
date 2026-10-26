<?php
/** @var string $titlu_pagina */
$titlu_pagina = $titlu_pagina ?? 'Aripi românești';
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
</head>
<body>
  <header class="site-header">
    <div class="container">
      <a class="logo" href="index.php">Aripi românești</a>
      <span class="eticheta-etapa">Etapa 2 · mediul de lucru</span>
    </div>
  </header>
