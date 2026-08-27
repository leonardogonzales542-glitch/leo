<style>
/* Chatbot Widget Styles */
.chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: 'Nunito', sans-serif;
}

.chatbot-toggle-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #f26522;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(242, 101, 34, 0.4);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.chatbot-toggle-btn:hover {
    transform: scale(1.1);
    background-color: #d9531e;
}

.chatbot-window {
    position: absolute;
    bottom: 75px;
    right: 0;
    width: 350px;
    height: 450px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    pointer-events: none;
    transition: opacity 0.3s ease, transform 0.3s ease;
    border: 1px solid #f0f0f0;
}

.chatbot-window.active {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}

.chatbot-header {
    background: linear-gradient(135deg, #16a34a, #15803d);
    color: white;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chatbot-header-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    margin: 0;
    font-size: 1.1rem;
}

.chatbot-close {
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 1.2rem;
    opacity: 0.8;
}

.chatbot-close:hover {
    opacity: 1;
}

.chatbot-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.chat-message {
    max-width: 85%;
    padding: 10px 15px;
    border-radius: 15px;
    font-size: 0.9rem;
    line-height: 1.4;
    word-wrap: break-word;
}

.chat-message.bot {
    background: white;
    color: #333;
    align-self: flex-start;
    border-bottom-left-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.chat-message.user {
    background: #f26522;
    color: white;
    align-self: flex-end;
    border-bottom-right-radius: 5px;
    box-shadow: 0 2px 5px rgba(242, 101, 34, 0.2);
}

.chatbot-input-area {
    padding: 15px;
    background: white;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 10px;
}

.chatbot-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    outline: none;
    font-size: 0.9rem;
    transition: border-color 0.3s;
}

.chatbot-input:focus {
    border-color: #16a34a;
}

.chatbot-send-btn {
    background: #16a34a;
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s;
}

.chatbot-send-btn:hover {
    background: #15803d;
}

.chatbot-send-btn:disabled {
    background: #e0e0e0;
    cursor: not-allowed;
}

/* Typing indicator */
.typing-indicator {
    display: none;
    align-items: center;
    gap: 4px;
    padding: 10px 15px;
    background: white;
    border-radius: 15px;
    align-self: flex-start;
    border-bottom-left-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.typing-indicator span {
    width: 6px;
    height: 6px;
    background-color: #16a34a;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out both;
}
.typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
.typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

@media (max-width: 480px) {
    .chatbot-window {
        width: calc(100vw - 40px);
        bottom: 75px;
        right: 0;
    }
}
</style>

<div class="chatbot-container">
    <div class="chatbot-window" id="chatbot-window">
        <div class="chatbot-header">
            <h5 class="chatbot-header-title">
                <i class="fa-solid fa-robot"></i> Asistente PetInsumos
            </h5>
            <button class="chatbot-close" id="chatbot-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="chatbot-messages" id="chatbot-messages">
            <div class="chat-message bot">
                ¡Hola! Soy el asistente virtual de PetInsumos 🐾. ¿En qué puedo ayudarte hoy? Puedes preguntarme sobre nuestros productos, precios o recomendaciones para tu mascota.
            </div>
            <div class="typing-indicator" id="chatbot-typing">
                <span></span><span></span><span></span>
            </div>
        </div>
        <div class="chatbot-input-area">
            <input type="text" id="chatbot-input" class="chatbot-input" placeholder="Escribe tu mensaje..." autocomplete="off">
            <button id="chatbot-send" class="chatbot-send-btn">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
    
    <button class="chatbot-toggle-btn" id="chatbot-toggle">
        <i class="fa-solid fa-comment-dots"></i>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbot-toggle');
    const closeBtn = document.getElementById('chatbot-close');
    const windowEl = document.getElementById('chatbot-window');
    const inputEl = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send');
    const messagesEl = document.getElementById('chatbot-messages');
    const typingIndicator = document.getElementById('chatbot-typing');

    // Conversation history for context
    let chatHistory = [];

    // Toggle chat window
    function toggleChat() {
        const isActive = windowEl.classList.contains('active');
        if (isActive) {
            windowEl.classList.remove('active');
            toggleBtn.innerHTML = '<i class="fa-solid fa-comment-dots"></i>';
        } else {
            windowEl.classList.add('active');
            toggleBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            setTimeout(() => inputEl.focus(), 300);
        }
    }

    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    // Send message on Enter
    inputEl.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Send message on button click
    sendBtn.addEventListener('click', sendMessage);

    function addMessage(text, isUser = false) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `chat-message ${isUser ? 'user' : 'bot'}`;
        
        // Escape HTML to prevent injection and cutoff
        let formattedText = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        
        // Markdown parsing
        formattedText = formattedText.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" style="color: #f26522; font-weight: bold;">$1</a>');
        formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        formattedText = formattedText.replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        // Replace newlines with <br> at the VERY END so markdown doesn't span across lines
        formattedText = formattedText.replace(/\n/g, '<br>');
        
        msgDiv.innerHTML = formattedText;
        
        messagesEl.insertBefore(msgDiv, typingIndicator);
        scrollToBottom();
    }

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    async function sendMessage() {
        const message = inputEl.value.trim();
        if (!message) return;

        inputEl.value = '';
        inputEl.disabled = true;
        sendBtn.disabled = true;

        addMessage(message, true);
        chatHistory.push({ role: 'user', content: message });

        typingIndicator.style.display = 'flex';
        scrollToBottom();

        try {
            const formData = new FormData();
            formData.append('message', message);
            formData.append('history', JSON.stringify(chatHistory.slice(-5)));

            let baseUrl = window.location.origin;
            if (window.location.pathname.includes('/tienda-insumos-main/')) {
                baseUrl += '/tienda-insumos-main';
            }

            const response = await fetch(`${baseUrl}/controllers/chatController.php`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            typingIndicator.style.display = 'none';

            if (data.status === 'success') {
                addMessage(data.reply);
                chatHistory.push({ role: 'model', content: data.reply });
            } else {
                addMessage("Lo siento, tuve un problema procesando tu consulta: " + (data.message || "Error desconocido."));
            }

        } catch (error) {
            console.error('Chatbot error:', error);
            typingIndicator.style.display = 'none';
            addMessage("Ocurrió un error al conectar con el asistente. Intenta más tarde.");
        } finally {
            inputEl.disabled = false;
            sendBtn.disabled = false;
            inputEl.focus();
        }
    }
});
</script>
