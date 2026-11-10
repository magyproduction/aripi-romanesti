<?php
// Despre: conceptul expoziției, autorul și sursele generale.
require __DIR__ . '/includes/functii.php';

$titlu_pagina = 'Despre · Aripi românești';
$pagina_curenta = 'despre';
require __DIR__ . '/includes/header.php';
?>
  <main id="continut" class="container pagina descriere">
    <h1>Despre expoziție</h1>

    <section aria-labelledby="t-concept">
      <h2 id="t-concept">Conceptul</h2>
      <p>„Aripi românești — 12 repere ale zborului” este o expoziție virtuală despre aviația și cosmonautica
        românească. Exponatele, de la manuscrisul lui Conrad Haas până la avionul IAR-99 Șoim, sunt grupate în
        patru secțiuni și pot fi parcurse și în ordine cronologică.</p>
    </section>

    <section aria-labelledby="t-autor">
      <h2 id="t-autor">Autorul</h2>
      <p>Marius Gabriel Ion, grupa 951 GID, Facultatea de Inginerie Aerospațială, UNSTPB. Proiect pentru disciplina
        Web Design, anul universitar 2026–2027.</p>
    </section>

    <section aria-labelledby="t-surse">
      <h2 id="t-surse">Sursele</h2>
      <p>Datele fiecărui exponat sunt luate din sursele trecute pe pagina lui: articole Wikipedia și lucrări
        tipărite. Imaginile exponatelor sunt deocamdată ilustrații de test.</p>
    </section>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
