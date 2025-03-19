document.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector('.container');
  const registerBtn = document.querySelector('.register-btn');
  const loginBtn = document.querySelector('.login-btn');

  if (registerBtn && loginBtn && container) {
      registerBtn.addEventListener('click', () => {
          container.classList.add('active');
      });

      loginBtn.addEventListener('click', () => {
          container.classList.remove('active');
      });
  } else {
      console.error("One or more elements not found!");
  }
});