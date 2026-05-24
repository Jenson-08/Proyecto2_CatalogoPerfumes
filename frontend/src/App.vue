<template>
  <AppNavbar />

  <main class="py-4">
    <div class="container">
      <Transition name="flash">
        <div
          v-if="flash.message"
          class="alert alert-success alert-dismissible fade show mb-3"
          role="alert"
        >
          {{ flash.message }}
          <button type="button" class="btn-close" @click="flash.message = ''"></button>
        </div>
      </Transition>

      <RouterView @flash="showFlash" />
    </div>
  </main>

  <AppFooter />
</template>

<script setup>
import { reactive } from 'vue'
import AppNavbar from './components/AppNavbar.vue'
import AppFooter from './components/AppFooter.vue'

const flash = reactive({ message: '' })

function showFlash(msg) {
  flash.message = msg
  setTimeout(() => {
    flash.message = ''
  }, 4000)
}
</script>

<style>
.flash-enter-active,
.flash-leave-active {
  transition: opacity 0.3s;
}
.flash-enter-from,
.flash-leave-to {
  opacity: 0;
}
</style>
