<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ENREMCO Management System</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/style.css'])
           
        @endif
    </head>
    <body class="d-flex flex-column min-vh-100">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="header-inner header-1">      
                <div class="main-menu">
                        <header class="container">
                            <div class="flex lg:justify-center lg:col-start-2">
                                <a href="{{ url('/') }}" class="dash-logo"><img src="{{ asset('assets/images/enremco_logo2.png') }}" 
                            alt="Admin Logo" class="img-fluid" style="max-width: 300px; height: auto;"></a>
                            </div>
                            @if (Route::has('login'))
                                <nav class="-mx-3 flex flex-1 justify-end desktop-menu">
                                <a href="{{ url('/') }}" class="active rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Home</a>
                                <a href="{{ route('faqs') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                FAQs</a>
                                <!-- <a href="{{ url('/') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Contact Us</a> -->
                                    @auth
                                            <a
                                                href="{{ Auth::user()->is_admin ? url('/admin/dashboard') : url('/dashboard') }}"
                                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                            >
                                                Dashboard
                                            </a>
                                    @else
                                        <a
                                            href="{{ route('login') }}"
                                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white login-front"
                                        >
                                            Log in
                                        </a>
                                    @endauth
                                </nav>
                            @endif
                        </header>
                    </div>

                    <!-- Burger Menu Icon (Visible on small screens) -->
                    <div class="mobile-menu">
                        <button id="burger-menu" class="text-black dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Mobile Menu (Hidden on desktop) -->
                    <div id="mobile-menu" class="lg:hidden hidden flex-col space-y-4 py-4 px-6 bg-gray-50 dark:bg-black text-black dark:text-white">
                        <a href="{{ url('/') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            Home
                        </a>
                        <a href="{{ route('faqs') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            FAQs
                        </a>
                    </div>
            </div>
        </div>

        <main class="flex-grow-1">
            <div class="main-container content-wrapper pad-none">
                <div class="home-template content-inner">
                    <section id="section-banner" class="section-banner pad-top-70 pad-bottom-60 section-bg-img bg-fixed">
                            <span class="bg-overlay pattern"></span>
                            <div class="container">
                                <!-- Row -->
                                <div class="row">                                
                                    <!-- Col -->
                                    <div class="section-left" data-animation="fadeInRightBig">
                                        <div class="title-wrap margin-bottom-30">
                                            <div class="section-title mb-0">                                            
                                                <h1 class="section-title margin-top-5 mb-0 text-uppercase typo-white">Welcome to ENREMCO</h1>                                            
                                            </div>
                                            <div class="pad-top-15">
                                                <p class="margin-bottom-20 relative typo-white">ENREMCO is here to support DENR X employees with financial services and community programs designed for growth and success.</p>
                                            </div>
                                            <div class="become-member">
                                                @if (Route::has('register'))
                                                    <a
                                                        href="{{ route('register') }}"
                                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                                    >
                                                        Become a Member
                                                    </a>
                                                @endif
                                            </div>
                                        </div>                                  
                                    </div>
                                    <!-- Col -->
                                    <!-- Col -->
                                    <!-- .col -->
                                </div>
                                <!-- Row -->
                            </div>
                            <!-- Container -->
                    </section>
                    <section class="about-us py-10 px-5 bg-white text-gray-800 dark:bg-gray-900 dark:text-white">
                        <div class="max-w-3xl mx-auto text-center">
                            <h2 class="text-3xl font-bold mb-4">About Us</h2>
                            <p class="mb-6">
                            Established by dedicated employees of the Department of Environment and Natural Resources - Region X (DENR X), ENREMCO (Environment and Natural Resources Employees' Multipurpose Cooperative) began as a simple idea: to unite the DENR X community under a shared vision of progress and sustainability. Over the years, we have grown into a supportive network focused on empowering each member to achieve financial security while staying true to our commitment to environmental stewardship.
                            </p>
                            <p class="mb-6">
                            At ENREMCO, we believe in fostering a culture of cooperation and mutual respect. Through continuous innovation and collaborative efforts, we strive to create opportunities that help our members thrive both professionally and personally. Our cooperative spirit drives us to champion responsible practices that benefit our environment, our members, and the broader community.
                            </p>
                        </div>
                    </section>
                    <section class="services-homepage py-10 px-5 bg-white text-gray-800 dark:bg-gray-900 dark:text-white">
                        <h2 class="text-3xl font-bold mb-4">Our Services</h2>
                        <div class="loan-section">
                              <div class="loan-card">
                                    <h4 class="ms-2 mb-0">Regular Loan</h4>
                                    <small>For general personal and financial needs.</small>
                                    <img src="/build/assets/regular.jpg">
                              </div>
                              <div class="loan-card">
                                    <h4 class="ms-2 mb-0">Educational Loan</h4>
                                    <small>To support tuition fees and school-related expenses.</small>
                                    <img src="/build/assets/educational.jpg">
                              </div>
                              <div class="loan-card">
                                    <h4 class="ms-2 mb-0">Appliance Loan</h4>
                                    <small>For purchasing essential home appliances.</small>
                                    <img src="/build/assets/appliance.jpg">
                              </div>
                              <div class="loan-card">
                                    <h4 class="ms-2 mb-0">Grocery Loan</h4>
                                    <small>To help with daily food and household essentials.</small>
                                    <img src="/build/assets/grocery.jpg">
                              </div>
                        </div>
                    </section>
                    <section class="about-us py-10 px-5 bg-white text-gray-800 dark:bg-gray-900 dark:text-white" style="padding: 15px 0px;">
                        <div class="max-w-3xl mx-auto text-center">
                            <p class="font-semibold">Join us and be part of a cooperative where every voice matters, and every step forward is a shared success.</p>
                        </div>
                    </section>
                </div>
            </div>
        </main>
            <div class="footer">
            <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            <!-- Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) -->
             <span>ENREMCO Management System V 1.0.12</span>
            </footer>
        </div>  

    </body>

<script>
    const burgerMenu = document.getElementById('burger-menu');
    const mobileMenu = document.getElementById('mobile-menu');

    burgerMenu.addEventListener('click', () => {
        // Toggle visibility and animation
        mobileMenu.classList.toggle('hidden');
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.style.maxHeight = '0';
            mobileMenu.style.opacity = '0';
        } else {
            mobileMenu.style.maxHeight = '500px'; // Adjust this value depending on the height of your menu
            mobileMenu.style.opacity = '1';
        }
    });
</script>

</html>
