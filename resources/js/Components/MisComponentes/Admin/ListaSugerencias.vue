<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Datos simulados por ahora
const props = defineProps({
    sugerencias: Array
})

function eliminarSugerencia(id) {
    if (confirm('¿Deseas eliminar esta sugerencia?')) {
        console.log('Eliminar sugerencia con ID:', id)
    }
}
</script>

<template>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold" style="color: #93c5fd;">Sugerencias de libros</h4>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Título sugerido</th>
                    <th>Descripción</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="sugerencia in sugerencias" :key="sugerencia.id">
                    <td>{{ sugerencia.id }}</td>
                    <td>{{ sugerencia.titulo }}</td>
                    <td>{{ sugerencia.descripcion }}</td>
                    <td>{{ sugerencia.usuario }}</td>
                    <td>
                        <button class="btn btn-sm btn-danger"
                            @click="eliminarSugerencia(sugerencia.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
