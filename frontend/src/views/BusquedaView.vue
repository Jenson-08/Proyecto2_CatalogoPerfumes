<template>
  <div>
    <div class="mb-4">
      <p class="section-title mb-1">Buscador</p>
      <h2 class="fw-normal mb-3">
        <template v-if="q">Resultados para &ldquo;{{ q }}&rdquo;</template>
        <template v-else>Todos los productos</template>
      </h2>

      <form class="d-flex gap-2" @submit.prevent="doSearch">
        <input
          v-model="inputQ"
          type="search"
          class="form-control"
          style="max-width: 400px"
          placeholder="Buscar por nombre o descripción..."
        />
        <button class="btn btn-dark px-4">Buscar</button>
      </form>
    </div>

    <p v-if="q && !loading" class="text-muted small mb-3">
      {{ paginator?.total }} resultado(s) encontrado(s)
    </p>

    <div v-if="loading" class="spinner-wrapper">
      <div class="spinner-border text-secondary" role="status"></div>
    </div>

    <template v-else>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <ProductCard
          v-for="producto in productos"
          :key="producto.id"
          :producto="producto"
        />
        <div v-if="productos.length === 0" class="col-12 text-center py-5">
          <p class="text-muted">No se encontraron resultados para &ldquo;{{ q }}&rdquo;.</p>
          <RouterLink to="/" class="btn btn-dark btn-sm mt-2">Ver catálogo completo</RouterLink>
        </div>
      </div>

      <AppPagination :paginator="paginator" @change="changePage" />
    </template>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import ProductCard from '../components/ProductCard.vue'
import AppPagination from '../components/AppPagination.vue'

const route = useRoute()
const router = useRouter()

const q = ref('')
const inputQ = ref('')
const loading = ref(false)
const productos = ref([])
const paginator = ref(null)

async function fetch(page = 1) {
  loading.value = true
  try {
    const res = await api.buscar(q.value, page)
    productos.value = res.data.productos.data
    paginator.value = res.data.productos
  } catch {
    productos.value = []
  } finally {
    loading.value = false
  }
}

function doSearch() {
  router.push({ name: 'buscar', query: { q: inputQ.value } })
}

function changePage(page) {
  fetch(page)
}

function syncFromRoute() {
  q.value = route.query.q || ''
  inputQ.value = q.value
  fetch()
}

onMounted(syncFromRoute)
watch(() => route.query.q, syncFromRoute)
</script>
