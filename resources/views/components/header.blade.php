<header class="sticky-header no-auth" id='header'>
    <div class="flex flex-wrap w-full h-16 items-center justify-between md:px-5">

        <a href="{{ url('/') }}" class="flex text-xl font-bold items-center">
            <img src="{{ asset('images/logo/ghs-32.png') }}" alt="" class="w-6 md:w-12">
            <div class="px-2">
                <div class="app-title text-sm md:text-lg font-medium">GHS 32/2L</div>
                <div class="app-subtitle text-xs font-thin hidden md:block">Believe in Excellence</div>
            </div>
        </a>

        <nav id='navbar' class="navbar">
            <ul>
                <li class="float-right md:hidden" onclick="toggleNavbarMobile()">
                    <i class="bi-x text-xl text-orange-300 hover:-rotate-90 transition duration-500 ease-in-out"></i>
                </li>
                <li><a href="{{ url('/') }}" class="nav-item">Home</a></li>
                <li><a href="{{ url('about') }}" class="nav-item">About</a></li>
                <li><a href="{{ url('faculty') }}" class="nav-item">Faculty</a></li>
                <li><a href="{{ url('gallary') }}" class="nav-item">Gallary</a></li>
                <li><a href="{{ url('contact') }}" class="nav-item">Contact Us</a></li>
                <li><a href="{{ url('login') }}" class="nav-item">Login</a></li>
            </ul>
        </nav>

        <button class="lg:hidden" onclick="toggleNavbarMobile()" id='menu'>
            <!-- menu -->
            <i class="bi-list text-lg"></i>
        </button>
    </div>
</header>
<script>
    var header = document.getElementById("header");
    window.onscroll = function() {
        if (window.pageYOffset > 5) {
            header.classList.add("scrolled-down");
        } else {
            header.classList.remove("scrolled-down");
        }
    };

    function toggleNavbarMobile() {
        $('#navbar').toggleClass('mobile');
    }

    function showLoginModal() {
        $('#loginModal').addClass('shown')
    }
</script>
