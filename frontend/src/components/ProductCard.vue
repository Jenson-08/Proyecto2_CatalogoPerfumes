<template>
  <div class="col">
    <div class="card h-100 category-card">
      <RouterLink :to="`/productos/${producto.id}`" class="text-decoration-none">
        <img
          v-if="producto.imagen_url"
          :src="producto.imagen_url"
          class="card-img-top"
          :alt="producto.nombre"
          loading="lazy"
        />
        <div v-else class="img-placeholder">✦</div>
      </RouterLink>

      <div class="card-body d-flex flex-column">
        <span
          v-if="producto.edicion_limitada"
          class="badge badge-limited mb-1"
          style="width: fit-content"
        >
          Edición Limitada
        </span>

        <p class="section-title mb-0">{{ producto.categoria?.nombre ?? '' }}</p>

        <h6 class="card-title mb-1">
          <RouterLink :to="`/productos/${producto.id}`" class="text-dark text-decoration-none">
            {{ producto.nombre }}
          </RouterLink>
        </h6>

        <p class="text-muted small mb-auto">{{ truncate(producto.descripcion, 80) }}</p>

        <div class="mt-2 d-flex justify-content-between align-items-center">
          <span class="price-tag">{{ producto.precio_desde ?? 'Consultar' }}</span>
          <span class="badge bg-secondary" style="font-size: 0.65rem">{{ producto.genero }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  producto: {
    type: Object,
    required: true,
  },
})

function truncate(str, len) {
  if (!str) return ''
  return str.length > len ? str.slice(0, len) + '…' : str
}
</script>
