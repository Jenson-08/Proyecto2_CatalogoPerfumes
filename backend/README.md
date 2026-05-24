# Perfumes Catalog — Backend (Laravel 12 API)

Laravel RESTful API que expone todos los datos del catálogo de perfumes.
Base de datos SQLite incluida con datos precargados.

---

## Requisitos

- PHP 8.2+
- Composer
- Extensión `pdo_sqlite`

---

## Instalación y ejecución local

```bash
cd backend

composer install

# Variables de entorno (SQLite ya configurado)
cp .env.example .env
php artisan key:generate

# La BD SQLite ya existe en database/database.sqlite
# Para recrearla desde cero:
#   php artisan migrate --force && php artisan db:seed

php artisan serve
# API disponible en http://localhost:8000/api
```

---

## Variables de entorno clave

| Variable         | Valor por defecto         | Descripción                  |
|------------------|---------------------------|------------------------------|
| `APP_URL`        | `http://localhost:8000`   | URL base del backend         |
| `DB_CONNECTION`  | `sqlite`                  | Motor de base de datos       |
| `FRONTEND_URL`   | `http://localhost:5173`   | URL del frontend (referencia)|

---

## Endpoints de la API

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/home` | Categorías con conteo de productos |
| GET | `/api/categorias` | Lista de categorías activas |
| GET | `/api/categorias/{slug}` | Productos de una categoría (paginado) |
| GET | `/api/colecciones` | Lista de colecciones activas |
| GET | `/api/form-data` | Categorías + colecciones para formularios |
| GET | `/api/buscar?q=texto` | Búsqueda de productos |
| GET | `/api/productos` | Lista admin de productos (paginado) |
| POST | `/api/productos` | Crear producto |
| GET | `/api/productos/{id}` | Detalle de producto |
| PUT | `/api/productos/{id}` | Actualizar producto |
| DELETE | `/api/productos/{id}` | Eliminar producto |
| GET | `/api/productos/{id}/variantes` | Variantes de un producto |
| POST | `/api/productos/{id}/variantes` | Crear variante |
| PUT | `/api/productos/{id}/variantes/{vid}` | Actualizar variante |
| DELETE | `/api/productos/{id}/variantes/{vid}` | Eliminar variante |

Todas las respuestas son JSON. CORS habilitado para `*` (ajustar `config/cors.php` en producción).

---

## Docker

```bash
docker build -t perfumes-backend .
docker run -p 8000:8000 perfumes-backend
```

---

## Estructura relevante

```
app/Http/Controllers/Api/   ← Controladores de la API
routes/api.php              ← Definición de rutas API
config/cors.php             ← Configuración CORS
database/database.sqlite    ← Base de datos con datos precargados
```
