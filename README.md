<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Instalación y despliegue en entorno local (sin Docker)

Requisitos mínimos:
- PHP 8.2+ con las extensiones habituales (`pdo_mysql`, `mbstring`, `xml`, `gd`, `bcmath`).
- Composer
- Servidor MySQL/MariaDB (XAMPP, WAMP, servidor local o remoto)

Pasos (rápidos):

1. Clona el repositorio en tu carpeta de trabajo:

```bash
git clone <repo-url> C:/Users/Usuario/Desktop/Facturacion
cd C:/Users/Usuario/Desktop/Facturacion
```

2. Copia el archivo de ejemplo y ajusta variables de entorno (conexión a la BD, correo, Stripe, etc.):

```bash
cp .env.example .env
# Edita .env y deja las credenciales de MySQL (ej. XAMPP):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=facturacion
# DB_USERNAME=root
# DB_PASSWORD=
```

3. Crea la base de datos (phpMyAdmin o línea de comandos). Ejemplo en Windows con XAMPP:

```powershell
# Ajusta la ruta si tu instalación es distinta
"C:\\xampp\\mysql\\bin\\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS facturacion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

4. Instala dependencias y prepara la aplicación:

```bash
composer install --no-interaction --prefer-dist
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan storage:link
```

5. Ejecuta la aplicación localmente (opciones):

- Usando el servidor de desarrollo de Laravel (rápido):

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

- O configura un virtual host en XAMPP/Apache apuntando a la carpeta `public/`.

6. Accede a la app en `http://127.0.0.1:8000` (o el host configurado).

Rutas importantes:
- `/clients` — gestión de clientes
- `/products` — gestión de productos
- `/invoices/create` — crear factura

Configuración adicional recomendada:
- Añade claves de Stripe en `.env` (`STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`) si vas a usar pagos.
- Configura correo (`MAIL_MAILER`, `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`) para envío de facturas.

Ejecutar tests:

```bash
php artisan test
```

Cómo probar Stripe localmente sin Docker:
- Si dispones de Stripe CLI: `stripe listen --forward-to http://127.0.0.1:8000/stripe/webhook` y copia `STRIPE_WEBHOOK_SECRET` en `.env`.
- Si no, usa el comando incluido para simular webhooks: `php artisan stripe:simulate checkout.session.completed --invoice={id} --paid`.

Arquitectura y patrones utilizados:
- Repositories: `app/Repositories`
- Services: `app/Services`
- Strategy (impuestos): `app/Services/Tax`
- Observers: `app/Observers` (auditoría, ajuste de stock)

Próximos pasos sugeridos:
- Añadir pruebas E2E (Cypress) para flujos críticos
- Internacionalizar vistas y mensajes (i18n)

