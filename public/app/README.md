# Vistas de la Aplicación

## 📁 Estructura de Archivos

```
public/app/
├── dashboard.html      - Dashboard principal
├── login.html          - Página de login
├── users.html          - Gestión de usuarios
├── roles.html          - Gestión de roles (Admin)
├── permissions.html    - Gestión de permisos (Admin)
└── README.md          - Este archivo
```

## 🚀 Inicio Rápido

### 1. Iniciar el servidor
```bash
php artisan serve
```

### 2. Acceder a la aplicación
```
http://localhost:8000/app/login.html
```

### 3. Credenciales de prueba
```
Email: admin@admin.com
Password: password
Rol: admin
```

## 📋 Vistas Disponibles

### Login (`/app/login.html`)
- Autenticación con email y password
- Guarda token y datos de usuario
- Redirección automática a dashboard

### Dashboard (`/app/dashboard.html`)
- Estadísticas generales
- Usuarios recientes
- Acceso rápido a módulos

### Usuarios (`/app/users.html`)
- Lista de usuarios con paginación
- Crear nuevos usuarios
- Asignar roles a usuarios
- Eliminar usuarios
- Ver roles asignados

### Roles (`/app/roles.html`) - Solo Admin
- Lista de roles con permisos
- Crear nuevos roles
- Editar roles existentes
- Asignar permisos a roles
- Eliminar roles personalizados

### Permisos (`/app/permissions.html`) - Solo Admin
- Lista de permisos
- Crear nuevos permisos
- Editar permisos
- Eliminar permisos

## 🔐 Control de Acceso

### Público
- `/app/login.html`

### Autenticado
- `/app/dashboard.html`
- `/app/users.html`

### Solo Admin
- `/app/roles.html`
- `/app/permissions.html`

## 🎨 Plantilla

Las vistas usan la plantilla **Nifty Admin Template** con:
- Bootstrap 3
- Font Awesome
- jQuery
- Diseño responsive
- Menú lateral colapsable

## 📡 API Endpoints

Todas las vistas consumen la API REST en `/pista`:

```
POST   /pista/login                    - Login
POST   /pista/logout                   - Logout
GET    /pista/me                       - Usuario actual
GET    /pista/dashboard                - Dashboard
GET    /pista/users                    - Listar usuarios
POST   /pista/users                    - Crear usuario
DELETE /pista/users/{id}               - Eliminar usuario
GET    /pista/roles                    - Listar roles
POST   /pista/roles                    - Crear rol
PUT    /pista/roles/{id}               - Actualizar rol
DELETE /pista/roles/{id}               - Eliminar rol
GET    /pista/permissions              - Listar permisos
POST   /pista/permissions              - Crear permiso
POST   /pista/users/{id}/roles         - Asignar roles
GET    /pista/users/{id}/permissions   - Ver permisos
```

## 🔧 Funciones Comunes

El archivo `/js/app-common.js` proporciona:

```javascript
// Autenticación
checkAuth()                    // Verifica token
fetchAPI(endpoint, options)    // Peticiones autenticadas
logout()                       // Cerrar sesión

// Usuario
displayUserName()              // Mostrar nombre
hasRole(roleName)              // Verificar rol
hasPermission(permissionName)  // Verificar permiso
isAdmin()                      // Es admin?

// Utilidades
formatDate(dateString)         // Formatear fecha
formatDateTime(dateString)     // Formatear fecha/hora
```

## 🎯 Flujos de Trabajo

### Crear Usuario con Rol
1. Login como admin
2. Ir a Usuarios
3. Click "Nuevo Usuario"
4. Llenar formulario
5. Guardar
6. Click botón escudo (🛡️)
7. Seleccionar roles
8. Guardar

### Crear Rol Personalizado
1. Login como admin
2. Ir a Roles
3. Click "Nuevo Rol"
4. Ingresar nombre
5. Seleccionar permisos
6. Guardar

### Crear Permiso
1. Login como admin
2. Ir a Permisos
3. Click "Nuevo Permiso"
4. Ingresar nombre (formato: recurso.acción)
5. Guardar

## 🐛 Solución de Problemas

### No puedo acceder a Roles/Permisos
- Verificar que tengas rol `admin`
- Revisar en Dashboard si aparece menú Administración
- Verificar en consola: `localStorage.getItem('user')`

### Error 401 en las peticiones
- Token expirado o inválido
- Hacer logout y login nuevamente
- Verificar que el servidor esté corriendo

### Los cambios no se reflejan
- Limpiar caché del navegador
- Hacer hard refresh (Ctrl+F5)
- Verificar en Network tab del navegador

### Menú no se colapsa en móvil
- Verificar que nifty.js esté cargado
- Revisar consola por errores de JavaScript

## 📱 Responsive

Las vistas son responsive y funcionan en:
- Desktop (>1200px)
- Tablet (768px - 1199px)
- Mobile (<768px)

## 🎨 Personalización

### Cambiar colores
Editar `/css/custom.css`

### Cambiar logo
Reemplazar `/img/logo.png`

### Agregar nueva vista
1. Copiar estructura de una vista existente
2. Actualizar título y contenido
3. Agregar enlace en menú de navegación
4. Incluir `app-common.js`

## 📚 Documentación Adicional

- [VISTAS_HTML_ROLES.md](../../doc/VISTAS_HTML_ROLES.md) - Documentación detallada
- [ROLES_Y_PERMISOS.md](../../doc/ROLES_Y_PERMISOS.md) - Sistema de roles
- [API_GUIDE.md](../../doc/API_GUIDE.md) - Documentación de API

## ✅ Checklist de Desarrollo

- [x] Login funcional
- [x] Dashboard con estadísticas
- [x] CRUD de usuarios
- [x] Gestión de roles
- [x] Gestión de permisos
- [x] Asignación de roles a usuarios
- [x] Control de acceso por rol
- [x] Notificaciones toast
- [x] Diseño responsive
- [x] Manejo de errores

## 🚀 Próximas Funcionalidades

- [ ] Búsqueda y filtros
- [ ] Exportar datos
- [ ] Perfil de usuario
- [ ] Cambio de contraseña
- [ ] Temas claro/oscuro
- [ ] Notificaciones en tiempo real

---

**Plantilla:** Nifty Admin Template  
**Framework:** Laravel 12 + Sanctum + Spatie Permission  
**Última actualización:** 23 de Febrero, 2026
