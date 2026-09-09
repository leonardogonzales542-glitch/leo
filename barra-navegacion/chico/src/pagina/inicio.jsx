import './inicio.css';

function Inicio() {
  return (
    <main className="inicio">
      <section className="inicio__hero">
        <div className="inicio__content">
          <span className="inicio__eyebrow">Tienda de insumos</span>
          <h1>Todo lo que tu negocio necesita, en un solo lugar.</h1>
          <p>
            Soluciones para almacenes, ferreterías, comercios y emprendimientos.
            Calidad, precio justo y atención rápida para que siempre puedas seguir creciendo.
          </p>

          <div className="inicio__actions">
            <button className="btn btn--primary">Ver productos</button>
            <button className="btn btn--secondary">Consultar precio</button>
          </div>

          <div className="inicio__stats">
            <div>
              <strong>+5.000</strong>
              <span>Productos</span>
            </div>
            <div>
              <strong>1.200+</strong>
              <span>Clientes</span>
            </div>
            <div>
              <strong>24h</strong>
              <span>Atención</span>
            </div>
          </div>
        </div>

        <div className="inicio__card">
          <div className="inicio__badge">Más vendido</div>
          <h3>Kit de ferretería esencial</h3>
          <ul>
            <li>Taladros profesionales</li>
            <li>Herramientas de precisión</li>
            <li>Accesorios y repuestos</li>
          </ul>
          <div className="inicio__price">
            <span>Desde</span>
            <strong>$49.990</strong>
          </div>
        </div>
      </section>

      <section className="inicio__features">
        <article className="feature-card">
          <div className="feature-card__icon">⚡</div>
          <h4>Entrega rápida</h4>
          <p>Despachos ágiles para que tus pedidos lleguen cuando los necesitás.</p>
        </article>

        <article className="feature-card">
          <div className="feature-card__icon">🛡️</div>
          <h4>Calidad garantizada</h4>
          <p>Trabajamos con marcas confiables y productos para uso diario.</p>
        </article>

        <article className="feature-card">
          <div className="feature-card__icon">💬</div>
          <h4>Atención personalizada</h4>
          <p>Te asesoramos para elegir la mejor opción según tus necesidades.</p>
        </article>
      </section>
    </main>
  );
}

export default Inicio;
