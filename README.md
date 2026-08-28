# AgriStock - Sistema de Insumos Agrícolas

AgriStock es una aplicación web moderna y premium diseñada para la gestión de insumos agrícolas. Cuenta con un diseño responsivo de alta calidad y un sistema robusto de autenticación.

## 🚀 Características Principales

- **Diseño Premium**: Interfaz moderna y adaptable construida con Bootstrap 5, Bootstrap Icons, fuentes personalizadas y hojas de estilo a medida.
- **Autenticación Completa**: Flujo seguro para el registro e inicio de sesión de usuarios.
- **Alertas Premium**: Integración directa del framework **SweetAlert2** en los controladores para mostrar mensajes elegantes e interactivos (éxito, campos obligatorios, correos duplicados, etc.).
- **Dashboard de Administrador**: Panel de control con métricas clave, estado de sistema interactivo, accesos rápidos responsivos, barra de navegación superior degradada y menú lateral (sidebar) premium con agrupamiento de menús y tarjeta de perfil de usuario integrada.
- **Arquitectura MVC Limpia**: Separación clara de responsabilidades con Controladores, Modelos y Vistas en PHP.

---

## 📁 Estructura del Proyecto

El proyecto está organizado de la siguiente manera:

```text
├── .agents/
│   └── AGENTS.md                # Reglas y contexto del proyecto para agentes de IA (Oculto)
├── config/
│   └── database.php             # Configuración y conexión a la base de datos (MySQLi)
├── controllers/
│   ├── auth/
│   │   ├── authController.php     # Controlador de inicio y cierre de sesión
│   │   └── registerController.php # Controlador de registro público
│   ├── RegisterProveedorController.php # Controlador de registro de proveedores
│   └── registerControllerAdmin.php # Controlador de registro desde el panel de admin
├── models/
│   ├── proveedor.php            # Modelo de Proveedores
│   ├── Usuario.php              # Modelo de Usuario (Registro, estados, roles, etc.)
│   └── rol.php                  # Modelo de Rol (Obtención de roles de la DB)
├── views/
│   ├── admin/
│   │   ├── dashboard.php        # Dashboard principal de administración
│   │   ├── gusuarios.php        # Interfaz de gestión de usuarios
│   │   └── proveedores.php      # Interfaz de gestión de proveedores
│   ├── auth/
│   │   ├── login.php            # Vista premium de inicio de sesión
│   │   └── register.php         # Vista premium de registro de usuario
│   └── layouts/
│       ├── header.php           # Cabecera global con carga de estilos y favicon
│       ├── footer.php           # Pie de página responsivo y scripts de Bootstrap 5
│       └── sidebaradmin.php     # Menú lateral premium responsivo con perfil de usuario
├── public/
│   ├── index.php                # Landing page principal
│   └── css/
│       └── style.css            # Estilos CSS personalizados (AgriStock Premium)
├── sql/
│   └── bdtienda.sql             # Script de base de datos
└── Documentacion Logica.md/     # Documentación técnica interna
```

---

## 🛠️ Requisitos e Instalación en Laragon

### 1. Requisitos Previos
* Tener instalado **Laragon** en tu equipo.
* PHP 7.4 o superior habilitado en Laragon.
* MySQL habilitado en Laragon.

### 2. Configuración del Proyecto en Laragon
1. Clona o mueve la carpeta del proyecto `tienda` dentro del directorio raíz de Laragon:
   ```text
   C:\laragon\www\tienda
   ```
2. Inicia todos los servicios en Laragon haciendo clic en **"Iniciar Todo"** (*Start All*).
3. Laragon creará y configurará automáticamente el host virtual local accesible desde:
   * **http://localhost/tienda**  o  **http://tienda.test**

### 3. Configuración de la Base de Datos
1. Abre la base de datos haciendo clic en **"Base de Datos"** (*Database*) en Laragon (esto abrirá HeidiSQL por defecto).
2. Crea una nueva base de datos llamada `tiendadb`.
3. Haz clic derecho sobre `tiendadb`, selecciona **Cargar archivo SQL...** y selecciona el archivo ubicado en:
   ```text
   C:\laragon\www\tienda\sql\bdtienda.sql
   ```
4. Ejecuta el archivo SQL para crear las tablas e insertar los datos iniciales.

### 4. Configuración de Conexión en PHP
Abre el archivo [database.php](file:///c:/laragon/www/tienda/config/database.php) y verifica que las credenciales de conexión coincidan con las de Laragon (por defecto el usuario de MySQL es `root` y no tiene contraseña):
```php
$host = "127.0.0.1";
$user = "root";
$password = "";
$bd = "tiendadb";
$port = 3306;
```

---

## 📝 Historial de Cambios y Agregados Recientes

- **Modelo Rol Añadido (`models/rol.php`)**: Se creó el modelo `Rol` para consultar dinámicamente los roles disponibles en la tabla `roles` en lugar de cargarlos de forma estática en la vista.
- **Simplificación de la Entidad Usuario**: Se unificaron los campos "nombres" y "apellidos" en un único campo "usuario" en todos los formularios y controladores, logrando una correspondencia exacta 1:1 con la base de datos y simplificando el registro.
- **Gestión de Usuarios Optimizada (`views/admin/gusuarios.php`)**:
  - Se conectaron correctamente los botones de **Agregar** y **Editar** usuario a sus respectivos modales de Bootstrap 5.
  - Se eliminó el código obsoleto escolar (*estudiantes, acudientes, asignaturas*) que generaba errores de ejecución fatal en PHP.
  - Se integró la carga de roles y estados directamente desde la base de datos a través de los modelos.
  - Se corrigió el selector del DataTable para adaptarlo a la nueva estructura de columnas sin la columna "Vincular".
- **Controlador Administrativo Creado (`controllers/registerControllerAdmin.php`)**: Se añadió este controlador para procesar de forma correcta la creación de nuevos usuarios desde el panel del Administrador, combinando los campos nombres/apellidos en una sola columna y redirigiendo de vuelta a `gusuarios.php` mediante alertas de **SweetAlert2**.
- **Registro de Fecha Automático**: El modelo `Usuario::registrar()` ahora ingresa automáticamente la fecha y hora de creación utilizando `NOW()` en la base de datos.
- **Gestión de Estados en Texto (VARCHAR)**: Se migró la columna `estado` en la base de datos y la lógica en PHP para almacenar de forma explícita `"Activo"` o `"Inactivo"`, actualizando la parametrización de variables en las consultas (`bind_param` de tipo string `'s'`).
- **Módulo de Proveedores Añadido**: Creación de la vista `views/admin/proveedores.php`, el modelo `models/proveedor.php` y el controlador `controllers/RegisterProveedorController.php` para la gestión de proveedores del sistema.
- **Estandarización UI de SweetAlert2 (Glassmorphism)**: Se aplicó un diseño premium (efecto blur/glassmorphism, gradientes, tipografías personalizadas y fondos difuminados) a las alertas generadas desde el servidor en los controladores `registerControllerAdmin.php`, `RegisterProveedorController.php` y `registerController.php` para mantener congruencia total con la estética moderna de AgriStock.
- **Contexto para Agentes (`.agents/AGENTS.md`)**: Se creó una carpeta oculta con directrices y reglas del proyecto para agilizar el trabajo de futuros agentes de IA y reducir el consumo de tokens.
