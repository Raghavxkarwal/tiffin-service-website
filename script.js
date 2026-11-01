const toggle = document.getElementById('menuToggle');
const links = document.getElementById('navLinks');
if (toggle && links) toggle.addEventListener('click', () => links.classList.toggle('open'));

const search = document.getElementById('menuSearch');
if (search) {
  const cards = [...document.querySelectorAll('.searchable-meal')];
  const empty = document.getElementById('noMeals');
  search.addEventListener('input', () => {
    const term = search.value.trim().toLowerCase();
    let visible = 0;
    cards.forEach(card => {
      const match = (card.dataset.search || '').includes(term);
      card.classList.toggle('hidden', !match);
      if (match) visible++;
    });
    if (empty) empty.classList.toggle('hidden', visible !== 0);
  });
}
