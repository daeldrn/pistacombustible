# 🔌 Ejemplos de Consumo de API

## 📋 Tabla de Contenidos
1. [JavaScript Vanilla](#javascript-vanilla)
2. [jQuery](#jquery)
3. [React](#react)
4. [Vue.js](#vuejs)
5. [Angular](#angular)
6. [Axios](#axios)
7. [Fetch API](#fetch-api)
8. [PHP (cURL)](#php-curl)

---

## JavaScript Vanilla

### Login y Guardar Token
```javascript
async function login(email, password) {
    try {
        const response = await fetch('http://localhost:8000/pista/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (data.success) {
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            return data;
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

// Uso
login('admin@example.com', 'password')
    .then(data => console.log('Login exitoso', data))
    .catch(error => console.error('Error en login', error));
```

### Petición Autenticada
```javascript
async function getDashboardStats() {
    const token = localStorage.getItem('token');

    const response = await fetch('http://localhost:8000/pista/dashboard/stats', {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    });

    if (response.status === 401) {
        // Token inválido, redirigir a login
        window.location.href = '/login.html';
        return;
    }

    return await response.json();
}

// Uso
getDashboardStats()
    .then(data => console.log('Stats:', data))
    .catch(error => console.error('Error:', error));
```

### Crear Usuario
```javascript
async function createUser(userData) {
    const token = localStorage.getItem('token');

    const response = await fetch('http://localhost:8000/pista/users', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(userData)
    });

    return await response.json();
}

// Uso
createUser({
    name: 'Nuevo Usuario',
    email: 'nuevo@example.com',
    password: 'password123',
    password_confirmation: 'password123',
    activo: true
}).then(data => console.log('Usuario creado:', data));
```

---

## jQuery

### Configuración Global
```javascript
// Configurar token en todas las peticiones AJAX
$.ajaxSetup({
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'Accept': 'application/json'
    }
});
```

### Login
```javascript
function login(email, password) {
    $.ajax({
        url: 'http://localhost:8000/pista/login',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ email, password }),
        success: function(data) {
            if (data.success) {
                localStorage.setItem('token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));
                window.location.href = '/dashboard.html';
            }
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseJSON.message);
        }
    });
}
```

### Obtener Usuarios
```javascript
function getUsers(page = 1) {
    $.ajax({
        url: 'http://localhost:8000/pista/users?page=' + page,
        method: 'GET',
        success: function(data) {
            console.log('Usuarios:', data.data);
            console.log('Paginación:', data.pagination);
        },
        error: function(xhr) {
            if (xhr.status === 401) {
                window.location.href = '/login.html';
            }
        }
    });
}
```

---

## React

### Servicio API (api.js)
```javascript
const API_URL = 'http://localhost:8000/pista';

class ApiService {
    static getToken() {
        return localStorage.getItem('token');
    }

    static async request(endpoint, options = {}) {
        const token = this.getToken();
        
        const config = {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...(token && { 'Authorization': `Bearer ${token}` }),
                ...options.headers
            }
        };

        const response = await fetch(`${API_URL}${endpoint}`, config);

        if (response.status === 401) {
            localStorage.removeItem('token');
            window.location.href = '/login';
            return;
        }

        return await response.json();
    }

    static async login(email, password) {
        const data = await this.request('/login', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });

        if (data.success) {
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
        }

        return data;
    }

    static async getDashboard() {
        return await this.request('/dashboard');
    }

    static async getUsers(page = 1) {
        return await this.request(`/users?page=${page}`);
    }

    static async createUser(userData) {
        return await this.request('/users', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
    }

    static async logout() {
        await this.request('/logout', { method: 'POST' });
        localStorage.removeItem('token');
        localStorage.removeItem('user');
    }
}

export default ApiService;
```

### Componente Login
```jsx
import React, { useState } from 'react';
import ApiService from './api';

function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');

        try {
            const data = await ApiService.login(email, password);
            
            if (data.success) {
                window.location.href = '/dashboard';
            } else {
                setError(data.message);
            }
        } catch (err) {
            setError('Error de conexión');
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            {error && <div className="alert alert-danger">{error}</div>}
            
            <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="Email"
                required
            />
            
            <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Password"
                required
            />
            
            <button type="submit">Login</button>
        </form>
    );
}

export default Login;
```

### Hook Personalizado
```jsx
import { useState, useEffect } from 'react';
import ApiService from './api';

function useDashboard() {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        async function fetchData() {
            try {
                const result = await ApiService.getDashboard();
                setData(result.data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        }

        fetchData();
    }, []);

    return { data, loading, error };
}

// Uso
function Dashboard() {
    const { data, loading, error } = useDashboard();

    if (loading) return <div>Cargando...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div>
            <h1>Dashboard</h1>
            <p>Usuarios: {data.stats.users}</p>
        </div>
    );
}
```

---

## Vue.js

### Plugin API (api.js)
```javascript
import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/pista',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
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
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default {
    login(email, password) {
        return api.post('/login', { email, password });
    },
    
    logout() {
        return api.post('/logout');
    },
    
    getDashboard() {
        return api.get('/dashboard');
    },
    
    getUsers(page = 1) {
        return api.get(`/users?page=${page}`);
    },
    
    createUser(userData) {
        return api.post('/users', userData);
    },
    
    updateUser(id, userData) {
        return api.put(`/users/${id}`, userData);
    },
    
    deleteUser(id) {
        return api.delete(`/users/${id}`);
    }
};
```

### Componente Login (Vue 3)
```vue
<template>
  <div class="login">
    <form @submit.prevent="handleLogin">
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
      
      <input
        v-model="email"
        type="email"
        placeholder="Email"
        required
      />
      
      <input
        v-model="password"
        type="password"
        placeholder="Password"
        required
      />
      
      <button type="submit" :disabled="loading">
        {{ loading ? 'Cargando...' : 'Login' }}
      </button>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue';
import api from './api';

export default {
  setup() {
    const email = ref('');
    const password = ref('');
    const error = ref('');
    const loading = ref(false);

    const handleLogin = async () => {
      loading.value = true;
      error.value = '';

      try {
        const { data } = await api.login(email.value, password.value);
        
        if (data.success) {
          localStorage.setItem('token', data.token);
          localStorage.setItem('user', JSON.stringify(data.user));
          window.location.href = '/dashboard';
        } else {
          error.value = data.message;
        }
      } catch (err) {
        error.value = 'Error de conexión';
      } finally {
        loading.value = false;
      }
    };

    return {
      email,
      password,
      error,
      loading,
      handleLogin
    };
  }
};
</script>
```

---

## Angular

### Servicio API (api.service.ts)
```typescript
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private apiUrl = 'http://localhost:8000/api';

  constructor(private http: HttpClient) {}

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(token && { 'Authorization': `Bearer ${token}` })
    });
  }

  login(email: string, password: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, { email, password });
  }

  logout(): Observable<any> {
    return this.http.post(`${this.apiUrl}/logout`, {}, {
      headers: this.getHeaders()
    });
  }

  getDashboard(): Observable<any> {
    return this.http.get(`${this.apiUrl}/dashboard`, {
      headers: this.getHeaders()
    });
  }

  getUsers(page: number = 1): Observable<any> {
    return this.http.get(`${this.apiUrl}/users?page=${page}`, {
      headers: this.getHeaders()
    });
  }

  createUser(userData: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/users`, userData, {
      headers: this.getHeaders()
    });
  }
}
```

### Componente Login (login.component.ts)
```typescript
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from './api.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html'
})
export class LoginComponent {
  email: string = '';
  password: string = '';
  error: string = '';
  loading: boolean = false;

  constructor(
    private apiService: ApiService,
    private router: Router
  ) {}

  onSubmit() {
    this.loading = true;
    this.error = '';

    this.apiService.login(this.email, this.password).subscribe({
      next: (data) => {
        if (data.success) {
          localStorage.setItem('token', data.token);
          localStorage.setItem('user', JSON.stringify(data.user));
          this.router.navigate(['/dashboard']);
        } else {
          this.error = data.message;
        }
        this.loading = false;
      },
      error: (err) => {
        this.error = 'Error de conexión';
        this.loading = false;
      }
    });
  }
}
```

---

## Axios

### Configuración Global
```javascript
import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/pista',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
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
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default api;
```

### Uso
```javascript
import api from './api';

// Login
async function login(email, password) {
    const data = await api.post('/login', { email, password });
    localStorage.setItem('token', data.token);
    return data;
}

// Get Dashboard
async function getDashboard() {
    return await api.get('/dashboard');
}

// Create User
async function createUser(userData) {
    return await api.post('/users', userData);
}
```

---

## Fetch API

### Clase Helper
```javascript
class API {
    constructor(baseURL) {
        this.baseURL = baseURL;
    }

    async request(endpoint, options = {}) {
        const token = localStorage.getItem('token');
        
        const config = {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...(token && { 'Authorization': `Bearer ${token}` }),
                ...options.headers
            }
        };

        const response = await fetch(`${this.baseURL}${endpoint}`, config);

        if (!response.ok) {
            if (response.status === 401) {
                localStorage.removeItem('token');
                window.location.href = '/login';
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    get(endpoint) {
        return this.request(endpoint);
    }

    post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    put(endpoint, data) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    delete(endpoint) {
        return this.request(endpoint, {
            method: 'DELETE'
        });
    }
}

const api = new API('http://localhost:8000/pista');
export default api;
```

---

## PHP (cURL)

### Clase API Client
```php
<?php

class ApiClient {
    private $baseUrl;
    private $token;

    public function __construct($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    private function request($endpoint, $method = 'GET', $data = null) {
        $ch = curl_init($this->baseUrl . $endpoint);
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        if ($this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'code' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }

    public function login($email, $password) {
        $response = $this->request('/login', 'POST', [
            'email' => $email,
            'password' => $password
        ]);

        if ($response['data']['success']) {
            $this->setToken($response['data']['token']);
        }

        return $response['data'];
    }

    public function getDashboard() {
        return $this->request('/dashboard');
    }

    public function getUsers($page = 1) {
        return $this->request("/users?page=$page");
    }
}

// Uso
$api = new ApiClient('http://localhost:8000/pista');
$loginData = $api->login('admin@example.com', 'password');

if ($loginData['success']) {
    $dashboard = $api->getDashboard();
    print_r($dashboard);
}
```

---

## 🔒 Mejores Prácticas

### 1. Manejo de Tokens
```javascript
// Guardar token
localStorage.setItem('token', token);

// Obtener token
const token = localStorage.getItem('token');

// Eliminar token
localStorage.removeItem('token');

// Verificar si hay token
const isAuthenticated = !!localStorage.getItem('token');
```

### 2. Manejo de Errores
```javascript
try {
    const data = await api.request('/endpoint');
    // Manejar éxito
} catch (error) {
    if (error.response?.status === 401) {
        // No autenticado
        redirectToLogin();
    } else if (error.response?.status === 422) {
        // Errores de validación
        showValidationErrors(error.response.data.errors);
    } else {
        // Otro error
        showError('Error de conexión');
    }
}
```

### 3. Refresh de Token (si implementas)
```javascript
async function refreshToken() {
    const refreshToken = localStorage.getItem('refresh_token');
    const response = await fetch('/pista/refresh', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ refresh_token: refreshToken })
    });
    
    const data = await response.json();
    localStorage.setItem('token', data.token);
    return data.token;
}
```

---

¡Estos ejemplos te ayudarán a consumir tu API desde cualquier tecnología! 🚀
