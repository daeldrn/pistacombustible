/**
 * Funciones comunes para la aplicación
 */

const API_URL = '/pista';

/**
 * Verificar autenticación
 */
function checkAuth() {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/app/login.html';
        return null;
    }
    return token;
}

/**
 * Hacer petición autenticada a la API
 */
async function fetchAPI(endpoint, options = {}) {
    const token = checkAuth();
    if (!token) return;

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
        ...options.headers
    };

    try {
        const response = await fetch(`${API_URL}${endpoint}`, {
            ...options,
            headers
        });

        if (response.status === 401) {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            window.location.href = '/app/login.html';
            return null;
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        return null;
    }
}

/**
 * Mostrar nombre de usuario en el navbar
 */
function displayUserName() {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const userNameElement = document.getElementById('userName');

    if (user.name && userNameElement) {
        userNameElement.textContent = user.name;
    }
}

/**
 * Verificar si el usuario tiene un rol específico
 */
function hasRole(roleName) {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    return user.roles && user.roles.includes(roleName);
}

/**
 * Verificar si el usuario tiene un permiso específico
 */
function hasPermission(permissionName) {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    return user.permissions && user.permissions.includes(permissionName);
}

/**
 * Verificar si el usuario es admin
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Ocultar elementos del menú según permisos
 */
function applyMenuPermissions() {
    const menuItems = {
        'users': { permission: 'view users', selector: 'a[href="/app/users.html"]' },
        'roles': { permission: 'view roles', selector: 'a[href="/app/roles.html"]' },
        'permissions': { permission: 'view permissions', selector: 'a[href="/app/permissions.html"]' }
    };

    Object.keys(menuItems).forEach(key => {
        const item = menuItems[key];
        const element = document.querySelector(item.selector);

        if (element) {
            const listItem = element.closest('li');
            if (listItem && !hasPermission(item.permission)) {
                listItem.style.display = 'none';
            }
        }
    });

    // Ocultar sección de Administración si no tiene ningún permiso
    const adminSection = document.querySelector('.list-header:not(:first-child)');
    if (adminSection && adminSection.textContent.includes('Administración')) {
        const hasAnyAdminPermission = hasPermission('view roles') || hasPermission('view permissions');
        if (!hasAnyAdminPermission) {
            adminSection.style.display = 'none';
            // Ocultar también el divider anterior
            const divider = adminSection.previousElementSibling;
            if (divider && divider.classList.contains('list-divider')) {
                divider.style.display = 'none';
            }
        }
    }
}

/**
 * Logout
 */
async function logout() {
    await fetchAPI('/logout', { method: 'POST' });
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    window.location.href = '/app/login.html';
}

/**
 * Configurar evento de logout
 */
document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            await logout();
        });
    }
});

/**
 * Formatear fecha
 */
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

/**
 * Formatear fecha y hora
 */
function formatDateTime(dateString) {
    return new Date(dateString).toLocaleString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
