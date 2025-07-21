let sizes = [];

function loadSizes() {
  fetch('http://localhost/SOFTENG2/backend/api/index2.php/sizes', {
    credentials: 'include'
  })
    .then(res => res.json())
    .then(data => {
      sizes = data;
      console.log('Loaded sizes:', sizes);
    })
    .catch(err => console.error('Could not load sizes:', err));
}

function checkLoginOnLoad() {
  if (localStorage.getItem("isGuest")) return; // ✅ Skip check if guest

  fetch('http://localhost/SOFTENG2/backend/api/index2.php/check_login', {
    credentials: 'include'
  })
    .then(res => {
      if (res.status === 401) {
        window.location.href = 'login2.html';
        throw new Error("Not logged in");
      }
      return res.json();
    })
    .then(data => {
      if (!data.status) {
        window.location.href = 'login2.html';
      }
    })
    .catch(err => console.warn("Login check failed:", err));
}


loadSizes();
if (!localStorage.getItem("isGuest")) {
  checkLoginOnLoad(); // ✅ Only run check if not guest
}


let currentCategory = '';
let currentItem = null;
const cart = [];

function loadCategory(categoryName) {
  currentCategory = categoryName;
  document.getElementById('categorySelect').style.display = 'none';
  document.getElementById('backButton').style.display = 'block';

  fetch('http://localhost/SOFTENG2/backend/api/index2.php/items', {
    credentials: 'include'
  })
    .then(res => res.json())
    .then(data => {
      const filtered = data.filter(item => {
        return (categoryName === 'Drink' && item.category_id == 1) ||
               (categoryName === 'Sandwich' && item.category_id == 2);
      });

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

function showCategories() {
  document.getElementById('categorySelect').style.display = 'block';
  document.getElementById('itemList').innerHTML = '';
  document.getElementById('backButton').style.display = 'none';
}

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
    const lineTotal = item.quantity * item.unitPrice;
    total += lineTotal;

    const div = document.createElement('div');
    div.textContent =
      `${item.name} (${item.sizeText}) x ${item.quantity} = ₱${lineTotal.toFixed(2)}`;

    cartItems.appendChild(div);
  });

  document.getElementById('cartTotal').textContent = total.toFixed(2);
  document.getElementById('cartDiscount').textContent = '0.00';
}

function checkout() {
  if (!cart.length) {
    return alert("Cart is empty.");
  }

  const isGuest = localStorage.getItem("isGuest") === "true";
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

  if (isGuest) {
    // 👇 Redirect to login2.html for guest upgrade options
    alert("🔐 To complete your checkout, please choose an option:");
    window.location.href = "login2.html";
    return;
  }

  if (isLoggedIn) {
    // 🔐 Check session with backend
    fetch('http://localhost/SOFTENG2/backend/api/index2.php/check_login', {
      credentials: 'include'
    })
      .then(res => {
        if (res.status === 401) {
          alert("Session expired. Please log in again.");
          window.location.href = 'login2.html';
          throw new Error("Unauthorized");
        }
        return res.json();
      })
      .then(data => {
        if (!data.status) {
          alert("Login required.");
          window.location.href = 'login2.html';
          throw new Error("Not logged in");
        }

        // ✅ Logged-in user: proceed
        const total = cart.reduce((sum, i) => sum + i.unitPrice * i.quantity, 0);
        showPaymentModal(total);
      })
      .catch(err => {
        console.warn("Checkout error:", err);
      });
  } else {
    // Not guest or logged in? Redirect to login2.html
    alert("Please log in or continue as guest.");
    window.location.href = 'login2.html';
  }
}




function showPaymentModal(total) {
  const discount = total * 0.10;
  const final = total - discount;

  document.getElementById('paymentTotal').textContent = total.toFixed(2);
  document.getElementById('paymentDiscount').textContent = discount.toFixed(2);
  document.getElementById('finalAmount').textContent = final.toFixed(2);

  document.getElementById('cashInput').value = '';
  document.getElementById('paymentModal').style.display = 'flex';

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

  fetch('http://localhost/SOFTENG2/backend/api/index2.php/payment', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'include',
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

        cart.length = 0;
        renderCart();
        closePaymentModal();

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

        fetch(`http://localhost/SOFTENG2/backend/api/index2.php/receipt?orderId=${data.orderId}`, {
          credentials: 'include'
        })
          .then(res => res.json())
          .then(pdf => {
            if (pdf.url) {
              window.open(pdf.url, '_blank');
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

// ✅ Expose functions globally
window.loadCategory = loadCategory;
window.showCategories = showCategories;
window.openModal = openModal;
window.closeModal = closeModal;
window.addToCart = addToCart;
window.checkout = checkout;
window.closePaymentModal = closePaymentModal;
window.processPayment = processPayment;
