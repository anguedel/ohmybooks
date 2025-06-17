<script setup>
import { ref } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'

const props = defineProps({
    libros: Object,
    generos: Array
})

const mostrarModalCrear = ref(false)
const mostrarToast = ref(false)
const mostrarModalConfirmarEliminacion = ref(false)
const libroAEliminar = ref(null)

const form = useForm({
    titulo: '',
    sinopsis: '',
    autor: '',
    paginas: '',
    editorial: '',
    genero_id: ''
})

function abrirModalCrear() {
    mostrarModalCrear.value = true
}

function cerrarModalCrear() {
    mostrarModalCrear.value = false
    form.reset()
}

function registrarLibro() {
    form.post('/admin/libros', {
        onSuccess: () => {
            cerrarModalCrear()
            mostrarToast.value = true
            setTimeout(() => {
                mostrarToast.value = false
            }, 4000)
        }
    })
}

function editarLibro(id) {
    router.visit(`/admin/libros/${id}/edit`)
}



const mostrarModalEditar = ref(false)
const libroSeleccionado = ref(null)

const formEditar = useForm({
    id: null,
    titulo: '',
    sinopsis: '',
    autor: '',
    paginas: '',
    editorial: '',
    genero_id: ''
})

function abrirModalEditar(libro) {
    libroSeleccionado.value = libro
    formEditar.id = libro.id
    formEditar.titulo = libro.titulo
    formEditar.sinopsis = libro.sinopsis
    formEditar.autor = libro.autor
    formEditar.paginas = libro.paginas
    formEditar.editorial = libro.editorial
    formEditar.genero_id = libro.generos?.[0]?.id ?? '' // si hay uno, tomamos el primero
    mostrarModalEditar.value = true
}

function cerrarModalEditar() {
    mostrarModalEditar.value = false
    formEditar.reset()
}

function actualizarLibro() {
    formEditar.put(`/admin/libros/${formEditar.id}`, {
        onSuccess: () => {
            cerrarModalEditar()
            mostrarToast.value = true
            setTimeout(() => {
                mostrarToast.value = false
            }, 4000)
        }
    })
}


function confirmarEliminar(libro) {
    libroAEliminar.value = libro
    mostrarModalConfirmarEliminacion.value = true
}

function cancelarEliminar() {
    mostrarModalConfirmarEliminacion.value = false
    libroAEliminar.value = null
}

function eliminarLibroConfirmado() {
    router.delete(`/admin/libros/${libroAEliminar.value.id}`, {
        onSuccess: () => {
            mostrarModalConfirmarEliminacion.value = false
            libroAEliminar.value = null
            mostrarToast.value = true
            setTimeout(() => mostrarToast.value = false, 4000)
        }
    })
}
</script>

<template>
    <div>
        <!-- ✅ TOAST Bootstrap de confirmación -->
        <div v-if="mostrarToast" class="position-fixed top-50 start-50 translate-middle p-3"
            style="z-index: 1055; min-width: 300px;">

            <div class="toast align-items-center text-white bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ libroAEliminar ? 'Libro eliminado correctamente.' : 'Libro creado correctamente.' }}
                    </div>

                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        @click="mostrarToast = false"></button>
                </div>
            </div>
        </div>

        <!-- Botón y encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold" style="color: #93c5fd;">Libros</h4>
            <button class="btn" style="background-color: #4b9cd3; color: white;" @click="abrirModalCrear">
                Crear nuevo libro
            </button>
        </div>

        <!-- Tabla de libros -->
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="libro in libros.data" :key="libro.id">
                    <td>{{ libro.id }}</td>
                    <td>{{ libro.titulo }}</td>
                    <td>
                        <span v-if="libro.generos && libro.generos.length">
                            <span v-for="g in libro.generos" :key="g.id" class="badge bg-primary me-1">
                                {{ g.genero }}
                            </span>
                        </span>
                        <span v-else class="text-muted">Sin género</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning me-2" @click="abrirModalEditar(libro)">Editar</button>

                        <button class="btn btn-sm btn-danger" @click="confirmarEliminar(libro)">Eliminar</button>

                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav v-if="libros.links && libros.links.length > 3" class="mt-3 d-flex justify-content-center">
            <ul class="pagination">
                <li v-for="link in libros.links" :key="link.label" class="page-item"
                    :class="{ active: link.active, disabled: !link.url }">
                    <button class="page-link" v-html="link.label" @click="link.url && router.get(link.url)"
                        :disabled="!link.url" />
                </li>
            </ul>
        </nav>


        <!-- Modal Crear Libro -->
        <div v-if="mostrarModalCrear" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo libro</h5>
                        <button type="button" class="btn-close" @click="cerrarModalCrear"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input v-model="form.titulo" type="text" class="form-control" />
                            <div v-if="form.errors.titulo" class="text-danger">{{ form.errors.titulo }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sinopsis</label>
                            <textarea v-model="form.sinopsis" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Autor</label>
                            <input v-model="form.autor" type="text" class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Número de páginas</label>
                            <input v-model="form.paginas" type="number" class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Editorial</label>
                            <input v-model="form.editorial" type="text" class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Género</label>
                            <select v-model="form.genero_id" class="form-select">
                                <option value="">Selecciona un género</option>
                                <option v-for="g in generos" :key="g.id" :value="g.id">{{ g.genero }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="cerrarModalCrear">Cancelar</button>
                        <button class="btn btn-primary" @click="registrarLibro">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Libro -->
    <div v-if="mostrarModalEditar" class="modal fade show d-block" tabindex="-1"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Editar libro</h5>
                    <button type="button" class="btn-close" @click="cerrarModalEditar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input v-model="formEditar.titulo" type="text" class="form-control" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sinopsis</label>
                        <textarea v-model="formEditar.sinopsis" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Autor</label>
                        <input v-model="formEditar.autor" type="text" class="form-control" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Número de páginas</label>
                        <input v-model="formEditar.paginas" type="number" class="form-control" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Editorial</label>
                        <input v-model="formEditar.editorial" type="text" class="form-control" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Género</label>
                        <select v-model="formEditar.genero_id" class="form-select">
                            <option value="">Selecciona un género</option>
                            <option v-for="g in generos" :key="g.id" :value="g.id">{{ g.genero }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="cerrarModalEditar">Cancelar</button>
                    <button class="btn btn-warning" @click="actualizarLibro">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Eliminación -->
    <div v-if="mostrarModalConfirmarEliminacion" class="modal fade show d-block" tabindex="-1"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" @click="cancelarEliminar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el libro
                        <strong>"{{ libroAEliminar?.titulo }}"</strong>?
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="cancelarEliminar">Cancelar</button>
                    <button class="btn btn-danger" @click="eliminarLibroConfirmado">Eliminar</button>
                </div>
            </div>
        </div>
    </div>


</template>
