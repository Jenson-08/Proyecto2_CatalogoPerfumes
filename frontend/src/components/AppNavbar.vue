<template>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <RouterLink class="navbar-brand text-white fw-bold" to="/">✦ PERFUMES CATALOG</RouterLink>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navMain"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav me-auto ms-3">
          <li class="nav-item">
            <RouterLink class="nav-link" :class="{ 'active-link': route.name === 'home' }" to="/">
              Catálogo
            </RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink
              class="nav-link"
              :class="{ 'active-link': route.name?.startsWith('productos') }"
              to="/productos"
            >
              Administrar
            </RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink class="nav-link" to="/productos/nuevo">+ Nuevo</RouterLink>
          </li>
        </ul>

        <form class="d-flex" @submit.prevent="doSearch">
          <input
            v-model="q"
            class="form-control form-control-sm me-2"
            style="border-radius: 0; font-size: 0.8rem; min-width: 180px"
            type="search"
            placeholder="Buscar fragancias..."
          />
          <button class="btn btn-sm btn-outline-light" style="border-radius: 0; font-size: 0.8rem">
            Buscar
          </button>
        </form>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const q = ref('')

function doSearch() {
  router.push({ name: 'buscar', query: { q: q.value } })
}
</script>
