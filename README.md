# 🚀 Mi Aplicación - API REST con Laravel

Sistema de gestión con backend API REST desarrollado en Laravel 12 y frontend estático con diseño Nifty.

## 📋 Descripción

Aplicación web moderna con arquitectura separada:
- **Backend:** API REST con Laravel + Sanctum
- **Frontend:** HTML/JS estático con plantilla Nifty
- **Base de datos:** MySQL/PostgreSQL
- **Autenticación:** Tokens Bearer (Sanctum)

## ✨ Características

- ✅ API REST completa con 11 endpoints
- ✅ Autenticación segura con tokens
- ✅ CRUD de usuarios con paginación
- ✅ Dashboard con estadísticas
- ✅ Rate limiting (60 req/min)
- ✅ CORS configurado
- ✅ Validaciones y autorización
- ✅ Soft deletes
- ✅ Frontend responsive con Nifty

## 🛠️ Tecnologías

- **Backend:** Laravel 12, PHP 8.2+
- **Autenticación:** Laravel Sanctum
- **Base de datos:** MySQL/PostgreSQL
- **Frontend:** HTML5, JavaScript, jQuery
- **CSS:** Bootstrap 3, Nifty Template
- **Iconos:** Font Awesome

## 📦 Instalación

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- MySQL o PostgreSQL
- Servidor web (Apache/Nginx) o PHP built-in server

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <url-del-repositorio>
   cd mi-proyecto
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar entorno**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **Configurar base de datos**
   
   Editar `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_base_datos
   DB_USERNAME=usuario
   DB_PASSWORD=contraseña
   ```

5. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

6. **Crear usuario de prueba**
   ```bash
   php artisan tinker
   ```
   
   En tinker:
   ```php
   \App\Models\User::create([
       'name' => 'Admin',
       'email' => 'admin@example.com',
       'password' => bcrypt('password'),
       'activo' => true
   ]);
   exit
   ```

7. **Iniciar servidor**
   ```bash
   php artisan serve
   ```

8. **Acceder a la aplicación**
   ```
   http://localhost:8000/app/login.html
   ```
   
   **Credenciales:**
   - Email: admin@example.com
   - Password: password

## 📚 Documentación

- **[API Documentation](API_DOCUMENTATION.md)** - Documentación completa de endpoints
- **[Backend Setup](BACKEND_SETUP.md)** - Guía de configuración del backend
- **[Ejemplos de Consumo](EJEMPLOS_CONSUMO_API.md)** - Ejemplos en React, Vue, Angular, etc.
- **[Comandos Útiles](comandos-utiles.md)** - Comandos para desarrollo
- **[Checklist](CHECKLIST_VERIFICACION.md)** - Verificación de instalación
- **[Testing API](test-api.http)** - Archivo para REST Client

## 🔌 Endpoints API

### Autenticación
- `POST /pista/login` - Login y obtener token
- `POST /pista/logout` - Cerrar sesión
- `GET /pista/me` - Usuario autenticado

### Dashboard
- `GET /pista/dashboard` - Datos completos
- `GET /pista/dashboard/stats` - Estadísticas
- `GET /pista/dashboard/recent-users` - Usuarios recientes

### Usuarios (CRUD)
- `GET /pista/users` - Listar (paginado)
- `POST /pista/users` - Crear
- `GET /pista/users/{id}` - Ver uno
- `PUT /pista/users/{id}` - Actualizar
- `DELETE /pista/users/{id}` - Eliminar

## 🔒 Seguridad

- ✅ Autenticación con tokens Bearer (Sanctum)
- ✅ Rate limiting (5 intentos login, 60 req/min API)
- ✅ Validación de inputs
- ✅ Políticas de autorización
- ✅ CORS configurado
- ✅ Passwords hasheados (bcrypt)
- ✅ Protección contra CSRF en rutas web

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=AuthTest

# Con coverage
php artisan test --coverage
```

## 📁 Estructura del Proyecto

```
mi-proyecto/
├── app/
│   ├── Http/Controllers/Api/    # Controladores API
│   ├── Services/                # Lógica de negocio
│   ├── Models/                  # Modelos Eloquent
│   └── Policies/                # Autorización
├── public/app/                  # Frontend estático
│   ├── login.html
│   ├── dashboard.html
│   └── users.html
├── routes/
│   ├── api.php                  # Rutas API
│   └── web.php                  # Rutas web
├── config/                      # Configuraciones
└── database/                    # Migraciones y seeders
```

## 🚀 Deploy a Producción

### 1. Optimizar aplicación
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Configurar .env
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
```

### 3. Configurar CORS
Editar `config/cors.php`:
```php
'allowed_origins' => [env('FRONTEND_URL', 'https://tudominio.com')],
```

### 4. Configurar Sanctum
```env
SANCTUM_STATEFUL_DOMAINS=tudominio.com
```

### 5. Usar HTTPS
Asegúrate de que tu servidor use HTTPS en producción.

## 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Ver rutas
php artisan route:list

# Crear usuario
php artisan tinker

# Ver logs
Get-Content storage/logs/laravel.log -Tail 50
```

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT.

## 👥 Autores

- Tu Nombre - Desarrollo inicial

## 🙏 Agradecimientos

- Laravel Framework
- Nifty Admin Template
- Laravel Sanctum
- Comunidad de Laravel

## 📞 Soporte

Para soporte, email: tu-email@example.com

---

**Desarrollado con ❤️ usando Laravel 12 + Sanctum + Nifty Template**
