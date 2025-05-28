<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({ generos: Array })

const cardWidth = 150
const cardGap = 16
</script>

<template>
  <div class="relative py-5">
    <!-- Fondo difuminado con tonos azules -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#cce4f6] to-[#4b9cd3] blur-sm brightness-75 z-0">
    </div>

    <!-- Contenido sobre fondo -->
    <div class="container relative z-10 px-3">
      <div class="mx-auto px-3">
        <div class="text-center mb-4">
          <h1 class="d-inline-block"
            style="background-color: #4b9cd3; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">
            Géneros
          </h1>
        </div>

        <section class="mb-5" v-for="genero in generos" :key="genero.id">
          <h4 class="fw-bold ps-3 mb-3" style="color: #084298;">
            {{ genero.genero }}
          </h4>

          <div class="scroll-sin-barra overflow-auto w-100">
            <div class="d-flex gap-3 pb-2 px-3"
              :style="{ minWidth: `${(cardWidth + cardGap) * genero.libros.length - cardGap}px` }">
              <div class="card libro-card d-flex flex-column justify-between shadow-sm border"
                v-for="libro in genero.libros" :key="libro.id"
                :style="{ border: '2px solid #4b9cd3', minWidth: `${cardWidth}px`, height: '320px' }">
                <Link :href="route('libros.show', libro.id)">
                <img :src="`/images/Libros/${libro.portada}`" class="card-img-top portada-libro" alt="Portada libro" />
                </Link>

                <div class="card-body p-2 text-center">
                  <h6 class="card-title fw-semibold mb-0 titulo-libro" style="color: #4b9cd3;">
                    {{ libro.titulo.slice(0, 40) }}<span v-if="libro.titulo.length > 50">...</span>
                  </h6>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<style scoped>
.scroll-sin-barra {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

.scroll-sin-barra::-webkit-scrollbar {
  display: none;
}

.portada-libro {
  width: 100%;
  height: 260px;
  object-fit: cover;
}

.titulo-libro {
  font-size: 0.95rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.libro-card {
  transition: all 0.25s ease-in-out;
}

.libro-card:hover {
  transform: translateY(-4px);
  border-color: #4b9cd3 !important;
  box-shadow: 0 0.5rem 1rem rgba(75, 156, 211, 0.2);
}
</style>
