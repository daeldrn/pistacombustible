# 🔧 Comandos Útiles

## 🚀 Iniciar Proyecto

```bash
# Instalar dependencias
composer install

# Configurar entorno
copy .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Crear usuario de prueba
php artisan tinker
# Luego: \App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'activo' => true]);

# Iniciar servidor
php artisan serve
```

---

## 🗄️ Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Revertir todas y ejecutar de nuevo
php artisan migrate:fresh

# Ejecutar seeders
php artisan db:seed

# Migrar y seedear
php artisan migrate:fresh --seed
```

---

## 🧹 Limpiar Cache

```bash
# Limpiar cache de configuración
php artisan config:clear

# Limpiar cache de rutas
php artisan route:clear

# Limpiar cache de vistas
php artisan view:clear

# Limpiar todo
php artisan optimize:clear
```

---

## 📊 Ver Información

```bash
# Ver todas las rutas
php artisan route:list

# Ver solo rutas API
php artisan route:list --path=api

# Ver configuración
php artisan config:show

# Ver información de la aplicación
php artisan about
```

---

## 🔐 Sanctum

```bash
# Publicar configuración de Sanctum (si necesitas personalizarla)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Ver tokens de un usuario (en tinker)
php artisan tinker
# Luego: \App\Models\User::find(1)->tokens
```

---

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=AuthTest

# Ejecutar con coverage
php artisan test --coverage
```

---

## 👤 Gestión de Usuarios (Tinker)

```bash
php artisan tinker
```

### Crear usuario
```php
\App\Models\User::create([
    'name' => 'Usuario Test',
    'email' => 'test@example.com',
    'password' => bcrypt('password123'),
    'activo' => true
]);
```

### Ver todos los usuarios
```php
\App\Models\User::all();
```

### Buscar usuario por email
```php
\App\Models\User::where('email', 'admin@example.com')->first();
```

### Actualizar usuario
```php
$user = \App\Models\User::find(1);
$user->name = 'Nuevo Nombre';
$user->save();
```

### Eliminar usuario
```php
\App\Models\User::find(1)->delete();
```

### Ver usuarios eliminados (soft delete)
```php
\App\Models\User::onlyTrashed()->get();
```

### Restaurar usuario eliminado
```php
\App\Models\User::withTrashed()->find(1)->restore();
```

---

## 🔑 Gestión de Tokens (Tinker)

```bash
php artisan tinker
```

### Ver tokens de un usuario
```php
$user = \App\Models\User::find(1);
$user->tokens;
```

### Crear token manualmente
```php
$user = \App\Models\User::find(1);
$token = $user->createToken('nombre-token');
echo $token->plainTextToken;
```

### Revocar todos los tokens de un usuario
```php
$user = \App\Models\User::find(1);
$user->tokens()->delete();
```

### Revocar token específico
```php
$user = \App\Models\User::find(1);
$user->tokens()->where('id', 1)->delete();
```

---

## 📝 Logs

```bash
# Ver logs en tiempo real (Windows PowerShell)
Get-Content storage/logs/laravel.log -Wait -Tail 50

# Ver últimas líneas del log
type storage\logs\laravel.log | more

# Limpiar logs
del storage\logs\laravel.log
```

---

## 🔄 Actualizar Proyecto

```bash
# Actualizar dependencias
composer update

# Actualizar autoload
composer dump-autoload

# Optimizar para producción
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🐛 Debugging

```bash
# Modo debug (en .env)
APP_DEBUG=true

# Ver queries SQL (en AppServiceProvider)
# \DB::listen(function($query) {
#     \Log::info($query->sql, $query->bindings);
# });

# Usar dd() en código para debug
dd($variable);

# Usar dump() para ver sin detener ejecución
dump($variable);
```

---

## 🌐 Servidor de Desarrollo

```bash
# Iniciar en puerto por defecto (8000)
php artisan serve

# Iniciar en puerto específico
php artisan serve --port=8080

# Iniciar en host específico
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 📦 Crear Nuevos Componentes

```bash
# Crear controlador API
php artisan make:controller Api/NombreController --api

# Crear modelo con migración
php artisan make:model Nombre -m

# Crear request de validación
php artisan make:request NombreRequest

# Crear policy
php artisan make:policy NombrePolicy

# Crear middleware
php artisan make:middleware NombreMiddleware

# Crear seeder
php artisan make:seeder NombreSeeder

# Crear evento
php artisan make:event NombreEvent

# Crear listener
php artisan make:listener NombreListener
```

---

## 🔒 Permisos (Windows)

```bash
# Si tienes problemas de permisos en storage o cache
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap\cache /grant Everyone:(OI)(CI)F /T
```

---

## 📊 Estadísticas del Proyecto

```bash
# Contar líneas de código
dir -Recurse -Include *.php | Get-Content | Measure-Object -Line

# Ver tamaño de carpetas
dir | ForEach-Object {$_.Name + ": " + (Get-ChildItem $_.FullName -Recurse | Measure-Object -Property Length -Sum).Sum / 1MB + " MB"}
```

---

## 🚀 Deploy a Producción

```bash
# 1. Optimizar autoloader
composer install --optimize-autoloader --no-dev

# 2. Cachear configuración
php artisan config:cache

# 3. Cachear rutas
php artisan route:cache

# 4. Cachear vistas
php artisan view:cache

# 5. Ejecutar migraciones
php artisan migrate --force

# 6. Configurar .env para producción
APP_ENV=production
APP_DEBUG=false
```
