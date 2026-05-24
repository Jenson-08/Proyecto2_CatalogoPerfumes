# Perfumes Catalog — Migración Laravel + Vue 3

Arquitectura separada entre **backend** (Laravel 12 API REST) y **frontend** (Vue 3 SPA).

---

## Estructura del repositorio

```
perfumes-catalog/
├── backend/      ← Laravel 12 (API REST, SQLite)
├── frontend/     ← Vue 3 + Vite (SPA)
│   └── dist/     ← Build de producción listo para desplegar
└── README.md
```

---

## Inicio rápido (desarrollo local)

### 1. Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
# → http://localhost:8000
```

La base de datos SQLite (`database/database.sqlite`) ya contiene datos.

### 2. Frontend

```bash
cd frontend
npm install
npm run dev
# → http://localhost:5173
```

Vite redirige `/api/*` al backend automáticamente en desarrollo.

---

## Producción

### Backend
Apunta el servidor web a `backend/public/`. Configurar `APP_ENV=production` y ajustar `config/cors.php` para permitir solo el dominio del frontend.

### Frontend
```bash
cd frontend && npm run build
```
Sirve `frontend/dist/` con cualquier servidor estático. Redirigir todas las rutas a `index.html`.

---

## Decisiones de arquitectura

| Decisión | Razonamiento |
|---|---|
| Rutas API bajo `/api/` con `api.php` | Convención Laravel; aisladas del frontend |
| CORS abierto (`*`) en dev | Simplifica la configuración local; restringir en producción |
| Vue Router en modo `history` | URLs limpias sin `#`; requiere fallback en el servidor |
| Axios centralizado en `src/api/index.js` | Un único punto para cambiar `baseURL` o headers |
| SQLite sin cambios | La BD original funciona directamente; sin migración de motor necesaria |
| Bootstrap 5 via CDN | Mantiene fidelidad visual exacta con el proyecto Blade original |
| `precio_desde` como accessor en el modelo | Reutiliza la lógica PHP existente; se expone con `->append()` |

---

## Dependencias principales

| Paquete | Versión | Rol |
|---|---|---|
| `laravel/framework` | ^12.0 | API backend |
| `vue` | ^3.5 | UI reactiva |
| `vue-router` | ^4.5 | Navegación SPA |
| `axios` | ^1.7 | Llamadas HTTP |
| `vite` + `@vitejs/plugin-vue` | ^6 / ^5 | Bundler y build |
