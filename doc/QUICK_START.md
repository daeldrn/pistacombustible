# 🚀 Quick Start - Laravel 12 API

## Instalación y Configuración

### 1. Instalar dependencias
```bash
composer install
```

### 2. Configurar entorno
```bash
# Copiar archivo de configuración
copy .env.example .env

# Generar key de aplicación
php artisan key:generate
```

### 3. Configurar base de datos
Edita el archivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Ejecutar migraciones
```bash
php artisan migrate
```

### 5. Ejecutar seeders (datos de prueba)
```bash
php artisan db:seed
```

Esto creará:
- **admin@example.com** / password (Administrador)
- **test@example.com** / password (Usuario de prueba)
- **inactive@example.com** / password (Usuario inactivo)
- 10 usuarios adicionales generados aleatoriamente

### 6. Iniciar servidor
```bash
php artisan serve
```

La API estará disponible en: `http://localhost:8000/pista`

---

## 🎯 Prueba Rápida

### 1. Login
```bash
curl -X POST http://localhost:8000/pista/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"password\"}"
```

**Copia el token de la respuesta**

### 2. Obtener usuarios
```bash
curl -X GET http://localhost:8000/pista/users ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### 3. Ver dashboard
```bash
curl -X GET http://localhost:8000/pista/dashboard ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

---

## 📁 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php      # Autenticación
│   │       ├── UserController.php      # CRUD de usuarios
│   │       └── DashboardController.php # Dashboard
│   ├── Requests/
│   │   ├── LoginRequest.php           # Validación de login
│   │   ├── StoreUserRequest.php       # Validación crear usuario
│   │   └── UpdateUserRequest.php      # Validación actualizar usuario
│   ├── Resources/
│   │   ├── UserResource.php           # Transformación de usuario
│   │   └── UserCollection.php         # Transformación de colección
│   └── Responses/
│       └── ApiResponse.php            # Respuestas estandarizadas
├── Models/
│   └── User.php                       # Modelo de usuario
├── Policies/
│   └── UserPolicy.php                 # Políticas de autorización
├── Repositories/
│   └── UserRepository.php             # Acceso a datos
├── Services/
│   ├── AuthService.php                # Lógica de autenticación
│   └── UserService.php                # Lógica de usuarios
├── Events/
│   └── UserCreated.php                # Evento de usuario creado
└── Listeners/
    └── SendUserCreatedNotification.php # Listener del evento
```

---

## 🔑 Características Implementadas

### ✅ Autenticación
- Login con email y password
- Tokens con Laravel Sanctum
- Logout (revoca token)
- Rate limiting (5 intentos por minuto)

### ✅ Gestión de Usuarios
- Listar usuarios (paginado)
- Crear usuario
- Ver usuario
- Actualizar usuario
- Eliminar usuario (soft delete)
- Validación de datos
- Autorización con policies

### ✅ Dashboard
- Estadísticas generales
- Usuarios recientes
- Dashboard completo
- Caché implementado (5 minutos)

### ✅ Arquitectura
- **API Resources**: Transformación de datos
- **ApiResponse**: Respuestas estandarizadas
- **Repository Pattern**: Separación de datos
- **Service Layer**: Lógica de negocio
- **Form Requests**: Validación
- **Policies**: Autorización
- **Events & Listeners**: Eventos del sistema

### ✅ Optimizaciones
- Caché en dashboard (5 minutos)
- Limpieza automática de caché
- Logging estructurado
- Manejo de excepciones
- Rate limiting

---

## 📚 Documentación

- **API_GUIDE.md**: Documentación completa de la API
- **MEJORAS_IMPLEMENTADAS.md**: Detalles de las mejoras
- **TESTING_GUIDE.md**: Guía de pruebas
- **API_DOCUMENTATION.md**: Documentación original

---

## 🧪 Testing

### Ejecutar tests
```bash
php artisan test
```

### Ejecutar tests específicos
```bash
php artisan test --filter UserTest
```

### Ver cobertura
```bash
php artisan test --coverage
```

---

## 🛠️ Comandos Útiles

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Ver rutas
```bash
php artisan route:list
```

### Ver logs en tiempo real
```bash
php artisan pail
```

### Crear nuevo usuario manualmente
```bash
php artisan tinker
```
```php
User::create([
    'name' => 'Nuevo Usuario',
    'email' => 'nuevo@example.com',
    'password' => Hash::make('password'),
    'activo' => true
]);
```

---

## 🔒 Seguridad

- ✅ Contraseñas hasheadas con bcrypt
- ✅ Tokens de autenticación con Sanctum
- ✅ Rate limiting en login
- ✅ Validación de datos
- ✅ Autorización con policies
- ✅ Soft deletes
- ✅ CORS configurado
- ✅ Respuestas JSON forzadas en API

---

## 📊 Endpoints Disponibles

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/pista/login` | Login | No |
| POST | `/pista/logout` | Logout | Sí |
| GET | `/pista/me` | Usuario autenticado | Sí |
| GET | `/pista/users` | Listar usuarios | Sí |
| POST | `/pista/users` | Crear usuario | Sí |
| GET | `/pista/users/{id}` | Ver usuario | Sí |
| PUT | `/pista/users/{id}` | Actualizar usuario | Sí |
| DELETE | `/pista/users/{id}` | Eliminar usuario | Sí |
| GET | `/pista/dashboard` | Dashboard completo | Sí |
| GET | `/pista/dashboard/stats` | Estadísticas | Sí |
| GET | `/pista/dashboard/recent-users` | Usuarios recientes | Sí |

---

## 🎨 Ejemplo de Respuesta

```json
{
  "success": true,
  "message": "Usuario obtenido exitosamente",
  "data": {
    "id": 1,
    "name": "Usuario Test",
    "email": "test@example.com",
    "activo": true,
    "created_at": "2026-02-23T10:00:00.000000Z",
    "updated_at": "2026-02-23T10:00:00.000000Z"
  }
}
```

---

## 🐛 Troubleshooting

### Error de conexión a base de datos
```bash
# Verificar configuración en .env
# Ejecutar migraciones
php artisan migrate
```

### Error "Class not found"
```bash
composer dump-autoload
```

### Caché no funciona
```bash
php artisan cache:clear
php artisan config:clear
```

### Tests fallan
```bash
php artisan config:clear
php artisan migrate --env=testing
```

---

## 📞 Soporte

Para más información, consulta:
- **API_GUIDE.md**: Documentación completa
- **TESTING_GUIDE.md**: Guía de pruebas
- **MEJORAS_IMPLEMENTADAS.md**: Detalles técnicos

---

## ✨ Próximos Pasos

1. Personalizar los endpoints según tus necesidades
2. Agregar más recursos (productos, categorías, etc.)
3. Implementar roles y permisos
4. Agregar más tests
5. Configurar CI/CD
6. Documentar con Swagger/OpenAPI

---

**¡Tu API Laravel 12 está lista para usar! 🎉**
