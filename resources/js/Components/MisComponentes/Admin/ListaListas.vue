<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    listas: Array
})

const mostrarModalCrear = ref(false)
const mostrarModalEditar = ref(false)
const mostrarModalEliminar = ref(false)
const mostrarToast = ref(false)
const listaSeleccionada = ref(null)

const form = useForm({
    nombre: ''
})

const formEditar = useForm({
    id: null,
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

function abrirModalEditar(lista) {
    formEditar.id = lista.id
    formEditar.nombre = lista.nombre
    mostrarModalEditar.value = true
}

function cerrarModalEditar() {
    mostrarModalEditar.value = false
    formEditar.reset()
}

function actualizarLista() {
    formEditar.put(`/admin/listas/${formEditar.id}`, {
        onSuccess: () => {
            cerrarModalEditar()
            mostrarToast.value = true
            setTimeout(() => {
                mostrarToast.value = false
            }, 4000)
        }
    })
}

function abrirModalEliminar(lista) {
    listaSeleccionada.value = lista
    mostrarModalEliminar.value = true
}

function cerrarModalEliminar() {
    mostrarModalEliminar.value = false
    listaSeleccionada.value = null
}

function confirmarEliminarLista() {
    router.delete(`/admin/listas/${listaSeleccionada.value.id}`, {
        onSuccess: () => {
            cerrarModalEliminar()
            mostrarToast.value = true
            setTimeout(() => {
                mostrarToast.value = false
            }, 4000)
        }
    })
}
</script>

<template>
    <div>
        <!-- ✅ TOAST DE CONFIRMACIÓN -->
        <div v-if="mostrarToast" class="position-fixed top-50 start-50 translate-middle p-3"
            style="z-index: 1055; min-width: 300px;">
            <div class="toast text-white bg-success border-0 show shadow" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Acción realizada correctamente.
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
                        <button class="btn btn-sm btn-warning me-2" @click="abrirModalEditar(lista)">Editar</button>
                        <button class="btn btn-sm btn-danger" @click="abrirModalEliminar(lista)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal Crear Lista -->
        <div v-if="mostrarModalCrear" class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
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

        <!-- Modal Editar Lista -->
        <div v-if="mostrarModalEditar" class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Editar lista</h5>
                        <button type="button" class="btn-close" @click="cerrarModalEditar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nuevo nombre</label>
                            <input v-model="formEditar.nombre" type="text" class="form-control" />
                            <div v-if="formEditar.errors.nombre" class="text-danger mt-1">{{ formEditar.errors.nombre }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="cerrarModalEditar">Cancelar</button>
                        <button class="btn btn-warning" @click="actualizarLista">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Confirmar Eliminación -->
        <div v-if="mostrarModalEliminar" class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Confirmar eliminación</h5>
                        <button type="button" class="btn-close" @click="cerrarModalEliminar"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar la lista <strong>{{ listaSeleccionada?.nombre }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="cerrarModalEliminar">Cancelar</button>
                        <button class="btn btn-danger" @click="confirmarEliminarLista">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
