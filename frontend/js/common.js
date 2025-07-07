document.addEventListener('DOMContentLoaded', function () {
    // Hamburger menu logic
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navbar = document.querySelector('.navbar');
    hamburgerBtn.addEventListener('click', function() {
      const expanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !expanded);
      navbar.classList.toggle('open');
    });
    document.querySelectorAll('.nav-links a').forEach(link => {
      link.addEventListener('click', () => {
        navbar.classList.remove('open');
        hamburgerBtn.setAttribute('aria-expanded', 'false');
      });
    });

    // Highlight active nav link
    document.querySelectorAll('.nav-links a').forEach(link => {
      if (window.location.pathname.endsWith(link.getAttribute('href')) ||
          (link.getAttribute('href') === 'index.html' && (window.location.pathname.endsWith('/') || window.location.pathname.endsWith('index.html')))) {
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');
      }
    });

    // Help Modal Logic
    const helpBtn = document.getElementById('helpBtn');
    const helpModal = document.getElementById('helpModal');
    const closeHelp = document.getElementById('closeHelp');
    if (helpBtn && helpModal && closeHelp) {
        helpBtn.onclick = () => helpModal.style.display = 'block';
        closeHelp.onclick = () => helpModal.style.display = 'none';
        window.onclick = function(event) {
          if (event.target === helpModal) helpModal.style.display = 'none';
        };
        document.querySelector('.help-form').onsubmit = function(e) {
          e.preventDefault();
          this.style.display = 'none';
          document.querySelector('.help-success').style.display = 'block';
          showToast('Your message was sent!');
        };
    }

    // Simple background slideshow
    const slides = document.querySelectorAll('.background-slideshow img');
    if (slides.length > 0) {
        let current = 0;
        setInterval(() => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, 4000);
    }

    // Fade-in on scroll
    function revealOnScroll() {
      document.querySelectorAll('.fade-in').forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight - 60) {
          el.classList.add('visible');
        }
      });
    }
    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('DOMContentLoaded', revealOnScroll);

    // Button Ripple Effect
    document.querySelectorAll('.btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const circle = document.createElement('span');
        circle.className = 'ripple';
        const rect = btn.getBoundingClientRect();
        circle.style.left = (e.clientX - rect.left) + 'px';
        circle.style.top = (e.clientY - rect.top) + 'px';
        btn.appendChild(circle);
        setTimeout(() => circle.remove(), 600);
      });
    });
});

// Toast Notification Function
function showToast(message) {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.textContent = message;
  container.appendChild(toast);
  toast.onclick = () => toast.remove();
  setTimeout(() => toast.remove(), 3000);
}
