document.addEventListener("DOMContentLoaded", function() {
  // Toggle sidebar
  const toggleBtn = document.getElementById("toggle-btn");
  const sidebar = document.getElementById("sidebar");
  
  toggleBtn.addEventListener("click", function() {
      sidebar.classList.toggle("expanded");
  });
  
  // Highlight active sidebar link
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  const sidebarLinks = document.querySelectorAll(".sidebar ul li a");
  
  sidebarLinks.forEach(link => {
      const linkPage = link.getAttribute('href');
      if ((currentPage === 'index.html' && linkPage === '#') || 
          (currentPage === linkPage)) {
          link.classList.add("active");
      }
      
      link.addEventListener("click", function(e) {
          if (linkPage === '#') return;
          e.preventDefault();
          window.location.href = linkPage;
      });
  });
  
  // Like button functionality
  document.addEventListener("click", function(e) {
      if (e.target.classList.contains("fa-heart")) {
          const btn = e.target.closest(".action-btn");
          btn.classList.toggle("liked");
          if (btn.classList.contains("liked")) {
              btn.innerHTML = '<i class="fas fa-heart" style="color: #f72585;"></i>';
          } else {
              btn.innerHTML = '<i class="fas fa-heart"></i>';
          }
      }
  });
  
  // Modal functionality (if exists on the page)
  const modal = document.getElementById("projectModal");
  if (modal) {
      const modalOverlay = document.getElementById("modalOverlay");
      const openModalBtn = document.getElementById("openModalBtn");
      const fabBtn = document.getElementById("fabBtn");
      const cancelProjectBtn = document.getElementById("cancelProjectBtn");
      const projectForm = document.getElementById("projectForm");
      const projectsGrid = document.querySelector(".projects-grid");
      
      function openModal() {
          modal.style.display = "block";
          modalOverlay.style.display = "block";
          document.body.style.overflow = "hidden";
      }
      
      function closeModal() {
          modal.style.display = "none";
          modalOverlay.style.display = "none";
          document.body.style.overflow = "auto";
          projectForm.reset();
      }
      
      if (openModalBtn) openModalBtn.addEventListener("click", openModal);
      if (fabBtn) fabBtn.addEventListener("click", openModal);
      if (cancelProjectBtn) cancelProjectBtn.addEventListener("click", closeModal);
      
      if (modalOverlay) modalOverlay.addEventListener("click", closeModal);
      
      modal.addEventListener("click", function(e) {
          e.stopPropagation();
      });
      
      if (projectForm) {
          projectForm.addEventListener("submit", function(e) {
              e.preventDefault();
              
              const name = document.getElementById("projectName").value;
              const description = document.getElementById("projectDescription").value;
              const languages = document.getElementById("Languages").value;
              
              if (!name.trim() || !description.trim()) {
                  alert("Please fill in all required fields");
                  return;
              }
              
              const projectCard = document.createElement("div");
              projectCard.className = "project-card";
              projectCard.style.opacity = "0";
              projectCard.innerHTML = `
                  <h3>${name}</h3>
                  <p>${description}</p>
                  <div class="project-meta">
                      <div class="project-languages">
                          ${languages.split(',').map(lang => 
                              `<span class="language-tag">${lang.trim()}</span>`
                          ).join('')}
                      </div>
                      <div class="project-actions">
                          <button class="action-btn"><i class="fas fa-heart"></i></button>
                          <button class="action-btn"><i class="fas fa-share-alt"></i></button>
                      </div>
                  </div>
              `;
              
              if (projectsGrid) {
                  projectsGrid.prepend(projectCard);
                  setTimeout(() => {
                      projectCard.style.opacity = "1";
                      projectCard.style.animation = "fadeInUp 0.6s forwards";
                  }, 10);
              }
              
              closeModal();
          });
      }
  }
});