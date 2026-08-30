<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../auth/login.php');
    exit();
}

$titulo = 'Experto Nutricional Purina | AgriStock';
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Estilos personalizados para el Asistente -->
<style>
    .assistant-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: calc(100vh - 70px);
        font-family: 'Inter', sans-serif;
    }
    .wizard-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        transition: all 0.3s ease;
    }
    .step {
        display: none;
        animation: fadeIn 0.5s ease;
    }
    .step.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .option-btn {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
        height: 100%;
    }
    .option-btn:hover, .option-btn.selected {
        border-color: #e3000f; /* Purina Red */
        background-color: rgba(227, 0, 15, 0.05);
        transform: translateY(-2px);
    }
    .option-icon {
        font-size: 2rem;
        color: #e3000f;
        margin-bottom: 10px;
    }
    .btn-premium {
        background: linear-gradient(45deg, #e3000f, #ff4d4d);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(227, 0, 15, 0.3);
        transition: transform 0.2s;
    }
    .btn-premium:hover {
        transform: scale(1.05);
        color: white;
    }
    .progress {
        height: 8px;
        border-radius: 10px;
        background-color: #e9ecef;
    }
    .progress-bar {
        background: linear-gradient(45deg, #e3000f, #ff4d4d);
        border-radius: 10px;
    }
    .product-card {
        border-radius: 15px;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
        height: 100%;
        background: white;
    }
    .product-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #e3000f;
    }
    .badge-purina {
        background-color: #e3000f;
        color: white;
    }
    .input-premium {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        font-size: 1.1rem;
        transition: all 0.3s;
    }
    .input-premium:focus {
        border-color: #e3000f;
        box-shadow: 0 0 0 0.25rem rgba(227, 0, 15, 0.25);
    }
</style>

    <!-- Sidebar -->
    <div class="bg-dark text-white p-3 d-flex flex-column d-none d-md-flex" style="width: 260px; min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000;">
        <div class="text-center mb-4 mt-2">
            <img src="../../public/img/logo.png" alt="Purinas Bet Logo" style="height: 40px; width: auto; object-fit: contain;">
            <small class="text-muted">Panel de Cliente</small>
        </div>
        <hr class="text-secondary mt-0">
        <ul class="nav flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="dashboard.php" class="nav-link text-light hover-bg-light rounded-3" style="opacity: 0.8;"><i class="fas fa-home me-3 w-20px"></i>Inicio</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white bg-danger rounded-3 shadow-sm"><i class="fas fa-dog me-3"></i>Asistente Purina</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-light hover-bg-light rounded-3" style="opacity: 0.8;"><i class="fas fa-shopping-basket me-3"></i>Mis Pedidos</a>
            </li>
        </ul>
        <hr class="text-secondary">
        <a href="../../controllers/auth/authController.php?accion=logout" class="nav-link text-danger fw-semibold d-flex align-items-center">
            <i class="fas fa-sign-out-alt me-3"></i> Cerrar Sesión
        </a>
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1 overflow-auto assistant-container p-4" style="margin-left: 260px;">
        <div class="container max-w-800 mx-auto" style="max-width: 800px;">
            
            <!-- Inicio del Asistente -->
            <div id="intro-step" class="wizard-card p-5 text-center mt-5">
                <div class="mb-4">
                    <img src="https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Dog Food Assistant" class="rounded-circle shadow-lg mb-4" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #e3000f;">
                </div>
                <h1 class="fw-bold text-dark mb-3">Encuentra la Purina ideal para tu perro 🐶</h1>
                <p class="text-muted fs-5 mb-4">Nuestro Experto Nutricional analizará las características y necesidades únicas de tu perro para recomendarte el alimento que potenciará su salud y bienestar.</p>
                <button class="btn btn-premium fs-5 px-5 py-3" onclick="startWizard()">
                    <i class="fas fa-bone me-2"></i> Comenzar Análisis Nutricional
                </button>
            </div>

            <!-- Cuestionario Wizard -->
            <div id="wizard-container" class="wizard-card p-4 p-md-5 mt-4" style="display: none;">
                <div class="mb-4">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span id="step-indicator">Pregunta 1 de 8</span>
                        <span>Progreso</span>
                    </div>
                    <div class="progress">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 12.5%;"></div>
                    </div>
                </div>

                <!-- Step 1: Raza -->
                <div class="step active" id="step-1">
                    <h3 class="fw-bold mb-4 text-center">¿Qué raza es tu perro?</h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-muted">Puedes elegir una o escribirla:</label>
                            <select id="input-raza" class="form-select input-premium mb-3" onchange="checkTextInput('raza', this.value, 1)">
                                <option value="" selected disabled>Selecciona una raza...</option>
                                <option value="Mestizo / Criollo">Mestizo / Criollo</option>
                                <option value="Chihuahua">Chihuahua</option>
                                <option value="Poodle / Caniche">Poodle / Caniche</option>
                                <option value="Bulldog Francés">Bulldog Francés</option>
                                <option value="Labrador Retriever">Labrador Retriever</option>
                                <option value="Golden Retriever">Golden Retriever</option>
                                <option value="Pastor Alemán">Pastor Alemán</option>
                                <option value="Rottweiler">Rottweiler</option>
                                <option value="Husky Siberiano">Husky Siberiano</option>
                                <option value="Pitbull">Pitbull</option>
                                <option value="Beagle">Beagle</option>
                                <option value="Schnauzer">Schnauzer</option>
                                <option value="Cocker Spaniel">Cocker Spaniel</option>
                                <option value="Yorkshire Terrier">Yorkshire Terrier</option>
                                <option value="Pug">Pug</option>
                                <option value="Dachshund / Salchicha">Dachshund / Salchicha</option>
                                <option value="Otra">Otra raza...</option>
                            </select>
                            <input type="text" id="input-raza-otra" class="form-control input-premium d-none" placeholder="Escribe la raza de tu perro" oninput="saveTextInput('raza', this.value, 1)">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Edad exacta -->
                <div class="step" id="step-2">
                    <h3 class="fw-bold mb-4 text-center">¿Qué edad tiene aproximadamente?</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="number" id="input-edad-num" class="form-control input-premium" placeholder="Ej. 2" oninput="saveTextInput('edad', this.value, 2)">
                        </div>
                        <div class="col-md-6">
                            <select id="input-edad-tipo" class="form-select input-premium" onchange="saveTextInput('edad_tipo', this.value, 2)">
                                <option value="meses">Meses</option>
                                <option value="años">Años</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Etapa -->
                <div class="step" id="step-3">
                    <h3 class="fw-bold mb-4 text-center">¿En qué etapa de vida se encuentra?</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('etapa', 'cachorro', 3)">
                                <i class="fas fa-baby option-icon"></i>
                                <h5 class="fw-bold">Cachorro</h5>
                                <p class="text-muted small mb-0">Menos de 1 año</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('etapa', 'adulto', 3)">
                                <i class="fas fa-dog option-icon"></i>
                                <h5 class="fw-bold">Adulto</h5>
                                <p class="text-muted small mb-0">1 a 7 años</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('etapa', 'senior', 3)">
                                <i class="fas fa-glasses option-icon"></i>
                                <h5 class="fw-bold">Senior</h5>
                                <p class="text-muted small mb-0">Más de 7 años</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Tamaño -->
                <div class="step" id="step-4">
                    <h3 class="fw-bold mb-4 text-center">¿Cuál es su tamaño?</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('tamano', 'pequeno', 4)">
                                <i class="fas fa-paw option-icon" style="font-size: 1.2rem;"></i>
                                <h5 class="fw-bold">Pequeño o Mini</h5>
                                <p class="text-muted small mb-0">Hasta 10 kg</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('tamano', 'mediano', 4)">
                                <i class="fas fa-paw option-icon" style="font-size: 1.8rem;"></i>
                                <h5 class="fw-bold">Mediano</h5>
                                <p class="text-muted small mb-0">10 a 25 kg</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('tamano', 'grande', 4)">
                                <i class="fas fa-paw option-icon" style="font-size: 2.5rem;"></i>
                                <h5 class="fw-bold">Grande o Gigante</h5>
                                <p class="text-muted small mb-0">Más de 25 kg</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Peso exacto -->
                <div class="step" id="step-5">
                    <h3 class="fw-bold mb-4 text-center">¿Cuánto pesa aproximadamente? (Kg)</h3>
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="number" id="input-peso" class="form-control input-premium" placeholder="Ej. 12" oninput="saveTextInput('peso', this.value, 5)">
                                <span class="input-group-text bg-white border-start-0 text-muted">kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Actividad -->
                <div class="step" id="step-6">
                    <h3 class="fw-bold mb-4 text-center">¿Qué tan activo es tu perro?</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('actividad', 'baja', 6)">
                                <i class="fas fa-couch option-icon"></i>
                                <h5 class="fw-bold">Poco Activo</h5>
                                <p class="text-muted small mb-0">Paseos cortos, duerme mucho.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('actividad', 'media', 6)">
                                <i class="fas fa-walking option-icon"></i>
                                <h5 class="fw-bold">Actividad Media</h5>
                                <p class="text-muted small mb-0">Paseos diarios regulares.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('actividad', 'alta', 6)">
                                <i class="fas fa-running option-icon"></i>
                                <h5 class="fw-bold">Muy Activo</h5>
                                <p class="text-muted small mb-0">Deporte, correr, juegos intensos.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 7: Necesidad Especial -->
                <div class="step" id="step-7">
                    <h3 class="fw-bold mb-4 text-center">¿Tiene alguna necesidad especial de salud?</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('necesidad', 'ninguna', 7)">
                                <i class="fas fa-check-circle option-icon text-success"></i>
                                <h5 class="fw-bold">Ninguna</h5>
                                <p class="text-muted small mb-0">Perro completamente sano.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('necesidad', 'piel', 7)">
                                <i class="fas fa-hand-sparkles option-icon text-warning"></i>
                                <h5 class="fw-bold">Piel Sensible</h5>
                                <p class="text-muted small mb-0">Alergias o caída de pelo.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="option-btn" onclick="selectOption('necesidad', 'digestivo', 7)">
                                <i class="fas fa-stomach option-icon text-warning"></i>
                                <h5 class="fw-bold">Digestión Delicada</h5>
                                <p class="text-muted small mb-0">Sensibilidad estomacal.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="option-btn" onclick="selectOption('necesidad', 'sobrepeso', 7)">
                                <i class="fas fa-weight option-icon text-danger"></i>
                                <h5 class="fw-bold">Sobrepeso</h5>
                                <p class="text-muted small mb-0">Necesita dieta baja en calorías.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="option-btn" onclick="selectOption('necesidad', 'medica', 7)">
                                <i class="fas fa-notes-medical option-icon text-danger"></i>
                                <h5 class="fw-bold">Condición Médica</h5>
                                <p class="text-muted small mb-0">Renal, Urinaria, Cardíaca, etc.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 8: Objetivo -->
                <div class="step" id="step-8">
                    <h3 class="fw-bold mb-4 text-center">¿Qué objetivo buscas con su alimentación?</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="option-btn" onclick="selectOption('objetivo', 'mantenimiento', 8)">
                                <i class="fas fa-shield-alt option-icon"></i>
                                <h5 class="fw-bold">Mantenimiento y Salud</h5>
                                <p class="text-muted small mb-0">Nutrición completa diaria.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="option-btn" onclick="selectOption('objetivo', 'desarrollo', 8)">
                                <i class="fas fa-dumbbell option-icon"></i>
                                <h5 class="fw-bold">Desarrollo/Rendimiento</h5>
                                <p class="text-muted small mb-0">Músculos y energía extra.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navegación -->
                <div class="d-flex justify-content-between mt-5">
                    <button id="btn-prev" class="btn btn-outline-secondary px-4 d-none" onclick="prevStep()">Anterior</button>
                    <button id="btn-next" class="btn btn-danger px-4 ms-auto" onclick="nextStep()" disabled>Siguiente</button>
                    <button id="btn-finish" class="btn btn-premium px-4 ms-auto d-none" onclick="analyzeResults()">Obtener Recomendación <i class="fas fa-magic ms-2"></i></button>
                </div>
            </div>

            <!-- Loader de Análisis -->
            <div id="loader-container" class="wizard-card p-5 text-center mt-5" style="display: none;">
                <div class="spinner-border text-danger mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h3 class="fw-bold">Analizando perfil nutricional...</h3>
                <p class="text-muted">Buscando la línea Purina perfecta para <span id="lbl-dog-name">tu perro</span>.</p>
            </div>

            <!-- Resultados -->
            <div id="results-container" class="mt-4" style="display: none;">
                
                <!-- Alerta Médica -->
                <div id="medical-alert" class="alert alert-danger mb-4 rounded-3 border-0 shadow-sm d-none" role="alert">
                    <h5 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Alerta de Salud</h5>
                    <p class="mb-0">Has indicado que tu perro tiene una <strong>condición médica especial</strong>. La recomendación a continuación es de nuestra línea clínica (Purina Pro Plan Veterinary Diets). <strong>Es estrictamente necesario consultar a tu veterinario antes de iniciar cualquier dieta terapéutica.</strong></p>
                </div>

                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark">Recomendación para tu perro <i class="fas fa-medal text-warning"></i></h2>
                    <p class="text-muted">Basado en sus características (Raza, edad, tamaño y necesidades), este es el alimento ideal.</p>
                </div>
                
                <!-- Tarjeta Principal de Recomendación -->
                <div id="main-recommendation" class="mb-5">
                    <!-- Contenido inyectado por JS -->
                </div>

                <!-- Productos en Tienda -->
                <div class="wizard-card p-4 mt-5">
                    <h4 class="fw-bold mb-4 text-center">Encuéntralo en nuestra tienda <i class="fas fa-shopping-cart text-danger"></i></h4>
                    <div class="row g-3 justify-content-center" id="products-results">
                        <!-- Cards de productos cargadas via AJAX -->
                    </div>
                </div>

                <div class="text-center mt-5 mb-5">
                    <button class="btn btn-outline-danger rounded-pill px-4" onclick="location.reload()">
                        <i class="fas fa-redo me-2"></i> Evaluar a otro perro
                    </button>
                </div>
            </div>

        </div>
    </main>

<!-- Scripts del Asistente -->
<script src="../../public/js/asistente.js"></script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
