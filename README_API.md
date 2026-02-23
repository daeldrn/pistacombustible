# 🚀 Backend API - Laravel

## 📖 Descripción

Este proyecto Laravel ha sido convertido de una aplicación monolítica con vistas Blade a una **API REST pura** que puede ser consumida por cualquier frontend (HTML estático, React, Vue, Angular, móvil, etc.).

---

## ✨ Características

- ✅ API REST completa con Laravel
- ✅ Autenticación con Laravel Sanctum (tokens)
- ✅ CORS configurado para frontend separado
- ✅ Validación de requests
- ✅ Políticas de autorización
- ✅ Rate limiting
- ✅ Respuestas JSON estandarizadas
- ✅ Soft deletes en usuarios
- ✅ Sistema de eventos
- ✅ Frontend estático de ejemplo incluido

---

## 🏗️ Arquitectura

```
┌─────────────────┐
│  Frontend       │
│  (HTML/JS)      │ ← Archivos estáticos en public/app/
└────────┬────────┘
         │ HTTP/JSON
         ▼
┌─────────────────┐
│  Laravel API    │
│  (Backend)      │ ← Controladores en app/Http/Controllers/Api/
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Base de Datos  │
│  (MySQL)        │
└─────────────────┘
```

---

## 📦 Instalación

### 1. Clonar y configurar
```bash
# Instalar dependencias
composer install

# Copiar archivo de entorno
copy .env.example .env

# Generar key
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### 2. Ejecutar migraciones
```bash
php artisan migrate
```

### 3. Crear usuario de prueba
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

### 4. Iniciar servidor
```bash
php artisan serve
```

---

## 🎯 Uso Rápido

### Frontend Estático (Incluido)
1. Abrir navegador: http://localhost:8000/app/login.html
2. Login: admin@example.com / password
3. Explorar dashboard

### Consumir API desde tu Frontend

#### Login
```javascript
const response = await fetch('http://localhost:8000/api/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'admin@example.com',
        password: 'password'
    })
});

const data = await response.json();
const token = data.token; // Guardar este token
```

#### Petición Autenticada
```javascript
const response = await fetch('http://localhost:8000/api/dashboard/stats', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});

const data = await response.json();
console.log(data);
```

---

## 📚 Documentación

- **API Endpoints:** Ver `API_DOCUMENTATION.md`
- **Configuración Backend:** Ver `BACKEND_SETUP.md`
- **Archivos Obsoletos:** Ver `ARCHIVOS_A_ELIMINAR.md`
- **Testing:** Ver `test-api.http`

---

## 🔑 Endpoints Principales

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/api/login` | Login | No |
| POST | `/api/logout` | Logout | Sí |
| GET | `/api/me` | Usuario actual | Sí |
| GET | `/api/dashboard` | Dashboard completo | Sí |
| GET | `/api/dashboard/stats` | Estadísticas | Sí |
| GET | `/api/users` | Listar usuarios | Sí |
| POST | `/api/users` | Crear usuario | Sí |
| GET | `/api/users/{id}` | Ver usuario | Sí |
| PUT | `/api/users/{id}` | Actualizar usuario | Sí |
| DELETE | `/api/users/{id}` | Eliminar usuario | Sí |

---

## 🧪 Testing

### Con cURL
```bash
# Login
curl -X POST http://localhost:8000/api/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"admin@example.com\",\"password\":\"password\"}"

# Dashboard (reemplaza TOKEN con el token obtenido)
curl -X GET http://localhost:8000/api/dashboard/stats ^
  -H "Authorization: Bearer TOKEN"
```

### Con Postman/Insomnia
1. Importar colección desde `test-api.http`
2. Hacer login para obtener token
3. Usar token en peticiones protegidas

---

## 🔒 Seguridad

- ✅ Autenticación con tokens Sanctum
- ✅ Rate limiting (5 intentos login, 60 req/min API)
- ✅ Validación de inputs
- ✅ Políticas de autorización
- ✅ CORS configurado
- ✅ Passwords hasheados con bcrypt
- ✅ Protección CSRF (para SPA stateful)

---

## 🌐 Desplegar Frontend Separado

### Opción 1: Mismo servidor
- Frontend en `public/app/`
- Backend API en `/api/*`

### Opción 2: Servidores separados
```
Frontend: https://miapp.com (Netlify, Vercel, etc.)
Backend:  https://api.miapp.com (servidor PHP)
```

Configurar CORS en `.env`:
```
FRONTEND_URL=https://miapp.com
SANCTUM_STATEFUL_DOMAINS=miapp.com
```

---

## 📁 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/              ← Controladores API
│   │       ├── AuthController.php
│   │       ├── DashboardController.php
│   │       └── UserController.php
│   ├── Middleware/
│   │   └── ForceJsonResponse.php
│   └── Requests/             ← Validaciones
├── Models/                   ← Modelos Eloquent
├── Policies/                 ← Autorización
├── Services/                 ← Lógica de negocio
├── Events/                   ← Eventos
└── Listeners/                ← Listeners

public/
└── app/                      ← Frontend estático
    ├── login.html
    └── dashboard.html

routes/
├── api.php                   ← Rutas API
└── web.php                   ← Rutas web (simplificado)
```

---

## 🛠️ Tecnologías

- Laravel 12
- Laravel Sanctum (autenticación)
- MySQL
- PHP 8.2+

---

## 📝 Notas Importantes

1. **Token Storage:** Guarda el token en `localStorage` o `sessionStorage`
2. **Token Expiration:** Por defecto los tokens no expiran (configurable en `config/sanctum.php`)
3. **CORS:** Configurado para desarrollo, ajustar para producción
4. **Rate Limiting:** 5 intentos de login por minuto, 60 peticiones API por minuto

---

## 🆘 Soporte

Si encuentras problemas:
1. Revisar `storage/logs/laravel.log`
2. Verificar configuración de base de datos
3. Verificar que las migraciones se ejecutaron
4. Verificar permisos de carpetas `storage/` y `bootstrap/cache/`

---

## 📄 Licencia

MIT
