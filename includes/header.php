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
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <a class="logo" href="index.php">Aripi românești</a>
    </div>
  </header>
