<template>
  <div>
    <!-- Hero -->
    <div class="py-5 mb-4 text-center" style="border-bottom: 1px solid #e0dbd0">
      <p class="section-title">Bienvenido</p>
      <h1 class="display-6 fw-normal mb-2">Perfumes Catalog</h1>
      <p class="text-muted mb-4" style="font-size: 0.9rem; letter-spacing: 0.5px">
        Fragancias de alta perfumería ·
        {{ categorias.reduce((s, c) => s + c.productos_count, 0) }} artículos
      </p>
      <div class="d-flex justify-content-center">
        <form class="d-flex gap-2" @submit.prevent="buscar">
          <input
            v-model="q"
            type="search"
            class="form-control"
            style="min-width: 280px"
            placeholder="Buscar por nombre o descripción..."
          />
          <button class="btn btn-dark px-4">Buscar</button>
        </form>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="spinner-wrapper">
      <div class="spinner-border text-secondary" role="status"></div>
    </div>

    <!-- Categorías -->
    <template v-else>
      <p class="section-title mb-3">Explorar por categoría</p>
      <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3">
        <div v-for="categoria in categorias" :key="categoria.id" class="col">
          <RouterLink
            :to="`/categorias/${categoria.slug}`"
            class="d-block p-3 text-decoration-none category-card"
            style="background: #fff; border: 1px solid #e8e4db"
          >
            <p class="section-title mb-1">{{ categoria.productos_count }} fragancias</p>
            <h6 class="mb-0 text-dark fw-bold">{{ categoria.nombre }}</h6>
          </RouterLink>
        </div>

        <div v-if="categorias.length === 0" class="col-12">
          <p class="text-muted text-center py-5">No hay categorías disponibles.</p>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'

const router = useRouter()
const categorias = ref([])
const loading = ref(true)
const q = ref('')

async function fetchHome() {
  loading.value = true
  try {
    const res = await api.getHome()
    categorias.value = res.data.categorias
  } catch {
    categorias.value = []
  } finally {
    loading.value = false
  }
}

function buscar() {
  router.push({ name: 'buscar', query: { q: q.value } })
}

onMounted(fetchHome)
</script>
