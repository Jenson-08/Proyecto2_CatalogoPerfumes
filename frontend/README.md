# Perfumes Catalog — Frontend (Vue 3 + Vite)

SPA en Vue 3 que consume la API REST del backend Laravel.  
Mantiene exactamente el mismo diseño visual del proyecto Blade original.

---

## Requisitos

- Node.js 18+
- npm 9+

---

## Instalación y ejecución local

```bash
cd frontend

npm install

# Configurar la URL del backend
cp .env.example .env
# Editar VITE_API_URL si el backend corre en un puerto distinto

npm run dev
# Frontend disponible en http://localhost:5173
```

> El proxy de Vite redirige `/api/*` → `http://localhost:8000` en desarrollo,
> por lo que no es necesario modificar nada si el backend corre en el puerto 8000.

---

## Build de producción

```bash
npm run build
# Salida en dist/
```

La carpeta `dist/` puede servirse con cualquier servidor estático (Nginx, Apache, Netlify, Vercel, etc.).  
Configurar el servidor para redirigir todas las rutas a `index.html` (modo history de Vue Router).

### Ejemplo Nginx

```nginx
server {
    listen 80;
    root /var/www/perfumes-frontend/dist;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }
}
```

---

## Variables de entorno

| Variable | Valor por defecto | Descripción |
|---|---|---|
| `VITE_API_URL` | `http://localhost:8000/api` | URL base de la API |

---

## Estructura del proyecto

```
src/
├── api/
│   └── index.js          ← Cliente Axios centralizado
├── components/
│   ├── AppNavbar.vue     ← Barra de navegación
│   ├── AppFooter.vue     ← Pie de página
│   ├── AppPagination.vue ← Paginación reutilizable
│   ├── DeleteConfirm.vue ← Modal de confirmación de eliminación
│   ├── ProductCard.vue   ← Tarjeta de producto
│   ├── ProductoForm.vue  ← Formulario CRUD de producto
│   └── VarianteForm.vue  ← Formulario CRUD de variante
├── router/
│   └── index.js          ← Vue Router (modo history)
├── views/
│   ├── HomeView.vue
│   ├── CategoriaView.vue
│   ├── BusquedaView.vue
│   ├── productos/
│   │   ├── ProductosIndex.vue
│   │   ├── ProductoShow.vue
│   │   ├── ProductoCreate.vue
│   │   └── ProductoEdit.vue
│   └── variantes/
│       ├── VarianteCreate.vue
│       └── VarianteEdit.vue
├── App.vue               ← Layout raíz + flash messages
├── main.js               ← Punto de entrada
└── style.css             ← Estilos globales (idénticos al original)
dist/                     ← Build de producción generado
```
