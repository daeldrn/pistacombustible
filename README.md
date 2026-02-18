# Mi Proyecto Laravel

Sistema de gestión de usuarios construido con Laravel 9, siguiendo las mejores prácticas y estándares de desarrollo.

## 🚀 Características

- ✅ Autenticación de usuarios con rate limiting
- ✅ CRUD completo de usuarios
- ✅ Sistema de usuarios activos/inactivos
- ✅ Soft deletes (eliminación lógica)
- ✅ Autorización con Policies
- ✅ Validación con Form Requests
- ✅ Service Layer para lógica de negocio
- ✅ Sistema de eventos y listeners
- ✅ Logging completo
- ✅ Tests automatizados
- ✅ Seeders y factories

## 📋 Requisitos

- PHP >= 8.0.2
- Composer
- MySQL/MariaDB
- Node.js y NPM (para assets)

## 🔧 Instalación

1. **Clonar el repositorio**
```bash
git clone <tu-repositorio>
cd mi-proyecto
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar el archivo .env**
```bash
copy .env.example .env
```

Edita el archivo `.env` con tus credenciales de base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

4. **Generar la clave de aplicación**
```bash
php artisan key:generate
```

5. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

6. **Compilar assets**
```bash
npm run dev
```

7. **Iniciar el servidor**
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## 👤 Credenciales de Prueba

Después de ejecutar los seeders, puedes usar estas credenciales:

- **Administrador**: admin@example.com / password
- **Usuario Test**: test@example.com / password
- **Usuario Inactivo**: inactive@example.com / password (no puede iniciar sesión)

## 🧪 Tests

Ejecutar todos los tests:
```bash
php artisan test
```

Ejecutar tests con cobertura:
```bash
php artisan test --coverage
```

## 📁 Estructura del Proyecto

```
app/
├── Events/              # Eventos de la aplicación
├── Http/
│   ├── Controllers/     # Controladores
│   ├── Middleware/      # Middleware personalizado
│   └── Requests/        # Form Requests para validación
├── Listeners/           # Listeners de eventos
├── Models/              # Modelos Eloquent
├── Policies/            # Policies de autorización
└── Services/            # Capa de servicios (lógica de negocio)

database/
├── factories/           # Factories para testing
├── migrations/          # Migraciones de base de datos
└── seeders/             # Seeders para datos de prueba

tests/
├── Feature/             # Tests de integración
└── Unit/                # Tests unitarios
```

## 🔐 Seguridad

- Rate limiting en login (5 intentos por minuto)
- Verificación de usuarios activos
- Regeneración de sesión en login/logout
- Protección CSRF
- Contraseñas hasheadas con bcrypt
- Soft deletes para auditoría

## 📚 Arquitectura

### Service Layer
La lógica de negocio está separada en servicios:
- `UserService`: Gestión de usuarios
- `AuthService`: Autenticación y autorización

### Form Requests
Validación centralizada:
- `LoginRequest`: Validación de login con rate limiting
- `StoreUserRequest`: Validación para crear usuarios
- `UpdateUserRequest`: Validación para actualizar usuarios

### Policies
Autorización basada en políticas:
- `UserPolicy`: Controla quién puede ver, crear, editar y eliminar usuarios

### Eventos y Listeners
- `UserCreated`: Se dispara al crear un usuario
- `SendUserCreatedNotification`: Procesa el evento (con queue)

## 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ver rutas
php artisan route:list

# Ver eventos
php artisan event:list

# Refrescar base de datos
php artisan migrate:fresh --seed

# Ejecutar queue worker
php artisan queue:work
```

## 📖 Documentación Adicional

Para más detalles sobre las mejoras implementadas, consulta:
- [MEJORAS_IMPLEMENTADAS.md](MEJORAS_IMPLEMENTADAS.md)

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Próximas Mejoras

- [ ] Sistema de roles y permisos (Spatie Permission)
- [ ] API RESTful con API Resources
- [ ] Verificación de email
- [ ] Autenticación de dos factores (2FA)
- [ ] Patrón Repository
- [ ] Notificaciones por email
- [ ] Dashboard con estadísticas

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - El framework PHP
- [Bootstrap](https://getbootstrap.com) - Framework CSS
- Comunidad de Laravel

---

Desarrollado con ❤️ usando Laravel
