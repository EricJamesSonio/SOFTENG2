function checkout() {
  if (!cart.length) {
    return alert("Cart is empty.");
  }

  const isGuest = localStorage.getItem("isGuest") === "true";
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

  if (isGuest) {
    alert("🔐 To complete your checkout, please log in or sign up.");
    window.location.href = "login2.html";
    return;
  }

  if (isLoggedIn) {
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
        } else {
          // ✅ PREPARE checkout payload
          const payload = {
            items: cart.map(i => ({
              item_id: i.id,
              size_id: i.sizeId,
              quantity: i.quantity,
              unit_price: i.unitPrice
            })),
            discount: 0
          };

          // ✅ SEND checkout request (SAVE TO DB FIRST!)
          return fetch('http://localhost/SOFTENG2/backend/api/index2.php/checkout', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(payload)
          });
        }
      })
      .then(res => {
        if (!res.ok) throw new Error("Checkout failed.");
        return res.json();
      })
      .then(data => {
        if (data.message === "Checkout successful!") {
          // ✅ Save orderId for receipt
          window.orderId = data.orderId;

          const total = cart.reduce((sum, i) => sum + i.unitPrice * i.quantity, 0);
          showPaymentModal(total);
        } else {
          alert(data?.message || "Something went wrong.");
        }
      })
      .catch(err => console.warn("Checkout error:", err));
  } else {
    alert("Please log in or continue as guest.");
    window.location.href = 'login2.html';
  }
}

function processPayment() {
  const amount = parseFloat(document.getElementById('cashInput').value);
  const type = document.getElementById('paymentType').value;
  const final = window.paymentData.final;

  if (isNaN(amount) || amount < final) {
    alert("❌ Not enough money!");
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

        // ✅ Save orderId
        const orderId = data.orderId;

        // ✅ Clone cart data BEFORE clearing it
        const receiptItems = cart.map(item => ({
          name: item.name,
          quantity: item.quantity,
          price: item.unitPrice,
          total: (item.quantity * item.unitPrice).toFixed(2)
        }));

        // ✅ Clear cart and modal
        cart.length = 0;
        renderCart();
        closePaymentModal();

        // ✅ Show only the HTML receipt (no PDF)
        showReceipt({
          order_id: orderId,
          discount_type: type,
          discount: window.paymentData.discount,
          discount_amount: (window.paymentData.total - final).toFixed(2),
          total: window.paymentData.total.toFixed(2),
          final: final.toFixed(2),
          paid: amount.toFixed(2),
          change: change.toFixed(2),
          date: new Date().toLocaleString(),
          items: receiptItems
        });

        // ❌ Remove PDF-fetching if not needed
        // fetch(`http://localhost/SOFTENG2/backend/api/index2.php/receipt?orderId=${orderId}`)
        //   .then(res => res.json())
        //   .then(pdf => {
        //     if (pdf.url) {
        //       window.open(pdf.url, '_blank');
        //     } else {
        //       alert("⚠️ Receipt PDF not found.");
        //     }
        //   });

      } else {
        alert("⚠️ Payment failed.");
      }
    })
    .catch(err => {
      alert("❌ Payment failed: " + err);
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

  window.paymentData = { total, discount, final };
}

function closePaymentModal() {
  document.getElementById('paymentModal').style.display = 'none';
}
// ✅ Fetch full receipt from backend (with items) and show it
fetch(`http://localhost/SOFTENG2/backend/api/index2.php/receipt?orderId=${data.orderId}`, {
  credentials: 'include'
})
  .then(res => res.json())
  .then(receipt => {
    if (receipt.items) {
      showReceipt(receipt); // Now it uses real DB items 🎯

      // ✅ Open backend-generated PDF (optional)
      if (receipt.url) {
        window.open(receipt.url, '_blank');
      }
    } else {
      alert("⚠️ Could not retrieve full receipt.");
    }
  });



window.checkout = checkout;
window.closePaymentModal = closePaymentModal;
window.processPayment = processPayment;
