<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <p class="section-title mb-0">Panel</p>
        <h2 class="fw-normal mb-0">Administrar Productos</h2>
      </div>
      <RouterLink to="/productos/nuevo" class="btn btn-dark btn-sm px-4">+ Nuevo Producto</RouterLink>
    </div>

    <div v-if="loading" class="spinner-wrapper">
      <div class="spinner-border text-secondary" role="status"></div>
    </div>

    <template v-else>
      <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size: 0.88rem">
          <thead style="background: #f5f3ef; font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase">
            <tr>
              <th>Producto</th>
              <th>SKU</th>
              <th>Categoría</th>
              <th>Colección</th>
              <th>Género</th>
              <th class="text-center">Variantes</th>
              <th class="text-center">Estado</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="producto in productos" :key="producto.id">
              <td>
                <div class="d-flex align-items-center gap-2">
                  <img
                    v-if="producto.imagen_url"
                    :src="producto.imagen_url"
                    style="width: 40px; height: 40px; object-fit: contain"
                    alt=""
                  />
                  <RouterLink
                    :to="`/productos/${producto.id}`"
                    class="text-dark fw-bold text-decoration-none"
                  >
                    {{ producto.nombre }}
                  </RouterLink>
                  <span v-if="producto.edicion_limitada" class="badge badge-limited">Ltd.</span>
                </div>
              </td>
              <td class="text-muted">{{ producto.sku }}</td>
              <td>{{ producto.categoria?.nombre ?? '—' }}</td>
              <td>{{ producto.coleccion?.nombre ?? '—' }}</td>
              <td>{{ capitalize(producto.genero) }}</td>
              <td class="text-center">{{ producto.variantes?.length ?? 0 }}</td>
              <td class="text-center">
                <span class="badge" :class="producto.activo ? 'bg-success' : 'bg-secondary'">
                  {{ producto.activo ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="text-end">
                <div class="d-flex gap-1 justify-content-end">
                  <RouterLink :to="`/productos/${producto.id}/editar`" class="btn btn-sm btn-outline-dark">
                    Editar
                  </RouterLink>
                  <DeleteConfirm
                    :uid="`prod${producto.id}`"
                    :label="producto.nombre"
                    @confirm="eliminar(producto)"
                  />
                </div>
              </td>
            </tr>

            <tr v-if="productos.length === 0">
              <td colspan="8" class="text-center py-5 text-muted">
                No hay productos.
                <RouterLink to="/productos/nuevo">Crea el primero.</RouterLink>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <AppPagination :paginator="paginator" @change="fetchPage" />
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
import AppPagination from '../../components/AppPagination.vue'
import DeleteConfirm from '../../components/DeleteConfirm.vue'

const emit = defineEmits(['flash'])

const loading = ref(true)
const productos = ref([])
const paginator = ref(null)

async function fetchPage(page = 1) {
  loading.value = true
  try {
    const res = await api.getProductos(page)
    productos.value = res.data.data
    paginator.value = res.data
  } catch {
    productos.value = []
  } finally {
    loading.value = false
  }
}

async function eliminar(producto) {
  try {
    await api.eliminarProducto(producto.id)
    emit('flash', 'Producto eliminado correctamente.')
    fetchPage()
  } catch {
    emit('flash', 'Error al eliminar el producto.')
  }
}

function capitalize(str) {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
}

onMounted(() => fetchPage())
</script>
