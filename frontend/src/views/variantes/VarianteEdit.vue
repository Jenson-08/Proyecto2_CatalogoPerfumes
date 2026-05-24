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
        <li class="breadcrumb-item active">Editar Variante</li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <p class="section-title mb-1">{{ producto?.nombre }}</p>
        <h2 class="fw-normal mb-4">
          Editar Variante · {{ variante?.presentacion }}
        </h2>

        <div v-if="loading" class="spinner-wrapper">
          <div class="spinner-border text-secondary" role="status"></div>
        </div>

        <VarianteForm
          v-else-if="variante"
          :initial="variante"
          :producto-id="productoId"
          :errors="errors"
          :field-errors="fieldErrors"
          :saving="saving"
          submit-label="Guardar Cambios"
          @submit="actualizar"
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
const varianteId = computed(() => route.params.varianteId)

const producto = ref(null)
const variante = ref(null)
const loading = ref(true)
const saving = ref(false)
const errors = ref([])
const fieldErrors = ref({})

async function fetchData() {
  loading.value = true
  try {
    const [prodRes, varRes] = await Promise.all([
      api.getProducto(productoId.value),
      api.getVariantes(productoId.value),
    ])
    producto.value = prodRes.data
    variante.value = varRes.data.find((v) => String(v.id) === String(varianteId.value)) ?? null
  } finally {
    loading.value = false
  }
}

async function actualizar(data) {
  saving.value = true
  errors.value = []
  fieldErrors.value = {}
  try {
    await api.actualizarVariante(productoId.value, varianteId.value, data)
    emit('flash', 'Variante actualizada correctamente.')
    router.push(`/productos/${productoId.value}`)
  } catch (e) {
    if (e.response?.status === 422) {
      fieldErrors.value = e.response.data.errors ?? {}
      errors.value = Object.values(fieldErrors.value).flat()
    } else {
      errors.value = ['Error al actualizar la variante.']
    }
  } finally {
    saving.value = false
  }
}

onMounted(fetchData)
</script>
