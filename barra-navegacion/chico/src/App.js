import React, { useState, useRef } from 'react';
import html2pdf from 'html2pdf.js';
import './App.css';

// SVG Icons
const DownloadIcon = () => (
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
);
const ArrowRightIcon = () => (
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
);
const SendIcon = () => (
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
);
const GithubIcon = () => (
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
);
const LinkedinIcon = () => (
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
);
const InstagramIcon = () => (
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
);

// Info Icons
const UserIcon = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>;
const BriefcaseIcon = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>;
const MapPinIcon = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>;
const MailIcon = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>;
const PhoneIcon = () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>;

// Skill Icons
const CodeIcon = () => <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>;
const SmartphoneIcon = () => <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>;
const DatabaseIcon = () => <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>;
const BrainIcon = () => <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 1.98-3A2.5 2.5 0 0 1 9.5 2Z"></path><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-1.98-3A2.5 2.5 0 0 0 14.5 2Z"></path></svg>;

function App() {
  // Referencia para la hoja de vida oculta
  const cvRef = useRef();

  // Estado para el formulario (Evento onChange y onSubmit)
  const [formData, setFormData] = useState({ nombre: '', email: '', mensaje: '' });
  const [enviado, setEnviado] = useState(false);

  // Evento para el botón de descargar CV (onClick) - genera PDF
  const handleDownloadCV = () => {
    const element = cvRef.current;
    
    // Opciones para la generación del PDF
    const opt = {
      margin:       0,
      filename:     'Hoja_de_Vida_Leonardo_Gonzalez.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2, useCORS: true },
      jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    // Generar el PDF
    html2pdf().set(opt).from(element).save();
  };

  // Evento para manejar cambios en los inputs
  const handleInputChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  // Evento para el envío del formulario
  const handleSubmit = (e) => {
    e.preventDefault();
    if (formData.nombre && formData.email) {
      setEnviado(true);
      setTimeout(() => setEnviado(false), 3000); // El mensaje desaparece en 3 seg
      setFormData({ nombre: '', email: '', mensaje: '' });
    } else {
      alert("Por favor, completa tu nombre y correo.");
    }
  };

  return (
    <div className="page-shell">
      {/* HEADER */}
      <header className="topbar">
        <div className="brand-block">
          <div className="brand-logo"><span className="text-white">L</span><span className="text-purple">G</span></div>
          <div className="brand-name">LeonardoDev</div>
        </div>

        <nav className="main-nav" aria-label="Navegación principal">
          <a href="#inicio" className="active">Inicio</a>
          <a href="#sobre-mi">Sobre mí</a>
          <a href="#proyectos">Proyectos</a>
          <a href="#habilidades">Habilidades</a>
          <a href="#contacto">Contacto</a>
        </nav>

        <button className="btn-outline-glow" onClick={handleDownloadCV}>
          <DownloadIcon /> Descargar CV
        </button>
      </header>

      {/* HERO SECTION */}
      <section id="inicio" className="hero-section">
        <div className="hero-content">
          <p className="hero-greeting">Hola, Soy</p>
          <h1 className="hero-title">
            <span className="text-white">Leonardo</span><br/>
            <span className="text-purple glow-text">Gonzalez Perdomo</span>
          </h1>
          <h2 className="hero-subtitle">DESARROLLADOR DE SOFTWARE</h2>
          <p className="hero-desc">
            Soy un desarrollador de software apasionado por crear soluciones web y aplicaciones modernas, eficientes y escalables. Me gusta aprender constantemente y enfrentar nuevos desafíos.
          </p>
          <div className="hero-buttons">
            <button className="btn-primary">
              <span className="btn-icon-left"><BriefcaseIcon /></span> Ver Proyectos <ArrowRightIcon />
            </button>
            <button className="btn-secondary">
              <SendIcon /> Contactarme
            </button>
          </div>
          <div className="social-links">
            <span>Sígueme en</span>
            <a href="#" className="social-icon"><GithubIcon /></a>
            <a href="#" className="social-icon"><LinkedinIcon /></a>
            <a href="#" className="social-icon"><InstagramIcon /></a>
          </div>
        </div>
        <div className="hero-graphic">
          <div className="graphic-card image-card">
            <img src="/perfil.jpg" alt="Leonardo Gonzalez Perdomo" className="profile-image" />
          </div>
        </div>
      </section>

      {/* ABOUT ME SECTION */}
      <section id="sobre-mi" className="about-section">
        <div className="section-header">
          <div className="header-dot"></div>
          <span className="header-eyebrow">SOBRE MÍ</span>
          <h2 className="section-title">Sobre Mí</h2>
        </div>

        <div className="about-grid">
          {/* Info Card */}
          <div className="info-card">
            <div className="info-item">
              <div className="info-icon"><UserIcon /></div>
              <span className="info-label">Nombre:</span>
              <span className="info-value">Leonardo Gonzalez Perdomo</span>
            </div>
            <div className="info-item">
              <div className="info-icon"><BriefcaseIcon /></div>
              <span className="info-label">Profesión:</span>
              <span className="info-value">Desarrollador de Software</span>
            </div>
            <div className="info-item">
              <div className="info-icon"><MapPinIcon /></div>
              <span className="info-label">Ubicación:</span>
              <span className="info-value">Yaguara, Huila</span>
            </div>
            <div className="info-item">
              <div className="info-icon"><MailIcon /></div>
              <span className="info-label">Correo:</span>
              <span className="info-value">leonardogonzales542@gmail.com</span>
            </div>
            <div className="info-item">
              <div className="info-icon"><PhoneIcon /></div>
              <span className="info-label">Teléfono:</span>
              <span className="info-value">3222673734</span>
            </div>
          </div>

          {/* Description Card */}
          <div className="skills-card">
            <h3 className="card-title">Sobre mí</h3>
            <p className="card-desc">
              Desarrollador de software con enfoque en el desarrollo web y móvil. Me especializo en crear soluciones modernas, funcionales y centradas en el usuario. Siempre buscando aprender nuevas tecnologías y mejorar mis habilidades.
            </p>
          </div>
        </div>
      </section>

      {/* SKILLS SECTION */}
      <section id="habilidades" className="content-section">
        <div className="section-header">
          <div className="header-dot"></div>
          <span className="header-eyebrow">HABILIDADES</span>
          <h2 className="section-title">Mi Experiencia</h2>
        </div>
        
        <div className="skills-card" style={{width: '100%', maxWidth: '900px'}}>
            <div className="skills-list">
              <div className="skill-item">
                <div className="skill-icon-wrap"><CodeIcon /></div>
                <span className="skill-pct">80%</span>
                <span className="skill-name">Desarrollo Web</span>
                <div className="skill-bar"><div className="skill-progress" style={{width: '80%'}}></div></div>
              </div>
              <div className="skill-item">
                <div className="skill-icon-wrap"><SmartphoneIcon /></div>
                <span className="skill-pct">75%</span>
                <span className="skill-name">Desarrollo Móvil</span>
                <div className="skill-bar"><div className="skill-progress" style={{width: '75%'}}></div></div>
              </div>
              <div className="skill-item">
                <div className="skill-icon-wrap"><DatabaseIcon /></div>
                <span className="skill-pct">70%</span>
                <span className="skill-name">Bases de Datos</span>
                <div className="skill-bar"><div className="skill-progress" style={{width: '70%'}}></div></div>
              </div>
              <div className="skill-item">
                <div className="skill-icon-wrap"><BrainIcon /></div>
                <span className="skill-pct">85%</span>
                <span className="skill-name">Lógica y Algoritmos</span>
                <div className="skill-bar"><div className="skill-progress" style={{width: '85%'}}></div></div>
              </div>
            </div>
        </div>
      </section>

      {/* PROJECTS SECTION */}
      <section id="proyectos" className="content-section">
        <div className="section-header">
          <div className="header-dot"></div>
          <span className="header-eyebrow">PORTAFOLIO</span>
          <h2 className="section-title">Mis Proyectos</h2>
        </div>

        <div className="projects-grid">
          {/* Project 1 */}
          <div className="project-card">
            <div className="project-img">
              <CodeIcon />
            </div>
            <div className="project-info">
              <h3 className="project-title">Sistema de Gestión</h3>
              <p className="project-desc">Plataforma web para la gestión de inventario y facturación de productos agrícolas.</p>
              <div className="project-tags">
                <span className="tag">PHP</span>
                <span className="tag">MySQL</span>
                <span className="tag">Bootstrap</span>
              </div>
            </div>
          </div>

          {/* Project 2 */}
          <div className="project-card">
            <div className="project-img">
              <SmartphoneIcon />
            </div>
            <div className="project-info">
              <h3 className="project-title">App de Pedidos</h3>
              <p className="project-desc">Aplicación móvil para realizar pedidos en restaurantes locales de manera rápida.</p>
              <div className="project-tags">
                <span className="tag">React Native</span>
                <span className="tag">Firebase</span>
              </div>
            </div>
          </div>

          {/* Project 3 */}
          <div className="project-card">
            <div className="project-img">
              <DatabaseIcon />
            </div>
            <div className="project-info">
              <h3 className="project-title">Dashboard Analítico</h3>
              <p className="project-desc">Panel de control interactivo para visualizar métricas de ventas y rendimiento.</p>
              <div className="project-tags">
                <span className="tag">React</span>
                <span className="tag">Node.js</span>
                <span className="tag">CSS</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CONTACT SECTION */}
      <section id="contacto" className="content-section">
        <div className="section-header">
          <div className="header-dot"></div>
          <span className="header-eyebrow">CONTACTO</span>
          <h2 className="section-title">Trabajemos Juntos</h2>
        </div>

        <div className="contact-container">
          <form className="contact-form" onSubmit={handleSubmit}>
            {enviado && <div style={{backgroundColor: '#4ade80', color: '#000', padding: '10px', borderRadius: '5px', marginBottom: '15px', textAlign: 'center'}}>¡Mensaje enviado con éxito!</div>}
            <div className="form-group">
              <label className="form-label">Nombre</label>
              <input type="text" name="nombre" value={formData.nombre} onChange={handleInputChange} className="form-input" placeholder="Tu nombre completo" />
            </div>
            <div className="form-group" style={{marginTop: '15px'}}>
              <label className="form-label">Correo Electrónico</label>
              <input type="email" name="email" value={formData.email} onChange={handleInputChange} className="form-input" placeholder="tucorreo@ejemplo.com" />
            </div>
            <div className="form-group" style={{marginTop: '15px'}}>
              <label className="form-label">Mensaje</label>
              <textarea name="mensaje" value={formData.mensaje} onChange={handleInputChange} className="form-textarea" placeholder="¿En qué puedo ayudarte?"></textarea>
            </div>
            <button type="submit" className="btn-primary submit-btn" style={{width: '100%'}}>
              <SendIcon /> Enviar Mensaje
            </button>
          </form>
        </div>
      </section>

      {/* FOOTER */}
      <footer className="footer">
        <p>© 2026 Leonardo Gonzalez Perdomo. Todos los derechos reservados.</p>
      </footer>

      {/* PLANTILLA DE HOJA DE VIDA (OCULTA EN PANTALLA, USADA PARA EXPORTAR PDF) */}
      <div style={{ position: 'absolute', top: '-9999px', left: '-9999px' }}>
        <div ref={cvRef} style={{ width: '210mm', minHeight: '297mm', background: '#ffffff', display: 'flex', fontFamily: 'Arial, sans-serif' }}>
          
          {/* Columna Izquierda (Sidebar) */}
          <div style={{ width: '35%', backgroundColor: '#1e293b', padding: '40px 30px', color: '#ffffff' }}>
            {/* Foto de perfil */}
            <div style={{ width: '130px', height: '130px', borderRadius: '50%', backgroundColor: '#334155', border: '3px solid #64748b', margin: '0 auto 30px', display: 'flex', justifyContent: 'center', alignItems: 'center', fontSize: '30px' }}>LG</div>
            
            <h3 style={{ fontSize: '18px', borderBottom: '2px solid #3b82f6', paddingBottom: '8px', marginBottom: '20px', textTransform: 'uppercase' }}>Contacto</h3>
            <ul style={{ listStyle: 'none', padding: 0, margin: '0 0 30px 0', fontSize: '14px', lineHeight: '1.8', color: '#cbd5e1' }}>
              <li>📍 Yaguara, Huila</li>
              <li>📞 3222673734</li>
              <li>📧 leonardogonzales542@<br/>gmail.com</li>
            </ul>

            <h3 style={{ fontSize: '18px', borderBottom: '2px solid #3b82f6', paddingBottom: '8px', marginBottom: '20px', textTransform: 'uppercase' }}>Habilidades</h3>
            <ul style={{ listStyle: 'none', padding: 0, margin: '0 0 30px 0', fontSize: '14px', lineHeight: '1.8', color: '#cbd5e1' }}>
              <li>✔ Desarrollo Web (80%)</li>
              <li>✔ Desarrollo Móvil (75%)</li>
              <li>✔ Bases de Datos (70%)</li>
              <li>✔ Lógica y Algoritmos (85%)</li>
            </ul>

            <h3 style={{ fontSize: '18px', borderBottom: '2px solid #3b82f6', paddingBottom: '8px', marginBottom: '20px', textTransform: 'uppercase' }}>Tecnologías</h3>
            <ul style={{ listStyle: 'none', padding: 0, margin: '0 0 30px 0', fontSize: '14px', lineHeight: '1.8', color: '#cbd5e1' }}>
              <li>React & React Native</li>
              <li>PHP & MySQL</li>
              <li>Node.js</li>
              <li>Bootstrap & CSS</li>
            </ul>
          </div>

          {/* Columna Derecha (Principal) */}
          <div style={{ width: '65%', padding: '40px', backgroundColor: '#ffffff', color: '#334155' }}>
            <div style={{ marginBottom: '40px' }}>
              <h1 style={{ fontSize: '38px', color: '#1e293b', margin: '0 0 5px 0', letterSpacing: '-1px' }}>LEONARDO GONZALEZ</h1>
              <h2 style={{ fontSize: '20px', color: '#3b82f6', margin: 0, fontWeight: 500 }}>DESARROLLADOR DE SOFTWARE</h2>
            </div>

            <div style={{ marginBottom: '35px' }}>
              <h3 style={{ fontSize: '22px', color: '#1e293b', margin: '0 0 15px 0', borderBottom: '2px solid #e2e8f0', paddingBottom: '8px' }}>Perfil Profesional</h3>
              <p style={{ fontSize: '14px', lineHeight: '1.6', margin: 0 }}>
                Desarrollador de software con enfoque en el desarrollo web y móvil. Me especializo en crear soluciones modernas, funcionales y centradas en el usuario. Siempre buscando aprender nuevas tecnologías y mejorar mis habilidades. Apasionado por crear aplicaciones eficientes y escalables, y enfrentar nuevos desafíos.
              </p>
            </div>

            <div style={{ marginBottom: '35px' }}>
              <h3 style={{ fontSize: '22px', color: '#1e293b', margin: '0 0 20px 0', borderBottom: '2px solid #e2e8f0', paddingBottom: '8px' }}>Proyectos Destacados</h3>
              
              <div style={{ marginBottom: '20px' }}>
                <h4 style={{ fontSize: '16px', color: '#1e293b', margin: '0 0 5px 0' }}>Sistema de Gestión (AgriStock)</h4>
                <p style={{ fontSize: '14px', color: '#64748b', margin: '0 0 5px 0' }}>Plataforma web para la gestión de inventario y facturación de productos agrícolas.</p>
                <div style={{ fontSize: '12px', fontWeight: 'bold', color: '#3b82f6' }}>PHP | MySQL | Bootstrap</div>
              </div>

              <div style={{ marginBottom: '20px' }}>
                <h4 style={{ fontSize: '16px', color: '#1e293b', margin: '0 0 5px 0' }}>App de Pedidos</h4>
                <p style={{ fontSize: '14px', color: '#64748b', margin: '0 0 5px 0' }}>Aplicación móvil para realizar pedidos en restaurantes locales de manera rápida.</p>
                <div style={{ fontSize: '12px', fontWeight: 'bold', color: '#3b82f6' }}>React Native | Firebase</div>
              </div>

              <div style={{ marginBottom: '20px' }}>
                <h4 style={{ fontSize: '16px', color: '#1e293b', margin: '0 0 5px 0' }}>Dashboard Analítico</h4>
                <p style={{ fontSize: '14px', color: '#64748b', margin: '0 0 5px 0' }}>Panel de control interactivo para visualizar métricas de ventas y rendimiento.</p>
                <div style={{ fontSize: '12px', fontWeight: 'bold', color: '#3b82f6' }}>React | Node.js | CSS</div>
              </div>
            </div>
            
          </div>
        </div>
      </div>

    </div>
  );
}

export default App;
