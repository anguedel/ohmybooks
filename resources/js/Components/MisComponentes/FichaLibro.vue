<script setup>
import { useForm, usePage, router } from '@inertiajs/vue3'
import { onMounted, ref, computed } from 'vue'

const user = usePage().props.auth.user
const props = defineProps({
    libro: Object,
    votoUsuario: Number,
    mediaVoto: Number,
    totalVotos: Number
})

const mostrarModalImagen = ref(false)
const imagenAmpliada = ref('')
// ------------------------
// Comentarios: añadir / editar / eliminar
// ------------------------

const mostrarModal = ref(false) // Añadir comentario
const showToast = ref(false)
const comentarioSeleccionado = ref(null)
const mostrarAccionesModal = ref(false)
const mostrarConfirmacionEliminacion = ref(false)
const mostrarEdicion = ref(false)
const listas = usePage().props.listas ?? []
const mostrarModalListas = ref(false)
const formComentario = useForm({ id_libro: props.libro.id, comentario: '' })
const formEdicion = useForm({ comentario: '' })

const notaMediaFormateada = computed(() =>
    typeof props.mediaVoto === 'number' ? props.mediaVoto.toFixed(1) : '—'
)



function mostrarToastTemporal() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}

function enviarComentario() {
    formComentario.post(route('comentarios.store'), {
        onSuccess: () => {
            mostrarModal.value = false
            formComentario.reset('comentario')
            mostrarToastTemporal()
            setTimeout(() => router.visit(window.location.pathname), 3000)
        }
    })
}

function abrirModalImagen(ruta) {
    imagenAmpliada.value = ruta
    mostrarModalImagen.value = true
}

function cerrarModalImagen() {
    mostrarModalImagen.value = false
}

function abrirAcciones(comentario) {
    comentarioSeleccionado.value = comentario
    mostrarAccionesModal.value = true
}

function confirmarEliminar() {
    mostrarAccionesModal.value = false
    mostrarConfirmacionEliminacion.value = true
}

function editarComentarioDesdeLista(comentario) {
    comentarioSeleccionado.value = comentario
    formEdicion.comentario = comentario.comentario
    mostrarEdicion.value = true
}

function eliminarComentarioDesdeLista(comentario) {
    comentarioSeleccionado.value = comentario
    mostrarConfirmacionEliminacion.value = true
}

function eliminarComentario() {
    if (!comentarioSeleccionado.value || !comentarioSeleccionado.value.id) {
        console.error("No hay comentario seleccionado para eliminar.")
        return
    }

    router.delete(route('comentarios.destroy', comentarioSeleccionado.value.id), {
        onSuccess: () => {
            mostrarConfirmacionEliminacion.value = false
            comentarioSeleccionado.value = null
            router.visit(window.location.pathname, { preserveScroll: true })
        }
    })
}

function abrirModalListas() {
    mostrarModalListas.value = true
}

function cerrarModalListas() {
    mostrarModalListas.value = false
}

function añadirLibroALista(listaId) {
    console.log(listaId, props.libro.id)
    router.post(route('listas.agregarLibro'), {
        id_lista: listaId,
        id_libro: props.libro.id
    }, {
        onSuccess: () => {
            cerrarModalListas()
            mostrarToastTemporal()
        }
    })
}


function guardarComentarioEditado() {
    router.put(route('comentarios.update', comentarioSeleccionado.value.id), {
        comentario: formEdicion.comentario
    }, {
        onSuccess: () => {
            mostrarEdicion.value = false
            comentarioSeleccionado.value = null
            router.visit(window.location.pathname, { preserveScroll: true })
        }
    })
}

// ------------------------
// Votación
// ------------------------

const form = useForm({
    id_libro: props.libro.id,
    puntuacion: props.votoUsuario !== null ? Number(props.votoUsuario) : ''
})

function votar() {
    if (form.puntuacion === '') return

    form.post(route('votar'), {
        preserveScroll: true
    })
}

console.log('votoUsuario:', props.votoUsuario)

</script>

<template>
    <div class="bg-blue-100 p-6 rounded shadow-md max-w-4xl mx-auto mt-10">
        <h1 class="text-2xl font-bold text-center text-blue-800 mb-6">{{ libro.titulo }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 space-y-2">
                <div class="bg-light p-4 rounded shadow-sm border border-primary-subtle">
                    <p class="mb-2">
                        <span class="text-primary fw-semibold">Título original:</span>
                        <span class="text-dark ms-1">{{ libro.titulo }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="text-primary fw-semibold">Autor:</span>
                        <span class="text-dark ms-1">{{ libro.autor }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="text-primary fw-semibold">Género:</span>
                        <span class="text-dark ms-1">{{ libro.genero }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="text-primary fw-semibold">Número de páginas:</span>
                        <span class="text-dark ms-1">{{ libro.paginas }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="text-primary fw-semibold">Fecha de publicación:</span>
                        <span class="text-dark ms-1">{{ libro.fecha_publicacion }}</span>
                    </p>
                    <p class="mb-0">
                        <span class="text-primary fw-semibold">Sinopsis:</span>
                        <span class="text-dark ms-1">{{ libro.sinopsis }}</span>
                    </p>
                </div>

            </div>

            <div class="flex flex-col items-center justify-start">
                <img :src="`/images/Libros/${libro.portada}`" alt="Portada del libro"
                    class="w-32 h-auto mb-2 border border-primary rounded shadow-sm" style="cursor: zoom-in;"
                    @click="abrirModalImagen(`/images/Libros/${libro.portada}`)" />

                <div v-if="user" class="mt-3 w-full text-center">
                    <label for="voto" class="block text-sm font-semibold text-gray-700 mb-1">Tu voto</label>
                    <div class="inline-block w-24">
                        <select id="voto" v-model="form.puntuacion" @change="votar"
                            class="form-select text-center rounded px-2 py-1 w-full">
                            <option disabled value="">Sin leer</option>
                            <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
                        </select>


                        <!-- ✅ Mensaje permanente si hay voto -->
                        <p v-if="form.puntuacion" class="mt-2 text-success small text-center">
                            Has votado con un {{ form.puntuacion }}
                        </p>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <div class="text-center mt-4 p-3 rounded border border-primary-subtle bg-light shadow-sm">
                        <div class="mb-3">
                            <p class="text-muted mb-1 fw-semibold">Nota media</p>
                            <p class="display-6 fw-bold text-primary mb-0">
                                {{ notaMediaFormateada }}
                            </p>
                        </div>

                        <div>
                            <p class="text-muted mb-1 fw-semibold">Votos totales</p>
                            <p class="fs-5 text-secondary mb-0">
                                {{ totalVotos }} {{ totalVotos === 1 ? 'voto' : 'votos' }}
                            </p>
                        </div>
                    </div>


                </div>

            </div>
        </div>

        <div v-if="user" class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 my-5">
            <button @click="mostrarModal = true" class="btn btn-outline-primary px-4 py-2 fw-semibold shadow-sm">
                Añadir un comentario
            </button>

            <button @click="abrirModalListas" class="btn btn-outline-success px-4 py-2 fw-semibold shadow-sm">
                Añadir a una lista
            </button>

        </div>


        <!-- Comentarios -->
        <div class="mt-4">
            <h2 class="text-lg font-semibold">Comentarios de usuarios:</h2>
            <template v-if="libro.comentarios.length">
                <div v-for="comentario in libro.comentarios" :key="comentario.id"
                    class="mt-2 p-3 rounded border bg-white shadow-sm position-relative">

                    <p class="italic mb-1">“{{ comentario.comentario }}”</p>
                    <p class="text-sm text-secondary fw-semibold mb-0">— {{ comentario.usuario.nombre }}</p>

                    <!-- Botones solo para el autor del comentario -->
                    <div v-if="user && user.id === comentario.usuario.id"
                        class="position-absolute top-0 end-0 m-2 d-flex flex-column gap-1">
                        <button @click="editarComentarioDesdeLista(comentario)" class="btn btn-sm btn-outline-primary"
                            title="Editar">
                            ✏️
                        </button>
                        <button @click="eliminarComentarioDesdeLista(comentario)" class="btn btn-sm btn-outline-danger"
                            title="Eliminar">
                            🗑️
                        </button>
                    </div>
                </div>

            </template>
            <p v-else class="italic text-gray-500">Este libro aún no tiene comentarios.</p>
        </div>

        <!-- Modal añadir comentario -->
        <div v-if="mostrarModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Añadir comentario</h2>
                <label for="comentario" class="block mb-1 font-medium">Escribe tu comentario</label>
                <textarea id="comentario" v-model="formComentario.comentario"
                    class="w-full border border-gray-300 rounded p-2 mb-4 resize-none" rows="4"></textarea>
                <div class="flex justify-end space-x-2">
                    <button @click="mostrarModal = false" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">
                        Cerrar ventana
                    </button>
                    <button @click="enviarComentario"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Enviar comentario
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal acciones -->
        <transition name="fade-modal">
            <div v-if="mostrarAccionesModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow w-full max-w-sm relative">
                    <!-- Botón cerrar -->
                    <button @click="mostrarAccionesModal = false"
                        class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl font-bold">
                        &times;
                    </button>

                    <h2 class="text-xl font-semibold mb-4 text-center">Opciones</h2>
                    <div class="flex justify-between">
                        <button @click="editarComentario"
                            class="bg-blue-500 text-white px-4 py-2 rounded">Editar</button>
                        <button @click="confirmarEliminar"
                            class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
                    </div>
                </div>
            </div>
        </transition>


        <!-- Confirmar eliminación -->
        <div v-if="mostrarConfirmacionEliminacion"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-sm">
                <h2 class="text-xl font-semibold mb-4">¿Eliminar comentario?</h2>
                <div class="flex justify-between">
                    <button @click="mostrarConfirmacionEliminacion = false"
                        class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                    <button @click="eliminarComentario"
                        class="bg-red-600 text-white px-4 py-2 rounded">Confirmar</button>

                </div>
            </div>
        </div>

        <!-- Editar comentario -->
        <div v-if="mostrarEdicion" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Editar comentario</h2>
                <textarea v-model="formEdicion.comentario" rows="4"
                    class="w-full border border-gray-300 rounded p-2 mb-4 resize-none"></textarea>
                <div class="flex justify-end space-x-2">
                    <button @click="mostrarEdicion = false" class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                    <button @click="guardarComentarioEditado"
                        class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast de éxito -->
    <transition name="fade">
        <div v-if="showToast"
            class="fixed bottom-6 right-6 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg text-lg font-semibold z-50 animate-bounce-in">
            ✨ Comentario publicado con éxito
        </div>
    </transition>

    <!-- Modal para ampliar imagen -->
    <div v-if="mostrarModalImagen" class="modal fade show d-block"
        style="background-color: #4b9cd3; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 1055;"
        @click.self="cerrarModalImagen">
        <div class="d-flex justify-content-center align-items-center h-100">
            <img :src="imagenAmpliada" class="img-fluid"
                style="max-height: 95vh; max-width: 95vw; object-fit: contain; border: 4px solid white; border-radius: 12px;" />
        </div>
        <button class="btn btn-light position-absolute top-0 end-0 m-4" @click="cerrarModalImagen">
            ✖
        </button>
    </div>

    <!-- Modal Añadir a una lista -->
    <div v-if="mostrarModalListas" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Tus listas</h2>
                <button @click="cerrarModalListas" class="text-gray-500 hover:text-red-600 text-xl font-bold">×</button>
            </div>
            <div v-if="listas.length > 0">
                <div v-for="lista in listas" :key="lista.id" class="border rounded p-3 mb-3 shadow-sm">
                    <div class="flex justify-between items-center">
                        <span class="fw-semibold">{{ lista.nombre }}</span>
                        <button class="btn btn-sm btn-outline-primary fw-semibold" @click="añadirLibroALista(lista.id)">
                            Añadir libro
                        </button>
                    </div>
                </div>
            </div>
            <p v-else class="text-muted italic text-center">No tienes listas creadas aún.</p>
        </div>
    </div>


</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes bounce-in {
    0% {
        transform: scale(0.9);
        opacity: 0;
    }

    60% {
        transform: scale(1.05);
        opacity: 1;
    }

    100% {
        transform: scale(1);
    }
}

.animate-bounce-in {
    animation: bounce-in 0.3s ease;
}

.fade-modal-enter-active,
.fade-modal-leave-active {
    transition: opacity 0.3s ease;
}

.fade-modal-enter-from,
.fade-modal-leave-to {
    opacity: 0;
}
</style>
