<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    listas: Array
})

const mostrarModalCrear = ref(false)
const mostrarToast = ref(false)

const form = useForm({
    nombre: ''
})

function abrirModalCrear() {
    mostrarModalCrear.value = true
}

function cerrarModalCrear() {
    mostrarModalCrear.value = false
    form.reset()
}

function guardarLista() {
    form.post('/admin/listas', {
        onSuccess: () => {
            cerrarModalCrear()
            mostrarToast.value = true
            setTimeout(() => {
                mostrarToast.value = false
            }, 4000)
        }
    })
}

function editarLista(id) {
    router.visit(`/admin/listas/${id}/edit`)
}

function eliminarLista(id) {
    if (confirm('¿Deseas eliminar esta lista?')) {
        router.delete(`/admin/listas/${id}`)
    }
}
</script>

<template>
    <div>
        <!-- ✅ TOAST DE CONFIRMACIÓN CENTRADO -->
        <div v-if="mostrarToast" class="position-fixed top-50 start-50 translate-middle p-3"
            style="z-index: 1055; min-width: 300px;">
            <div class="toast text-white bg-success border-0 show shadow" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ✅ Lista creada correctamente.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        @click="mostrarToast = false"></button>
                </div>
            </div>
        </div>

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold" style="color: #93c5fd;">Listas de usuarios</h4>
            <button class="btn" style="background-color: #4b9cd3; color: white;" @click="abrirModalCrear">
                Crear nueva lista
            </button>
        </div>

        <!-- Tabla -->
        <table class="table table-striped table-bordered">
            <thead class="table-warning">
                <tr>
                    <th>ID</th>
                    <th>ID usuario</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="lista in listas" :key="lista.id">
                    <td>{{ lista.id }}</td>
                    <td>{{ lista.id_usuario }}</td>
                    <td>{{ lista.nombre }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning me-2" @click="editarLista(lista.id)">Editar</button>
                        <button class="btn btn-sm btn-danger" @click="eliminarLista(lista.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal Crear Lista -->
        <div v-if="mostrarModalCrear" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear nueva lista</h5>
                        <button type="button" class="btn-close" @click="cerrarModalCrear"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la lista</label>
                            <input v-model="form.nombre" type="text" class="form-control" />
                            <div v-if="form.errors.nombre" class="text-danger mt-1">{{ form.errors.nombre }}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="cerrarModalCrear">Cancelar</button>
                        <button class="btn btn-primary" @click="guardarLista">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
