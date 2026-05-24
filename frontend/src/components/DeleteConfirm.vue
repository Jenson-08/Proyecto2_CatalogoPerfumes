<template>
  <!-- Trigger button -->
  <button
    class="btn btn-sm btn-outline-danger"
    style="border-radius: 0"
    data-bs-toggle="modal"
    :data-bs-target="`#deleteModal${uid}`"
  >
    Eliminar
  </button>

  <!-- Modal -->
  <div class="modal fade" :id="`deleteModal${uid}`" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius: 0">
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Confirmar eliminación</h6>
        </div>
        <div class="modal-body">
          <p class="mb-0 text-muted">
            ¿Eliminar <strong>{{ label }}</strong
            >? Esta acción no se puede deshacer.
          </p>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button
            type="button"
            class="btn btn-sm btn-outline-dark"
            data-bs-dismiss="modal"
          >
            Cancelar
          </button>
          <button
            type="button"
            class="btn btn-sm btn-danger"
            style="border-radius: 0"
            data-bs-dismiss="modal"
            @click="$emit('confirm')"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: String,
  uid: { type: [String, Number], required: true },
})

defineEmits(['confirm'])
</script>
