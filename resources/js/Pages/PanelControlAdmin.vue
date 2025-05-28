<script setup>
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import AdminPanelDeControl from '@/Components/MisComponentes/AdminPanelDeControl.vue'

const props = defineProps({
  usuarios: Array,
  libros: Object,
  generos: Array,
  listas: Array,
  escritos: Array,
  frases: Array,
  sugerencias: Array
})

</script>

<template>
  <AdminLayout>


    <AdminPanelDeControl :usuarios="usuarios" :libros="libros" :generos="generos" :listas="listas" :escritos="escritos"
      :frases="frases" :sugerencias="sugerencias" />


  </AdminLayout>
</template>
