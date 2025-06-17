<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    sugerencias: Array
})

const mostrarModalEliminar = ref(false)
const sugerenciaSeleccionada = ref(null)
const mostrarToast = ref(false)

function abrirModalEliminar(sugerencia) {
    sugerenciaSeleccionada.value = sugerencia
    mostrarModalEliminar.value = true
}

function cerrarModalEliminar() {
    sugerenciaSeleccionada.value = null
    mostrarModalEliminar.value = false
}

function eliminarSugerencia(id) {
    if (confirm('¿Deseas eliminar esta sugerencia?')) {
        router.delete(`/admin/libros-sugeridos/${sugerenciaSeleccionada.value.id}`, {
            onSuccess: () => {
                cerrarModalEliminar()
                mostrarToast.value = true
                setTimeout(() => mostrarToast.value = false, 3000)
            }
        })

    }
}


function confirmarEliminarSugerencia() {
    if (!sugerenciaSeleccionada.value) return

    router.delete(`/admin/libros-sugeridos/${sugerenciaSeleccionada.value.id}`, {
        onSuccess: () => {
            cerrarModalEliminar()
            mostrarToast.value = true
            setTimeout(() => mostrarToast.value = false, 3000)
        }
    })

}
</script>

<template>
    <div>
        <!-- Toast -->
        <div v-if="mostrarToast"
            class="position-fixed top-50 start-50 translate-middle bg-success text-white p-3 rounded shadow"
            style="z-index: 1055;">
            ✅ Sugerencia eliminada correctamente.
        </div>

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
                        <button class="btn btn-sm btn-danger" @click="abrirModalEliminar(sugerencia)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal Confirmar Eliminación -->
        <div v-if="mostrarModalEliminar" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">¿Eliminar sugerencia?</h5>
                        <button type="button" class="btn-close" @click="cerrarModalEliminar"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar la sugerencia: <strong>{{ sugerenciaSeleccionada?.titulo
                                }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="cerrarModalEliminar">Cancelar</button>
                        <button class="btn btn-danger" @click="confirmarEliminarSugerencia">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
