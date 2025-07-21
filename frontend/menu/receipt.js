function showReceipt(data) {
  const win = window.open('', '', 'width=600,height=800');

  const itemsHtml = data.items.map(i => `
    <tr>
      <td>${i.name}</td>
      <td>${i.quantity}</td>
      <td>₱${parseFloat(i.price).toFixed(2)}</td>
      <td>₱${(i.quantity * i.price).toFixed(2)}</td>
    </tr>
  `).join('');

  win.document.write(`
    <html>
      <head>
        <title>Receipt</title>
        <style>
          body { font-family: Arial, sans-serif; padding: 20px; }
          h2 { margin-bottom: 10px; }
          table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
          th { background-color: #f2f2f2; }
          p { margin: 5px 0; }
          button { padding: 10px 15px; font-size: 16px; margin-top: 10px; }
        </style>
      </head>
      <body>
        <h2>Starbucks Receipt</h2>
        <p><strong>Order ID:</strong> ${data.order_id}</p>

        <table>
          <thead>
            <tr>
              <th>Item</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            ${itemsHtml}
          </tbody>
        </table>

        <p><strong>Discount Type:</strong> ${data.discount_type}</p>
        <p><strong>Total:</strong> ₱${parseFloat(data.total).toFixed(2)}</p>
        <p><strong>Final Amount:</strong> ₱${parseFloat(data.final).toFixed(2)}</p>
        <p><strong>Amount Paid:</strong> ₱${parseFloat(data.paid).toFixed(2)}</p>
        <p><strong>Change:</strong> ₱${parseFloat(data.change).toFixed(2)}</p>
        <p><strong>Date:</strong> ${data.date}</p>
        
        <button onclick="window.print()">🖨️ Print</button>
      </body>
    </html>
  `);

  win.document.close();
}

// ✅ Expose globally so it can be called from anywhere
window.showReceipt = showReceipt;
