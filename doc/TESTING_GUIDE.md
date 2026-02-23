# Guía de Pruebas - API Laravel 12

## Pruebas Rápidas con cURL

### 1. Verificar que el servidor está corriendo
```bash
php artisan serve
```

### 2. Login (obtener token)
```bash
curl -X POST http://localhost:8000/pista/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"password\"}"
```

**Respuesta esperada:**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "token": "1|abc123...",
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@example.com",
      "activo": true,
      "created_at": "2026-02-23T10:00:00.000000Z",
      "updated_at": "2026-02-23T10:00:00.000000Z"
    }
  }
}
```

### 3. Obtener usuario autenticado
```bash
curl -X GET http://localhost:8000/pista/me ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### 4. Listar usuarios
```bash
curl -X GET http://localhost:8000/pista/users ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### 5. Crear usuario
```bash
curl -X POST http://localhost:8000/pista/users ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"Nuevo Usuario\",\"email\":\"nuevo@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\",\"activo\":true}"
```

### 6. Ver usuario específico
```bash
curl -X GET http://localhost:8000/pista/users/1 ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### 7. Actualizar usuario
```bash
curl -X PUT http://localhost:8000/pista/users/1 ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"Usuario Actualizado\",\"email\":\"actualizado@example.com\",\"activo\":false}"
```

### 8. Eliminar usuario
```bash
curl -X DELETE http://localhost:8000/pista/users/2 ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### 9. Dashboard - Estadísticas
```bash
curl -X GET http://localhost:8000/pista/dashboard/stats ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### 10. Dashboard - Usuarios recientes
```bash
curl -X GET http://localhost:8000/pista/dashboard/recent-users ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### 11. Dashboard - Completo
```bash
curl -X GET http://localhost:8000/pista/dashboard ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### 12. Logout
```bash
curl -X POST http://localhost:8000/pista/logout ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

---

## Pruebas de Errores

### Error de validación (422)
```bash
curl -X POST http://localhost:8000/pista/users ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"\",\"email\":\"invalid-email\"}"
```

### No autenticado (401)
```bash
curl -X GET http://localhost:8000/pista/users ^
  -H "Accept: application/json"
```

### No autorizado (403)
```bash
# Intentar actualizar otro usuario
curl -X PUT http://localhost:8000/pista/users/999 ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"Hacker\"}"
```

### Recurso no encontrado (404)
```bash
curl -X GET http://localhost:8000/pista/users/99999 ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json"
```

### Rate limiting (429)
```bash
# Ejecutar este comando 6 veces seguidas
curl -X POST http://localhost:8000/pista/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"wrong\"}"
```

---

## Pruebas con Postman

### Configuración Inicial

1. Crear una nueva colección llamada "Laravel 12 API"
2. Agregar variable de entorno `base_url` = `http://localhost:8000/pista`
3. Agregar variable de entorno `token` (se llenará después del login)

### Requests a crear:

#### 1. Login
- **Method**: POST
- **URL**: `{{base_url}}/login`
- **Body** (JSON):
```json
{
  "email": "test@example.com",
  "password": "password"
}
```
- **Tests** (para guardar el token):
```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    pm.environment.set("token", jsonData.data.token);
}
```

#### 2. Get Me
- **Method**: GET
- **URL**: `{{base_url}}/me`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`

#### 3. List Users
- **Method**: GET
- **URL**: `{{base_url}}/users?per_page=10`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`

#### 4. Create User
- **Method**: POST
- **URL**: `{{base_url}}/users`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`
- **Body** (JSON):
```json
{
  "name": "Nuevo Usuario",
  "email": "nuevo@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "activo": true
}
```

#### 5. Get User
- **Method**: GET
- **URL**: `{{base_url}}/users/1`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`

#### 6. Update User
- **Method**: PUT
- **URL**: `{{base_url}}/users/1`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`
- **Body** (JSON):
```json
{
  "name": "Usuario Actualizado",
  "email": "actualizado@example.com",
  "activo": false
}
```

#### 7. Delete User
- **Method**: DELETE
- **URL**: `{{base_url}}/users/2`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`

#### 8. Dashboard Stats
- **Method**: GET
- **URL**: `{{base_url}}/dashboard/stats`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`

#### 9. Dashboard Recent Users
- **Method**: GET
- **URL**: `{{base_url}}/dashboard/recent-users`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`

#### 10. Dashboard All
- **Method**: GET
- **URL**: `{{base_url}}/dashboard`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`

#### 11. Logout
- **Method**: POST
- **URL**: `{{base_url}}/logout`
- **Headers**: 
  - `Authorization`: `Bearer {{token}}`

---

## Pruebas Unitarias con PHPUnit

### Ejecutar todos los tests
```bash
php artisan test
```

### Ejecutar tests específicos
```bash
php artisan test --filter UserTest
```

### Ejecutar con cobertura
```bash
php artisan test --coverage
```

---

## Verificar Caché

### Ver si el caché está funcionando

1. Primera llamada (sin caché):
```bash
curl -X GET http://localhost:8000/pista/dashboard/stats ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json" ^
  -w "\nTime: %{time_total}s\n"
```

2. Segunda llamada (con caché - debería ser más rápida):
```bash
curl -X GET http://localhost:8000/pista/dashboard/stats ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json" ^
  -w "\nTime: %{time_total}s\n"
```

3. Crear un usuario (limpia el caché):
```bash
curl -X POST http://localhost:8000/pista/users ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"Test Cache\",\"email\":\"cache@test.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"
```

4. Verificar que el caché se limpió (debería tomar más tiempo):
```bash
curl -X GET http://localhost:8000/pista/dashboard/stats ^
  -H "Authorization: Bearer TU_TOKEN_AQUI" ^
  -H "Accept: application/json" ^
  -w "\nTime: %{time_total}s\n"
```

---

## Verificar Logs

### Ver logs en tiempo real
```bash
php artisan pail
```

O manualmente:
```bash
tail -f storage/logs/laravel.log
```

---

## Checklist de Pruebas

- [ ] Login exitoso retorna token
- [ ] Login con credenciales incorrectas retorna 401
- [ ] Acceso sin token retorna 401
- [ ] Listar usuarios retorna paginación correcta
- [ ] Crear usuario retorna 201 y limpia caché
- [ ] Validación de email único funciona
- [ ] Actualizar propio usuario funciona
- [ ] Actualizar otro usuario retorna 403
- [ ] Eliminar usuario retorna 200 y limpia caché
- [ ] Eliminar propio usuario retorna 403
- [ ] Dashboard stats retorna datos correctos
- [ ] Dashboard usa caché (segunda llamada más rápida)
- [ ] Caché se limpia al crear/actualizar/eliminar usuario
- [ ] Rate limiting funciona (5 intentos de login)
- [ ] Logs se generan correctamente
- [ ] Respuestas tienen estructura consistente
- [ ] API Resources transforman datos correctamente
- [ ] Excepciones se manejan correctamente

---

## Troubleshooting

### Error: "Unauthenticated"
- Verifica que el token esté en el header `Authorization: Bearer TOKEN`
- Verifica que el token no haya expirado
- Verifica que el usuario exista

### Error: "SQLSTATE[HY000]"
- Verifica la conexión a la base de datos en `.env`
- Ejecuta `php artisan migrate`

### Error: "Class not found"
- Ejecuta `composer dump-autoload`

### Caché no funciona
- Verifica que `CACHE_DRIVER` esté configurado en `.env`
- Ejecuta `php artisan cache:clear`

### Tests fallan
- Ejecuta `php artisan config:clear`
- Ejecuta `php artisan migrate --env=testing`
