<?php
session_start();
require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed

// Only load .env if it exists (prevents fatal error in production)
$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}
require_once 'theme_manager.php';
$theme = getThemeClasses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pest & Disease Management</title>
    <link rel="icon" href="../photos/home/favicon2.svg" type="image/svg+xml">
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./homecss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom scrollbar styling for dark theme */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: rgba(55, 65, 81, 0.3);
            border-radius: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.6);
            border-radius: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.8);
        }
        
        /* For Firefox */
        .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.6) rgba(55, 65, 81, 0.3);
        }

        /* Improve text readability */
        .text-sm {
            line-height: 1.5;
        }
        
        .text-lg {
            line-height: 1.6;
        }
        
        /* Better contrast for headings */
        .font-semibold {
            font-weight: 600;
        }
        
        .font-bold {
            font-weight: 700;
        }
        
        /* Rating button styles */
        .rating-btn {
            transition: all 0.3s ease;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
        }
        
        .rating-btn:hover {
            transform: scale(1.05);
            text-decoration: none !important;
        }
        
        .rating-btn:active {
            transform: scale(0.95);
        }
        
        /* Prevent duplicate ratings */
        .rating-message, .rating-response {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Dropdown menu styles */
        .dropdown-menu {
            position: absolute;
            bottom: 100%;
            left: 0;
            margin-bottom: 8px;
            width: 320px;
            background: white;
            color: #1f2937;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            z-index: 50;
            transition: all 0.3s ease-in-out;
            transform-origin: bottom;
            opacity: 0;
            transform: translateY(10px) scale(0.95);
        }
        
        .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        
        .dropdown-menu button {
            width: 100%;
            text-align: left;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .dropdown-menu button:hover {
            background-color: #dbeafe;
        }
        
        /* Dark theme support */
        .dark .dropdown-menu {
            background: #1f2937;
            color: white;
            border-color: #3b82f6;
        }
        
        .dark .dropdown-menu button:hover {
            background-color: #1e3a8a;
        }
    </style>
</head>
<body class="font-mono <?php echo $theme['bg']; ?> <?php echo $theme['text']; ?> min-h-screen">
            <?php include 'header.php'; ?>


            <main class="flex flex-col min-h-[300px]">
    <div class="flex flex-col md:flex-row  <?php echo $theme['bg']; ?>">
        <!-- Left: Info Section -->

        
        <section class=" md:w-1/3 p-3 md:p-4 <?php echo $theme['bg']; ?> flex flex-col " style="height:90vh; ">
            <div class="<?php echo $theme['bg_card']; ?> rounded-xl shadow-lg  p-4 flex flex-col h-full border <?php echo $theme['border']; ?>">
                <div class="overflow-y-auto flex-1 h-full pr-2">
                    <h1 class="text-2xl md:text-3xl font-bold mb-2 text-blue-400 top-0 bg-inherit py-2 z-10">Pest & Disease Control </h1>
                    <p class="text-base <?php echo $theme['text_secondary']; ?> mb-6">Learn about common crop pests, their signs, and how to control them using safe and effective methods.</p>
                    <div class="space-y-4 pb-4">
                        <!-- Card 1 -->
                        <div class="<?php echo $theme['bg']; ?> rounded-xl shadow-lg p-4 flex flex-col md:flex-row gap-3 items-center border <?php echo $theme['border']; ?>">
                            <img src="./home/aphids.jpeg" alt="Aphids" class="w-20 h-20 object-cover rounded-lg border <?php echo $theme['border']; ?>">
                            <div>
                                <h2 class="text-lg font-semibold mb-1 text-blue-400">Aphids</h2>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Symptoms:</b> Curled leaves, sticky residue (honeydew)</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Affected Crops:</b> Vegetables, fruits, grains</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?>"><b>Prevention/Treatment:</b> Neem oil spray, natural predators like ladybugs</div>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="<?php echo $theme['bg']; ?> rounded-xl shadow-lg p-4 flex flex-col md:flex-row gap-3 items-center border <?php echo $theme['border']; ?>">
                            <img src="./home/Cutworms.jpeg" alt="Cutworms" class="w-20 h-20 object-cover rounded-lg border <?php echo $theme['border']; ?>">
                            <div>
                                <h2 class="text-lg font-semibold mb-1 text-blue-400">Cutworms</h2>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Symptoms:</b> Damaged stems, missing seedlings</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Affected Crops:</b> Corn, tomato, lettuce</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?>"><b>Prevention/Treatment:</b> Barriers, nighttime inspection</div>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="<?php echo $theme['bg']; ?> rounded-xl shadow-lg p-4 flex flex-col md:flex-row gap-3 items-center border <?php echo $theme['border']; ?>">
                            <img src="./home/powdery-mildew.jpg" alt="Powdery Mildew" class="w-20 h-20 object-cover rounded-lg border <?php echo $theme['border']; ?>">
                            <div>
                                <h2 class="text-lg font-semibold mb-1 text-blue-400">Powdery Mildew</h2>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Symptoms:</b> White powder on leaves</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Affected Crops:</b> Squash, grapes, cereals</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?>"><b>Prevention/Treatment:</b> Proper spacing, sulfur-based fungicides</div>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="<?php echo $theme['bg']; ?> rounded-xl shadow-lg p-4 flex flex-col md:flex-row gap-3 items-center border <?php echo $theme['border']; ?>">
                            <img src="./home/armyworm.jpeg" alt="Armyworm" class="w-20 h-20 object-cover rounded-lg border <?php echo $theme['border']; ?>">
                            <div>
                                <h2 class="text-lg font-semibold mb-1 text-blue-400">Armyworm</h2>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Symptoms:</b> Ragged leaves, chewed stems</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Affected Crops:</b> Rice, maize, wheat</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?>"><b>Prevention/Treatment:</b> Handpicking, pheromone traps, biological control</div>
                            </div>
                        </div>
                        <!-- Additional Cards for more content -->
                        <div class="<?php echo $theme['bg']; ?> rounded-xl shadow-lg p-4 flex flex-col md:flex-row gap-3 items-center border <?php echo $theme['border']; ?>">
                            <img src="./home/Mites.webp" alt="Spider Mites" class="w-20 h-20 object-cover rounded-lg border <?php echo $theme['border']; ?>">
                            <div>
                                <h2 class="text-lg font-semibold mb-1 text-blue-400">Spider Mites</h2>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Symptoms:</b> Fine webbing, yellow speckled leaves</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Affected Crops:</b> Tomatoes, beans, strawberries</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?>"><b>Prevention/Treatment:</b> Regular misting, predatory mites, insecticidal soap</div>
                            </div>
                        </div>
                        <div class="<?php echo $theme['bg']; ?> rounded-xl shadow-lg p-4 flex flex-col md:flex-row gap-3 items-center border <?php echo $theme['border']; ?>">
                            <img src="./home/Whiteflies1.jpg" alt="Whiteflies" class="w-20 h-20 object-cover rounded-lg border <?php echo $theme['border']; ?>">
                            <div>
                                <h2 class="text-lg font-semibold mb-1 text-blue-400">Whiteflies</h2>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Symptoms:</b> White insects flying when disturbed, yellow leaves</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?> mb-1"><b>Affected Crops:</b> Tomatoes, peppers, cucumbers</div>
                                <div class="text-xs <?php echo $theme['text_secondary']; ?>"><b>Prevention/Treatment:</b> Yellow sticky traps, neem oil, beneficial insects</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Right: Chatbot Section -->
        <section class="p-3 md:p-4 <?php echo $theme['bg']; ?> flex flex-col" style="height:90vh; width:1500px;">

            <div class="<?php echo $theme['bg_card']; ?> rounded-xl shadow-lg p-4 flex flex-col h-full border <?php echo $theme['border']; ?>">
                <div class="flex items-center gap-3  mb-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-blue-400">Agriculture Help Desk</h2>
                        <p class="text-xs <?php echo $theme['text_secondary']; ?>">Ask about pests, plant diseases, or crop issues and get instant, reliable solutions.</p>
                    </div>
                </div>
                
                <!-- Chat Messages Area -->
                <div id="chatbot" class="<?php echo $theme['bg']; ?> rounded-lg p-3 flex-1 overflow-y-auto shadow-inner mb-3 border <?php echo $theme['border']; ?> flex flex-col">
                    <div id="messages" class="space-y-2 flex-1"></div>
                </div>
                
                <!-- Input Area -->
                <div class="space-y-2">
                    <div class="flex gap-2">
                        <div class="relative">
                            <button id="plusBtn" type="button" class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white hover:bg-blue-600 transition focus:outline-none">
                                 <i class="fas fa-plus"></i>
                            </button>
                            <div id="plusDropdown" class="dropdown-menu hidden">
                              <button onclick="document.getElementById('fileInput').click(); hideDropdown();" class="w-full text-left px-4 py-2 hover:bg-blue-100 dark:hover:bg-blue-900 flex items-center gap-2">
                                <i class="fas fa-paperclip"></i> Upload Image
                                </button>
                                <button onclick="generateSpraySchedule(); hideDropdown();" class="w-full text-left px-4 py-2 hover:bg-blue-100 dark:hover:bg-blue-900 flex items-center gap-2">
                                <i class="fas fa-calendar-alt"></i> Generate Spray Schedule
                                </button>
                                </div>
                            </div>
                            <div class="flex-grow relative flex items-center">
                            <input type="text" id="userInput" class="w-full px-3 py-2 pl-10 pr-10 rounded-lg border <?php echo $theme['input_border']; ?> <?php echo $theme['input_bg']; ?> <?php echo $theme['text']; ?> focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm" placeholder="Describe the pest or disease issue...">
                            <!-- <label for="fileInput" class="absolute left-2 cursor-pointer text-blue-400 hover:text-blue-600 transition z-10" title="Upload image">
                                <i class="fas fa-paperclip text-sm"></i>
                            </label> -->
                            <input type="file" id="fileInput" class="hidden" accept="image/*" />
                            </div>
                            <button onclick="sendMessage()" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg <?php echo $theme['text']; ?> font-semibold shadow transition flex items-center gap-1 text-sm">
                            <i class="fas fa-paper-plane text-sm"></i>
                            Send
                            </button>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Bottom Section -->
    <section class="<?php echo $theme['bg_card']; ?> border-t <?php echo $theme['border']; ?> p-6 flex flex-col h-15 md:flex-row items-center justify-between gap-4 shadow">
        <!-- <button onclick="generateSpraySchedule()" class="bg-blue-500 text-white font-semibold px-6 py-3 rounded-xl shadow hover:bg-blue-600 transition text-lg flex items-center gap-2">
            <i class="fas fa-calendar-alt"></i>
            Generate Spray Schedule
        </button> -->
        <div class="<?php echo $theme['text']; ?> text-base flex items-center gap-2">
            <span>Was the chatbot helpful?</span>
            <button onclick="rateChatbot('yes')" class="rating-btn ml-2 text-blue-400 hover:underline font-semibold">Yes</button>
            <button onclick="rateChatbot('no')" class="rating-btn ml-2 text-red-400 hover:underline font-semibold">No</button>
        </div>
    </section>
</main>


<script>
const GEMINI_API_KEY = "<?php echo $_ENV['GEMINI_API_KEY']; ?>";
const GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent';

// Add welcome message when page loads
document.addEventListener('DOMContentLoaded', function() {
    const messageContainer = document.getElementById('messages');
    const welcomeMessage = document.createElement('div');
    welcomeMessage.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-white text-sm"></i>
            </div>
            <div class="bg-blue-500 bg-opacity-10 rounded-lg p-3 flex-1">
                <div class="font-semibold text-blue-400 mb-1">AI Assistant</div>
                <div class="text-sm">Hello! I'm your agricultural pest and disease expert. Ask me anything about crop pests, diseases, prevention methods, or treatment options. You can also upload images for visual analysis.</div>
            </div>
        </div>
    `;
    messageContainer.appendChild(welcomeMessage);
});

async function sendMessage() {
    const input = document.getElementById('userInput');
    const msg = input.value.trim();
    const fileInput = document.getElementById('fileInput');
    
    if (!msg && !fileInput.files.length) return;

    const messageContainer = document.getElementById('messages');
    
    // Add user message
    if (msg) {
        const userMessage = document.createElement('div');
        userMessage.innerHTML = `
            <div class="flex items-start gap-3 justify-end">
                <div class="bg-blue-500 bg-opacity-20 rounded-lg p-3 max-w-xs">
                    <div class="font-semibold text-blue-400 mb-1">You</div>
                    <div class="text-sm">${msg}</div>
                </div>
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
            </div>
        `;
        messageContainer.appendChild(userMessage);
    }

    // Add loading message
    const loadingMessage = document.createElement('div');
    loadingMessage.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-white text-sm"></i>
            </div>
            <div class="bg-gray-600 bg-opacity-20 rounded-lg p-3 flex-1">
                <div class="font-semibold text-blue-400 mb-1">AI Assistant</div>
                <div class="text-sm flex items-center gap-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-400"></div>
                    Thinking...
                </div>
            </div>
        </div>
    `;
    messageContainer.appendChild(loadingMessage);

    input.value = '';

    try {
        // Prepare the prompt for pest-related assistance
        let prompt = `You are an agricultural pest and disease expert. A farmer is asking about: "${msg}". 
        Please provide helpful, practical advice about pest identification, symptoms, prevention, and treatment methods. 
        Keep your response concise, informative, and focused on agricultural solutions. 
        If the query is not related to agriculture, politely redirect them to ask about crop pests or diseases.
        
        Format your response in a clear, structured way with bullet points or short paragraphs.`;

        if (fileInput.files.length > 0) {
            prompt += `\n\nThe user has also uploaded an image. Please mention that you can see the image and provide analysis based on their description.`;
        }

        console.log('Sending request to Gemini API...');
        console.log('API URL:', `${GEMINI_API_URL}?key=${GEMINI_API_KEY}`);
        console.log('Prompt:', prompt);

        const requestBody = {
            contents: [{
                parts: [{
                    text: prompt
                }]
            }]
        };

        console.log('Request body:', JSON.stringify(requestBody, null, 2));

        const response = await fetch(`${GEMINI_API_URL}?key=${GEMINI_API_KEY}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestBody)
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('API Response:', data);
        
        // Remove loading message
        messageContainer.removeChild(loadingMessage);

        if (data.candidates && data.candidates[0] && data.candidates[0].content && data.candidates[0].content.parts && data.candidates[0].content.parts[0]) {
            const aiResponse = data.candidates[0].content.parts[0].text;
            
            // Add AI response
            const responseMessage = document.createElement('div');
            responseMessage.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    <div class="bg-gray-600 bg-opacity-20 rounded-lg p-3 flex-1">
                        <div class="font-semibold text-blue-400 mb-1">AI Assistant</div>
                        <div class="text-sm whitespace-pre-wrap">${aiResponse}</div>
                    </div>
                </div>
            `;
            messageContainer.appendChild(responseMessage);
        } else {
            console.error('Unexpected API response structure:', data);
            // Handle API error
            const errorMessage = document.createElement('div');
            errorMessage.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <div class="bg-red-500 bg-opacity-20 rounded-lg p-3 flex-1">
                        <div class="font-semibold text-red-400 mb-1">API Error</div>
                        <div class="text-sm">Sorry, I received an unexpected response from the AI service. Please try again.</div>
                    </div>
                </div>
            `;
            messageContainer.appendChild(errorMessage);
        }
    } catch (error) {
        console.error('API Error:', error);
        
        // Remove loading message
        messageContainer.removeChild(loadingMessage);
        
        // Add error message
        const errorMessage = document.createElement('div');
        errorMessage.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                </div>
                <div class="bg-red-500 bg-opacity-20 rounded-lg p-3 flex-1">
                    <div class="font-semibold text-red-400 mb-1">Connection Error</div>
                    <div class="text-sm">Sorry, I'm having trouble connecting to the AI service. Error: ${error.message}</div>
                </div>
            </div>
        `;
        messageContainer.appendChild(errorMessage);
    }

    // Scroll to bottom
    messageContainer.scrollTop = messageContainer.scrollHeight;
}

// Handle Enter key press
document.getElementById('userInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Handle file upload for image-based queries
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const messageContainer = document.getElementById('messages');
        const uploadMessage = document.createElement('div');
        uploadMessage.innerHTML = `
            <div class="flex items-start gap-3 justify-end">
                <div class="bg-blue-500 bg-opacity-20 rounded-lg p-3 max-w-xs">
                    <div class="font-semibold text-blue-400 mb-1">You</div>
                    <div class="text-sm flex items-center gap-2">
                        <i class="fas fa-image"></i>
                        Image uploaded: ${file.name}
                    </div>
                </div>
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
            </div>
        `;
        messageContainer.appendChild(uploadMessage);
        
        // Auto-send message about the image
        const input = document.getElementById('userInput');
        input.value = `I've uploaded an image of what appears to be a pest or disease issue. Can you help me identify what this is and how to treat it?`;
        
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
});

// Function to generate a spray schedule using AI
async function generateSpraySchedule() {
    const messageContainer = document.getElementById('messages');
    
    // Add loading message
    const loadingMessage = document.createElement('div');
    loadingMessage.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-alt text-white text-sm"></i>
            </div>
            <div class="bg-blue-500 bg-opacity-10 rounded-lg p-3 flex-1">
                <div class="font-semibold text-blue-400 mb-1">Spray Schedule Generator</div>
                <div class="text-sm flex items-center gap-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-400"></div>
                    Generating personalized spray schedule...
                </div>
            </div>
        </div>
    `;
    messageContainer.appendChild(loadingMessage);
    messageContainer.scrollTop = messageContainer.scrollHeight;

    try {
        // Get chat history to understand the context
        const chatHistory = getChatHistory();
        
        const prompt = `You are an agricultural expert creating a spray schedule. Based on this conversation: "${chatHistory}", 
        please create a detailed, practical spray schedule for the farmer. 
        
        Format the response as a simple date-wise schedule with the following structure:
        
        **Week 1 (Date):**
        • Time: [specific time]
        • Product: [product name and dilution]
        • Method: [application method]
        • Notes: [important notes]
        
        **Week 2 (Date):**
        • Time: [specific time]
        • Product: [product name and dilution]
        • Method: [application method]
        • Notes: [important notes]
        
        Continue for 3-4 weeks. Include:
        1. Crop type and current growth stage
        2. Identified pests/diseases
        3. Recommended treatments with specific products
        4. Application schedule (dates and times)
        5. Safety precautions
        6. Alternative organic options
        
        Keep it simple and easy to follow. Don't use tables, just use bullet points and clear dates.`;

        const response = await fetch(`${GEMINI_API_URL}?key=${GEMINI_API_KEY}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contents: [{
                    parts: [{
                        text: prompt
                    }]
                }]
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        // Remove loading message
        messageContainer.removeChild(loadingMessage);

        if (data.candidates && data.candidates[0] && data.candidates[0].content && data.candidates[0].content.parts && data.candidates[0].content.parts[0]) {
            const scheduleResponse = data.candidates[0].content.parts[0].text;
            
            // Add spray schedule response
            const scheduleMessage = document.createElement('div');
            scheduleMessage.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                    </div>
                    <div class="bg-blue-500 bg-opacity-10 rounded-lg p-3 flex-1">
                        <div class="font-semibold text-blue-400 mb-1">🌱 Personalized Spray Schedule</div>
                        <div class="text-sm whitespace-pre-wrap">${scheduleResponse}</div>
                        <div class="mt-3 pt-3 border-t border-blue-400 border-opacity-30">
                            <div class="text-xs text-blue-400">
                                💡 Tip: Always follow safety guidelines and wear protective equipment when applying pesticides.
                            </div>
                        </div>
                    </div>
                </div>
            `;
            messageContainer.appendChild(scheduleMessage);
        } else {
            // Fallback schedule if API fails
            const fallbackMessage = document.createElement('div');
            fallbackMessage.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                    </div>
                    <div class="bg-blue-500 bg-opacity-10 rounded-lg p-3 flex-1">
                        <div class="font-semibold text-blue-400 mb-1">🌱 Sample Spray Schedule</div>
                        <div class="text-sm">
                            <strong>Crop:</strong> General Vegetable Garden<br>
                            <strong>Issue:</strong> Pest Management<br><br>
                            
                            <strong>Week 1 (Tomorrow):</strong><br>
                            • Time: 6:00 AM (before sun)<br>
                            • Product: Neem oil (1:100 dilution)<br>
                            • Method: Foliar spray<br>
                            • Notes: Cover all leaf surfaces thoroughly<br><br>
                            
                            <strong>Week 2:</strong><br>
                            • Time: 6:00 PM (evening)<br>
                            • Product: Insecticidal soap<br>
                            • Method: Complete coverage<br>
                            • Notes: Focus on undersides of leaves<br><br>
                            
                            <strong>Week 3:</strong><br>
                            • Time: Morning<br>
                            • Product: Beneficial insects release<br>
                            • Method: Release ladybugs<br>
                            • Notes: Water plants before release<br><br>
                            
                            <strong>Safety:</strong> Wear gloves, mask, and protective clothing. Avoid spraying during peak sun hours.
                        </div>
                    </div>
                </div>
            `;
            messageContainer.appendChild(fallbackMessage);
        }
    } catch (error) {
        console.error('Spray Schedule Error:', error);
        
        // Remove loading message
        messageContainer.removeChild(loadingMessage);
        
        // Add error message
        const errorMessage = document.createElement('div');
        errorMessage.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                </div>
                <div class="bg-red-500 bg-opacity-20 rounded-lg p-3 flex-1">
                    <div class="font-semibold text-red-400 mb-1">Schedule Generation Failed</div>
                    <div class="text-sm">Sorry, I couldn't generate a spray schedule. Please try again or consult with a local agricultural expert.</div>
                </div>
            </div>
        `;
        messageContainer.appendChild(errorMessage);
    }

    messageContainer.scrollTop = messageContainer.scrollHeight;
}

// Function to get chat history for context
function getChatHistory() {
    const messages = document.querySelectorAll('#messages .text-sm');
    let history = '';
    messages.forEach(msg => {
        if (msg.textContent && !msg.textContent.includes('Thinking...') && !msg.textContent.includes('Generating')) {
            history += msg.textContent + ' ';
        }
    });
    return history.trim() || 'No specific crop or pest mentioned yet. Please ask about your crops or pest issues first.';
}

// Function to rate the chatbot
function rateChatbot(rating) {
    console.log('Rating clicked:', rating); // Debug log
    
    const messageContainer = document.getElementById('messages');
    
    // Check if rating already exists to prevent duplicates
    const existingRating = messageContainer.querySelector('.rating-message');
    if (existingRating) {
        existingRating.remove();
    }
    
    const ratingMessage = document.createElement('div');
    ratingMessage.className = 'rating-message';
    ratingMessage.innerHTML = `
        <div class="flex items-start gap-3 justify-end">
            <div class="bg-blue-500 bg-opacity-20 rounded-lg p-3 max-w-xs">
                <div class="font-semibold text-blue-400 mb-1">You</div>
                <div class="text-sm">You rated the chatbot as: ${rating === 'yes' ? '👍 Helpful' : '👎 Not Helpful'}</div>
            </div>
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
        </div>
    `;
    messageContainer.appendChild(ratingMessage);
    
    // Add AI response with delay for better UX
    setTimeout(() => {
        const aiResponse = document.createElement('div');
        aiResponse.className = 'rating-response';
        aiResponse.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="bg-gray-600 bg-opacity-20 rounded-lg p-3 flex-1">
                    <div class="font-semibold text-blue-400 mb-1">AI Assistant</div>
                    <div class="text-sm">${rating === 'yes' ? 'Thank you! I\'m glad I could help. Feel free to ask more questions about your crops.' : 'I\'m sorry I couldn\'t help enough. Please try asking your question in a different way or provide more details about your specific issue.'}</div>
                </div>
            </div>
        `;
        messageContainer.appendChild(aiResponse);
        
        // Scroll to bottom
        messageContainer.scrollTop = messageContainer.scrollHeight;
        
        // Store rating in localStorage for analytics
        try {
            const ratings = JSON.parse(localStorage.getItem('chatbot_ratings') || '[]');
            ratings.push({
                rating: rating,
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent
            });
            localStorage.setItem('chatbot_ratings', JSON.stringify(ratings));
        } catch (e) {
            console.log('Could not save rating:', e);
        }
        
    }, 500); // 500ms delay for better UX
    
    // Scroll to bottom immediately
    messageContainer.scrollTop = messageContainer.scrollHeight;
    
    // Visual feedback on the button
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = rating === 'yes' ? '✓ Rated' : '✓ Rated';
    button.style.backgroundColor = rating === 'yes' ? '#10B981' : '#EF4444';
    button.style.color = 'white';
    
    // Reset button after 2 seconds
    setTimeout(() => {
        button.textContent = originalText;
        button.style.backgroundColor = '';
        button.style.color = '';
    }, 2000);
}

const plusBtn = document.getElementById('plusBtn');
const plusDropdown = document.getElementById('plusDropdown');

// Toggle dropdown on button click
plusBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    if (plusDropdown.classList.contains('hidden')) {
        // Show dropdown with upward animation
        plusDropdown.classList.remove('hidden');
        setTimeout(() => {
            plusDropdown.classList.add('show');
        }, 10);
    } else {
        // Hide dropdown with downward animation
        plusDropdown.classList.remove('show');
        setTimeout(() => {
            plusDropdown.classList.add('hidden');
        }, 300);
    }
});

// Hide dropdown only if clicked outside
document.addEventListener('click', function (event) {
    const isClickInsideDropdown = plusDropdown.contains(event.target);
    const isClickOnButton = plusBtn.contains(event.target);

    if (!isClickInsideDropdown && !isClickOnButton) {
        // Hide dropdown with downward animation
        plusDropdown.classList.remove('show');
        setTimeout(() => {
            plusDropdown.classList.add('hidden');
        }, 300);
    }
});
function hideDropdown() {
    const plusDropdown = document.getElementById('plusDropdown');
    if (plusDropdown && !plusDropdown.classList.contains('hidden')) {
        // Hide dropdown with downward animation
        plusDropdown.classList.remove('show');
        setTimeout(() => {
            plusDropdown.classList.add('hidden');
        }, 300);
    }
}

function toggleDropdown() {
        const dropdown = document.getElementById("dropdownMenu");
        dropdown.classList.toggle("hidden");
    }

    // Optional: hide dropdown if clicked outside
document.addEventListener("click", function(event) {
    const dropdown = document.getElementById("dropdownMenu");
    const button = document.querySelector("button[onclick='toggleDropdown()']");
    
    if (!dropdown.contains(event.target) && !button.contains(event.target)) {
        dropdown.classList.add("hidden");
    }
});


</script>
</body>
</html>