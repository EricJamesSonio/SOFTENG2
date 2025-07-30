// api.js
import { openModal } from './modal.js';
let currentCategory = null;

export function loadCategory(categoryName) {
  currentCategory = categoryName;
  document.getElementById('categorySelect').style.display = 'none';
  document.getElementById('backButton').style.display = 'block';

  fetch('http://localhost/SOFTENG2/backend/api/index2.php/items', {
    credentials: 'include'
  })
    .then(res => res.json())
    .then(data => {
      const filtered = data.filter(item =>
        (categoryName === 'Drink' && item.category_id == 1) ||
        (categoryName === 'Sandwich' && item.category_id == 2)
      );
      displayItems(filtered);
    })
    .catch(err => console.error('Could not load items:', err));
}

function displayItems(items) {
  const itemList = document.getElementById('itemList');
  itemList.innerHTML = '';

  items.forEach(item => {
    const card = document.createElement('div');
    card.className = 'item-card';
    card.onclick = () => openModal(item);

    card.innerHTML = `
      <img src="images/${item.image_url || 'default.jpg'}" class="item-img" alt="${item.name}">
      <div class="item-name">${item.name}</div>
      <div class="item-description">${item.description}</div>
      <div class="item-price">₱${parseFloat(item.price).toFixed(2)}</div>
    `;
    itemList.appendChild(card);
  });
}

export function showCategories() {
  document.getElementById('categorySelect').style.display = 'block';
  document.getElementById('itemList').innerHTML = '';
  document.getElementById('backButton').style.display = 'none';
}
