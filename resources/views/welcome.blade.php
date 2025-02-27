<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orchid Tailoring</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <style>
    .dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      background-color: white;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      min-width: 180px;
      z-index: 10;
    }
    .dropdown:hover .dropdown-menu {
      display: block;
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

  <!-- Navigation Bar -->
  <header class="bg-white shadow-md">
    <div class="container mx-auto flex justify-between items-center px-6 py-4">
      <div class="flex items-center gap-4">
        <img src="{{ asset('images/company-logo2.png') }}" alt="Orchid Tailoring Logo" class="h-12">
        <h1 class="text-2xl font-bold text-purple-700" style="font-family: 'Palatino', 'Palatino Linotype', 'Book Antiqua', serif;">
          Orchid Tailoring
        </h1>
      </div>
      <div class="flex gap-4">
        <!-- Customer Dropdown -->
        <div class="relative dropdown">
          <button class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg">
            Customer <i class="fas fa-caret-down ml-2"></i>
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('customer.login') }}" class="block px-4 py-2 hover:bg-gray-100">Login</a>
            <a href="{{ route('customer.register') }}" class="block px-4 py-2 hover:bg-gray-100">Register</a>
          </div>
        </div>

        <!-- Tailor Dropdown -->
        <div class="relative dropdown">
          <button class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg">
            Tailor <i class="fas fa-caret-down ml-2"></i>
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('tailor.login') }}" class="block px-4 py-2 hover:bg-gray-100">Login</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Image Slider -->
  <section class="relative">
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img src="{{ asset('images/slide-1.jpg') }}" class="w-full h-[500px] object-cover">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/slide-2.jpg') }}" class="w-full h-[500px] object-cover">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/slide-3.jpg') }}" class="w-full h-[500px] object-cover">
          </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <!-- Centered Login Button -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
      <a href="{{ route('customer.login') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg text-lg shadow-lg hover:bg-purple-700 transition">
        Register Now!
      </a>
    </div>
  </section>

  <!-- Services Section -->
  <section class="container mx-auto py-16 px-6 text-center">
    <h3 class="text-3xl font-bold text-gray-800 mb-6">Our Services</h3>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <i class="fas fa-cut text-purple-600 text-4xl mb-4"></i>
        <h4 class="text-xl font-semibold">Custom Tailoring</h4>
        <p class="text-gray-600 mt-2">Perfectly fitted garments designed just for you.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <i class="fas fa-ruler text-purple-600 text-4xl mb-4"></i>
        <h4 class="text-xl font-semibold">Measurement Services</h4>
        <p class="text-gray-600 mt-2">Accurate measurements for the best fit.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <i class="fas fa-toolbox text-purple-600 text-4xl mb-4"></i>
        <h4 class="text-xl font-semibold">Repair and Restoration Garments</h4>
        <p class="text-gray-600 mt-2">Cloth capture many moment together.</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-purple-700 text-white py-10">
    <div class="container mx-auto grid md:grid-cols-3 gap-8 px-6">
      <div>
        <h4 class="text-xl font-semibold">Orchid Tailoring</h4>
        <p class="mt-2 text-gray-300">Your perfect fit, tailored with precision.</p>
      </div>
      
      <div>
        <h4 class="text-xl font-semibold">Contact Us</h4>
        <p class="mt-2 text-gray-300">123 Tailor Street,Negeri Sembilan, Malaysia</p>
        <p class="text-gray-300">Email: seamlesstailor@frsb.xyz</p>
        <div class="flex gap-4 mt-4">
          <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook fa-lg"></i></a>
          <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram fa-lg"></i></a>
          <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter fa-lg"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    var swiper = new Swiper('.swiper-container', {
      loop: true,
      autoplay: { delay: 4000 },
      pagination: { el: '.swiper-pagination', clickable: true }
    });
  </script>

</body>
</html>
