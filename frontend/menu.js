// ─── Step 2: Load size options from backend ──────────────────────────
let sizes = [];

function loadSizes() {
  fetch('http://localhost/SOFTENG/backend/api/index.php/sizes')
    .then(res => res.json())
    .then(data => {
      sizes = data;  
      console.log('Loaded sizes:', sizes);
    })
    .catch(err => console.error('Could not load sizes:', err));
}

// Call it immediately so sizes[] is populated before user opens modal
loadSizes();

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

  // 1️⃣ Set the item name
  document.getElementById('modalItemName').textContent = item.name;
  document.getElementById('modalQuantity').value = 1;

  // 2️⃣ Populate the size dropdown from our `sizes[]` array
  const sizeSelect = document.getElementById('modalSize');
  sizeSelect.innerHTML = '';                    // clear “Loading…”
  sizes.forEach(sz => {
    const opt = document.createElement('option');
    opt.value = sz.id;
    // show name + surcharge
    opt.textContent = `${sz.name} (+₱${parseFloat(sz.price_modifier).toFixed(2)})`;
    opt.dataset.modifier = sz.price_modifier;
    sizeSelect.appendChild(opt);
  });

  // 3️⃣ Show the modal
  document.getElementById('itemModal').style.display = 'flex';
}


function closeModal() {
  document.getElementById('itemModal').style.display = 'none';
}

function addToCart() {
  const qty = parseInt(document.getElementById('modalQuantity').value, 10);
  if (qty < 1) return;

  const sizeSelect = document.getElementById('modalSize');
  const sizeId   = sizeSelect.value;
  const sizeText = sizeSelect.options[sizeSelect.selectedIndex].text;
  const mod      = parseFloat(sizeSelect.options[sizeSelect.selectedIndex].dataset.modifier);

  // Calculate final unit price
  const basePrice    = parseFloat(currentItem.price);
  const unitPrice    = basePrice + mod;

  // Check if same item+size already in cart
  const existing = cart.find(i => i.id === currentItem.id && i.sizeId === sizeId);
  if (existing) {
    existing.quantity += qty;
  } else {
    cart.push({
      ...currentItem,
      sizeId,
      sizeText,
      quantity: qty,
      unitPrice
    });
  }

  closeModal();
  renderCart();
}


function renderCart() {
  const cartItems = document.getElementById('cartItems');
  cartItems.innerHTML = '';

  let total = 0;

  cart.forEach(item => {
    // calculate line total using unitPrice (base + modifier)
    const lineTotal = item.quantity * item.unitPrice;
    total += lineTotal;

    // show sizeText and unitPrice in the display
    const div = document.createElement('div');
    div.textContent = 
      `${item.name} (${item.sizeText}) x ${item.quantity} = ₱${lineTotal.toFixed(2)}`;

    cartItems.appendChild(div);
  });

  // update totals in the sidebar
  document.getElementById('cartTotal').textContent    = total.toFixed(2);
  document.getElementById('cartDiscount').textContent = '0.00'; // future discount
}


function checkout() {
  if (!cart.length) {
    return alert("Cart is empty.");
  }

  const payload = {
    items: cart.map(i => ({
      item_id:    i.id,
      size_id:    i.sizeId,
      quantity:   i.quantity,
      unit_price: i.unitPrice
    })),
    discount: 0
  };

  fetch('http://localhost/SOFTENG/backend/api/index.php/checkout', {
    method: 'POST',
    headers: { 'Content-Type':'application/json' },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.message === "Checkout successful!") {
      // calculate total using the same surcharged prices
      const total = payload.items
        .reduce((sum, it) => sum + it.unit_price * it.quantity, 0);
      showPaymentModal(total);
    } else {
      alert(data.message || "Something went wrong.");
    }
  })
  .catch(err => {
    alert("Checkout failed: " + err);
  });
}


function showPaymentModal(total) {
  const discount = total * 0.10;
  const final = total - discount;

  document.getElementById('paymentTotal').textContent = total.toFixed(2);
  document.getElementById('paymentDiscount').textContent = discount.toFixed(2);
  document.getElementById('finalAmount').textContent = final.toFixed(2);

  document.getElementById('cashInput').value = '';
  document.getElementById('paymentModal').style.display = 'flex';

  // Save values for later use
  window.paymentData = { total, discount, final };
}

function closePaymentModal() {
  document.getElementById('paymentModal').style.display = 'none';
}
function processPayment() {
  const amount = parseFloat(document.getElementById('cashInput').value);
  const type = document.getElementById('paymentType').value;
  const final = window.paymentData.final;

  if (isNaN(amount) || amount < final) {
    alert("Not enough money!");
    return;
  }

  const change = amount - final;

  fetch('http://localhost/SOFTENG/backend/api/index.php/payment', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      type: type,
      amountPaid: amount,
      total: window.paymentData.total,
      discount: window.paymentData.discount,
      finalAmount: final
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.message === "Payment successful!") {
      alert("✅ Payment successful! Change: ₱" + change.toFixed(2));

      // ✅ Clear cart
      cart.length = 0;
      renderCart();
      closePaymentModal();

      // ✅ Show popup receipt
      showReceipt({
        order_id: data.orderId,
        discount_type: type,
        discount: window.paymentData.discount,
        discount_amount: (window.paymentData.total - final).toFixed(2),
        total: window.paymentData.total.toFixed(2),
        final: final.toFixed(2),
        paid: amount.toFixed(2),
        change: change.toFixed(2),
        date: new Date().toLocaleString()
      });

      // ✅ Open the generated PDF from backend
      fetch(`http://localhost/SOFTENG/backend/api/index.php/receipt?orderId=${data.orderId}`)
        .then(res => res.json())
        .then(pdf => {
          if (pdf.url) {
            window.open(pdf.url, '_blank'); // open in new tab
          } else {
            alert("⚠️ Receipt PDF not found.");
          }
        });

    } else {
      alert("⚠️ Payment failed.");
    }
  })
  .catch(err => {
    alert("❌ Payment failed: " + err);
  });
}

function showReceipt(receiptData) {
  const win = window.open('', '', 'width=600,height=800');
  win.document.write(`
    <html><head><title>Receipt</title></head><body>
    <h2>Starbucks Receipt</h2>
    <p><strong>Order ID:</strong> ${receiptData.order_id}</p>
    <p><strong>Discount Type:</strong> ${receiptData.discount_type}</p>
    <p><strong>Total:</strong> ₱${receiptData.total}</p>
    <p><strong>Final Amount:</strong> ₱${receiptData.final}</p>
    <p><strong>Amount Paid:</strong> ₱${receiptData.paid}</p>
    <p><strong>Change:</strong> ₱${receiptData.change}</p>
    <p><strong>Date:</strong> ${receiptData.date}</p>
    <button onclick="window.print()">Print</button>
    </body></html>
  `);
  win.document.close();
}

