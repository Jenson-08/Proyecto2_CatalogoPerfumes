<template>
  <div>
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb" style="font-size: 0.8rem">
        <li class="breadcrumb-item">
          <RouterLink to="/productos" class="text-dark">Productos</RouterLink>
        </li>
        <li class="breadcrumb-item">
          <RouterLink :to="`/productos/${productoId}`" class="text-dark">
            {{ producto?.nombre }}
          </RouterLink>
        </li>
        <li class="breadcrumb-item active">Nueva Variante</li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <p class="section-title mb-1">{{ producto?.nombre }}</p>
        <h2 class="fw-normal mb-4">Agregar Variante</h2>

        <div v-if="loading" class="spinner-wrapper">
          <div class="spinner-border text-secondary" role="status"></div>
        </div>

        <VarianteForm
          v-else
          :producto-id="productoId"
          :errors="errors"
          :field-errors="fieldErrors"
          :saving="saving"
          submit-label="Agregar Variante"
          @submit="crear"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../api'
import VarianteForm from '../../components/VarianteForm.vue'

const route = useRoute()
const router = useRouter()
const emit = defineEmits(['flash'])

const productoId = computed(() => route.params.productoId)
const producto = ref(null)
const loading = ref(true)
const saving = ref(false)
const errors = ref([])
const fieldErrors = ref({})

async function fetchProducto() {
  loading.value = true
  try {
    const res = await api.getProducto(productoId.value)
    producto.value = res.data
  } finally {
    loading.value = false
  }
}

async function crear(data) {
  saving.value = true
  errors.value = []
  fieldErrors.value = {}
  try {
    await api.crearVariante(productoId.value, data)
    emit('flash', 'Variante agregada correctamente.')
    router.push(`/productos/${productoId.value}`)
  } catch (e) {
    if (e.response?.status === 422) {
      fieldErrors.value = e.response.data.errors ?? {}
      errors.value = Object.values(fieldErrors.value).flat()
    } else {
      errors.value = ['Error al agregar la variante.']
    }
  } finally {
    saving.value = false
  }
}

onMounted(fetchProducto)
</script>
