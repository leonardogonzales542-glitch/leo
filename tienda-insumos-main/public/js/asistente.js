// Base de datos de Líneas de Purina
const purinaDB = [
    {
        id: "pp_puppy_small",
        name: "Purina Pro Plan Puppy Razas Pequeñas",
        brand: "Purina Pro Plan",
        stage: "Cachorro",
        size: "pequeno",
        benefits: "Contiene calostro para un sistema inmunológico fuerte y DHA para el desarrollo visual y cerebral.",
        presentations: "1 kg, 3 kg, 7.5 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Puppy%20Razas%20Peque%C3%B1as.png?itok=yE_qP5_b",
        alt_name: "Dog Chow Cachorros Minis y Pequeños",
        match: (ans) => ans.etapa === 'cachorro' && ans.tamano === 'pequeno' && ans.necesidad === 'ninguna'
    },
    {
        id: "pp_puppy_large",
        name: "Purina Pro Plan Puppy Razas Medianas y Grandes",
        brand: "Purina Pro Plan",
        stage: "Cachorro",
        size: ["mediano", "grande"],
        benefits: "Optimiza el crecimiento de huesos largos y fortalece el sistema inmunológico con anticuerpos naturales.",
        presentations: "3 kg, 15 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Puppy%20Razas%20Medianas.png?itok=oQyB3lU-",
        alt_name: "Dog Chow Cachorros Medianos y Grandes",
        match: (ans) => ans.etapa === 'cachorro' && (ans.tamano === 'mediano' || ans.tamano === 'grande') && ans.necesidad === 'ninguna'
    },
    {
        id: "pp_adult_small",
        name: "Purina Pro Plan Adult Razas Pequeñas",
        brand: "Purina Pro Plan",
        stage: "Adulto",
        size: "pequeno",
        benefits: "Croqueta pequeña con OptiHealth. Ayuda a mantener una salud óptima y buena higiene dental.",
        presentations: "1 kg, 3 kg, 7.5 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Adult%20Razas%20Peque%C3%B1as.png?itok=O4Pz_Zl7",
        alt_name: "Dog Chow Adultos Minis y Pequeños",
        match: (ans) => ans.etapa === 'adulto' && ans.tamano === 'pequeno' && ans.necesidad === 'ninguna'
    },
    {
        id: "pp_adult_large",
        name: "Purina Pro Plan Adult Razas Medianas y Grandes",
        brand: "Purina Pro Plan",
        stage: "Adulto",
        size: ["mediano", "grande"],
        benefits: "Fórmula de alta digestibilidad con prebióticos, mantiene músculos fuertes y cuida las articulaciones.",
        presentations: "3 kg, 15 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Adult%20Razas%20Medianas.png?itok=7U5JzBf4",
        alt_name: "Dog Chow Adultos Medianos y Grandes",
        match: (ans) => ans.etapa === 'adulto' && (ans.tamano === 'mediano' || ans.tamano === 'grande') && ans.necesidad === 'ninguna'
    },
    {
        id: "pp_sensitive_skin",
        name: "Purina Pro Plan Sensitive Skin",
        brand: "Purina Pro Plan",
        stage: "Cualquiera",
        size: "Cualquiera",
        benefits: "Proteína alternativa (Salmón) y Omega 3 y 6 para calmar la irritación de la piel y embellecer el pelaje.",
        presentations: "3 kg, 7.5 kg, 15 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Sensitive%20Skin%20Razas%20Medianas%20y%20Grandes.png?itok=zQ-4p4n-",
        alt_name: "Excellent Skin Care",
        match: (ans) => ans.necesidad === 'piel'
    },
    {
        id: "pp_sensitive_digestion",
        name: "Purina Pro Plan Sensitive Digestion",
        brand: "Purina Pro Plan",
        stage: "Cualquiera",
        size: "Cualquiera",
        benefits: "Hecho con cordero. Promueve un equilibrio intestinal saludable y mejora la calidad de las heces.",
        presentations: "3 kg, 15 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Sensitive%20Digestion%20Razas%20Medianas%20y%20Grandes.png?itok=tG9BfO-A",
        alt_name: "Excellent Adulto Cordero",
        match: (ans) => ans.necesidad === 'digestivo'
    },
    {
        id: "pp_reduced_calorie",
        name: "Purina Pro Plan Reduced Calorie",
        brand: "Purina Pro Plan",
        stage: "Adulto",
        size: "Cualquiera",
        benefits: "20% menos calorías. Ayuda a perder peso manteniendo la masa muscular gracias a su alto nivel de proteínas.",
        presentations: "3 kg, 15 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Reduced%20Calorie.png?itok=qYlO9D2W",
        alt_name: "Dog Chow Sano y en Forma",
        match: (ans) => ans.necesidad === 'sobrepeso'
    },
    {
        id: "pp_active_mind",
        name: "Purina Pro Plan Active Mind 7+ (Senior)",
        brand: "Purina Pro Plan",
        stage: "Senior",
        size: "Cualquiera",
        benefits: "Nutrición avanzada con aceites botánicos que nutren el cerebro y mantienen a tu perro activo y alerta en su vejez.",
        presentations: "3 kg, 15 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Active%20Mind%20Razas%20Medianas%20y%20Grandes.png?itok=38sK_J4x",
        alt_name: "Dog Chow Adultos Mayores",
        match: (ans) => ans.etapa === 'senior' && ans.necesidad === 'ninguna'
    },
    {
        id: "pp_performance",
        name: "Purina Pro Plan Performance",
        brand: "Purina Pro Plan",
        stage: "Adulto",
        size: "Cualquiera",
        benefits: "Alta concentración de energía y proteínas (30/20) para perros atléticos, de trabajo o muy activos.",
        presentations: "15 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Performance.png?itok=5yqW5e3n",
        alt_name: "Dog Chow Extralife Activo",
        match: (ans) => ans.actividad === 'alta' && ans.objetivo === 'desarrollo' && ans.necesidad === 'ninguna'
    },
    {
        id: "vd_renal",
        name: "Purina Pro Plan Veterinary Diets - NF (Renal)",
        brand: "Purina Veterinary Diets",
        stage: "Todas",
        size: "Cualquiera",
        benefits: "Dieta terapéutica baja en fósforo y proteínas restringidas para apoyar el manejo de insuficiencia renal crónica.",
        presentations: "2.5 kg, 10 kg",
        image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Pro%20Plan%C2%AE%20Veterinary%20Diets%20NF%20Kidney%20Function%20Canine%20Formula.png?itok=bZ4x-Q_X",
        alt_name: "Consulta siempre con tu Veterinario",
        match: (ans) => ans.necesidad === 'medica'
    }
];

// Fallback recommendation
const defaultPurina = {
    id: "dog_chow_adult",
    name: "Purina Dog Chow Adultos (Salud Integral)",
    brand: "Purina Dog Chow",
    stage: "Adulto",
    size: "Todas",
    benefits: "Nutrición 100% balanceada con ExtraLife para maximizar la calidad de vida de tu perro día a día.",
    presentations: "2 kg, 4 kg, 21 kg",
    image: "https://www.purina-latam.com/sites/g/files/auxxlc391/files/styles/kraken_generic_max_width_960/public/2021-10/Purina%C2%AE%20Dog%20Chow%C2%AE%20Adultos%20Razas%20Medianas%20y%20Grandes.png?itok=4E5C0s9R",
    alt_name: "Purina Campeón Adultos"
};

// Estado de las respuestas
let answers = {
    raza: '',
    edad: '',
    edad_tipo: 'meses',
    etapa: '',
    tamano: '',
    peso: '',
    actividad: '',
    necesidad: '',
    objetivo: ''
};

let currentStep = 1;
const totalSteps = 8;

// Event Listeners y Navegación
function startWizard() {
    document.getElementById('intro-step').style.display = 'none';
    document.getElementById('wizard-container').style.display = 'block';
}

function checkTextInput(category, value, step) {
    if (value === 'Otra') {
        document.getElementById('input-raza-otra').classList.remove('d-none');
        answers[category] = '';
    } else {
        document.getElementById('input-raza-otra').classList.add('d-none');
        saveTextInput(category, value, step);
    }
}

function saveTextInput(category, value, step) {
    answers[category] = value;
    checkStepCompletion(step);
}

function selectOption(category, value, step) {
    answers[category] = value;
    
    // UI Update
    const stepEl = document.getElementById(`step-${step}`);
    const btns = stepEl.querySelectorAll('.option-btn');
    btns.forEach(btn => btn.classList.remove('selected'));
    
    // Encontrar el botón clickeado
    event.currentTarget.classList.add('selected');
    
    checkStepCompletion(step);
    
    // Avanzar automáticamente si es click en botón
    setTimeout(() => nextStep(), 400);
}

function checkStepCompletion(step) {
    let isComplete = false;
    
    if (step === 1) isComplete = answers.raza.trim() !== '';
    if (step === 2) isComplete = answers.edad.trim() !== '';
    if (step === 3) isComplete = answers.etapa !== '';
    if (step === 4) isComplete = answers.tamano !== '';
    if (step === 5) isComplete = answers.peso.trim() !== '';
    if (step === 6) isComplete = answers.actividad !== '';
    if (step === 7) isComplete = answers.necesidad !== '';
    if (step === 8) isComplete = answers.objetivo !== '';

    const btnNext = document.getElementById('btn-next');
    if (step < totalSteps) {
        btnNext.disabled = !isComplete;
    } else {
        btnNext.classList.add('d-none');
        document.getElementById('btn-finish').classList.toggle('d-none', !isComplete);
    }
}

function nextStep() {
    if (currentStep < totalSteps) {
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        currentStep++;
        document.getElementById(`step-${currentStep}`).classList.add('active');
        updateProgress();
        checkStepCompletion(currentStep); // Re-check para el nuevo paso
    }
}

function prevStep() {
    if (currentStep > 1) {
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        currentStep--;
        document.getElementById(`step-${currentStep}`).classList.add('active');
        updateProgress();
        
        if (currentStep !== totalSteps) {
            document.getElementById('btn-finish').classList.add('d-none');
            document.getElementById('btn-next').classList.remove('d-none');
        }
        checkStepCompletion(currentStep);
    }
}

function updateProgress() {
    const percentage = (currentStep / totalSteps) * 100;
    document.getElementById('progress-bar').style.width = `${percentage}%`;
    document.getElementById('step-indicator').innerText = `Pregunta ${currentStep} de ${totalSteps}`;
    
    if (currentStep > 1) {
        document.getElementById('btn-prev').classList.remove('d-none');
    } else {
        document.getElementById('btn-prev').classList.add('d-none');
    }
}

// Lógica de Recomendación
function analyzeResults() {
    document.getElementById('wizard-container').style.display = 'none';
    document.getElementById('loader-container').style.display = 'block';
    
    // Obtener recomendación experta
    const match = findBestPurinaMatch();
    
    setTimeout(() => {
        renderRecommendation(match);
        fetchStoreProducts(match.name);
        
        document.getElementById('loader-container').style.display = 'none';
        document.getElementById('results-container').style.display = 'block';
    }, 2500); // Simulando el análisis "IA"
}

function findBestPurinaMatch() {
    // Buscar la primera dieta que cumpla las reglas en el orden estricto
    for (let product of purinaDB) {
        if (product.match && product.match(answers)) {
            return product;
        }
    }
    return defaultPurina;
}

function renderRecommendation(product) {
    const container = document.getElementById('main-recommendation');
    const isMedical = answers.necesidad === 'medica';
    
    if (isMedical) {
        document.getElementById('medical-alert').classList.remove('d-none');
    }

    const html = `
    <div class="card wizard-card border-0 overflow-hidden" style="border: 2px solid #e3000f !important;">
        <div class="row g-0">
            <div class="col-md-4 bg-white d-flex align-items-center justify-content-center p-4">
                <img src="${product.image}" class="img-fluid" alt="${product.name}" style="max-height: 300px; object-fit: contain;">
            </div>
            <div class="col-md-8">
                <div class="card-body p-4 p-md-5">
                    <span class="badge badge-purina px-3 py-2 fs-6 mb-3">${product.brand}</span>
                    <h2 class="fw-bold text-dark mb-3">${product.name}</h2>
                    
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-light text-dark border"><i class="fas fa-history me-1"></i> ${product.stage}</span>
                        <span class="badge bg-light text-dark border"><i class="fas fa-paw me-1"></i> Tamaño: ${Array.isArray(product.size) ? product.size.join(' y ') : product.size}</span>
                    </div>

                    <p class="text-muted fs-5 mb-4">${product.benefits}</p>
                    
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="fw-bold"><i class="fas fa-box text-danger me-2"></i> Presentaciones:</h6>
                            <p class="text-muted">${product.presentations}</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="fw-bold"><i class="fas fa-sync text-danger me-2"></i> Excelente alternativa:</h6>
                            <p class="text-muted">${product.alt_name}</p>
                        </div>
                    </div>

                    <div class="alert alert-light border shadow-sm">
                        <i class="fas fa-lightbulb text-warning me-2"></i> <strong>¿Por qué lo recomendamos?</strong>
                        <br>Porque tu perro es un <strong>${answers.raza}</strong> en etapa <strong>${answers.etapa}</strong>, con un peso de <strong>${answers.peso}kg</strong> y un nivel de actividad <strong>${answers.actividad}</strong>. Esta fórmula de Purina cumple con todos sus requerimientos metabólicos diarios.
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;
    
    container.innerHTML = html;
}

function fetchStoreProducts(recommendedName) {
    // Usar la palabra clave más fuerte para buscar en el inventario real
    const keyword = answers.etapa === 'cachorro' ? 'puppy' : (answers.necesidad === 'sobrepeso' ? 'peso' : 'purina');
    
    fetch(`../../controllers/AsistenteController.php?action=get_productos_recomendados&kw=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('products-results');
            container.innerHTML = '';
            
            if (data.success && data.data.length > 0) {
                // Limitar a 4 productos en pantalla
                const productsToShow = data.data.slice(0, 4);
                productsToShow.forEach(prod => {
                    const html = `
                    <div class="col-6 col-md-3">
                        <div class="card product-card text-center p-3">
                            <img src="${prod.imagen_url}" class="card-img-top mx-auto mb-3" alt="${prod.nombre}" style="height: 140px; width: auto; object-fit: contain;">
                            <div class="card-body p-0 d-flex flex-column">
                                <h6 class="fw-bold mb-2 text-truncate" title="${prod.nombre}" style="font-size: 0.9rem;">${prod.nombre}</h6>
                                <p class="text-danger fw-bold fs-5 mb-3">$${parseFloat(prod.precio_venta).toFixed(2)}</p>
                                <a href="dashboard.php" class="btn btn-outline-danger w-100 rounded-pill mt-auto fw-bold"><i class="fas fa-shopping-cart me-1"></i> Comprar</a>
                            </div>
                        </div>
                    </div>
                    `;
                    container.innerHTML += html;
                });
            } else {
                container.innerHTML = `
                <div class="col-12 text-center text-muted py-4">
                    <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                    <h5>No encontramos stock exacto de este producto en línea en este momento.</h5>
                    <p>Por favor, revisa directamente en el catálogo completo o contáctanos.</p>
                    <a href="dashboard.php" class="btn btn-danger px-4 mt-2">Ver Catálogo Completo</a>
                </div>`;
            }
        })
        .catch(error => {
            console.error('Error fetching store products:', error);
        });
}
