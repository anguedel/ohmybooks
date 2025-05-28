<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    escritos: Array
})

const mostrarModalCrear = ref(false)
const mostrarToast = ref(false)

const form = useForm({
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
            setTimeout(() => {
                mostrarToast.value = false
            }, 4000)
        }
    })
}

function verEscrito(id) {
    router.visit(`/admin/escritos/${id}`)
}

function editarEscrito(id) {
    router.visit(`/admin/escritos/${id}/edit`)
}

function eliminarEscrito(id) {
    if (confirm('¿Deseas eliminar este escrito?')) {
        router.delete(`/admin/escritos/${id}`)
    }
}
</script>

<template>
    <div>
        <!-- ✅ TOAST de confirmación centrado -->
        <div v-if="mostrarToast" class="position-fixed top-50 start-50 translate-middle p-3"
            style="z-index: 1055; min-width: 300px;">
            <div class="toast text-white bg-success border-0 show shadow" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ✅ Escrito creado correctamente.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        @click="mostrarToast = false"></button>
                </div>
            </div>
        </div>

        <!-- ENCABEZADO -->
        <div class="d-flex justify-content-between align-items-center mb-3" style="color: #93c5fd">
            <h4 class="fw-bold" style="color: #93c5fd;">Escritos de usuarios</h4>
            <button class="btn" style="background-color: #4b9cd3; color: white;" @click="abrirModalCrear">
                Crear nuevo escrito
            </button>
        </div>

        <!-- TABLA -->
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
                        <button class="btn btn-sm btn-info me-2" @click="verEscrito(escrito.id)">Ver</button>
                        <button class="btn btn-sm btn-warning me-2" @click="editarEscrito(escrito.id)">Editar</button>
                        <button class="btn btn-sm btn-danger" @click="eliminarEscrito(escrito.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- MODAL -->
        <div v-if="mostrarModalCrear" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo escrito</h5>
                        <button type="button" class="btn-close" @click="cerrarModalCrear"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input v-model="form.titulo" type="text" class="form-control" />
                            <div v-if="form.errors.titulo" class="text-danger mt-1">{{ form.errors.titulo }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contenido</label>
                            <textarea v-model="form.contenido" class="form-control" rows="4"></textarea>
                            <div v-if="form.errors.contenido" class="text-danger mt-1">{{ form.errors.contenido }}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="cerrarModalCrear">Cancelar</button>
                        <button class="btn btn-primary" @click="registrarEscrito">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
