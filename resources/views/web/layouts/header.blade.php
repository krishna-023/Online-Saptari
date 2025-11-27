<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Online Saptari</title>
<link rel="shortcut icon" href="{{ asset('web/images/Online_Saptari_Logo.jpeg') }}">
<link rel="stylesheet" href="{{ asset('web/css/stylesheet.css') }}">
<link rel="stylesheet" href="{{ asset('web/css/mmenu.css') }}">
<link rel="stylesheet" href="{{ asset('web/css/style.css') }}" id="colors">

<!-- Magnific Popup CSS (used for the inline modal) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800&display=swap&subset=latin-ext,vietnamese" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800" rel="stylesheet">
<style>
/* small style to avoid flash if JS disabled */
.dropdown-menu {
  border-radius: 12px;
  padding: 0.5rem 0;
  font-size: 14px;
}
.dropdown-menu .dropdown-item {
  padding: 10px 20px;
  transition: background 0.2s;
}
.dropdown-menu .dropdown-item:hover {
  background: #f8f9fa;
}

.tab_content { display: none; }
.utf_tabs_nav li.active > a { font-weight:700; }
.my-mfp-zoom-in { /* Magnific popup zoom animation */
  -webkit-animation: zoomIn .3s;
  animation: zoomIn .3s;
}
@keyframes zoomIn {
  from { transform: scale(0.9); opacity:0; }
  to { transform: scale(1); opacity:1; }
}
</style>
@yield('css')
</head>
<body class="header-one">

<!-- Preloader -->
<div id="preloader">
  <div class="loader">
    <div class="ring"></div>
    <div class="text">Online <span>Saptari</span></div>
  </div>
</div>
<div id="main_wrapper">
  <header id="header_part" class="fullwidth">
    <div id="header">
      <div class="container">
        <div class="utf_left_side">
          <div id="logo">
            <a href="{{ route('home') }}">
              <img src="{{ asset('web/images/Online_Saptari_Logo.jpeg') }}" alt="Online Saptari">
            </a>
          </div>

          <!-- Mobile Hamburger -->
          <div class="mmenu-trigger">
            <button class="hamburger utfbutton_collapse" type="button">
              <span class="utf_inner_button_box">
                <span class="utf_inner_section"></span>
              </span>
            </button>
          </div>

          <!-- Navigation -->
          <nav id="navigation" class="style_one">
            <ul id="responsive">
              <li><a class="{{ request()->routeIs('home') ? 'current' : '' }}" href="{{ route('home') }}">Home</a></li>

              @auth
                @if(auth()->user()->role !== 'user')
                  <li><a href="{{ route('item.index') }}">Listings</a></li>
                  <li><a href="#">User Panel</a>
                    <ul>
                      <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                      <li><a href="{{ route('user.add') }}">Add Listing</a></li>
                      <li><a href="{{ route('item.profile') }}">My Profile</a></li>
                    </ul>
                  </li>
                @endif
              @endauth

              <li><a href="#">Pages</a></li>
            </ul>
          </nav>
          <div class="clearfix"></div>
        </div>

        <!-- Right Side Buttons -->
         <div class="d-flex gap-2 align-items-center">
        @guest
          <a href="#dialog_signin_part" class="button border sign-in popup-with-zoom-anim">Sign In</a>
          <a href="{{ route('user.add') }}" class="button border with-icon">Add Listing</a>
        @else
  <div class="dropdown">
    <button class="btn btn-light d-flex align-items-center gap-2 dropdown-toggle border-0 bg-transparent"
            type="button" id="userDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
      <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('web/images/default-profile.png') }}"
           alt="{{ Auth::user()->name }}" class="rounded-circle" width="40" height="40">
      <span>{{ Auth::user()->name }}</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
      <li>
        <a class="dropdown-item" href="{{ route('user.profile') }}">
          <i class="fa fa-user me-2"></i> My Profile
        </a>
      </li>
      <li>
        <a class="dropdown-item" href="{{ route('account.settings') }}">
          <i class="fa fa-cog me-2"></i> Settings
        </a>
      </li>
      <li><hr class="dropdown-divider"></li>
      <li>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="dropdown-item">
            <i class="fa fa-sign-out-alt me-2"></i> Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
@endguest
          </div>
        </div>

        <!-- Login / Register Popup (inline content for Magnific Popup) -->
        <div id="dialog_signin_part" class="zoom-anim-dialog mfp-hide">
          <div class="small_dialog_header"><h3>Sign In</h3></div>

          <div class="utf_signin_form style_one">
            <ul class="utf_tabs_nav d-flex gap-2">
              <li class="active"><a href="#tab1">Log In</a></li>
              <li><a href="#tab2">Register</a></li>
            </ul>

            <div class="tab_container alt">
              <!-- Login Form -->
              <div class="tab_content" id="tab1">
                <form method="POST" action="{{ route('login.submit') }}" class="login">
                  @csrf
                  <p class="utf_row_form utf_form_wide_block">
                    <input type="email" class="input-text" name="email" placeholder="Email" required autofocus value="{{ old('email') }}">
                  </p>
                  <p class="utf_row_form utf_form_wide_block">
                    <input type="password" class="input-text" name="password" placeholder="Password" required>
                  </p>

                  <div class="utf_row_form utf_form_wide_block form_forgot_part">
                    <span class="lost_password fl_left"><a href="{{ route('password.request') }}">Forgot Password?</a></span>
                    <div class="checkboxes fl_right">
                      <input id="remember-me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                      <label for="remember-me">Remember Me</label>
                    </div>
                  </div>

                  <div class="utf_row_form">
                    <button type="submit" class="button border margin-top-5">Login</button>
                  </div>
                </form>
              </div>

              <!-- Register Form -->
              <div class="tab_content" id="tab2">
                <form method="POST" action="{{ route('register') }}" class="register">
                  @csrf
                  <p class="utf_row_form utf_form_wide_block">
                    <input type="text" name="name" placeholder="Full Name" required value="{{ old('name') }}">
                  </p>
                  <p class="utf_row_form utf_form_wide_block">
                    <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                  </p>
                  <p class="utf_row_form utf_form_wide_block">
                    <input type="password" name="password" placeholder="Password" required>
                  </p>
                  <p class="utf_row_form utf_form_wide_block">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                  </p>
                  <button type="submit" class="button border fw margin-top-10">Register</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /popup content -->

      </div>
    </div>
  </header>

  <!-- rest of page... -->

  <!-- jQuery (required for Magnific Popup and other plugins) -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>

  <!-- Magnific Popup JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  jQuery(document).ready(function($) {
    // initialize popup
    $('.popup-with-zoom-anim').magnificPopup({
      type: 'inline',
      preloader: false,
      focus: '#email',
      removalDelay: 300,
      mainClass: 'my-mfp-zoom-in',
      callbacks: {
        open: function() {
          // when opening ensure first tab is visible properly
          $('.tab_content').hide();
          $('#tab1').show();
          $('.utf_tabs_nav li').removeClass('active');
          $('.utf_tabs_nav li:first').addClass('active');
        },
        beforeOpen: function() {
          if($(window).width() < 700) {
            this.st.focus = false;
          } else {
            this.st.focus = this.st.focus || '#email';
          }
        }
      }
    });

    // Tabs switching
    $('.utf_tabs_nav a').on('click', function(e){
      e.preventDefault();
      var target = $(this).attr('href');
      $(this).closest('.utf_tabs_nav').find('li').removeClass('active');
      $(this).parent().addClass('active');
      $(this).closest('.tab_container').find('.tab_content').hide();
      $(target).show();
    });

    // Show first tab content by default (if popup opened manually)
    $('.tab_container').each(function(){
      $(this).find('.tab_content').hide();
      $(this).find('.tab_content').first().show();
    });

    // Auto-open modal if there are validation errors (login/register)
    @if ($errors->any())
      // open popup and show relevant tab based on which fields have errors
      $.magnificPopup.open({
        items: { src: '#dialog_signin_part' },
        type: 'inline',
        removalDelay: 300,
        mainClass: 'my-mfp-zoom-in',
        callbacks: {
          open: function() {
            // decide which tab to show: if registration fields present in old input show tab2
            @if(old('name') || session()->has('open_register') )
              $('#tab1, #tab2').hide();
              $('#tab2').show();
              $('.utf_tabs_nav li').removeClass('active');
              $('.utf_tabs_nav li:has(a[href="#tab2"])').addClass('active');
            @else
              $('#tab1, #tab2').hide();
              $('#tab1').show();
              $('.utf_tabs_nav li').removeClass('active');
              $('.utf_tabs_nav li:has(a[href="#tab1"])').addClass('active');
            @endif
          }
        }
      }, 0);
    @endif
  });
  </script>
  <script>
// Optional JS: auto-hide after 1.5s
document.addEventListener("DOMContentLoaded", function() {
  setTimeout(() => {
    document.getElementById("preloader").style.display = "none";
  }, 1000);
});
</script>

</div> <!-- #main_wrapper -->
</body>
</html>
