# Plataforma de Donación de Alimentos Próximos a Vencer (Huancayo)

Este es el Proyecto 2 del curso de Programación Web. Es una plataforma web desarrollada en **Laravel** bajo la arquitectura **MVC nativa**, orientada a mitigar el desperdicio de alimentos y canalizar donaciones de empresas o particulares hacia comedores populares y organizaciones benéficas de Huancayo.

## 🚀 Tecnologías y Arquitectura

- **Framework**: Laravel 12 + PHP 8.2 (Portable)
- **Base de Datos**: SQLite (Base de datos local rápida, auto-configurada en `database/database.sqlite`)
- **Autenticación**: Laravel Breeze (Blade, control de roles e inicio de sesión seguro)
- **Seguridad**: Protección contra ataques CSRF nativa, hashes de contraseñas con bcrypt, validación robusta con FormRequests, y Middleware de Control de Acceso por Rol.
- **Diseño**: Responsivo y moderno estructurado mediante los componentes Blade y Tailwind CSS provistos por Laravel Breeze.

---

## ⚙️ Roles de Usuario

1. **Donante**:
   - Puede registrar, editar y eliminar sus donaciones de alimentos (siempre que estén con estado `disponible`).
   - Puede ver las solicitudes de reserva hechas a sus donaciones y confirmar la entrega física del alimento (estado pasa a `entregada`/`completada`).
2. **Organización Receptora**:
   - Tiene un perfil de organización donde configura su dirección, teléfono de contacto y límite de reservas diarias.
   - Puede navegar por el catálogo de alimentos disponibles y realizar reservas rápidas.
   - Puede gestionar sus reservas activas o cancelarlas si fuera necesario.

---

## 🛠️ Instalación y Ejecución

*Nota: El proyecto incluye un entorno de PHP y Composer portable localizado en `PAFINAL/tools/` para evitar la necesidad de instalaciones globales.*

1. **Crear archivo de entorno y base de datos SQLite**:
   Si no existe el archivo `.env` o la base de datos:
   ```bash
   cp .env.example .env
   # En Windows PowerShell
   New-Item -ItemType File -Path database/database.sqlite -Force
   ```

2. **Ejecutar migraciones y poblar base de datos (Seeders)**:
   Esto configurará las tablas y registrará datos demo (1 Donante, 1 Organización, y 3 Donaciones de prueba):
   ```bash
   # Usando el helper de PHP portable
   ..\..\tools\php.bat artisan migrate:fresh --seed
   ```

3. **Iniciar el Servidor de Desarrollo**:
   ```bash
   ..\..\tools\php.bat artisan serve
   ```
   La aplicación estará disponible en [http://127.0.0.1:8000](http://127.0.0.1:8000).

4. **Credenciales de Prueba (Demo)**:
   - **Donante**:
     - Correo: `donante@continental.edu.pe`
     - Contraseña: `password`
   - **Organización (Comedor)**:
     - Correo: `comedor@continental.edu.pe`
     - Contraseña: `password`

---

## 🧪 Ejecución de Pruebas

Para ejecutar las pruebas feature (pruebas de autenticación, flujos de creación de donaciones y flujo de reservas) omitiendo las configuraciones locales de codificación en Windows:

```bash
# En Windows PowerShell (inyectando variables de entorno necesarias)
$env:APP_ENV="testing"; $env:DB_CONNECTION="sqlite"; $env:DB_DATABASE=":memory:"; $env:SESSION_DRIVER="array"; $env:CACHE_STORE="array"; $env:BCRYPT_ROUNDS="4"; $env:QUEUE_CONNECTION="sync"; $env:MAIL_MAILER="array"; ..\..\tools\php.bat vendor\phpunit\phpunit\phpunit --no-configuration tests/Feature
```
