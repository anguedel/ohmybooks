<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    frases: Array
})

const mostrarModalCrear = ref(false)
const toastVisible = ref(false)
const toastMensaje = ref('')

const categorias = ['Filosofía', 'Estoicismo', 'Persuasión', 'Motivación', 'Poesía']

const form = useForm({
    texto: '',
    autor: '',
    categoria: ''
})

function abrirModalCrear() {
    mostrarModalCrear.value = true
}

function cerrarModalCrear() {
    mostrarModalCrear.value = false
    form.reset()
}

function registrarFrase() {
    form.post('/admin/frases', {
        onSuccess: () => {
            toastMensaje.value = 'Frase creada correctamente'
            toastVisible.value = true
            cerrarModalCrear()
            setTimeout(() => (toastVisible.value = false), 3000)
        }
    })
}

function editarFrase(id) {
    router.visit(`/admin/frases/${id}/edit`)
}

function eliminarFrase(id) {
    if (confirm('¿Deseas eliminar esta frase?')) {
        router.delete(`/admin/frases/${id}`)
    }
}
</script>

<template>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold" style="color: #93c5fd;">Frases célebres</h4>
            <button class="btn" style="background-color: #4b9cd3; color: white;" @click="abrirModalCrear">
                Crear nueva frase
            </button>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th>ID</th>
                    <th>ID Usuario</th>
                    <th>Frase</th>
                    <th>Autor</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="frase in frases" :key="frase.id">
                    <td>{{ frase.id }}</td>
                    <td>{{ frase.usuario_id }}</td>
                    <td>{{ frase.texto }}</td>
                    <td>{{ frase.autor }}</td>
                    <td>{{ frase.categoria }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning me-2" @click="editarFrase(frase.id)">Editar</button>
                        <button class="btn btn-sm btn-danger" @click="eliminarFrase(frase.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal Crear Frase -->
        <div v-if="mostrarModalCrear" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva frase</h5>
                        <button type="button" class="btn-close" @click="cerrarModalCrear"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Texto</label>
                            <textarea v-model="form.texto" class="form-control"></textarea>
                            <div v-if="form.errors.texto" class="text-danger">{{ form.errors.texto }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Autor</label>
                            <input v-model="form.autor" type="text" class="form-control" />
                            <div v-if="form.errors.autor" class="text-danger">{{ form.errors.autor }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select v-model="form.categoria" class="form-select">
                                <option value="">Selecciona una categoría</option>
                                <option v-for="cat in categorias" :key="cat" :value="cat">{{ cat }}</option>
                            </select>
                            <div v-if="form.errors.categoria" class="text-danger">{{ form.errors.categoria }}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="cerrarModalCrear">Cancelar</button>
                        <button class="btn btn-primary" @click="registrarFrase">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast de confirmación -->
        <div v-if="toastVisible"
            class="position-fixed top-50 start-50 translate-middle bg-success text-white p-3 rounded shadow"
            style="z-index: 1055;">
            {{ toastMensaje }}
        </div>
    </div>
</template>
