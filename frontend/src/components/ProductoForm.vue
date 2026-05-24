<template>
  <form @submit.prevent="$emit('submit', form)">
    <div v-if="errors.length" class="alert alert-danger">
      <ul class="mb-0 small">
        <li v-for="(e, i) in errors" :key="i">{{ e }}</li>
      </ul>
    </div>

    <div class="row g-3">
      <!-- Nombre -->
      <div class="col-md-8">
        <label class="form-label section-title">Nombre *</label>
        <input
          v-model="form.nombre"
          type="text"
          class="form-control"
          :class="{ 'is-invalid': fieldError('nombre') }"
          required
        />
        <div v-if="fieldError('nombre')" class="invalid-feedback">{{ fieldError('nombre') }}</div>
      </div>

      <!-- SKU -->
      <div class="col-md-4">
        <label class="form-label section-title">SKU *</label>
        <input
          v-model="form.sku"
          type="text"
          class="form-control"
          :class="{ 'is-invalid': fieldError('sku') }"
          required
        />
        <div v-if="fieldError('sku')" class="invalid-feedback">{{ fieldError('sku') }}</div>
      </div>

      <!-- Categoría -->
      <div class="col-md-6">
        <label class="form-label section-title">Categoría *</label>
        <select
          v-model="form.categoria_id"
          class="form-select"
          :class="{ 'is-invalid': fieldError('categoria_id') }"
          required
        >
          <option value="">— Seleccionar —</option>
          <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
            {{ cat.nombre }}
          </option>
        </select>
        <div v-if="fieldError('categoria_id')" class="invalid-feedback">
          {{ fieldError('categoria_id') }}
        </div>
      </div>

      <!-- Colección -->
      <div class="col-md-6">
        <label class="form-label section-title">Colección *</label>
        <select
          v-model="form.coleccion_id"
          class="form-select"
          :class="{ 'is-invalid': fieldError('coleccion_id') }"
          required
        >
          <option value="">— Seleccionar —</option>
          <option v-for="col in colecciones" :key="col.id" :value="col.id">
            {{ col.nombre }}
          </option>
        </select>
        <div v-if="fieldError('coleccion_id')" class="invalid-feedback">
          {{ fieldError('coleccion_id') }}
        </div>
      </div>

      <!-- Descripción -->
      <div class="col-12">
        <label class="form-label section-title">Descripción</label>
        <textarea v-model="form.descripcion" rows="3" class="form-control"></textarea>
      </div>

      <!-- Notas olfativas -->
      <div class="col-md-8">
        <label class="form-label section-title">Notas Olfativas</label>
        <input
          v-model="form.notas_olfativas"
          type="text"
          class="form-control"
          placeholder="Rosa, Jazmín, Sándalo..."
        />
      </div>

      <!-- Perfil olfativo -->
      <div class="col-md-4">
        <label class="form-label section-title">Perfil Olfativo</label>
        <select v-model="form.perfil_olfativo" class="form-select">
          <option value="">— Ninguno —</option>
          <option v-for="p in perfiles" :key="p" :value="p">
            {{ p.replace(/_/g, ' ') }}
          </option>
        </select>
      </div>

      <!-- Género -->
      <div class="col-md-4">
        <label class="form-label section-title">Género *</label>
        <select v-model="form.genero" class="form-select" required>
          <option value="femenino">Femenino</option>
          <option value="masculino">Masculino</option>
          <option value="unisex">Unisex</option>
        </select>
      </div>

      <!-- Imagen URL -->
      <div class="col-md-8">
        <label class="form-label section-title">URL de Imagen</label>
        <input
          v-model="form.imagen_url"
          type="url"
          class="form-control"
          :class="{ 'is-invalid': fieldError('imagen_url') }"
        />
        <div v-if="fieldError('imagen_url')" class="invalid-feedback">
          {{ fieldError('imagen_url') }}
        </div>
      </div>

      <!-- Checkboxes -->
      <div class="col-12 d-flex gap-4">
        <div class="form-check">
          <input
            v-model="form.edicion_limitada"
            type="checkbox"
            id="edicion_limitada"
            class="form-check-input"
          />
          <label for="edicion_limitada" class="form-check-label small">Edición Limitada</label>
        </div>
        <div class="form-check">
          <input v-model="form.activo" type="checkbox" id="activo" class="form-check-input" />
          <label for="activo" class="form-check-label small">Activo</label>
        </div>
      </div>
    </div>

    <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-dark px-5" :disabled="saving">
        <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
        {{ submitLabel }}
      </button>
      <RouterLink :to="cancelTo" class="btn btn-outline-dark">Cancelar</RouterLink>
    </div>
  </form>
</template>

<script setup>
import { reactive, computed } from 'vue'

const props = defineProps({
  initial: { type: Object, default: () => ({}) },
  categorias: { type: Array, default: () => [] },
  colecciones: { type: Array, default: () => [] },
  errors: { type: Array, default: () => [] },
  fieldErrors: { type: Object, default: () => ({}) },
  saving: Boolean,
  submitLabel: { type: String, default: 'Guardar' },
  cancelTo: { type: String, default: '/productos' },
})

defineEmits(['submit'])

const perfiles = [
  'floral_aldhehido', 'fresco_especiado', 'acustico_floral', 'gourmand_floral',
  'amaderado_especiado', 'amaderado_oriental', 'fougere_floral', 'citrico',
  'chypre_frutal', 'amaderado_mineral', 'oriental_floral', 'floral_fresco',
  'fresco_oriental', 'amaderado_floral', 'floral_frutal', 'fougere_clasico',
  'gourmand_oriental', 'floral_clasico', 'oriental_especiado', 'amaderado_terroso',
  'floral_especiado', 'oriental_resinoso', 'acustico_fresco', 'floral_polvoroso',
  'acustico_amaderado',
]

const form = reactive({
  nombre: props.initial.nombre ?? '',
  sku: props.initial.sku ?? '',
  categoria_id: props.initial.categoria_id ?? '',
  coleccion_id: props.initial.coleccion_id ?? '',
  descripcion: props.initial.descripcion ?? '',
  notas_olfativas: props.initial.notas_olfativas ?? '',
  perfil_olfativo: props.initial.perfil_olfativo ?? '',
  genero: props.initial.genero ?? 'unisex',
  imagen_url: props.initial.imagen_url ?? '',
  edicion_limitada: props.initial.edicion_limitada ?? false,
  activo: props.initial.activo !== undefined ? props.initial.activo : true,
})

function fieldError(field) {
  return props.fieldErrors?.[field]?.[0] ?? null
}
</script>
