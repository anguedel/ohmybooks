<script setup>
defineProps({
    listas: Array
})
</script>


<template>
    <div class="container py-5" style="background-color: #dbe9f4;">
        <h1 class="text-center text-primary fw-bold mb-4">Listas predefinidas</h1>

        <!-- Botón para añadir lista -->
        <div class="text-center mb-4">
            <button class="btn btn-warning">Añadir lista</button>
        </div>

        <!-- Tabla de listas -->
        <div class="mx-auto" style="max-width: 600px;">
            <div v-for="(lista, index) in listas" :key="index"
                class="d-flex align-items-center justify-content-between border px-3 py-2 mb-2 bg-white rounded">
                <!-- Número -->
                <div style="width: 30px;">{{ index + 1 }}</div>

                <!-- Título -->
                <div class="flex-grow-1 text-start">{{ lista.nombre }}</div>

                <!-- Cantidad -->
                <div class="text-end me-2">{{ lista.libros_count }}</div>


                <!-- Icono papelera -->
                <button class="btn p-0" title="Eliminar lista">
                    <i class="bi bi-trash text-primary fs-5"></i>
                </button>
            </div>
        </div>
    </div>
</template>