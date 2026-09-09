document.addEventListener('DOMContentLoaded', () => {
    // Referencias a los elementos del DOM
    const chatToggle = document.getElementById('chatToggle');
    const closeChat = document.getElementById('closeChat');
    const chatWindow = document.getElementById('chatWindow');
    const chatInput = document.getElementById('chatInput');
    const sendMessage = document.getElementById('sendMessage');
    const chatBody = document.getElementById('chatBody');

    // Toggle para abrir/cerrar el chat
    chatToggle.addEventListener('click', () => {
        chatWindow.classList.add('active');
        // Pequeño timeout para que el foco no interfiera con la animación
        setTimeout(() => chatInput.focus(), 300);
    });

    closeChat.addEventListener('click', () => {
        chatWindow.classList.remove('active');
    });

    // Función para enviar mensaje
    const handleSendMessage = () => {
        const text = chatInput.value.trim();
        if (!text) return;

        // Añadir mensaje del usuario
        addMessage(text, 'user');
        chatInput.value = '';

        // Simular que el bot escribe
        showTypingIndicator();

        // Respuesta del bot después de un breve retraso
        setTimeout(() => {
            removeTypingIndicator();
            const botResponse = getBotResponse(text);
            addMessage(botResponse, 'bot');
        }, 1500);
    };

    // Escuchar el clic en el botón de enviar
    sendMessage.addEventListener('click', handleSendMessage);

    // Escuchar la tecla Enter
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            handleSendMessage();
        }
    });

    // Función para añadir mensaje al DOM
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('chat-message', sender);
        
        const contentDiv = document.createElement('div');
        contentDiv.classList.add('message-content');
        contentDiv.innerHTML = text; // Usamos innerHTML para permitir negritas en la respuesta

        messageDiv.appendChild(contentDiv);
        chatBody.appendChild(messageDiv);
        
        // Auto-scroll hacia abajo
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Función para mostrar el indicador de escritura
    function showTypingIndicator() {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('chat-message', 'bot', 'typing-element');
        
        const contentDiv = document.createElement('div');
        contentDiv.classList.add('message-content', 'typing-indicator');
        
        contentDiv.innerHTML = '<span></span><span></span><span></span>';

        messageDiv.appendChild(contentDiv);
        chatBody.appendChild(messageDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Función para quitar el indicador de escritura
    function removeTypingIndicator() {
        const typingElement = chatBody.querySelector('.typing-element');
        if (typingElement) {
            typingElement.remove();
        }
    }

    // Lógica simple de respuesta del bot
    function getBotResponse(input) {
        const text = input.toLowerCase();
        
        if (text.includes('experiencia') || text.includes('trabajo')) {
            return 'Leonardo es Desarrollador de Software con experiencia en la creación de interfaces web modernas y responsivas usando React.js, Tailwind CSS y Vanilla JS. ¡Siempre enfocado en código limpio!';
        }
        
        if (text.includes('habilidad') || text.includes('tecnologia') || text.includes('stack')) {
            return 'Sus principales habilidades incluyen: <strong>React.js, Tailwind CSS, JavaScript (ES6+), HTML5, CSS3, Git y GitHub</strong>. También tiene conocimientos básicos en Node.js.';
        }
        
        if (text.includes('contacto') || text.includes('email') || text.includes('telefono') || text.includes('correo')) {
            return 'Puedes contactarlo a través del correo: <strong>leonardogonzales542@gmail.com</strong> o al teléfono <strong>+57 322 267 3734</strong>.';
        }
        
        if (text.includes('hola') || text.includes('saludos') || text.includes('buenos dias') || text.includes('buenas tardes')) {
            return '¡Hola! Qué gusto saludarte. ¿Te gustaría saber sobre la experiencia de Leonardo, sus habilidades o cómo contactarlo?';
        }

        if (text.includes('gracias')) {
            return '¡Con mucho gusto! Si tienes alguna otra duda, aquí estaré.';
        }
        
        return 'Interesante... Como soy un bot muy sencillo, no entiendo exactamente lo que dices. Puedes preguntarme sobre <strong>experiencia</strong>, <strong>habilidades</strong> o <strong>contacto</strong>.';
    }
});
