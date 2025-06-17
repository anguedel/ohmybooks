<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    escritos: Array
})

const mostrarModalCrear = ref(false)
const mostrarModalVer = ref(false)
const mostrarModalEditar = ref(false)
const mostrarModalEliminar = ref(false)
const mostrarToast = ref(false)

const escritoSeleccionado = ref(null)

const form = useForm({
    titulo: '',
    contenido: ''
})

const formEditar = useForm({
    id: null,
    titulo: '',
    contenido: ''
})

function abrirModalCrear() {
    mostrarModalCrear.value = true
}

function cerrarModalCrear() {
    mostrarModalCrear.value = false
    form.reset()
}

function registrarEscrito() {
    form.post('/admin/escritos', {
        onSuccess: () => {
            cerrarModalCrear()
            mostrarToast.value = true
            setTimeout(() => mostrarToast.value = false, 4000)
        }
    })
}

function abrirModalVer(escrito) {
    escritoSeleccionado.value = escrito
    mostrarModalVer.value = true
}

function abrirModalEditar(escrito) {
    formEditar.id = escrito.id
    formEditar.titulo = escrito.titulo
    formEditar.contenido = escrito.contenido
    mostrarModalEditar.value = true
}

function actualizarEscrito() {
    formEditar.put(`/admin/escritos/${formEditar.id}`, {
        onSuccess: () => {
            mostrarModalEditar.value = false
            mostrarToast.value = true
            setTimeout(() => mostrarToast.value = false, 4000)
        }
    })
}

function abrirModalEliminar(escrito) {
    escritoSeleccionado.value = escrito
    mostrarModalEliminar.value = true
}

function confirmarEliminarEscrito() {
    router.delete(`/admin/escritos/${escritoSeleccionado.value.id}`, {
        onSuccess: () => mostrarModalEliminar.value = false
    })
}
</script>

<template>
    <div>
        <div v-if="mostrarToast" class="position-fixed top-50 start-50 translate-middle p-3"
            style="z-index: 1055; min-width: 300px;">
            <div class="toast text-white bg-success border-0 show shadow" role="alert">
                <div class="d-flex">
                    <div class="toast-body">✅ Acción realizada correctamente.</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        @click="mostrarToast = false"></button>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3" style="color: #93c5fd">
            <h4 class="fw-bold" style="color: #93c5fd;">Escritos de usuarios</h4>
            <button class="btn" style="background-color: #4b9cd3; color: white;" @click="abrirModalCrear">Crear nuevo
                escrito</button>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-danger">
                <tr>
                    <th>ID</th>
                    <th>ID Usuario</th>
                    <th>Título</th>
                    <th>Contenido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="escrito in escritos" :key="escrito.id">
                    <td>{{ escrito.id }}</td>
                    <td>{{ escrito.usuario_id }}</td>
                    <td>{{ escrito.titulo }}</td>
                    <td>{{ escrito.contenido }}</td>
                    <td>
                        <button class="btn btn-sm btn-info me-2" @click="abrirModalVer(escrito)">Ver</button>
                        <button class="btn btn-sm btn-warning me-2" @click="abrirModalEditar(escrito)">Editar</button>
                        <button class="btn btn-sm btn-danger" @click="abrirModalEliminar(escrito)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal: Crear -->
        <div v-if="mostrarModalCrear" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo escrito</h5>
                        <button type="button" class="btn-close" @click="cerrarModalCrear"></button>
                    </div>
                    <div class="modal-body">
                        <input v-model="form.titulo" type="text" placeholder="Título" class="form-control mb-3" />
                        <textarea v-model="form.contenido" class="form-control" rows="4"
                            placeholder="Contenido"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="cerrarModalCrear">Cancelar</button>
                        <button class="btn btn-primary" @click="registrarEscrito">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Ver -->
        <div v-if="mostrarModalVer" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ver escrito</h5>
                        <button type="button" class="btn-close" @click="mostrarModalVer = false"></button>
                    </div>
                    <div class="modal-body">
                        <h6><strong>Título:</strong> {{ escritoSeleccionado?.titulo }}</h6>
                        <p><strong>Contenido:</strong><br>{{ escritoSeleccionado?.contenido }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Editar -->
        <div v-if="mostrarModalEditar" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar escrito</h5>
                        <button type="button" class="btn-close" @click="mostrarModalEditar = false"></button>
                    </div>
                    <div class="modal-body">
                        <input v-model="formEditar.titulo" type="text" class="form-control mb-3" />
                        <textarea v-model="formEditar.contenido" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="mostrarModalEditar = false">Cancelar</button>
                        <button class="btn btn-warning" @click="actualizarEscrito">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Confirmar eliminación -->
        <div v-if="mostrarModalEliminar" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">¿Eliminar escrito?</h5>
                        <button type="button" class="btn-close" @click="mostrarModalEliminar = false"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar <strong>{{ escritoSeleccionado?.titulo }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="mostrarModalEliminar = false">Cancelar</button>
                        <button class="btn btn-danger" @click="confirmarEliminarEscrito">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
