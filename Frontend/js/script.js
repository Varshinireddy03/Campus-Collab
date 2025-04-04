
document.getElementById('signupForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const response = await fetch('http://localhost:82/mysite/campus_collab/api/auth/register.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      name: document.getElementById('signupName').value,
      email: document.getElementById('signupEmail').value,
      password: document.getElementById('signupPassword').value
    })
  });
  
  const result = await response.json();
  alert(result.message); // Shows "Registration successful!"
});

// Handle Login
document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const response = await fetch('http://localhost:82/campus_collab/api/auth/login.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      email: document.getElementById('loginEmail').value,
      password: document.getElementById('loginPassword').value
    })
  });
  
  const result = await response.json();
  if (result.success) {
    window.location.href = 'Frontend/hompage.html'; // Redirect after login
  } else {
    alert("Login failed: " + result.message);
  }
});