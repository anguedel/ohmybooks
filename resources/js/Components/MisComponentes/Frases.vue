<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const categorias = ['Filosofía', 'Estoicismo', 'Persuasión', 'Motivación', 'Poesía']
const categoriaSeleccionada = ref(categorias[0])
const user = usePage().props.auth.user

const props = defineProps({
  categorias: Array,
  frasesPorCategoria: Object,
})

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
</script>

<template>
  <div class="relative py-5">
    <div class="absolute inset-0 bg-cover bg-center blur-sm brightness-75 z-0"
      style="background-image: url('/images/Fondo2.avif');">
    </div>

    <div class="container relative z-10">
      <div class="text-center mb-5">
        <h1 class="d-inline-block"
          style="background-color: #d38e0d; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">
          Frases célebres
        </h1>
      </div>

      <!-- Pestañas de categorías -->
      <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
        <button v-for="cat in categorias" :key="cat" class="btn fw-semibold"
          :style="categoriaSeleccionada === cat ? 'background-color: #d38e0d; color: white; border: 2px solid #d38e0d;' : 'color: #d38e0d; border: 2px solid #d38e0d;'"
          @click="categoriaSeleccionada = cat">
          {{ cat }}
        </button>
      </div>

      <!-- Frases con transición -->
      <Transition name="fade" mode="out-in">
        <div v-if="frasesPorCategoria[categoriaSeleccionada]" :key="categoriaSeleccionada"
          class="d-flex flex-column gap-3">
          <div v-for="frase in frasesPorCategoria[categoriaSeleccionada]" :key="frase.texto"
            class="border rounded p-3 bg-white d-flex align-items-center justify-content-between">

            <!-- Frase -->
            <div class="flex-grow-1 text-center p-3 rounded" style="background-color: #fff4e1;">
              <p class="mb-1 fs-5">"{{ frase.texto }}"</p>
              <p class="mb-0 text-muted">- {{ frase.autor }}</p>
            </div>

            <!-- Likes -->
            <div class="text-center">
              <form v-if="user" :action="route('frases.like', frase.id)" method="POST">
                <input type="hidden" name="_token" :value="csrf" />
                <button type="submit" :style="frase.yaDioLike
                  ? 'background-color: #d38e0d; color: white; border: 2px solid #d38e0d;'
                  : 'color: #d38e0d; border: 2px solid #d38e0d;'" class="btn btn-sm mb-2 fw-semibold">
                  ❤️ {{ frase.yaDioLike ? 'Quitar like' : 'Me gusta' }}
                </button>
              </form>
              <div class="mt-1 fw-bold" style="color: #f57c00; font-size: 1rem;">
                {{ frase.likes }} {{ frase.likes === 1 ? 'like' : 'likes' }}
              </div>



            </div>
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
