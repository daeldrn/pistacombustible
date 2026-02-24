# 🚀 Mi Aplicación - API REST con Laravel

Sistema de gestión con backend API REST desarrollado en Laravel 12 y frontend estático con diseño Nifty.

## 📋 Descripción

Aplicación web moderna con arquitectura separada:
- **Backend:** API REST con Laravel + Sanctum
- **Frontend:** HTML/JS estático con plantilla Nifty
- **Base de datos:** MySQL/PostgreSQL
- **Autenticación:** Tokens Bearer (Sanctum)

## ✨ Características Principales

- ✅ API REST completa con 20+ endpoints
- ✅ Autenticación segura con tokens
- ✅ Sistema de roles y permisos (Spatie)
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
- **Roles y Permisos:** Spatie Laravel Permission
- **Base de datos:** MySQL/PostgreSQL
- **Frontend:** HTML5, JavaScript, jQuery
- **CSS:** Bootstrap 3, Nifty Template

## 🚀 Inicio Rápido

### 1. Instalar dependencias
```bash
composer install
```

### 2. Configurar entorno
```bash
copy .env.example .env
php artisan key:generate
```

### 3. Configurar base de datos en `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

### 4. Ejecutar migraciones y seeders
```bash
php artisan migrate
php artisan db:seed
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### 5. Iniciar servidor
```bash
php artisan serve
```

### 6. Acceder a la aplicación
```
http://localhost:8000/app/login.html
```

**Credenciales de prueba:**
- Email: admin@admin.com
- Password: password
- Rol: admin (acceso completo)

## 📚 Documentación

Toda la documentación está organizada en la carpeta `doc/`:

- **[doc/README.md](doc/README.md)** - Información completa del proyecto
- **[doc/QUICK_START.md](doc/QUICK_START.md)** - Guía de inicio rápido
- **[doc/API_GUIDE.md](doc/API_GUIDE.md)** - Documentación de la API
- **[doc/ROLES_Y_PERMISOS.md](doc/ROLES_Y_PERMISOS.md)** - Sistema de roles y permisos
- **[doc/TESTING_GUIDE.md](doc/TESTING_GUIDE.md)** - Guía de pruebas
- **[doc/INTEGRACION_FRONTEND.md](doc/INTEGRACION_FRONTEND.md)** - Integración con frontend
- **[doc/EJEMPLOS_CONSUMO_API.md](doc/EJEMPLOS_CONSUMO_API.md)** - Ejemplos de uso
- **[doc/EJEMPLOS_ROLES_PERMISOS.md](doc/EJEMPLOS_ROLES_PERMISOS.md)** - Ejemplos de roles
- **[doc/comandos-utiles.md](doc/comandos-utiles.md)** - Comandos útiles
- **[doc/INDICE_DOCUMENTACION.md](doc/INDICE_DOCUMENTACION.md)** - Índice completo

## 🔌 Endpoints API

### Autenticación
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/pista/login` | Login | No |
| POST | `/pista/logout` | Logout | Sí |
| GET | `/pista/me` | Usuario autenticado | Sí |

### Usuarios
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/pista/users` | Listar usuarios | Sí |
| POST | `/pista/users` | Crear usuario | Sí |
| GET | `/pista/users/{id}` | Ver usuario | Sí |
| PUT | `/pista/users/{id}` | Actualizar usuario | Sí |
| DELETE | `/pista/users/{id}` | Eliminar usuario | Sí |

### Dashboard
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/pista/dashboard` | Dashboard completo | Sí |
| GET | `/pista/dashboard/stats` | Estadísticas | Sí |
| GET | `/pista/dashboard/recent-users` | Usuarios recientes | Sí |

### Roles y Permisos (Solo Admin)
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/pista/roles` | Listar roles | Admin |
| POST | `/pista/roles` | Crear rol | Admin |
| GET | `/pista/roles/{id}` | Ver rol | Admin |
| PUT | `/pista/roles/{id}` | Actualizar rol | Admin |
| DELETE | `/pista/roles/{id}` | Eliminar rol | Admin |
| GET | `/pista/permissions` | Listar permisos | Admin |
| POST | `/pista/permissions` | Crear permiso | Admin |

### Asignación de Roles/Permisos
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/pista/users/{id}/roles` | Asignar roles | Sí |
| DELETE | `/pista/users/{id}/roles` | Remover rol | Sí |
| POST | `/pista/users/{id}/permissions` | Asignar permisos | Sí |
| DELETE | `/pista/users/{id}/permissions` | Revocar permiso | Sí |
| GET | `/pista/users/{id}/permissions` | Ver permisos | Sí |

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=AuthTest
```

## 🔒 Seguridad

- ✅ Autenticación con tokens Bearer (Sanctum)
- ✅ Sistema de roles y permisos (admin, editor, user)
- ✅ Middleware de autorización por rol/permiso
- ✅ Rate limiting (5 intentos login, 60 req/min API)
- ✅ Validación de inputs
- ✅ Políticas de autorización
- ✅ CORS configurado
- ✅ Passwords hasheados (bcrypt)

### Roles Predefinidos

- **admin**: Acceso completo al sistema
- **editor**: Puede crear y editar usuarios
- **user**: Acceso básico de lectura

## 📁 Estructura del Proyecto

```
mi-proyecto/
├── app/                      # Código de la aplicación
│   ├── Http/Controllers/Api/ # Controladores API
│   ├── Services/            # Lógica de negocio
│   ├── Repositories/        # Acceso a datos
│   └── Models/              # Modelos Eloquent
├── public/app/              # Frontend estático
├── routes/                  # Rutas de la aplicación
├── doc/                     # Documentación
└── tests/                   # Tests
```

## 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan permission:cache-reset

# Ver rutas
php artisan route:list

# Seeders
php artisan db:seed --class=RolesAndPermissionsSeeder

# Crear usuario
php artisan tinker
```

## 📞 Soporte

Para más información, consulta la [documentación completa](doc/INDICE_DOCUMENTACION.md).

## 📝 Licencia

Este proyecto está bajo la Licencia MIT.

---

**Desarrollado con ❤️ usando Laravel 12 + Sanctum + Nifty Template**
