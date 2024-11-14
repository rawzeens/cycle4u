<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Period Tracker - Track with Confidence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Navbar -->
<div class="px-6 pt-4 fixed w-full z-20">
        <nav class="p-4 rounded-[30px] w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-8 shadow-lg relative ">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Logo -->
                <a href="#" class="text-2xl font-bold text-white">Cycle4U</a>

                <!-- Nav Links -->
                <div class="hidden md:flex space-x-6">
                    <a href="#features" class="text-white hover:text-indigo-300">Features</a>
                    <a href="#about" class="text-white hover:text-indigo-300">About</a>
                    <a href="#contact" class="text-white hover:text-indigo-300">Contact</a>
                </div>

                <!-- Sign Up / Login Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <button onclick="toggleModal('signin-modal')" class="px-4 py-2 text-white hover:text-indigo-300">Login</button>
                    <button onclick="toggleModal('signup-modal')" class="bg-white text-indigo-500 px-4 py-2 rounded-full hover:bg-indigo-100">Sign Up</button>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-4">
                <a href="#features" class="block text-white hover:text-indigo-300 mb-2">Features</a>
                <a href="#about" class="block text-white hover:text-indigo-300 mb-2">About</a>
                <a href="#contact" class="block text-white hover:text-indigo-300 mb-2">Contact</a>
                <div class="mt-4 pb-6">
                    <button onclick="toggleModal('signin-modal')" class="block bg-white text-indigo-500 px-4 py-2 rounded-[30px] w-full text-center mb-2">Login</button>
                    <button onclick="toggleModal('signup-modal')" class="block bg-white text-indigo-500 px-4 py-2 rounded-[30px] w-full text-center">Sign Up</button>
                </div>
            </div>
        </nav>
    </div>

<!-- Sign In Modal -->
<div id="signin-modal" class="fixed z-50 inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center px-6">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-4">Sign In</h2>
        <form id="signin-form" onsubmit="submitSignIn(event)">
            <div class="mb-4">
                <label for="signin-username" class="block text-gray-700">Username</label>
                <input type="text" id="signin-username" class="w-full px-4 py-2 border rounded-lg focus:outline-none" placeholder="Enter your username" required>
            </div>
            <div class="mb-4">
                <label for="signin-password" class="block text-gray-700">Password</label>
                <input type="password" id="signin-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none" placeholder="Enter your password" required>
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-[30px] hover:bg-indigo-600">Sign In</button>
                <button type="button" onclick="toggleModal('signin-modal')" class="text-gray-500">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Sign Up Modal -->
<div id="signup-modal" class="fixed z-50 inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center px-6">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-4">Sign Up</h2>
        <form id="signup-form" onsubmit="submitSignUp(event)">
            <div class="mb-4">
                <label for="signup-username" class="block text-gray-700">Username</label>
                <input type="text" id="signup-username" class="w-full px-4 py-2 border rounded-lg focus:outline-none" placeholder="Enter your username" required>
            </div>
            <div class="mb-4">
                <label for="signup-email" class="block text-gray-700">Email</label>
                <input type="email" id="signup-email" class="w-full px-4 py-2 border rounded-lg focus:outline-none" placeholder="Enter your email" required>
            </div>
            <div class="mb-4">
                <label for="signup-password" class="block text-gray-700">Password</label>
                <input type="password" id="signup-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none" placeholder="Enter your password" required>
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-[30px] hover:bg-indigo-600">Sign Up</button>
                <button type="button" onclick="toggleModal('signup-modal')" class="text-gray-500">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
// Function to toggle modal visibility
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.toggle('hidden');
}

// Function to toggle modal visibility
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.toggle('hidden');
}

// Function to submit the Sign In form using XHR
function submitSignIn(event) {
    event.preventDefault();

    const xhr = new XMLHttpRequest();
    const username = document.getElementById('signin-username').value;
    const password = document.getElementById('signin-password').value;

    xhr.open('POST', 'api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            // Parse the JSON response
            let response = JSON.parse(xhr.responseText);
            
            if (xhr.status === 200) {
                if (response.status === 'success') {
                    console.log(response.message); // Handle success
                    alert('Sign in successful');
                    toggleModal('signin-modal'); // Close modal on success
                    window.location.href ="dashboard.php"
                } else {
                    console.error(response.message); // Handle error
                    alert('Sign in failed: ' + response.message);
                }
            } else {
                console.error(xhr.responseText); // Handle HTTP error
                alert('Sign in failed with status: ' + xhr.status);
            }
        }
    };

    const params = `action=signin&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`;
    xhr.send(params);
}

// Function to submit the Sign Up form using XHR
function submitSignUp(event) {
    event.preventDefault();

    const xhr = new XMLHttpRequest();
    const username = document.getElementById('signup-username').value;
    const email = document.getElementById('signup-email').value;
    const password = document.getElementById('signup-password').value;

    xhr.open('POST', 'api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            // Parse the JSON response
            let response = JSON.parse(xhr.responseText);
            
            if (xhr.status === 200) {
                if (response.status === 'success') {
                    console.log(response.message); // Handle success
                    alert('Sign up successful');
                    toggleModal('signup-modal');
                    window.location.href ="dashboard.php"

                } else {
                    console.error(response.message); // Handle error
                    alert('Sign up failed: ' + response.message);
                }
            } else {
                console.error(xhr.responseText); // Handle HTTP error
                alert('Sign up failed with status: ' + xhr.status);
            }
        }
    };

    const params = `action=signup&username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`;
    xhr.send(params);
}
</script>



    <!-- Scripts -->
    <script>
        
        // Toggle mobile menu
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Function to toggle modals
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }
    </script>



    <!-- Hero Section -->
     <div class="h-screen flex justify-center items-center">
    <section class="text-center px-4">
        <h1 class="text-5xl font-extrabold text-gray-800">Track Your Cycle with Confidence</h1>
        <p class="text-xl text-gray-600 mt-4 max-w-2xl mx-auto">CycleEase helps you stay on top of your menstrual health with accurate predictions and personalized insights. Plan your life, stress-free.</p>
        <a href="#" class="mt-6 inline-block bg-indigo-500 text-white px-6 py-3 rounded-full hover:bg-indigo-400">Get Started for Free</a>
    </section>
    </div>

    <!-- Features Section -->
    <section id="features" class="mt-16 px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center hover:shadow-lg transition-shadow duration-200">
                <h3 class="text-xl font-semibold text-gray-800">Accurate Predictions</h3>
                <p class="text-gray-600 mt-2">Powered by data, CycleEase delivers precise cycle tracking and fertility forecasts.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center hover:shadow-lg transition-shadow duration-200">
                <h3 class="text-xl font-semibold text-gray-800">Personalized Insights</h3>
                <p class="text-gray-600 mt-2">Get custom insights and tips tailored to your unique health needs.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center hover:shadow-lg transition-shadow duration-200">
                <h3 class="text-xl font-semibold text-gray-800">Privacy Guaranteed</h3>
                <p class="text-gray-600 mt-2">We take privacy seriously. Your data is fully encrypted and secure.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="mt-16 bg-blue-50 py-16 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800">What Our Users Are Saying</h2>
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-gray-600">"CycleEase has made tracking my cycle so easy and stress-free. I never miss a day, and the predictions are spot-on!"</p>
                    <p class="text-gray-800 mt-4 font-semibold">- Jessica M.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-gray-600">"I love the simplicity and accuracy of CycleEase. It’s the best period tracker I’ve used so far!"</p>
                    <p class="text-gray-800 mt-4 font-semibold">- Sarah K.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-gray-600">"CycleEase’s privacy features give me peace of mind knowing my data is safe."</p>
                    <p class="text-gray-800 mt-4 font-semibold">- Emily R.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
     <div class="px-6">
    <section class="mt-16 text-center py-16 px-6 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-[30px] shadow-lg">
        <h2 class="text-2xl font-extrabold">Ready to Take Control?</h2>
        <p class="text-xl mt-4">Join thousands of women worldwide who trust CycleEase to manage their cycle health.</p>
        <a href="#" class="mt-8 inline-block bg-white text-indigo-500 px-6 py-3 rounded-full hover:bg-indigo-100">Sign Up Today</a>
    </section>
    </div>

    <!-- Footer -->
    <footer id="contact" class="mt-16 bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto text-center">
            <h3 class="text-xl font-semibold">Contact Us</h3>
            <p class="text-gray-400 mt-2">Email: support@cycleease.com</p>
            <p class="text-gray-400">Phone: +123 456 7890</p>

            <div class="mt-4">
                <a href="#" class="text-gray-400 hover:text-white mx-2">Facebook</a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">Twitter</a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">Instagram</a>
            </div>
        </div>
    </footer>

    <script>

    </script>

</body>
</html>
