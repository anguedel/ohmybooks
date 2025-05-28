<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  libros: Array
})
</script>

<template>
  <div class="relative py-5">
    <!-- Fondo difuminado con tonos azules -->
    <div
      class="absolute inset-0 bg-gradient-to-br from-[#cce4f6] to-[#4b9cd3] blur-sm brightness-75 z-0">
    </div>

    <!-- Contenido sobre fondo -->
    <div class="container relative z-10">
      <div class="text-center mb-5">
        <h2 class="d-inline-block" style="background-color: #4b9cd3; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">
          Últimas novedades
        </h2>
      </div>

      <div class="row g-4 justify-content-center">
        <div v-for="libro in libros" :key="libro.id" class="col-12 col-sm-6 col-md-4 col-lg-3">
          <Link :href="route('libros.show', libro.id)" class="text-decoration-none">
            <div class="card libro-card d-flex flex-column h-100 shadow-sm border" style="border-color: #4b9cd3;">
              <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                <img :src="`/images/Libros/${libro.portada}`" :alt="libro.titulo"
                  class="img-fluid object-fit-cover portada-libro" />
              </div>
              <div class="card-body text-center p-2">
                <h6 class="card-title fw-semibold mb-0 titulo-libro" style="color: #4b9cd3;">
                  {{ libro.titulo }}
                </h6>
              </div>
            </div>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
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