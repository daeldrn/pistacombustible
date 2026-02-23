# Configuración del Backend API

## ✅ Cambios Implementados

### 1. Controladores API Creados
- `app/Http/Controllers/Api/AuthController.php` - Autenticación con tokens
- `app/Http/Controllers/Api/UserController.php` - CRUD de usuarios
- `app/Http/Controllers/Api/DashboardController.php` - Estadísticas

### 2. Rutas API Configuradas
- `routes/api.php` - Todas las rutas API con autenticación Sanctum
- `routes/web.php` - Simplificado para servir solo frontend estático

### 3. Middleware y Configuración
- `app/Http/Middleware/ForceJsonResponse.php` - Forzar respuestas JSON en API
- `config/cors.php` - Configurado para permitir peticiones cross-origin
- `config/sanctum.php` - Ya estaba configurado
- `bootstrap/app.php` - Actualizado con middleware API

### 4. Modelo User
- Actualizado con trait `HasApiTokens` de Sanctum

### 5. Frontend Estático de Ejemplo
- `public/app/login.html` - Página de login
- `public/app/dashboard.html` - Dashboard con consumo de API

### 6. Documentación
- `API_DOCUMENTATION.md` - Documentación completa de endpoints
- `test-api.http` - Archivo para probar la API

---

## 🚀 Pasos para Configurar

### 1. Actualizar archivo .env
```bash
# Copiar el ejemplo si no existe
copy .env.example .env

# Asegurarse de tener estas variables:
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000
FRONTEND_URL=http://localhost:3000
```

### 2. Ejecutar migraciones (si no lo has hecho)
```bash
php artisan migrate
```

### 3. Crear un usuario de prueba
```bash
php artisan tinker
```

Luego en tinker:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'activo' => true
]);
```

### 4. Iniciar el servidor
```bash
php artisan serve
```

### 5. Probar la API

#### Opción A: Usar el frontend estático
1. Abrir navegador en: http://localhost:8000/app/login.html
2. Login con: admin@example.com / password

#### Opción B: Usar herramientas de API
- Usar Postman, Insomnia o REST Client (VS Code)
- Importar el archivo `test-api.http`
- Seguir la documentación en `API_DOCUMENTATION.md`

---

## 📋 Endpoints Principales

### Autenticación
- `POST /pista/login` - Login (retorna token)
- `POST /pista/logout` - Logout
- `GET /pista/me` - Usuario autenticado

### Dashboard
- `GET /pista/dashboard` - Datos completos
- `GET /pista/dashboard/stats` - Solo estadísticas
- `GET /pista/dashboard/recent-users` - Usuarios recientes

### Usuarios
- `GET /pista/users` - Listar (paginado)
- `POST /pista/users` - Crear
- `GET /pista/users/{id}` - Ver uno
- `PUT /pista/users/{id}` - Actualizar
- `DELETE /pista/users/{id}` - Eliminar

---

## 🗑️ Archivos que YA NO SE USAN (pueden eliminarse)

### Controladores Web (ya no necesarios)
- `app/Http/Controllers/AuthController.php` ❌
- `app/Http/Controllers/UserController.php` ❌
- `app/Http/Controllers/DashboardController.php` ❌

### Vistas Blade (ya no necesarias)
- `resources/views/auth/*` ❌
- `resources/views/users/*` ❌
- `resources/views/dashboard.blade.php` ❌
- `resources/views/layouts/*` ❌
- `resources/views/welcome.blade.php` ❌

### Middleware Web (opcional mantener)
- `app/Http/Middleware/CheckUserActive.php` - Ya no se usa en API
- `app/Http/Middleware/RedirectIfAuthenticated.php` - Ya no se usa

---

## 🔧 Configuración de Producción

### 1. CORS
Editar `config/cors.php`:
```php
'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
```

### 2. Sanctum
Editar `.env`:
```
SANCTUM_STATEFUL_DOMAINS=tudominio.com,www.tudominio.com
```

### 3. Rate Limiting
Ya está configurado en `bootstrap/app.php`:
- API: 60 peticiones/minuto
- Login: 5 intentos/minuto

---

## 🧪 Testing

### Probar Login
```bash
curl -X POST http://localhost:8000/pista/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### Probar Endpoint Protegido
```bash
curl -X GET http://localhost:8000/pista/dashboard/stats \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

---

## 📦 Próximos Pasos

1. **Eliminar archivos obsoletos** (ver lista arriba)
2. **Crear frontend completo** en HTML/JS o usar framework (React, Vue, etc.)
3. **Implementar refresh tokens** si necesitas sesiones largas
4. **Agregar más endpoints** según necesidades
5. **Implementar tests** para la API

---

## 🆘 Solución de Problemas

### Error 401 en todas las peticiones
- Verificar que el token se envía en el header: `Authorization: Bearer {token}`
- Verificar que el usuario existe y está activo

### Error CORS
- Verificar `config/cors.php`
- Verificar que `HandleCors` middleware está activo en `bootstrap/app.php`

### Token no funciona
- Verificar que la tabla `personal_access_tokens` existe
- Ejecutar: `php artisan migrate`

### Frontend no carga
- Verificar que los archivos están en `public/app/`
- Verificar permisos de lectura
