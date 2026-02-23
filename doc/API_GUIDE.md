# Guía de API - Laravel 12

## Información General

- **Base URL**: `http://tu-dominio.com/pista`
- **Autenticación**: Laravel Sanctum (Bearer Token)
- **Formato de respuesta**: JSON
- **Charset**: UTF-8

## Estructura de Respuestas

### Respuesta Exitosa
```json
{
  "success": true,
  "message": "Mensaje descriptivo",
  "data": {
    // Datos de la respuesta
  }
}
```

### Respuesta de Error
```json
{
  "success": false,
  "message": "Mensaje de error",
  "errors": {
    // Detalles del error (opcional)
  }
}
```

## Autenticación

### Login
**POST** `/pista/login`

**Request Body:**
```json
{
  "email": "usuario@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "token": "1|abc123...",
    "user": {
      "id": 1,
      "name": "Usuario",
      "email": "usuario@example.com",
      "activo": true,
      "created_at": "2026-02-23T10:00:00.000000Z",
      "updated_at": "2026-02-23T10:00:00.000000Z"
    }
  }
}
```

**Errores:**
- `401`: Credenciales inválidas
- `422`: Error de validación
- `429`: Demasiados intentos

### Logout
**POST** `/pista/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logout exitoso"
}
```

### Usuario Autenticado
**GET** `/pista/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Usuario autenticado",
  "data": {
    "id": 1,
    "name": "Usuario",
    "email": "usuario@example.com",
    "activo": true,
    "created_at": "2026-02-23T10:00:00.000000Z",
    "updated_at": "2026-02-23T10:00:00.000000Z"
  }
}
```

## Usuarios

### Listar Usuarios
**GET** `/pista/users?per_page=10`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `per_page` (opcional): Número de usuarios por página (default: 10)

**Response (200):**
```json
{
  "success": true,
  "message": "Lista de usuarios obtenida exitosamente",
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Usuario 1",
        "email": "usuario1@example.com",
        "activo": true,
        "created_at": "2026-02-23T10:00:00.000000Z",
        "updated_at": "2026-02-23T10:00:00.000000Z"
      }
    ],
    "meta": {
      "total": 50,
      "per_page": 10,
      "current_page": 1,
      "last_page": 5,
      "from": 1,
      "to": 10
    }
  }
}
```

### Crear Usuario
**POST** `/pista/users`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "name": "Nuevo Usuario",
  "email": "nuevo@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "activo": true
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Usuario creado exitosamente",
  "data": {
    "id": 2,
    "name": "Nuevo Usuario",
    "email": "nuevo@example.com",
    "activo": true,
    "created_at": "2026-02-23T10:00:00.000000Z",
    "updated_at": "2026-02-23T10:00:00.000000Z"
  }
}
```

**Errores:**
- `422`: Error de validación

### Ver Usuario
**GET** `/pista/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Usuario obtenido exitosamente",
  "data": {
    "id": 1,
    "name": "Usuario",
    "email": "usuario@example.com",
    "activo": true,
    "created_at": "2026-02-23T10:00:00.000000Z",
    "updated_at": "2026-02-23T10:00:00.000000Z"
  }
}
```

**Errores:**
- `404`: Usuario no encontrado

### Actualizar Usuario
**PUT** `/pista/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "name": "Usuario Actualizado",
  "email": "actualizado@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123",
  "activo": false
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Usuario actualizado exitosamente",
  "data": {
    "id": 1,
    "name": "Usuario Actualizado",
    "email": "actualizado@example.com",
    "activo": false,
    "created_at": "2026-02-23T10:00:00.000000Z",
    "updated_at": "2026-02-23T11:00:00.000000Z"
  }
}
```

**Errores:**
- `403`: No autorizado
- `404`: Usuario no encontrado
- `422`: Error de validación

### Eliminar Usuario
**DELETE** `/pista/users/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Usuario eliminado exitosamente"
}
```

**Errores:**
- `403`: No autorizado (no puedes eliminarte a ti mismo)
- `404`: Usuario no encontrado

## Dashboard

### Estadísticas
**GET** `/pista/dashboard/stats`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Estadísticas obtenidas exitosamente",
  "data": {
    "users": 50,
    "active_users": 45,
    "inactive_users": 5,
    "orders": 89,
    "sales": 234,
    "messages": 45
  }
}
```

### Usuarios Recientes
**GET** `/pista/dashboard/recent-users`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Usuarios recientes obtenidos exitosamente",
  "data": [
    {
      "id": 10,
      "name": "Usuario Reciente",
      "email": "reciente@example.com",
      "activo": true,
      "created_at": "2026-02-23T10:00:00.000000Z",
      "updated_at": "2026-02-23T10:00:00.000000Z"
    }
  ]
}
```

### Dashboard Completo
**GET** `/pista/dashboard`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Dashboard obtenido exitosamente",
  "data": {
    "stats": {
      "users": 50,
      "active_users": 45,
      "inactive_users": 5,
      "orders": 89,
      "sales": 234,
      "messages": 45
    },
    "recent_users": [
      {
        "id": 10,
        "name": "Usuario Reciente",
        "email": "reciente@example.com",
        "activo": true,
        "created_at": "2026-02-23T10:00:00.000000Z",
        "updated_at": "2026-02-23T10:00:00.000000Z"
      }
    ]
  }
}
```

## Códigos de Estado HTTP

- `200 OK`: Solicitud exitosa
- `201 Created`: Recurso creado exitosamente
- `204 No Content`: Solicitud exitosa sin contenido
- `400 Bad Request`: Solicitud incorrecta
- `401 Unauthorized`: No autenticado
- `403 Forbidden`: No autorizado
- `404 Not Found`: Recurso no encontrado
- `422 Unprocessable Entity`: Error de validación
- `429 Too Many Requests`: Límite de solicitudes excedido
- `500 Internal Server Error`: Error del servidor

## Rate Limiting

- **API General**: 60 solicitudes por minuto por usuario/IP
- **Login**: 5 intentos por minuto por IP

## Caché

Los siguientes endpoints utilizan caché para mejorar el rendimiento:

- **Dashboard Stats**: 5 minutos
- **Dashboard Recent Users**: 2 minutos
- **Dashboard All**: 5 minutos

## Notas de Seguridad

1. Todas las contraseñas se almacenan hasheadas con bcrypt
2. Los tokens de autenticación expiran según la configuración de Sanctum
3. Se implementa rate limiting para prevenir ataques de fuerza bruta
4. Los usuarios eliminados se marcan con soft delete
5. Las políticas de autorización previenen acciones no autorizadas

## Ejemplos con cURL

### Login
```bash
curl -X POST http://tu-dominio.com/pista/login \
  -H "Content-Type: application/json" \
  -d '{"email":"usuario@example.com","password":"password123"}'
```

### Listar Usuarios
```bash
curl -X GET http://tu-dominio.com/pista/users \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Accept: application/json"
```

### Crear Usuario
```bash
curl -X POST http://tu-dominio.com/pista/users \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Content-Type: application/json" \
  -d '{
    "name":"Nuevo Usuario",
    "email":"nuevo@example.com",
    "password":"password123",
    "password_confirmation":"password123",
    "activo":true
  }'
```

## Soporte

Para más información o soporte, contacta al equipo de desarrollo.
