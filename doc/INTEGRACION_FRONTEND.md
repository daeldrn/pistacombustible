# 🎨 Guía de Integración con Frontend

## 📋 Información General

**Base URL:** `http://tu-dominio.com/pista`  
**Formato:** JSON  
**Autenticación:** Bearer Token (Laravel Sanctum)  
**Headers requeridos:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
```

---

## 🔐 Flujo de Autenticación

### 1. Login

**Endpoint:** `POST /pista/login`

**Request:**
```javascript
fetch('http://localhost:8000/pista/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    email: 'test@example.com',
    password: 'password'
  })
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    // Guardar token
    localStorage.setItem('token', data.data.token);
    localStorage.setItem('user', JSON.stringify(data.data.user));
    // Redirigir al dashboard
    window.location.href = '/dashboard';
  } else {
    alert(data.message);
  }
});
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
      "name": "Usuario Test",
      "email": "test@example.com",
      "activo": true,
      "created_at": "2026-02-23T10:00:00.000000Z",
      "updated_at": "2026-02-23T10:00:00.000000Z"
    }
  }
}
```

**Response (401):**
```json
{
  "success": false,
  "message": "Credenciales incorrectas"
}
```

### 2. Obtener Usuario Autenticado

**Endpoint:** `GET /pista/me`

```javascript
fetch('http://localhost:8000/pista/me', {
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Usuario:', data.data);
  }
});
```

### 3. Logout

**Endpoint:** `POST /pista/logout`

```javascript
fetch('http://localhost:8000/pista/logout', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
  }
});
```

---

## 👥 Gestión de Usuarios

### 1. Listar Usuarios

**Endpoint:** `GET /pista/users?per_page=10`

```javascript
async function getUsers(page = 1, perPage = 10) {
  const response = await fetch(
    `http://localhost:8000/pista/users?page=${page}&per_page=${perPage}`,
    {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    }
  );
  
  const data = await response.json();
  
  if (data.success) {
    return {
      users: data.data.data,
      pagination: data.data.meta
    };
  }
  
  throw new Error(data.message);
}

// Uso
getUsers(1, 10).then(result => {
  console.log('Usuarios:', result.users);
  console.log('Paginación:', result.pagination);
});
```

**Response:**
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

### 2. Crear Usuario

**Endpoint:** `POST /pista/users`

```javascript
async function createUser(userData) {
  const response = await fetch('http://localhost:8000/pista/users', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    },
    body: JSON.stringify(userData)
  });
  
  const data = await response.json();
  
  if (!data.success) {
    throw new Error(data.message);
  }
  
  return data.data;
}

// Uso
createUser({
  name: 'Nuevo Usuario',
  email: 'nuevo@example.com',
  password: 'password123',
  password_confirmation: 'password123',
  activo: true
})
.then(user => {
  console.log('Usuario creado:', user);
  alert('Usuario creado exitosamente');
})
.catch(error => {
  console.error('Error:', error);
  alert(error.message);
});
```

### 3. Actualizar Usuario

**Endpoint:** `PUT /pista/users/{id}`

```javascript
async function updateUser(userId, userData) {
  const response = await fetch(`http://localhost:8000/pista/users/${userId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    },
    body: JSON.stringify(userData)
  });
  
  const data = await response.json();
  
  if (!data.success) {
    throw new Error(data.message);
  }
  
  return data.data;
}

// Uso
updateUser(1, {
  name: 'Usuario Actualizado',
  email: 'actualizado@example.com',
  activo: false
})
.then(user => {
  console.log('Usuario actualizado:', user);
})
.catch(error => {
  console.error('Error:', error);
});
```

### 4. Eliminar Usuario

**Endpoint:** `DELETE /pista/users/{id}`

```javascript
async function deleteUser(userId) {
  const response = await fetch(`http://localhost:8000/pista/users/${userId}`, {
    method: 'DELETE',
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    }
  });
  
  const data = await response.json();
  
  if (!data.success) {
    throw new Error(data.message);
  }
  
  return true;
}

// Uso
if (confirm('¿Estás seguro de eliminar este usuario?')) {
  deleteUser(2)
    .then(() => {
      alert('Usuario eliminado exitosamente');
      // Recargar lista
    })
    .catch(error => {
      alert(error.message);
    });
}
```

---

## 📊 Dashboard

### 1. Obtener Estadísticas

**Endpoint:** `GET /pista/dashboard/stats`

```javascript
async function getDashboardStats() {
  const response = await fetch('http://localhost:8000/pista/dashboard/stats', {
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    }
  });
  
  const data = await response.json();
  
  if (data.success) {
    return data.data;
  }
  
  throw new Error(data.message);
}

// Uso
getDashboardStats().then(stats => {
  document.getElementById('total-users').textContent = stats.users;
  document.getElementById('active-users').textContent = stats.active_users;
  document.getElementById('inactive-users').textContent = stats.inactive_users;
});
```

### 2. Obtener Usuarios Recientes

**Endpoint:** `GET /pista/dashboard/recent-users`

```javascript
async function getRecentUsers() {
  const response = await fetch('http://localhost:8000/pista/dashboard/recent-users', {
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    }
  });
  
  const data = await response.json();
  
  if (data.success) {
    return data.data;
  }
  
  throw new Error(data.message);
}

// Uso
getRecentUsers().then(users => {
  const tbody = document.getElementById('recent-users-table');
  tbody.innerHTML = users.map(user => `
    <tr>
      <td>${user.id}</td>
      <td>${user.name}</td>
      <td>${user.email}</td>
      <td>${user.activo ? 'Activo' : 'Inactivo'}</td>
    </tr>
  `).join('');
});
```

### 3. Dashboard Completo

**Endpoint:** `GET /pista/dashboard`

```javascript
async function getDashboard() {
  const response = await fetch('http://localhost:8000/pista/dashboard', {
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    }
  });
  
  const data = await response.json();
  
  if (data.success) {
    return data.data;
  }
  
  throw new Error(data.message);
}

// Uso
getDashboard().then(dashboard => {
  // Actualizar estadísticas
  updateStats(dashboard.stats);
  // Actualizar tabla de usuarios recientes
  updateRecentUsers(dashboard.recent_users);
});
```

---

## 🛡️ Manejo de Errores

### Interceptor de Errores

```javascript
async function apiRequest(url, options = {}) {
  const token = localStorage.getItem('token');
  
  const defaultOptions = {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      ...(token && { 'Authorization': `Bearer ${token}` })
    }
  };
  
  const response = await fetch(url, { ...defaultOptions, ...options });
  const data = await response.json();
  
  // Manejar errores
  if (!response.ok) {
    switch (response.status) {
      case 401:
        // No autenticado - redirigir a login
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
        break;
      
      case 403:
        // No autorizado
        alert('No tienes permisos para realizar esta acción');
        break;
      
      case 404:
        // No encontrado
        alert('Recurso no encontrado');
        break;
      
      case 422:
        // Error de validación
        const errors = data.errors;
        const errorMessages = Object.values(errors).flat().join('\n');
        alert(`Errores de validación:\n${errorMessages}`);
        break;
      
      case 429:
        // Rate limiting
        alert('Demasiadas solicitudes. Por favor espera un momento.');
        break;
      
      case 500:
        // Error del servidor
        alert('Error del servidor. Por favor intenta más tarde.');
        break;
      
      default:
        alert(data.message || 'Ha ocurrido un error');
    }
    
    throw new Error(data.message);
  }
  
  return data;
}

// Uso
apiRequest('http://localhost:8000/pista/users')
  .then(data => {
    console.log('Usuarios:', data.data);
  })
  .catch(error => {
    console.error('Error:', error);
  });
```

---

## 🎨 Ejemplo Completo con Axios

```javascript
// Configuración de Axios
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/pista',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});

// Interceptor para agregar token
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Interceptor para manejar errores
api.interceptors.response.use(
  response => response.data,
  error => {
    const { response } = error;
    
    if (response) {
      switch (response.status) {
        case 401:
          localStorage.removeItem('token');
          window.location.href = '/login';
          break;
        case 403:
          alert('No autorizado');
          break;
        case 422:
          const errors = Object.values(response.data.errors).flat();
          alert(errors.join('\n'));
          break;
        default:
          alert(response.data.message || 'Error');
      }
    }
    
    return Promise.reject(error);
  }
);

// Servicios
export const authService = {
  login: (email, password) => api.post('/login', { email, password }),
  logout: () => api.post('/logout'),
  me: () => api.get('/me')
};

export const userService = {
  list: (page = 1, perPage = 10) => api.get(`/users?page=${page}&per_page=${perPage}`),
  create: (data) => api.post('/users', data),
  get: (id) => api.get(`/users/${id}`),
  update: (id, data) => api.put(`/users/${id}`, data),
  delete: (id) => api.delete(`/users/${id}`)
};

export const dashboardService = {
  stats: () => api.get('/dashboard/stats'),
  recentUsers: () => api.get('/dashboard/recent-users'),
  all: () => api.get('/dashboard')
};

// Uso
authService.login('test@example.com', 'password')
  .then(data => {
    localStorage.setItem('token', data.data.token);
    localStorage.setItem('user', JSON.stringify(data.data.user));
    window.location.href = '/dashboard';
  });

userService.list(1, 10)
  .then(data => {
    console.log('Usuarios:', data.data.data);
    console.log('Paginación:', data.data.meta);
  });
```

---

## 🎯 Ejemplo con React

```jsx
import { useState, useEffect } from 'react';
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/pista',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});

// Hook personalizado para autenticación
function useAuth() {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token'));
  
  useEffect(() => {
    if (token) {
      api.defaults.headers.Authorization = `Bearer ${token}`;
      // Obtener usuario actual
      api.get('/me')
        .then(response => setUser(response.data.data))
        .catch(() => {
          localStorage.removeItem('token');
          setToken(null);
        });
    }
  }, [token]);
  
  const login = async (email, password) => {
    const response = await api.post('/login', { email, password });
    const { token, user } = response.data.data;
    localStorage.setItem('token', token);
    setToken(token);
    setUser(user);
  };
  
  const logout = async () => {
    await api.post('/logout');
    localStorage.removeItem('token');
    setToken(null);
    setUser(null);
  };
  
  return { user, token, login, logout };
}

// Componente de Login
function LoginForm() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const { login } = useAuth();
  
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await login(email, password);
      // Redirigir al dashboard
    } catch (error) {
      alert('Error al iniciar sesión');
    }
  };
  
  return (
    <form onSubmit={handleSubmit}>
      <input
        type="email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
        placeholder="Email"
      />
      <input
        type="password"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        placeholder="Contraseña"
      />
      <button type="submit">Iniciar Sesión</button>
    </form>
  );
}

// Componente de Lista de Usuarios
function UserList() {
  const [users, setUsers] = useState([]);
  const [pagination, setPagination] = useState({});
  const [loading, setLoading] = useState(true);
  
  useEffect(() => {
    loadUsers();
  }, []);
  
  const loadUsers = async (page = 1) => {
    setLoading(true);
    try {
      const response = await api.get(`/users?page=${page}&per_page=10`);
      setUsers(response.data.data.data);
      setPagination(response.data.data.meta);
    } catch (error) {
      alert('Error al cargar usuarios');
    } finally {
      setLoading(false);
    }
  };
  
  if (loading) return <div>Cargando...</div>;
  
  return (
    <div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          {users.map(user => (
            <tr key={user.id}>
              <td>{user.id}</td>
              <td>{user.name}</td>
              <td>{user.email}</td>
              <td>{user.activo ? 'Activo' : 'Inactivo'}</td>
            </tr>
          ))}
        </tbody>
      </table>
      
      <div>
        Página {pagination.current_page} de {pagination.last_page}
        <button onClick={() => loadUsers(pagination.current_page - 1)} disabled={pagination.current_page === 1}>
          Anterior
        </button>
        <button onClick={() => loadUsers(pagination.current_page + 1)} disabled={pagination.current_page === pagination.last_page}>
          Siguiente
        </button>
      </div>
    </div>
  );
}
```

---

## 📝 Notas Importantes

1. **CORS**: Asegúrate de configurar CORS en Laravel si el frontend está en otro dominio
2. **Token**: Guarda el token de forma segura (localStorage o sessionStorage)
3. **Refresh**: Considera implementar refresh de token para sesiones largas
4. **Errores**: Maneja todos los códigos de estado HTTP
5. **Loading**: Muestra indicadores de carga durante las peticiones
6. **Validación**: Valida datos en el frontend antes de enviar

---

**¡Tu frontend está listo para integrarse con la API! 🚀**
