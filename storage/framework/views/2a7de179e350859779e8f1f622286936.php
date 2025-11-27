
  <!-- Footer -->
<footer class="bg-dark text-light mt-5 pt-5 pb-3">
  <div class="container">
    <div class="row g-4">
      <!-- About Section -->
      <div class="col-lg-4 col-md-6">
        <h5 class="fw-bold mb-3">
          <i class="fas fa-store me-2"></i>About Online Saptari
        </h5>
        <p class="small text-light-emphasis">
          Your premier destination for discovering the best businesses, attractions, restaurants, and hotels in Saptari.
          Connect with local entrepreneurs and explore what makes our community unique.
        </p>
        <div class="d-flex gap-2 mt-3">
          <a href="#" class="text-light text-decoration-none" title="Facebook">
            <i class="fab fa-facebook fa-lg"></i>
          </a>
          <a href="#" class="text-light text-decoration-none" title="Twitter">
            <i class="fab fa-twitter fa-lg"></i>
          </a>
          <a href="#" class="text-light text-decoration-none" title="Instagram">
            <i class="fab fa-instagram fa-lg"></i>
          </a>
          <a href="#" class="text-light text-decoration-none" title="LinkedIn">
            <i class="fab fa-linkedin fa-lg"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-lg-2 col-md-6">
        <h5 class="fw-bold mb-3">
          <i class="fas fa-link me-2"></i>Quick Links
        </h5>
        <ul class="list-unstyled small">
          <li class="mb-2">
            <a href="<?php echo e(route('home')); ?>" class="text-light text-decoration-none">
              <i class="fas fa-chevron-right me-2"></i>Home
            </a>
          </li>
          <li class="mb-2">
            <a href="<?php echo e(route('item.index')); ?>" class="text-light text-decoration-none">
              <i class="fas fa-chevron-right me-2"></i>Listings
            </a>
          </li>
          <li class="mb-2">
            <a href="#" class="text-light text-decoration-none">
              <i class="fas fa-chevron-right me-2"></i>Categories
            </a>
          </li>
          <li class="mb-2">
            <a href="#" class="text-light text-decoration-none">
              <i class="fas fa-chevron-right me-2"></i>Blog
            </a>
          </li>
        </ul>
      </div>

      <!-- My Account -->
      <div class="col-lg-2 col-md-6">
        <h5 class="fw-bold mb-3">
          <i class="fas fa-user me-2"></i>My Account
        </h5>
        <ul class="list-unstyled small">
          <?php if(auth()->guard()->check()): ?>
            <li class="mb-2">
              <a href="<?php echo e(route('dashboard')); ?>" class="text-light text-decoration-none">
                <i class="fas fa-chevron-right me-2"></i>Dashboard
              </a>
            </li>
            <li class="mb-2">
              <a href="<?php echo e(route('item.profile')); ?>" class="text-light text-decoration-none">
                <i class="fas fa-chevron-right me-2"></i>My Profile
              </a>
            </li>
            <li class="mb-2">
              <a href="<?php echo e(route('item.add')); ?>" class="text-light text-decoration-none">
                <i class="fas fa-chevron-right me-2"></i>Add Listing
              </a>
            </li>
            <li class="mb-2">
              <form action="<?php echo e(route('logout')); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-light bg-transparent border-0 p-0 text-decoration-none" style="cursor:pointer;">
                  <i class="fas fa-chevron-right me-2"></i>Logout
                </button>
              </form>
            </li>
          <?php else: ?>
            <li class="mb-2">
              <a href="<?php echo e(route('login')); ?>" class="text-light text-decoration-none">
                <i class="fas fa-chevron-right me-2"></i>Sign In
              </a>
            </li>
            <li class="mb-2">
              <a href="<?php echo e(route('register')); ?>" class="text-light text-decoration-none">
                <i class="fas fa-chevron-right me-2"></i>Register
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>

      <!-- Support Section -->
      <div class="col-lg-4 col-md-6">
        <h5 class="fw-bold mb-3">
          <i class="fas fa-headset me-2"></i>Support & Legal
        </h5>
        <ul class="list-unstyled small">
          <li class="mb-2">
            <a href="#" class="text-light text-decoration-none">
              <i class="fas fa-chevron-right me-2"></i>Contact Us
            </a>
          </li>
          <li class="mb-2">
            <a href="#" class="text-light text-decoration-none">
              <i class="fas fa-chevron-right me-2"></i>Privacy Policy
            </a>
          </li>
          <li class="mb-2">
            <a href="#" class="text-light text-decoration-none">
              <i class="fas fa-chevron-right me-2"></i>Terms of Service
            </a>
          </li>
          <li class="mb-2">
            <a href="#" class="text-light text-decoration-none">
              <i class="fas fa-chevron-right me-2"></i>FAQ
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Footer Bottom -->
    <hr class="border-light-subtle my-4">
    <div class="row align-items-center">
      <div class="col-md-6 text-center text-md-start small text-light-emphasis">
        <p class="mb-0">
          &copy; 2025 <strong>Online Saptari</strong>. All Rights Reserved.
        </p>
      </div>
      <div class="col-md-6 text-center text-md-end small">
        <p class="mb-0">
          Made with <i class="fas fa-heart text-danger"></i> for the Saptari Community
        </p>
      </div>
    </div>
  </div>
</footer>

<!-- Back to Top Button -->
<div id="back-to-top" class="position-fixed bottom-0 end-0 m-3 d-none">
  <button class="btn btn-primary rounded-circle p-3 shadow" id="back-to-top-btn" style="width:50px; height:50px;">
    <i class="fas fa-arrow-up"></i>
  </button>
</div>

<script>
// Back to Top button functionality
const backToTopBtn = document.getElementById('back-to-top-btn');
const backToTopDiv = document.getElementById('back-to-top');

window.addEventListener('scroll', () => {
  if (window.pageYOffset > 300) {
    backToTopDiv.classList.remove('d-none');
  } else {
    backToTopDiv.classList.add('d-none');
  }
});

if (backToTopBtn) {
  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}

// Initialize Typed.js only if library is loaded
if (typeof Typed !== 'undefined') {
    var typed = new Typed('.typed-words', {
        strings: ["Attractions", " Restaurants", " Hotels"],
        typeSpeed: 80,
        backSpeed: 80,
        backDelay: 4000,
        startDelay: 1000,
        loop: true,
        showCursor: true
    });
}
</script>


<script>
// Initialize Typed.js only if library is loaded
if (typeof Typed !== 'undefined') {
    var typed = new Typed('.typed-words', {
        strings: ["Attractions", " Restaurants", " Hotels"],
        typeSpeed: 80,
        backSpeed: 80,
        backDelay: 4000,
        startDelay: 1000,
        loop: true,
        showCursor: true
    });
}
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Online_Saptari\resources\views/web/layouts/footer.blade.php ENDPATH**/ ?>