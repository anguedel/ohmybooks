<script setup>
import { ref } from 'vue'

// Componentes de cada sección
import ListaUsuarios from '@/Components/MisComponentes/Admin/ListaUsuarios.vue'
import ListaLibros from '@/Components/MisComponentes/Admin/ListaLibros.vue'
import ListaListas from '@/Components/MisComponentes/Admin/ListaListas.vue'
import ListaEscritos from '@/Components/MisComponentes/Admin/ListaEscritos.vue'
import ListaFrases from '@/Components/MisComponentes/Admin/ListaFrases.vue'
import ListaSugerencias from '@/Components/MisComponentes/Admin/ListaSugerencias.vue'

const seccionActiva = ref('usuarios')

const props = defineProps({
  usuarios: Array,
  libros: Object,
  generos: Array,
  listas: Array,
  escritos: Array,
  frases: Array,
  sugerencias: Array
})



const modulos = [
  { clave: 'usuarios', nombre: 'Administrar usuarios' },
  { clave: 'libros', nombre: 'Administrar libros' },
  { clave: 'listas', nombre: 'Administrar listas' },
  { clave: 'escritos', nombre: 'Administrar escritos de usuarios' },
  { clave: 'frases', nombre: 'Administrar frases célebres' },
  { clave: 'sugerencias', nombre: 'Propuestas de libros' }
]
</script>

<template>
  <div class="relative py-5">
    <!-- Fondo oscuro sobrio -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 to-gray-800 blur-sm brightness-75 z-0"></div>

    <!-- Contenido sobre fondo -->
    <div class="container relative z-10">


      <!-- Botones del panel -->
      <div class="row g-4 justify-content-center mb-5">
        <div class="col-md-4" v-for="modulo in modulos" :key="modulo.clave">
          <button class="btn w-100 py-3 fw-semibold boton-panel-admin" @click="seccionActiva = modulo.clave">
            {{ modulo.nombre }}
          </button>
        </div>
      </div>


      <!-- Contenido dinámico -->
      <div v-if="seccionActiva === 'usuarios'">
        <ListaUsuarios :usuarios="usuarios" />
      </div>

      <div v-else-if="seccionActiva === 'libros'">
        <ListaLibros :libros="libros" :generos="generos" />
      </div>
      <div v-else-if="seccionActiva === 'listas'">
        <ListaListas :listas="listas" />
      </div>

      <div v-else-if="seccionActiva === 'escritos'">
        <ListaEscritos :escritos="escritos" />
      </div>

      <div v-else-if="seccionActiva === 'frases'">
        <ListaFrases :frases="frases" />
      </div>
      <div v-else-if="seccionActiva === 'sugerencias'">
        <ListaSugerencias :sugerencias="sugerencias" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.texto-cabecera-admin {
  background-color: #1f2937;
  /* gray-800 */
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.3);
  font-size: 1.5rem;
}

.boton-panel-admin {
  background-color: #111827;
  /* gray-900 */
  border: 2px solid #4b5563;
  /* gray-600 */
  color: #f9fafb;
  /* gray-50 */
  transition: all 0.3s ease;
}

.boton-panel-admin:hover {
  background-color: #374151;
  /* gray-700 */
  color: white;
  border-color: #9ca3af;
  /* gray-400 */
}
</style>
