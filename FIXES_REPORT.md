# FIXES REPORT — Perfumes Catalog

## Resumen

El frontend cargaba correctamente pero las APIs de `/home`, `/form-data`, `/categorias` y `/colecciones` devolvían error 500 con el mensaje **"Malformed UTF-8 characters, possibly incorrectly encoded"**. Esto impedía mostrar categorías en Home, el contador de artículos, y las opciones de Categoría/Colección en el formulario de productos. Los perfiles olfativos no se ven afectados porque son un array hardcodeado en el componente Vue (no vienen de la base de datos).

---

## Causa Raíz

### Problema 1 — Datos con encoding mixto en SQLite

El seeder fue ejecutado **dos veces** con el CSV en encodings diferentes:
- Primera ejecución: CSV en **UTF-8** → datos correctos (`\xc3\xa1` = á válido en UTF-8)
- Segunda ejecución: CSV en **Latin-1/ISO-8859-1** → datos corruptos (`\xe1` = á inválido en UTF-8)

Esto creó filas duplicadas con el mismo nombre pero encoding diferente:
| Tabla | ID correcto | ID duplicado (Latin-1) |
|---|---|---|
| `categorias` | 3 → `Acuático` | 9 → `Acu\xe1tico` |
| `categorias` | 6 → `Fougère` | 10 → `Foug\xe8re` |
| `colecciones` | 1 → `Clásicos Eternos` | 5 → `Cl\xe1sicos Eternos` |
| `colecciones` | 4 → `Edición Limitada 2024` | 6 → `Edici\xf3n Limitada 2024` |

Cuando Laravel intentaba serializar estos registros a JSON, `json_encode()` fallaba con `InvalidArgumentException` porque los bytes Latin-1 no son UTF-8 válido.

### Problema 2 — Sin flag de escape en `response()->json()`

Los controladores usaban `response()->json($data)` sin pasar `JSON_INVALID_UTF8_SUBSTITUTE`, lo que hacía que cualquier byte no-UTF-8 causara un error fatal en vez de ser sustituido.

### Problema 3 — Seeder sin normalización de encoding

El seeder usaba `firstOrCreate(['nombre' => $nombre])` para buscar categorías/colecciones, pero si el mismo nombre llegaba con dos encodings distintos (`Acuático` vs `Acu\xe1tico`), los consideraba registros distintos y creaba duplicados.

---

## Archivos Modificados

| Archivo | Tipo de cambio |
|---|---|
| `backend/database/database.sqlite` | **Datos corregidos** — 4 registros con Latin-1 convertidos a UTF-8; 4 filas duplicadas eliminadas |
| `backend/app/Http/Controllers/Api/HomeController.php` | Agregado `JSON_UNESCAPED_UNICODE \| JSON_INVALID_UTF8_SUBSTITUTE` |
| `backend/app/Http/Controllers/Api/CategoriaController.php` | Ídem en los 3 métodos |
| `backend/app/Http/Controllers/Api/ProductoController.php` | Ídem en todos los métodos |
| `backend/app/Http/Controllers/Api/BusquedaController.php` | Ídem |
| `backend/database/seeders/CatalogoPerfumesSeeder.php` | Agregado método `toUtf8()` para sanitizar strings del CSV; `firstOrCreate` por `slug` en lugar de `nombre` para evitar duplicados por encoding |

---

## Cambios Realizados en Detalle

### 1. Base de datos (SQLite)
```sql
-- Corregir encoding Latin-1 → UTF-8
UPDATE categorias SET nombre = 'Acuático' WHERE id = 9;
UPDATE categorias SET nombre = 'Fougère'  WHERE id = 10;
UPDATE colecciones SET nombre = 'Clásicos Eternos'       WHERE id = 5;
UPDATE colecciones SET nombre = 'Edición Limitada 2024'  WHERE id = 6;

-- Eliminar duplicados (sin productos asignados)
DELETE FROM categorias  WHERE id IN (9, 10);
DELETE FROM colecciones WHERE id IN (5, 6);
```

### 2. Controladores PHP
```php
// Antes
return response()->json(['categorias' => $categorias]);

// Después
return response()->json(['categorias' => $categorias], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
```

### 3. Seeder
```php
// Añadido método de sanitización
private function toUtf8(string $value): string
{
    if (mb_check_encoding($value, 'UTF-8')) return $value;
    return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
}

// firstOrCreate por slug (idempotente) en lugar de por nombre (sensible a encoding)
$categoria = Categoria::firstOrCreate(
    ['slug' => $catSlug],
    ['nombre' => $catNombre, 'activo' => true]
);
```

---

## Cómo Probar los Arreglos

### Requisitos
- PHP 8.2+
- Composer
- Node.js 18+

### Backend
```bash
cd backend
composer install
php artisan serve
# → Escucha en http://localhost:8000
```

Verificar endpoints:
```bash
curl http://localhost:8000/api/home
# → {"categorias":[{"id":1,"nombre":"Floral","productos_count":4,...},...]}

curl http://localhost:8000/api/form-data
# → {"categorias":[...],"colecciones":[...]}

curl http://localhost:8000/api/categorias
# → [{"id":1,"nombre":"Floral",...},...]
```

### Frontend
```bash
cd frontend
npm install
npm run dev
# → Escucha en http://localhost:5173
```

### Validaciones esperadas
- ✅ Home muestra 8 categorías con conteo de fragancias
- ✅ Contador en Home (ej. "128 artículos") muestra número real > 0
- ✅ Formulario "Nuevo Producto" carga categorías, colecciones y perfiles olfativos
- ✅ Sin errores 500 en consola del navegador
- ✅ Sin errores en `storage/logs/laravel.log`

---

## Por qué Perfiles Olfativos Funcionaban y el Resto No

Los perfiles olfativos son un **array hardcodeado** en `ProductoForm.vue` (frontend), por lo que nunca hacen una petición a la API y no se ven afectados por el error de encoding del backend.

```javascript
// ProductoForm.vue — hardcodeado, no depende de API
const perfiles = [
  'floral_aldhehido', 'fresco_especiado', ...
]
```
