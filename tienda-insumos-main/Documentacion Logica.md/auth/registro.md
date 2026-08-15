# ¿Cómo explicamos el Registro de Usuarios? (Patrón MVC)

*Esta guía está diseñada con un lenguaje humano y conversacional, ideal para sustentar o explicar el funcionamiento interno de la aplicación ante el instructor, demostrando dominio de la arquitectura Modelo-Vista-Controlador (MVC).*

---

### 1. La Vista (El rostro de la aplicación)
Todo el proceso empieza cuando la persona interactúa con la pantalla de registro (`views/auth/register.php`). 
Aquí tenemos un formulario visual muy moderno donde la persona ingresa sus datos principales (nombre de usuario, correo, y contraseña). 

En el código, este formulario tiene una instrucción muy clara: cuando el usuario haga clic en el botón de "Registrarse", la Vista **no debe** intentar guardar nada por su cuenta. En lugar de eso, empaqueta los datos de forma invisible y segura (usando el método `POST`) y se los envía directamente a nuestro cerebro encargado de procesarlos: **El Controlador**.

### 2. El Controlador (El director de orquesta)
El archivo `registerController.php` recibe ese "paquete" de información que mandó la Vista. Piensa en el Controlador como un recepcionista estricto pero eficiente. Su trabajo consiste en:

- **Recibir y limpiar:** Primero, desempaqueta la información que llenó el usuario (capturando los `$_POST`) y le quita espacios accidentales.
- **Validar lo básico:** Se hace una pregunta lógica: *"¿El usuario me envió los campos en blanco?"*. Si es así, el Controlador interrumpe el proceso inmediatamente y manda una alerta (estilo SweetAlert2) de vuelta a la pantalla para decirle al usuario que llene todo.
- **Delegar el trabajo pesado:** Si los datos se ven bien, el Controlador sabe que **su trabajo no es hablar con la base de datos**; eso sería romper la arquitectura. Para eso, llama a su especialista: **El Modelo**.

### 3. El Modelo (El experto en la Base de Datos)
Aquí es donde entra el archivo `Usuario.php`. El Modelo es el único archivo autorizado en nuestro sistema para hacer las consultas a MySQL. El Controlador se comunica con él en dos pasos:

1. **La verificación:** El Controlador le pide un favor al Modelo: *"Revisa en tus registros si este correo electrónico ya existe"*. El Modelo va a la tabla de base de datos, revisa, y si encuentra el correo, le avisa al Controlador. (Si esto pasa, el Controlador le avisa a la Vista que el correo ya está en uso).
2. **El guardado definitivo:** Si el Modelo dice que el correo es nuevo y está libre, el Controlador le da la orden final: *"Perfecto, registra a este usuario"*. 
   El Modelo toma todos los datos, les aplica seguridad (como encriptar la contraseña si es necesario), inserta automáticamente la fecha y hora de creación (`NOW()`), guarda la información en MySQL de forma exitosa y le confirma al Controlador que el trabajo está hecho.

### 4. El Cierre del Ciclo
Una vez que el Modelo le dice al Controlador *"Todo salió perfecto y el usuario se guardó"*, el Controlador vuelve a tomar el mando por última vez. 
Llama de nuevo a la Vista (pantalla) para renderizar una alerta muy estética de **"Registro Exitoso"** y redirige al usuario a la pantalla de Inicio de Sesión para que por fin pueda entrar a su cuenta.

---
**💡 Resumen rápido para el instructor:**
*"La **Vista** captura los datos del usuario, el **Controlador** valida que la información esté correcta y toma las decisiones, y finalmente el **Modelo** es quien interactúa directamente con la base de datos para guardar la información de manera segura."*