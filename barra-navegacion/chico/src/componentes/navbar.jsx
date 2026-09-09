function Navbar() {
  const links = ['Inicio', 'Nosotros', 'Contactos'];

  return (
    <nav
      style={{
        background: '#f3f3f1',
        borderBottom: '1px solid #dfe3e8',
        boxShadow: '0 1px 2px rgba(15, 23, 42, 0.06)',
      }}
    >
      <div
        style={{
          maxWidth: '1200px',
          margin: '0 auto',
          padding: '14px 20px',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          gap: '20px',
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
          <div
            style={{
              width: '40px',
              height: '40px',
              borderRadius: '50%',
              background: '#0ea5e9',
              color: '#fff',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              fontWeight: 700,
              fontSize: '22px',
            }}
          >
            C
          </div>

          <div style={{ lineHeight: 1.1 }}>
            <div style={{ fontSize: '22px', fontWeight: 700, color: '#1d4ed8' }}>Chico</div>
            <div style={{ fontSize: '22px', fontWeight: 700, color: '#1d4ed8' }}>Insumos</div>
          </div>
        </div>

        <div style={{ display: 'flex', alignItems: 'center', gap: '26px' }}>
          {links.map((item) => (
            <a
              key={item}
              href="/"
              style={{
                color: '#1d4ed8',
                textDecoration: 'none',
                fontSize: '17px',
                fontWeight: 500,
              }}
            >
              {item}
            </a>
          ))}
        </div>

        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
          <button
            style={{
              background: '#fff',
              color: '#374151',
              border: '1px solid #d1d5db',
              borderRadius: '6px',
              padding: '10px 16px',
              fontSize: '14px',
              cursor: 'pointer',
              fontWeight: 600,
            }}
          >
            Ingresar
          </button>
          <button
            style={{
              background: '#111827',
              color: '#fff',
              border: 'none',
              borderRadius: '6px',
              padding: '10px 16px',
              fontSize: '14px',
              cursor: 'pointer',
              fontWeight: 600,
            }}
          >
            Comprar
          </button>
        </div>
      </div>
    </nav>
  );
}

export default Navbar;
