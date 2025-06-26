let currentCategory = '';
let currentItem = null;
const cart = [];

function loadCategory(categoryName) {
  currentCategory = categoryName;
  document.getElementById('categorySelect').style.display = 'none';
  document.getElementById('backButton').style.display = 'block';

  fetch('http://localhost/SOFTENG/backend/api/index.php/items')
    .then(res => res.json())
    .then(data => {
      const filtered = data.filter(item => {
  return (categoryName === 'Drink' && item.category_id == 1) || 
         (categoryName === 'Sandwich' && item.category_id == 2);
});

      displayItems(filtered);
    });
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

function showCategories() {
  document.getElementById('categorySelect').style.display = 'block';
  document.getElementById('itemList').innerHTML = '';
  document.getElementById('backButton').style.display = 'none';
}

function openModal(item) {
  currentItem = item;
  document.getElementById('modalItemName').textContent = item.name;
  document.getElementById('modalQuantity').value = 1;
  document.getElementById('itemModal').style.display = 'flex';
}

function closeModal() {
  document.getElementById('itemModal').style.display = 'none';
}

function addToCart() {
  const quantity = parseInt(document.getElementById('modalQuantity').value);
  if (quantity <= 0) return;

  const existing = cart.find(i => i.id === currentItem.id);
  if (existing) {
    existing.quantity += quantity;
  } else {
    cart.push({ ...currentItem, quantity });
  }

  closeModal();
  renderCart();
}

function renderCart() {
  const cartItems = document.getElementById('cartItems');
  cartItems.innerHTML = '';

  let total = 0;

  cart.forEach(item => {
    const subtotal = item.quantity * item.price;
    total += subtotal;

    const div = document.createElement('div');
    div.innerHTML = `
      ${item.name} x ${item.quantity} = ₱${subtotal.toFixed(2)}
    `;
    cartItems.appendChild(div);
  });

  document.getElementById('cartTotal').textContent = total.toFixed(2);
  document.getElementById('cartDiscount').textContent = '0.00'; // future discount
}

function checkout() {
  alert("Checkout logic will be implemented soon!");
}
