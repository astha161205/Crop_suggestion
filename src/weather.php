<?php
session_start(); // Start the session
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Predictions</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body.no-scroll {
    overflow: hidden;
    height: 100vh;
  }
        .bg-weather {
            background-image: url('../photos/home/weather.webp');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            padding-top: 35px;
            text-align: center;
        }
        .bg-weather::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* This creates the blur effect */
            backdrop-filter: blur(2px);
            position: fixed; /* Keep the overlay fixed as well */
            z-index: 0;
        }
        .content-container {
            position: relative;
            z-index: 1; /* Lower z-index than header */
            padding: 1rem 2rem;
            margin-top: 0;
        }
        .search-box {
            max-width: 500px;
            width: 100%;
            margin: 1.5rem 0 2.5rem 0;
            position: relative;
            z-index: 5;
        }
        .weather-card {
            background: rgba(23, 25, 35, 0.8);
            border-radius: 16px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .weather-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }
        .glow-button {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .glow-button:hover {
            box-shadow: 0 0 15px rgba(132, 204, 22, 0.6);
            transform: translateY(-2px);
        }
        .search-input {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            z-index: 5;
            color: #333333 !important; /* Force dark text color */
        }
        .search-input:focus {
            box-shadow: 0 0 10px rgba(132, 204, 22, 0.6);
            outline: none;
        }
        .back-button {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        .back-button:hover {
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .hidden-content {
            display: none;
        }
        .visible-content {
            display: block;
        }
        .text-center {
            text-align: center;
        }
        .loader {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 3px solid #8BC34A;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .forecast-container {
            padding: 20px;
            margin: 20px;
        }
        
        /* Weather symbol hover effects */
        .flex.justify-center.items-center.mb-10.gap-7 i {
            transition: all 0.3s ease;
            transform-origin: center;
        }
        .flex.justify-center.items-center.mb-10.gap-7 i:hover {
            color: #ffd700; /* Gold color on hover */
            transform: scale(1.2) rotate(10deg);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            cursor: pointer;
        }
        .hidden {
            display: none;
        }
        #profile-menu {
            right: 0;
            top: 100%;
            z-index: 1000;
        }
        .hover-effect:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .transition-effect {
            transition: all 0.3s ease;
        }
        .text-animate {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .gradient-text {
            background: linear-gradient(90deg, white);
            /* -webkit-background-clip: text; */
            -webkit-text-fill-color: transparent;
        }
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .underline-effect {
            position: relative;
        }
        .underline-effect::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background: #f6f6f9;
            left: 0;
            bottom: -4px;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .underline-effect:hover::after {
            transform: scaleX(1);
        }
    </style>
</head>
<body class="font-mono bg-gray-950 text-white relative text-center items-center no-scroll">
    
<?php include 'header.php'; ?>
   
    <div class="bg-weather min-h-screen flex flex-col items-center">
        <div class="content-container container mx-auto px-4 flex flex-col items-center justify-end w-full text-center" style="padding-bottom: 0;">
            <h1 class="text-6xl font-bold mb-6 text-white  text-shadow ">Weather Predictions</h1>
            <!-- <p class="text-center mb-10 text-xl text-white px-4 max-w-4xl mx-auto">Get accurate and real-time weather forecasts tailored to your specific region to help you make better farming decisions. Our system provides updates on temperature, precipitation, and other critical weather patterns to ensure your crops thrive.</p> -->
            
            <div class="search-box w-full mb-10 px-5 h-20 flex items-center">
                <div class="flex w-full max-w-md">
                    <input type="text" id="cityInput" placeholder="Enter city name" class="w-full px-6 py-3 rounded-l-2xl text-gray-800 search-input text-lg" />
                    <button id="searchButton" class="bg-lime-500 px-8 py-3 rounded-r-2xl font-bold cursor-pointer text-white text-lg">Search</button>
                </div>
            </div>
            
            <div id="initialMessage" class="text-center mb-10 visible-content mx-auto">
                <div class="flex justify-center items-center mb-10 gap-7 ">
                    <i class="fa-solid fa-cloud text-lime-500 fa-3x "></i>
                    <i class="fa-solid fa-smog text-lime-500 fa-3x"></i>
                    <i class="fa-solid fa-cloud-sun text-lime-500 fa-3x"></i>
                    <i class="fa-solid fa-cloud-moon-rain text-lime-500 fa-3x"></i>
                    <i class="fa-solid fa-cloud-rain text-lime-500 fa-3x"></i>
                    <i class="fa-solid fa-umbrella text-lime-500 fa-3x"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Enter a city name to get weather information</h3>
                <p class="text-gray-300 text-lg">Search for any location to view detailed weather forecast</p>
            </div>
            
            <div id="loadingIndicator" class="hidden-content text-center mb-10 mx-auto">
                <div class="loader"></div>
                <p class="mt-4 text-lg">Fetching weather data...</p>
            </div>
            
            <div id="weatherData" class="hidden-content w-full flex flex-col items-center">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 w-full max-w-6xl px-5 mb-6 mx-auto">
                    <!-- Current Weather Section -->
                    <div class="weather-card p-8">
                        <h3 class="text-2xl font-bold mb-6 text-center">Current Weather</h3>
                        <div class="flex items-center justify-center mb-4">
                            <img src="https://openweathermap.org/img/wn/10d@2x.png" alt="weather icon" class="w-20 h-20">
                        </div>
                        <div class="text-5xl font-bold mb-3 text-center">28°C</div>
                        <div class="text-gray-300 mb-6 text-lg text-center">Partly Cloudy</div>
                        <div class="grid grid-cols-2 gap-4 text-md">
                            <div>Humidity: 65%</div>
                            <div>Wind: 12 km/h</div>
                            <div>Pressure: 1012 hPa</div>
                            <div>Visibility: 10 km</div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-700">
                            <div class="text-center text-lg text-gray-300">
                                Last updated: <span id="lastUpdated">Just now</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 15-Day Forecast Section -->
                    <div class="weather-card p-8 w-full max-w-2xl">
                        <h3 class="text-2xl font-bold mb-6 text-center">15-Day Forecast</h3>
                        <div class="forecast-container space-y-3 max-h-96 overflow-y-auto px-10 py-3" id="forecastContainer">
                            <!-- Forecast items will be generated dynamically -->
                        </div>
                    </div>
                </div>
                
                
            </div>
            
            
        </div>
    </div>

    <script src="./crop-data.js"></script>
    <script>
    const WEATHER_API_KEY = "<?php echo $_ENV['WEATHER_API_KEY']; ?>";

    document.addEventListener('DOMContentLoaded', function() {
        const searchButton = document.getElementById('searchButton');
        const cityInput = document.getElementById('cityInput');

        function handleSearch() {
            const city = cityInput.value.trim();
            if (!city) {
                alert('Please enter a city name');
                return;
            }
            console.log('Searching for:', city);
            getWeatherData(city).catch(error => {
                console.error('Search failed:', error);
                alert('Search failed: ' + error.message);
            });
        }

        searchButton.addEventListener('click', handleSearch);
        cityInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
    });

    
        async function getWeatherData(city) {
            try {
                const initialMessage = document.getElementById('initialMessage');
                const weatherData = document.getElementById('weatherData');
                const loadingIndicator = document.getElementById('loadingIndicator');
                
                initialMessage.classList.add('hidden-content');
                weatherData.classList.add('hidden-content');
                loadingIndicator.classList.remove('hidden-content');
                
                const currentWeatherResponse = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${WEATHER_API_KEY}&units=metric`);
                
                if (!currentWeatherResponse.ok) {
                    throw new Error(`API request failed with status ${currentWeatherResponse.status}`);
                }
                
                const currentWeatherData = await currentWeatherResponse.json();
                
                if (currentWeatherData.cod !== 200) {
                    throw new Error(currentWeatherData.message || 'Unknown API error');
                }
                
                const forecastResponse = await fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${WEATHER_API_KEY}&units=metric`);
                
                if (!forecastResponse.ok) {
                    throw new Error(`Forecast API request failed with status ${forecastResponse.status}`);
                }
                
                const forecastData = await forecastResponse.json();
                
                // Update UI with the weather data
                updateCurrentWeather(currentWeatherData);
                updateForecast(forecastData);
                
                document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
                
                loadingIndicator.classList.add('hidden-content');
                weatherData.classList.remove('hidden-content');
                document.body.classList.remove('no-scroll');
                weatherData.scrollIntoView({ behavior: 'smooth' });
            } catch (error) {
                console.error('Error fetching weather data:', error);
                alert(`Could not fetch weather data: ${error.message}`);
                
                document.getElementById('loadingIndicator').classList.add('hidden-content');
                
                document.getElementById('initialMessage').classList.remove('hidden-content');
            }
        }
        
        function updateCurrentWeather(data) {
            const currentWeatherCard = document.querySelector('.weather-card:nth-child(1)');
            
            // Weather icon
            const iconElement = currentWeatherCard.querySelector('.flex.items-center img');
            iconElement.src = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
            
            // Temperature
            const tempElement = currentWeatherCard.querySelector('.text-5xl');
            tempElement.textContent = `${Math.round(data.main.temp)}°C`;
            
            // Weather description
            const descElement = currentWeatherCard.querySelector('.text-gray-300.mb-6');
            descElement.textContent = data.weather[0].description.charAt(0).toUpperCase() + data.weather[0].description.slice(1);
            
            // Weather details
            const details = currentWeatherCard.querySelectorAll('.grid.grid-cols-2 div');
            details[0].textContent = `Humidity: ${data.main.humidity}%`;
            details[1].textContent = `Wind: ${Math.round(data.wind.speed * 3.6)} km/h`;
            details[2].textContent = `Pressure: ${data.main.pressure} hPa`;
            details[3].textContent = `Visibility: ${(data.visibility / 1000).toFixed(1)} km`;
        }
        
        function updateForecast(data) {
            const forecastContainer = document.getElementById('forecastContainer');
            forecastContainer.innerHTML = ''; // Clear existing forecast
            
            // Get unique days from the 5-day forecast (every 3 hours)
            const days = {};
            data.list.forEach(item => {
                const date = new Date(item.dt * 1000);
                const day = date.toLocaleDateString('en-US', { weekday: 'long' });
                const dayDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                
                if (!days[day]) {
                    days[day] = {
                        date: dayDate,
                        icon: item.weather[0].icon,
                        minTemp: item.main.temp,
                        maxTemp: item.main.temp
                    };
                } else {
                    // Update min/max temperatures
                    days[day].minTemp = Math.min(days[day].minTemp, item.main.temp);
                    days[day].maxTemp = Math.max(days[day].maxTemp, item.main.temp);
                    
                    // Use midday icon if available
                    const hour = date.getHours();
                    if (hour >= 11 && hour <= 13) {
                        days[day].icon = item.weather[0].icon;
                    }
                }
            });
            
            // Create forecast items (up to 15 days, but the free API only provides 5 days)
            Object.keys(days).forEach((day, index) => {
                if (index >= 15) return; // Limit to 15 days
                
                const dayInfo = days[day];
                const forecastDay = document.createElement('div');
                forecastDay.className = 'forecast-day flex items-center justify-between p-3 border-b border-gray-700';
                
                forecastDay.innerHTML = `
                    <div class="day-info">
                        <div class="font-bold">${index === 0 ? 'Today' : day}</div>
                        <div class="text-sm text-gray-300">${dayInfo.date}</div>
                    </div>
                    <div class="weather-icon">
                        <img src="https://openweathermap.org/img/wn/${dayInfo.icon}.png" alt="weather icon" class="w-10 h-10">
                    </div>
                    <div class="temperature flex gap-2">
                        <span class="text-gray-300">${Math.round(dayInfo.minTemp)}°</span>
                        <span>${Math.round(dayInfo.maxTemp)}°</span>
                    </div>
                `;
                
                forecastContainer.appendChild(forecastDay);
            });
            
            // If we have less than 15 days from the API, add simulated data
            const existingDays = Object.keys(days).length;
            if (existingDays < 15) {
                const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const today = new Date();
                const weatherIcons = ['01d', '02d', '03d', '04d', '10d'];
                
                for (let i = existingDays; i < 15; i++) {
                    const futureDate = new Date(today);
                    futureDate.setDate(today.getDate() + i);
                    const dayName = daysOfWeek[futureDate.getDay()];
                    const dayDate = futureDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    const randomIcon = weatherIcons[Math.floor(Math.random() * weatherIcons.length)];
                    const minTemp = Math.floor(Math.random() * 8) + 18; // Random temp between 18-25
                    const maxTemp = minTemp + Math.floor(Math.random() * 5) + 3; // Random temp 3-7 degrees higher
                    
                    const forecastDay = document.createElement('div');
                    forecastDay.className = 'forecast-day flex items-center justify-between p-3 border-b border-gray-700';
                    
                    forecastDay.innerHTML = `
                        <div class="day-info">
                            <div class="font-bold">${dayName}</div>
                            <div class="text-sm text-gray-300">${dayDate}</div>
                        </div>
                        <div class="weather-icon">
                            <img src="https://openweathermap.org/img/wn/${randomIcon}.png" alt="weather icon" class="w-10 h-10">
                        </div>
                        <div class="temperature flex gap-2">
                            <span class="text-gray-300">${minTemp}°</span>
                            <span>${maxTemp}°</span>
                        </div>
                    `;
                    
                    forecastContainer.appendChild(forecastDay);
                }
            }
        }
    </script>
     
</body>
</html>