import './contactos.css';

function Contactos() {
  return (
    <main className="contactos">
      <section className="contactos__header">
        <span className="contactos__eyebrow">Contacto</span>
        <h1>Hablemos de tu próximo pedido</h1>
        <p>
          Estamos listos para ayudarte con productos, asesoramiento y entregas rápidas.
        </p>
      </section>

      <section className="contactos__grid">
        <div className="contactos__info">
          <div className="info-card">
            <span className="info-card__icon">📍</span>
            <div>
              <h3>Dirección</h3>
              <p>Av. San Martín 1425, Córdoba</p>
            </div>
          </div>

          <div className="info-card">
            <span className="info-card__icon">📞</span>
            <div>
              <h3>Teléfono</h3>
              <p>+54 9 351 456-7890</p>
            </div>
          </div>

          <div className="info-card">
            <span className="info-card__icon">✉️</span>
            <div>
              <h3>Email</h3>
              <p>ventas@chicoinsumos.com</p>
            </div>
          </div>
        </div>

        <form className="contactos__form">
          <div className="field-row">
            <label>
              Nombre
              <input type="text" placeholder="Tu nombre" />
            </label>
            <label>
              Email
              <input type="email" placeholder="tuemail@mail.com" />
            </label>
          </div>

          <label>
            Asunto
            <input type="text" placeholder="Ej: Consulta por compra mayorista" />
          </label>

          <label>
            Mensaje
            <textarea rows="6" placeholder="Escribí tu consulta..." />
          </label>

          <button type="submit" className="btn-contacto">Enviar mensaje</button>
        </form>
      </section>
    </main>
  );
}

export default Contactos;
