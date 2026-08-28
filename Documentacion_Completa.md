# Documentación del Proyecto AgriStock

Este documento sirve como un "bloque de notas" técnico que explica línea por línea o por bloques la función de cada parte del código del sistema.

---

## Fase 1: Configuración y Modelos (Base de Datos)

### 1. Archivo: `config/database.php`
**Lenguaje:** PHP

Este archivo es crucial porque establece la conexión con el motor de base de datos MySQL usando la extensión `mysqli`. También define variables globales del sistema.

**Código Original:**
```php
define('BASE_URL', '/tienda-insumos-main');
$host = "127.0.0.1";
$user = "root";
$password = "";
$bd = "tiendadb";
$port = 3306;

try {
    $conn = new mysqli($host, $user, $password, $bd, $port);
} catch (mysqli_sql_exception $e) {
    die("Error de Conexion: " . $e->getMessage());
}
```

**Explicación:**
- `define('BASE_URL', ...)`: Crea una constante global. Se utiliza para generar enlaces absolutos a los recursos del sistema (CSS, JS, imágenes).
- `$host, $user, $password, $bd, $port`: Son las credenciales locales para acceder a la base de datos `tiendadb`.
- `new mysqli(...)`: Instancia un nuevo objeto de conexión a la base de datos utilizando programación orientada a objetos.
- `try { ... } catch (...)`: Bloque de captura de errores. Si el servidor de base de datos está apagado o los datos son incorrectos, `die()` detiene por completo la ejecución de la página y muestra el mensaje de error.

**Código Comentado:**
```php
// Definimos la constante BASE_URL que servirá para construir rutas absolutas en todo el proyecto
define('BASE_URL', '/tienda-insumos-main');

// Variables de configuración para la conexión al servidor de base de datos MySQL
$host = "127.0.0.1"; // Dirección del servidor (localhost)
$user = "root";      // Usuario de la base de datos
$password = "";      // Contraseña del usuario (vacía por defecto en Laragon/XAMPP)
$bd = "tiendadb";    // Nombre de la base de datos del proyecto
$port = 3306;        // Puerto de conexión a MySQL

try {
    // Intentamos establecer la conexión utilizando la extensión mysqli orientada a objetos
    $conn = new mysqli($host, $user, $password, $bd, $port);
} catch (mysqli_sql_exception $e) {
    // Si la conexión falla, se captura la excepción y se detiene la ejecución (die) mostrando el error
    die("Error de Conexion: " . $e->getMessage());
}
```

---

### 2. Archivo: `models/rol.php`
**Lenguaje:** PHP (y SQL)

**Código Original:**
```php
require_once __DIR__ . '/../config/database.php';
class Rol {
    private $conn;
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    public function obtenerTodos() {
        $query = 'SELECT id_rol, nombre AS rol FROM roles';
        $result = $this->conn->query($query);
        $roles = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $roles[] = $row;
            }
        }
        return $roles;
    }
}
```

**Explicación:**
- `require_once`: Se asegura de traer el archivo de conexión a la base de datos de manera estricta y única antes de cargar el resto.
- `class Rol`: Define una clase (modelo) siguiendo el patrón MVC, que encapsulará todas las acciones hacia la tabla `roles`.
- `__construct()`: Es el constructor de la clase. Al crear un nuevo objeto `Rol`, automáticamente toma la variable de conexión global `$conn` y la almacena de manera privada (`$this->conn`).
- `obtenerTodos()`: Este método ejecuta una consulta SQL `SELECT` para extraer la lista de roles existentes. Usa un bucle `while` apoyado por `fetch_assoc()` para leer los resultados fila por fila y convertirlos en un arreglo de PHP.

**Código Comentado:**
```php
// Importamos la conexión a la base de datos si aún no se ha cargado
require_once __DIR__ . '/../config/database.php';

/**
 * Clase Rol
 * Modelo encargado de gestionar las operaciones relacionadas con la tabla 'roles' de la base de datos.
 */
class Rol
{
    private $conn; // Almacenará la conexión a la base de datos

    /**
     * Constructor de la clase.
     * Al instanciar este objeto, obtiene la variable global $conn (desde database.php) y la asigna a la propiedad privada.
     */
    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * obtenerTodos()
     * Recupera todos los roles disponibles en la base de datos.
     * 
     * @return array Un arreglo asociativo con los roles encontrados.
     */
    public function obtenerTodos()
    {
        // Preparamos la consulta SQL para seleccionar el ID y el nombre del rol (como 'rol')
        $query = 'SELECT id_rol, nombre AS rol FROM roles';
        // Ejecutamos la consulta a través de la conexión
        $result = $this->conn->query($query);
        
        $roles = []; // Arreglo inicializado para guardar los resultados
        
        // Verificamos si la consulta fue exitosa y si devolvió al menos una fila
        if ($result && $result->num_rows > 0) {
            // Recorremos cada fila devuelta por la base de datos
            while ($row = $result->fetch_assoc()) {
                $roles[] = $row; // Agregamos la fila al arreglo
            }
        }
        return $roles; // Devolvemos el arreglo completo
    }
}
```

---

### 3. Archivo: `models/configuracion.php`
**Lenguaje:** PHP (y SQL)

Este archivo es similar, se encarga de leer y actualizar los datos maestros de la tienda (Nombre, NIT, Dirección) que se configuran desde el panel de administración. A diferencia del rol, recibe `$db` por parámetro en lugar de usar una variable global.

*Se omite el código original completo por brevedad, pero destacamos el uso de consultas preparadas (`prepare` y `bind_param`) para actualizar los datos, lo cual previene ataques de Inyección SQL.*

*(Nota: En los archivos fuentes reales ya se han inyectado los comentarios correspondientes)*.

---

> El proceso de documentación de la **Fase 1 (Modelos)** está en curso. Iremos agregando progresivamente los demás modelos a este documento y a sus respectivos archivos.
