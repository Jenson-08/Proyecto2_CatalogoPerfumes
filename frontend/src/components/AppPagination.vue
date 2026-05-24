<template>
  <nav v-if="paginator && paginator.last_page > 1" class="mt-4">
    <ul class="pagination justify-content-center">
      <li class="page-item" :class="{ disabled: paginator.current_page === 1 }">
        <button class="page-link" @click="$emit('change', paginator.current_page - 1)">
          &laquo;
        </button>
      </li>

      <li
        v-for="page in pages"
        :key="page"
        class="page-item"
        :class="{ active: page === paginator.current_page }"
      >
        <button class="page-link" @click="$emit('change', page)">{{ page }}</button>
      </li>

      <li class="page-item" :class="{ disabled: paginator.current_page === paginator.last_page }">
        <button class="page-link" @click="$emit('change', paginator.current_page + 1)">
          &raquo;
        </button>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  paginator: Object,
})

defineEmits(['change'])

const pages = computed(() => {
  if (!props.paginator) return []
  const total = props.paginator.last_page
  const current = props.paginator.current_page
  const delta = 2
  const range = []

  for (let i = Math.max(1, current - delta); i <= Math.min(total, current + delta); i++) {
    range.push(i)
  }
  return range
})
</script>
