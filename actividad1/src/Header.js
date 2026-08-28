import React from 'react';
import './Header.css';

function Header() {
  return (
    <header className="header">
      <div className="logo">
        Mi Tienda
      </div>

      <nav>
        <a href="/">Inicio</a>
        <a href="/productos">Productos</a>
        <a href="/nosotros">Nosotros</a>
        <a href="/contacto">Contacto</a>
      </nav>

      <button className="btn-login">
        Iniciar sesión
      </button>
    </header>
  );
}

export default Header;
