document.addEventListener("DOMContentLoaded", async () => {
  const container = document.getElementById("history-container");

  try {
    const response = await fetch("http://localhost/SOFTENG2/backend/api/routes/history.php");
    const historyData = await response.json();

    if (!historyData.status || !Array.isArray(historyData.history) || historyData.history.length === 0) {
      container.innerHTML = "<p>No order history available.</p>";
      return;
    }

    historyData.history.forEach((receipt) => {
      const div = document.createElement("div");
      div.classList.add("receipt-box");

      // Convert items into badge spans
      const itemBadges = receipt.items
        .split('\n')
        .map(item => `<span class="receipt-item">${item}</span>`)
        .join(' ');

      div.innerHTML = `
        <h3>Receipt ID: ${receipt.id}</h3>
        <p><strong>Date:</strong> ${receipt.date}</p>
        <p><strong>Items:</strong><br>${itemBadges}</p>
        <p><strong>Total:</strong> ₱${receipt.total}</p>
        <hr>
      `;
      container.appendChild(div);
    });

  } catch (error) {
    console.error("Error fetching history:", error);
    container.innerHTML = "<p>Failed to load order history.</p>";
  }
});
