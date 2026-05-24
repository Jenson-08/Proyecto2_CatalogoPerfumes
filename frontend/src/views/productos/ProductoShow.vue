<template>
  <div>
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb" style="font-size: 0.8rem">
        <li class="breadcrumb-item">
          <RouterLink to="/" class="text-dark">Inicio</RouterLink>
        </li>
        <li v-if="producto?.categoria" class="breadcrumb-item">
          <RouterLink :to="`/categorias/${producto.categoria.slug}`" class="text-dark">
            {{ producto.categoria.nombre }}
          </RouterLink>
        </li>
        <li class="breadcrumb-item active">{{ producto?.nombre }}</li>
      </ol>
    </nav>

    <div v-if="loading" class="spinner-wrapper">
      <div class="spinner-border text-secondary" role="status"></div>
    </div>

    <div v-else-if="producto" class="row g-5">
      <!-- Imagen -->
      <div class="col-md-5">
        <img
          v-if="producto.imagen_url"
          :src="producto.imagen_url"
          class="w-100"
          :alt="producto.nombre"
          style="object-fit: contain; max-height: 420px; background: #fafafa; padding: 2rem"
        />
        <div
          v-else
          class="img-placeholder"
          style="height: 420px; background: #f0ece4; font-size: 4rem"
        >
          ✦
        </div>
      </div>

      <!-- Info -->
      <div class="col-md-7">
        <span v-if="producto.edicion_limitada" class="badge badge-limited mb-2">
          Edición Limitada
        </span>

        <p class="section-title mb-1">
          {{ producto.categoria?.nombre }} &bull; {{ producto.coleccion?.nombre }}
        </p>
        <h1 class="fw-normal mb-1" style="font-size: 1.8rem">{{ producto.nombre }}</h1>
        <p class="text-muted small mb-3">
          SKU: {{ producto.sku }} &bull; {{ capitalize(producto.genero) }}
        </p>

        <p v-if="producto.descripcion" class="mb-3" style="font-size: 0.95rem; line-height: 1.7">
          {{ producto.descripcion }}
        </p>

        <div v-if="producto.notas_olfativas" class="mb-3">
          <p class="section-title mb-1">Notas olfativas</p>
          <p class="mb-0" style="font-size: 0.9rem">{{ producto.notas_olfativas }}</p>
        </div>

        <div v-if="producto.perfil_olfativo" class="mb-4">
          <p class="section-title mb-1">Perfil olfativo</p>
          <span class="badge bg-light text-dark border" style="font-size: 0.75rem">
            {{ producto.perfil_olfativo.replace(/_/g, ' ') }}
          </span>
        </div>

        <!-- Variantes -->
        <div class="mb-4">
          <p class="section-title mb-2">Presentaciones disponibles</p>

          <div
            v-for="variante in producto.variantes"
            :key="variante.id"
            class="d-flex align-items-center justify-content-between p-3 mb-2"
            style="background: #fff; border: 1px solid #e8e4db"
          >
            <div>
              <span class="fw-bold" style="font-size: 0.95rem">{{ variante.presentacion }}</span>
              <span class="text-muted small ms-2">Stock: {{ variante.stock }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="price-tag">${{ formatPrice(variante.precio_usd) }}</span>
              <RouterLink
                :to="`/productos/${producto.id}/variantes/${variante.id}/editar`"
                class="btn btn-sm btn-outline-dark"
              >
                Editar
              </RouterLink>
              <DeleteConfirm
                :uid="`var${variante.id}`"
                :label="variante.presentacion"
                @confirm="eliminarVariante(variante)"
              />
            </div>
          </div>

          <p v-if="!producto.variantes?.length" class="text-muted small">
            Sin variantes.
            <RouterLink :to="`/productos/${producto.id}/variantes/nueva`">
              Agrega la primera.
            </RouterLink>
          </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <RouterLink :to="`/productos/${producto.id}/variantes/nueva`" class="btn btn-dark btn-sm px-4">
            + Agregar variante
          </RouterLink>
          <RouterLink :to="`/productos/${producto.id}/editar`" class="btn btn-outline-dark btn-sm px-4">
            Editar producto
          </RouterLink>
          <RouterLink to="/" class="btn btn-sm btn-link text-muted text-decoration-none">
            ← Catálogo
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../api'
import DeleteConfirm from '../../components/DeleteConfirm.vue'

const route = useRoute()
const emit = defineEmits(['flash'])

const loading = ref(true)
const producto = ref(null)

async function fetchProducto() {
  loading.value = true
  try {
    const res = await api.getProducto(route.params.id)
    producto.value = res.data
  } catch {
    producto.value = null
  } finally {
    loading.value = false
  }
}

async function eliminarVariante(variante) {
  try {
    await api.eliminarVariante(producto.value.id, variante.id)
    emit('flash', 'Variante eliminada.')
    fetchProducto()
  } catch {
    emit('flash', 'Error al eliminar la variante.')
  }
}

function capitalize(str) {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
}

function formatPrice(n) {
  return Number(n).toFixed(2)
}

onMounted(fetchProducto)
</script>
