<template>
  <div>
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb" style="font-size: 0.8rem">
        <li class="breadcrumb-item">
          <RouterLink to="/productos" class="text-dark">Productos</RouterLink>
        </li>
        <li class="breadcrumb-item">
          <RouterLink :to="`/productos/${route.params.id}`" class="text-dark">
            {{ producto?.nombre }}
          </RouterLink>
        </li>
        <li class="breadcrumb-item active">Editar</li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <p class="section-title mb-1">Editar</p>
        <h2 class="fw-normal mb-4">{{ producto?.nombre }}</h2>

        <div v-if="loading" class="spinner-wrapper">
          <div class="spinner-border text-secondary" role="status"></div>
        </div>

        <ProductoForm
          v-else-if="producto"
          :initial="producto"
          :categorias="categorias"
          :colecciones="colecciones"
          :errors="errors"
          :field-errors="fieldErrors"
          :saving="saving"
          submit-label="Guardar Cambios"
          :cancel-to="`/productos/${producto.id}`"
          @submit="actualizar"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../api'
import ProductoForm from '../../components/ProductoForm.vue'

const route = useRoute()
const router = useRouter()
const emit = defineEmits(['flash'])

const producto = ref(null)
const categorias = ref([])
const colecciones = ref([])
const loading = ref(true)
const saving = ref(false)
const errors = ref([])
const fieldErrors = ref({})

async function fetchAll() {
  loading.value = true
  try {
    const [prodRes, formRes] = await Promise.all([
      api.getProducto(route.params.id),
      api.getFormData(),
    ])
    producto.value = prodRes.data
    categorias.value = formRes.data.categorias
    colecciones.value = formRes.data.colecciones
  } finally {
    loading.value = false
  }
}

async function actualizar(data) {
  saving.value = true
  errors.value = []
  fieldErrors.value = {}
  try {
    await api.actualizarProducto(producto.value.id, data)
    emit('flash', 'Producto actualizado correctamente.')
    router.push(`/productos/${producto.value.id}`)
  } catch (e) {
    if (e.response?.status === 422) {
      fieldErrors.value = e.response.data.errors ?? {}
      errors.value = Object.values(fieldErrors.value).flat()
    } else {
      errors.value = ['Error al actualizar el producto.']
    }
  } finally {
    saving.value = false
  }
}

onMounted(fetchAll)
</script>
