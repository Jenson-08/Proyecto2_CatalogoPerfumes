<template>
  <div>
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb" style="font-size: 0.8rem">
        <li class="breadcrumb-item">
          <RouterLink to="/" class="text-dark">Inicio</RouterLink>
        </li>
        <li class="breadcrumb-item active">{{ categoria?.nombre }}</li>
      </ol>
    </nav>

    <div v-if="loading" class="spinner-wrapper">
      <div class="spinner-border text-secondary" role="status"></div>
    </div>

    <template v-else>
      <div class="d-flex justify-content-between align-items-baseline mb-4">
        <div>
          <p class="section-title mb-0">Categoría</p>
          <h2 class="fw-normal mb-0">{{ categoria?.nombre }}</h2>
        </div>
        <span class="text-muted small">{{ paginator?.total }} producto(s)</span>
      </div>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <ProductCard
          v-for="producto in productos"
          :key="producto.id"
          :producto="producto"
        />
        <div v-if="productos.length === 0" class="col-12 text-center py-5 text-muted">
          No hay productos en esta categoría.
        </div>
      </div>

      <AppPagination :paginator="paginator" @change="changePage" />
    </template>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api'
import ProductCard from '../components/ProductCard.vue'
import AppPagination from '../components/AppPagination.vue'

const route = useRoute()
const loading = ref(true)
const categoria = ref(null)
const productos = ref([])
const paginator = ref(null)

async function fetch(page = 1) {
  loading.value = true
  try {
    const res = await api.getCategoria(route.params.slug)
    categoria.value = res.data.categoria
    paginator.value = res.data.productos
    productos.value = res.data.productos.data
  } catch {
    productos.value = []
  } finally {
    loading.value = false
  }
}

function changePage(page) {
  fetch(page)
}

onMounted(() => fetch())
watch(() => route.params.slug, () => fetch())
</script>
