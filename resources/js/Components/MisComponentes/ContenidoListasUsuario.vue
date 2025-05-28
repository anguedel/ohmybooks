<script setup>
defineProps({
    lista: Object
})
</script>

<template>
  <div class="relative py-5" v-if="lista">
    <!-- Fondo degradado azul difuminado -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#cce4f6] to-[#4b9cd3] blur-sm brightness-75 z-0"></div>

    <!-- Contenido sobre fondo -->
    <div class="container relative z-10">
      <div class="mx-auto" style="max-width: 900px;">
        <h3 class="text-center mb-4 d-inline-block" style="background-color: #4b9cd3; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">
          Lista: <span class="fw-normal">{{ lista.nombre }}</span>
        </h3>

        <div v-if="lista.libros.length">
          <div v-for="(libro, index) in lista.libros" :key="libro.id"
            class="libro-card border rounded p-3 mb-3 d-flex align-items-center bg-white shadow-sm"
            style="border-color: #4b9cd3;">

            <!-- Posición -->
            <div class="me-3 fw-bold">{{ index + 1 }}</div>

            <!-- Portada -->
            <div class="me-3" style="width: 80px;">
              <img :src="`/images/Libros/${libro.portada}`" :alt="libro.titulo"
                class="portada-libro" />
            </div>

            <!-- Información del libro -->
            <div class="flex-grow-1">
              <h6 class="fw-bold mb-1" style="color: #4b9cd3;">{{ libro.titulo }}</h6>
              <p class="mb-0">{{ libro.autor }}</p>
              <p class="mb-0">{{ libro.anio }}</p>
              <p class="mb-0">{{ libro.nota_global }} <small>(nota global)</small></p>
            </div>

            <!-- Voto del usuario -->
            <div class="text-center">
              <small class="d-block text-muted">Tu voto</small>
              <div class="fs-2 fw-bold">
                {{ libro.voto_usuario ?? '–' }}
              </div>
            </div>
          </div>
        </div>

        <p v-else class="text-center text-muted">Esta lista aún no tiene libros.</p>
      </div>
    </div>
  </div>

  <div v-else class="text-center py-5">
    <p class="text-muted">Cargando lista...</p>
  </div>
</template>

<style scoped>
.portada-libro {
  width: 100%;
  height: 120px;
  object-fit: cover;
  border-radius: 0.25rem;
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