import React from 'react';
import './App.css';  
import miImagen from './candelaria.svg';
import Header from './Header';
import Footer from './Footer';


function App() {
  return (
    <>
      <Header />
      <div className="body">
        <div className="hero">
          <section className="App-content">
            <div className="image-container">
              <img src={miImagen} alt="La Candelaria" className="profile-image" />
              <p className="image-label">La Candelaria</p>
            </div>
            <h2>Hola, soy Leonardo<span className="underline"></span></h2>
            <p className="description">
              Soy un entusiasta de la programación y me encanta aprender nuevas tecnologías. 
              Estoy aprendiendo React y disfrutando el proceso.
            </p>
            <h3>Contacto</h3>
            <ul className="contact-list">
              <li><a href="mailto:leonardogonzalez@gmail.com">Correo electrónico: leonardogonzalez@gmail.com</a></li>
              <li>LinkedIn: <a href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer">Mi LinkedIn</a></li>
              <li>GitHub: <a href="https://github.com" target="_blank" rel="noopener noreferrer">Mi GitHub</a></li>
            </ul>
          </section>
        </div>
      </div>
      <Footer />
    </>
  );
}

export default App;
