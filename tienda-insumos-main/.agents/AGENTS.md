# Contexto del Proyecto: AgriStock

Este archivo contiene las reglas y el contexto del proyecto AgriStock para que cualquier agente de IA pueda entender rápidamente la estructura y las directrices del desarrollo sin consumir tokens innecesarios.

## 🛠️ Stack Tecnológico
* **Backend:** PHP nativo (MVC limpio sin frameworks de backend).
* **Base de Datos:** MySQL utilizando la extensión **MySQLi** (conexión global `$conn` en `config/database.php`).
* **Frontend:** HTML5, CSS personalizado (`public/css/style.css`), Bootstrap 5.3.2, FontAwesome 6, SweetAlert2.
* **Componentes de Interfaz:** DataTables.net para listado de tablas.

## 📁 Estructura del Proyecto
* `/config/database.php`: Configuración de base de datos MySQLi (variable global `$conn`).
* `/models/`: Contiene los modelos (`usuario.php`, `rol.php`).
* `/controllers/`: Controladores de peticiones.
  * `/controllers/auth/`: Controladores de autenticación pública (`authController.php`, `registerController.php`).
  * `/controllers/registerControllerAdmin.php`: Controlador de registro del panel administrativo.
* `/views/`: Contiene los layouts (`header.php`, `footer.php`, `sidebaradmin.php`) y las páginas de administración/clientes/vendedores.

## 📊 Reglas Importantes de la Base de Datos
* **Usuarios (`usuarios`):**
  * Campo `usuario`: Contiene el nombre completo del usuario.
  * Campo `estado` (`VARCHAR(50)`): Puede tomar los valores de texto `'Activo'` o `'Inactivo'` (por defecto `'Activo'`).
  * Campo `id_rol` (`INT`): Vincula al rol del usuario (1 = Administrador, 2 = Vendedor, 3 = Cliente).
  * Campo `fecha_creacion` (`DATETIME`): Se asigna automáticamente con `NOW()` en el insert del modelo `Usuario`.
* **Roles (`roles`):**
  * Columnas: `id_rol` (PK), `nombre` (varchar), `descripcion` (varchar).

## ⚠️ Reglas de Programación
1. **Evitar PDO:** Toda consulta a la base de datos debe realizarse a través de la conexión global `$conn` con la extensión **MySQLi** de PHP.
2. **Alertas con SweetAlert2:** No utilizar `echo` simple o redirecciones crudas en PHP cuando se procese información; renderizar las alertas estéticas de SweetAlert2 usando la función `mostrarAlerta` o enviándolas a la variable de sesión `$_SESSION['alert']`.
3. **Controladores Separados:** Mantener el flujo público de registro de usuarios separado del flujo administrativo de gestión de usuarios.
