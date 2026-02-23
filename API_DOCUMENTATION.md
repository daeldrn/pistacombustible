# API Documentation

## Base URL
```
http://localhost:8000/api
```

## Autenticación
La API usa Laravel Sanctum con tokens Bearer. Después del login, incluye el token en el header:
```
Authorization: Bearer {token}
```

---

## Endpoints

### 1. Autenticación

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

**Respuesta exitosa (200):**
```json
{
  "success": true,
  "message": "Login exitoso",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Usuario",
    "email": "user@example.com",
    "activo": true
  }
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
  "success": true,
  "message": "Logout exitoso"
}
```

#### Obtener usuario autenticado
```http
GET /api/me
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Usuario",
    "email": "user@example.com",
    "activo": true
  }
}
```

---

### 2. Dashboard

#### Obtener estadísticas
```http
GET /api/dashboard/stats
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
  "success": true,
  "data": {
    "users": 150,
    "active_users": 120,
    "inactive_users": 30,
    "orders": 89,
    "sales": 234,
    "messages": 45
  }
}
```

#### Obtener usuarios recientes
```http
GET /api/dashboard/recent-users
Authorization: Bearer {token}
```

#### Obtener todo el dashboard
```http
GET /api/dashboard
Authorization: Bearer {token}
```

---

### 3. Usuarios

#### Listar usuarios (con paginación)
```http
GET /api/users?per_page=10&page=1
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Usuario",
      "email": "user@example.com",
      "activo": true,
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "pagination": {
    "total": 100,
    "per_page": 10,
    "current_page": 1,
    "last_page": 10,
    "from": 1,
    "to": 10
  }
}
```

#### Crear usuario
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Nuevo Usuario",
  "email": "nuevo@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "activo": true
}
```

**Respuesta exitosa (201):**
```json
{
  "success": true,
  "message": "Usuario creado exitosamente",
  "data": {
    "id": 2,
    "name": "Nuevo Usuario",
    "email": "nuevo@example.com",
    "activo": true
  }
}
```

#### Obtener usuario específico
```http
GET /api/users/{id}
Authorization: Bearer {token}
```

#### Actualizar usuario
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Usuario Actualizado",
  "email": "actualizado@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123",
  "activo": false
}
```

**Nota:** El campo `password` es opcional en la actualización.

#### Eliminar usuario (soft delete)
```http
DELETE /api/users/{id}
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
  "success": true,
  "message": "Usuario eliminado exitosamente"
}
```

---

## Códigos de Estado HTTP

- `200` - OK (Operación exitosa)
- `201` - Created (Recurso creado)
- `401` - Unauthorized (No autenticado o credenciales inválidas)
- `403` - Forbidden (No autorizado para realizar la acción)
- `404` - Not Found (Recurso no encontrado)
- `422` - Unprocessable Entity (Errores de validación)
- `500` - Internal Server Error (Error del servidor)

---

## Errores de Validación

Cuando hay errores de validación (422), la respuesta incluye:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "El email es obligatorio."
    ],
    "password": [
      "La contraseña debe tener al menos 8 caracteres."
    ]
  }
}
```

---

## Rate Limiting

- Login: 5 intentos por minuto por IP
- API general: 60 peticiones por minuto

---

## CORS

La API está configurada para aceptar peticiones desde cualquier origen en desarrollo.
En producción, configura los dominios permitidos en `config/cors.php`.
