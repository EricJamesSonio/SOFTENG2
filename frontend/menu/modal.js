function openModal(item) {
  currentItem = item;
  document.getElementById('modalItemName').textContent = item.name;
  document.getElementById('modalQuantity').value = 1;

  const sizeSelect = document.getElementById('modalSize');
  sizeSelect.innerHTML = '';

  sizes.forEach(sz => {
    const opt = document.createElement('option');
    opt.value = sz.id;
    opt.textContent = `${sz.name} (+₱${parseFloat(sz.price_modifier).toFixed(2)})`;
    opt.dataset.modifier = sz.price_modifier;
    sizeSelect.appendChild(opt);
  });

  document.getElementById('itemModal').style.display = 'flex';
}

function closeModal() {
  document.getElementById('itemModal').style.display = 'none';
}

window.openModal = openModal;
window.closeModal = closeModal;
