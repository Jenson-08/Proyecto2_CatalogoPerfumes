<template>
  <form @submit.prevent="$emit('submit', form)">
    <div v-if="errors.length" class="alert alert-danger">
      <ul class="mb-0 small">
        <li v-for="(e, i) in errors" :key="i">{{ e }}</li>
      </ul>
    </div>

    <div class="row g-3">
      <!-- Presentación -->
      <div class="col-12">
        <label class="form-label section-title">Presentación *</label>
        <select
          v-model="form.presentacion"
          class="form-select"
          :class="{ 'is-invalid': fieldError('presentacion') }"
          required
        >
          <option value="">— Seleccionar —</option>
          <option value="EDP 50ml">EDP 50ml</option>
          <option value="EDP 100ml">EDP 100ml</option>
        </select>
        <div v-if="fieldError('presentacion')" class="invalid-feedback">
          {{ fieldError('presentacion') }}
        </div>
      </div>

      <!-- Precio -->
      <div class="col-md-6">
        <label class="form-label section-title">Precio (USD) *</label>
        <div class="input-group">
          <span class="input-group-text" style="border-radius: 0">$</span>
          <input
            v-model="form.precio_usd"
            type="number"
            step="0.01"
            min="0"
            class="form-control"
            :class="{ 'is-invalid': fieldError('precio_usd') }"
            required
          />
        </div>
        <div v-if="fieldError('precio_usd')" class="text-danger" style="font-size: 0.85rem">
          {{ fieldError('precio_usd') }}
        </div>
      </div>

      <!-- Stock -->
      <div class="col-md-6">
        <label class="form-label section-title">Stock *</label>
        <input
          v-model="form.stock"
          type="number"
          min="0"
          class="form-control"
          :class="{ 'is-invalid': fieldError('stock') }"
          required
        />
        <div v-if="fieldError('stock')" class="invalid-feedback">{{ fieldError('stock') }}</div>
      </div>
    </div>

    <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-dark px-5" :disabled="saving">
        <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
        {{ submitLabel }}
      </button>
      <RouterLink :to="`/productos/${productoId}`" class="btn btn-outline-dark">
        Cancelar
      </RouterLink>
    </div>
  </form>
</template>

<script setup>
import { reactive } from 'vue'

const props = defineProps({
  initial: { type: Object, default: () => ({}) },
  productoId: { type: [String, Number], required: true },
  errors: { type: Array, default: () => [] },
  fieldErrors: { type: Object, default: () => ({}) },
  saving: Boolean,
  submitLabel: { type: String, default: 'Guardar' },
})

defineEmits(['submit'])

const form = reactive({
  presentacion: props.initial.presentacion ?? '',
  precio_usd: props.initial.precio_usd ?? '',
  stock: props.initial.stock ?? 0,
})

function fieldError(field) {
  return props.fieldErrors?.[field]?.[0] ?? null
}
</script>
