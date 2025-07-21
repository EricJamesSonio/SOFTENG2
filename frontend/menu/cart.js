function renderCart() {
  const cartItems = document.getElementById('cartItems');
  cartItems.innerHTML = '';

  let total = 0;

  cart.forEach(item => {
    const lineTotal = item.quantity * item.unitPrice;
    total += lineTotal;

    const div = document.createElement('div');
    div.textContent = `${item.name} (${item.sizeText}) x ${item.quantity} = ₱${lineTotal.toFixed(2)}`;
    cartItems.appendChild(div);
  });

  document.getElementById('cartTotal').textContent = total.toFixed(2);
  document.getElementById('cartDiscount').textContent = '0.00';
}

function addToCart() {
  const qty = parseInt(document.getElementById('modalQuantity').value, 10);
  if (qty < 1) return;

  const sizeSelect = document.getElementById('modalSize');
  const sizeId = sizeSelect.value;
  const sizeText = sizeSelect.options[sizeSelect.selectedIndex].text;
  const mod = parseFloat(sizeSelect.options[sizeSelect.selectedIndex].dataset.modifier);

  const basePrice = parseFloat(currentItem.price);
  const unitPrice = basePrice + mod;

  const existing = cart.find(i => i.id === currentItem.id && i.sizeId === sizeId);
  if (existing) {
    existing.quantity += qty;
  } else {
    cart.push({ ...currentItem, sizeId, sizeText, quantity: qty, unitPrice });
  }

  closeModal();
  renderCart();
}

window.addToCart = addToCart;
