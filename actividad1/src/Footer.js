import React from 'react';
import './Footer.css';

function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="footer">
      <p>Crafted with <span className="heart">♥</span> using React</p>
      <p>&copy; {currentYear} Leonardo González. Todos los derechos reservados.</p>
      <p>Crafted with <span className="heart">♥</span> using React</p>
    </footer>
  );
}

export default Footer;
