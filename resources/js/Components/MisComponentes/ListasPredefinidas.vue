<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    listas: Array
})

const listaSeleccionada = ref(null)
const mostrarModal = ref(false)

function abrirModal(lista) {
    listaSeleccionada.value = lista
    mostrarModal.value = true
}

function cerrarModal() {
    mostrarModal.value = false
}

console.log(props.listas)
</script>


<template>
    <div class="relative py-5">
        <div class="absolute inset-0 bg-gradient-to-br from-[#cce4f6] to-[#4b9cd3] blur-sm brightness-75 z-0"></div>

        <div class="container relative z-10 px-3">
            <div class="text-center mb-4">
                <h1 class="d-inline-block"
                    style="background-color: #4b9cd3; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">
                    Listas predefinidas
                </h1>
            </div>

            <br>

            <div class="mx-auto d-flex flex-wrap gap-3 justify-content-center">
                <div v-for="(lista, index) in listas" :key="index"
                    class="card lista-card shadow-sm border bg-white position-relative text-center"
                    style="width: 220px; height: 160px; border: 2px solid #4b9cd3; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                    @click="abrirModal(lista)">
                    <h5 class="fw-bold m-0" style="color: #4b9cd3;">{{ lista.nombre }}</h5>

                    <div class="lista-info-hover position-absolute bottom-0 start-0 end-0 d-flex justify-content-between align-items-center px-3 py-2"
                        style="background: rgba(255, 255, 255, 0.9); display: none;">
                        <small class="text-muted">📚 {{ lista.libros_count }} libros</small>
                        <i class="bi bi-eye text-primary fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="mostrarModal"
            class="fixed inset-0 bg-black bg-opacity-50 d-flex justify-content-center align-items-center z-50">
            <div class="bg-white rounded-4 shadow-lg p-4 border border-primary-subtle"
                style="width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto;">

                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h4 class="mb-0 fw-bold" style="color: #084298;">{{ listaSeleccionada.nombre }}</h4>
                    <button class="btn btn-sm btn-outline-secondary" @click="cerrarModal">Cerrar</button>
                </div>

                <!-- Lista de libros -->
                <ul class="list-group border-0">
                    <li v-if="listaSeleccionada?.libros?.length" v-for="libro in listaSeleccionada.libros"
                        :key="libro.id"
                        class="list-group-item libro-item mb-2 d-flex justify-content-between align-items-center border rounded"
                        style="border-color: #cce4f6;">

                        <Link :href="route('libros.show', libro.id)"
                            class="text-decoration-none w-100 d-flex justify-content-between align-items-center libro-link">
                        <span class="libro-titulo">{{ libro.titulo }}</span>
                        <i class="bi bi-book text-primary fs-5"></i>
                        </Link>
                    </li>

                    <li v-else class="list-group-item text-muted text-center rounded">
                        No hay libros en esta lista.
                    </li>
                </ul>
            </div>
        </div>

    </div>


</template>

<style scoped>
.lista-card {
    transition: all 0.25s ease-in-out;
    border-radius: 0.75rem;
}

.lista-card:hover {
    transform: translateY(-4px);
    border-color: #4b9cd3 !important;
    box-shadow: 0 0.5rem 1rem rgba(75, 156, 211, 0.2);
}

.lista-card:hover .lista-info-hover {
    display: flex !important;
}

.libro-item {
    transition: all 0.25s ease;
}

.libro-link {
    color: #084298;
    transition: all 0.25s ease;
}

.libro-titulo {
    font-weight: 500;
    transition: transform 0.25s ease;
}

.libro-item:hover {
    background-color: #e6f3fc;
    box-shadow: 0 0.5rem 1rem rgba(75, 156, 211, 0.2);
}

.libro-item:hover .libro-titulo {
    transform: scale(1.03);
    font-weight: 600;
}
</style>
