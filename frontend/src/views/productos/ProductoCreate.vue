<template>
  <div>
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb" style="font-size: 0.8rem">
        <li class="breadcrumb-item">
          <RouterLink to="/productos" class="text-dark">Productos</RouterLink>
        </li>
        <li class="breadcrumb-item active">Nuevo</li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <p class="section-title mb-1">Catálogo</p>
        <h2 class="fw-normal mb-4">Nuevo Producto</h2>

        <div v-if="loadingForm" class="spinner-wrapper">
          <div class="spinner-border text-secondary" role="status"></div>
        </div>

        <ProductoForm
          v-else
          :categorias="categorias"
          :colecciones="colecciones"
          :errors="errors"
          :field-errors="fieldErrors"
          :saving="saving"
          submit-label="Crear Producto"
          cancel-to="/productos"
          @submit="crear"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../api'
import ProductoForm from '../../components/ProductoForm.vue'

const router = useRouter()
const emit = defineEmits(['flash'])

const categorias = ref([])
const colecciones = ref([])
const loadingForm = ref(true)
const saving = ref(false)
const errors = ref([])
const fieldErrors = ref({})

async function fetchFormData() {
  loadingForm.value = true
  try {
    const res = await api.getFormData()
    categorias.value = res.data.categorias
    colecciones.value = res.data.colecciones
  } finally {
    loadingForm.value = false
  }
}

async function crear(data) {
  saving.value = true
  errors.value = []
  fieldErrors.value = {}
  try {
    const res = await api.crearProducto(data)
    emit('flash', 'Producto creado correctamente. Ahora agrega sus variantes.')
    router.push(`/productos/${res.data.id}`)
  } catch (e) {
    if (e.response?.status === 422) {
      fieldErrors.value = e.response.data.errors ?? {}
      errors.value = Object.values(fieldErrors.value).flat()
    } else {
      errors.value = ['Error al crear el producto.']
    }
  } finally {
    saving.value = false
  }
}

onMounted(fetchFormData)
</script>
