<?php
// Contact: formularul salvează mesajul în tabelul mesaje.
require __DIR__ . '/includes/functii.php';

$campuri = ['nume' => 100, 'email' => 190, 'subiect' => 150, 'mesaj' => 5000];
$date = array_fill_keys(array_keys($campuri), '');
$erori = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($campuri as $camp => $max) {
        $valoare = $_POST[$camp] ?? '';
        $date[$camp] = is_string($valoare) ? trim($valoare) : '';
        if (mb_strlen($date[$camp]) > $max) {
            $erori[$camp] = "Textul poate avea cel mult $max de caractere.";
        }
    }
    if ($date['nume'] === '') {
        $erori['nume'] = 'Completați numele.';
    }
    if (!filter_var($date['email'], FILTER_VALIDATE_EMAIL)) {
        $erori['email'] = 'Completați o adresă de email validă.';
    }
    if ($date['mesaj'] === '') {
        $erori['mesaj'] = 'Completați mesajul.';
    }
    if (!$erori) {
        $st = db()->prepare('INSERT INTO mesaje (nume, email, subiect, mesaj) VALUES (?, ?, ?, ?)');
        $st->execute([$date['nume'], $date['email'], $date['subiect'] !== '' ? $date['subiect'] : null, $date['mesaj']]);
        header('Location: contact.php?trimis=1', true, 303);
        exit;
    }
}

function camp(string $nume, string $eticheta, array $date, array $erori, string $tip = 'text', bool $obligatoriu = true): string
{
    $id = 'c-' . $nume;
    $atribute = ' id="' . $id . '" name="' . $nume . '"' . ($obligatoriu ? ' required' : '')
        . (isset($erori[$nume]) ? ' aria-invalid="true" aria-describedby="' . $id . '-eroare"' : '');
    $control = $tip === 'textarea'
        ? '<textarea' . $atribute . '>' . e($date[$nume]) . '</textarea>'
        : '<input type="' . $tip . '"' . $atribute . ' value="' . e($date[$nume]) . '">';
    return '<div><label for="' . $id . '">' . $eticheta . '</label>' . $control
        . (isset($erori[$nume]) ? '<p class="eroare" id="' . $id . '-eroare">' . e($erori[$nume]) . '</p>' : '') . '</div>';
}

$titlu_pagina = 'Contact · Aripi românești';
$pagina_curenta = 'contact';
require __DIR__ . '/includes/header.php';
?>
  <main id="continut" class="container pagina">
    <h1>Contact</h1>
<?php if (parametru_get('trimis') === '1'): ?>
    <p class="confirmare" role="status">Mesajul a fost trimis. Vă mulțumim!</p>
<?php else: ?>
    <p>Întrebări sau observații despre expoziție? Scrieți-ne aici.</p>
<?php endif; ?>

    <form class="formular" action="contact.php" method="post" novalidate>
      <?= camp('nume', 'Nume', $date, $erori) ?>

      <?= camp('email', 'Email', $date, $erori, 'email') ?>

      <?= camp('subiect', 'Subiect (opțional)', $date, $erori, 'text', false) ?>

      <?= camp('mesaj', 'Mesaj', $date, $erori, 'textarea') ?>

      <div><button class="buton" type="submit">Trimite mesajul</button></div>
    </form>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
