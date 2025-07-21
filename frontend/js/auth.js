// Expose to global scope so HTML buttons can use it
window.showForm = function(type) {
  document.getElementById('start-screen')?.classList.add('hidden');
  document.getElementById('form-login')?.classList.add('hidden');
  document.getElementById('form-signup')?.classList.add('hidden');
  document.getElementById('form-' + type)?.classList.remove('hidden');
  document.getElementById('errorMsg').textContent = '';
};

window.goBack = function() {
  document.getElementById('form-login')?.classList.add('hidden');
  document.getElementById('form-signup')?.classList.add('hidden');
  document.getElementById('start-screen')?.classList.remove('hidden');
  document.getElementById('errorMsg').textContent = '';
};


async function login() {
  const email = document.getElementById('loginEmail').value.trim();
  const password = document.getElementById('loginPass').value.trim();

  try {
    const res = await fetch(`${BASE_URL}/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });

    const text = await res.text();
    const result = text ? JSON.parse(text) : {};

    if (res.ok && result.success) {
      localStorage.setItem('isLoggedIn', 'true');
      localStorage.removeItem('isGuest');
      window.location.href = 'menu2.html';
    } else {
      document.getElementById('errorMsg').textContent = result.message || 'Login failed';
    }
  } catch (err) {
    console.error(err);
    document.getElementById('errorMsg').textContent = 'Network or server error';
  }
}

async function signup() {
  const first_name = document.getElementById('firstName').value.trim();
  const middle_name = document.getElementById('middleName').value.trim();
  const last_name = document.getElementById('lastName').value.trim();
  const email = document.getElementById('signupEmail').value.trim();
  const password = document.getElementById('signupPass').value;
  const street = document.getElementById('street').value.trim();
  const city = document.getElementById('city').value.trim();
  const province = document.getElementById('province').value.trim();
  const postal_code = document.getElementById('postalCode').value.trim();
  const country = document.getElementById('country').value.trim();
  const phone = document.getElementById('signupPhone').value.trim();

  try {
    const res = await fetch(`${BASE_URL}/signup`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        first_name,
        middle_name,
        last_name,
        email,
        phone,
        password,
        street,
        city,
        province,
        postal_code,
        country
      })
    });

    const text = await res.text();
    const result = text ? JSON.parse(text) : {};

    if (res.ok && result.success) {
      showForm('login');
      document.getElementById('errorMsg').style.color = 'green';
      document.getElementById('errorMsg').textContent = 'Sign up successful! Please log in.';
    } else {
      document.getElementById('errorMsg').style.color = 'red';
      document.getElementById('errorMsg').textContent = result.message || 'Sign up failed';
    }
  } catch (err) {
    console.error(err);
    document.getElementById('errorMsg').textContent = 'Network or server error';
  }
}

window.continueWithoutAccount = function () {
  // Set guest flag
  localStorage.setItem('isGuest', 'true');
  localStorage.removeItem('isLoggedIn');

  // ✅ Wait a bit longer to ensure everything writes before navigation
  setTimeout(() => {
    console.log("✅ Guest flag set, navigating to menu2.html");
    window.location.href = 'menu2.html';
  }, 500); // Increased to 500ms for stability
};

// Save login state
function saveLoginStatus(user) {
    localStorage.setItem("loggedInUser", JSON.stringify(user));
}

function logout() {
    localStorage.removeItem("loggedInUser");
}

function isLoggedIn() {
    return localStorage.getItem("loggedInUser") !== null;
}
