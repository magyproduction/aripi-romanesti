// Sub 768 px meniul se strânge într-un buton „Meniu”. Fără JavaScript, meniul rămâne afișat întreg.
document.addEventListener('DOMContentLoaded', () => {
  const buton = document.querySelector('.buton-meniu');
  const meniu = document.getElementById('meniu');
  if (!buton || !meniu) return;
  buton.addEventListener('click', () => {
    const deschis = buton.getAttribute('aria-expanded') === 'true';
    buton.setAttribute('aria-expanded', String(!deschis));
    meniu.classList.toggle('deschis', !deschis);
  });
});
