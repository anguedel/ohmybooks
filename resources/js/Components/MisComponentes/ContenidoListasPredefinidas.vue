<template>
    <div class="container py-5" style="background-color: #dbe9f4;">
        <div class="mx-auto" style="max-width: 900px;">
            <h3 class="text-center mb-4">
                Ranking de la lista: <span class="fw-normal">(Nombre de la lista)</span>
            </h3>

            <div v-for="(libro, index) in libros" :key="index"
                class="border rounded p-3 mb-3 d-flex align-items-center bg-white">
                <!-- Posición -->
                <div class="me-3 fw-bold">{{ index + 1 }}</div>

                <!-- Portada -->
                <div class="me-3" style="width: 80px;">
                    <img :src="libro.portada" alt="Portada" class="img-fluid border"
                        style="width: 100%; height: auto; object-fit: cover;" />
                </div>

                <!-- Info libro -->
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">{{ libro.titulo }}</h6>
                    <p class="mb-0">{{ libro.autor }}</p>
                    <p class="mb-0">{{ libro.anio }}</p>
                    <p class="mb-0">{{ libro.notaGlobal }} <small>(nota global)</small></p>
                </div>

                <!-- Usuarios con esta lista -->
                <div class="text-center">
                    <small class="d-block text-muted">Usuarios con esta lista</small>
                    <div class="fs-4 fw-bold">{{ libro.usuarios }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const libros = [
    {
        titulo: 'El guerrero pacífico',
        autor: 'Dan Millman',
        anio: 2005,
        notaGlobal: 6.5,
        usuarios: 63,
        portada: 'https://via.placeholder.com/80x110.png?text=Portada'
    },
    {
        titulo: 'El guerrero pacífico',
        autor: 'Dan Millman',
        anio: 2005,
        notaGlobal: 6.5,
        usuarios: 63,
        portada: 'https://via.placeholder.com/80x110.png?text=Portada'
    },
    {
        titulo: 'El guerrero pacífico',
        autor: 'Dan Millman',
        anio: 2005,
        notaGlobal: 6.5,
        usuarios: 63,
        portada: 'https://via.placeholder.com/80x110.png?text=Portada'
    },
    {
        titulo: 'El guerrero pacífico',
        autor: 'Dan Millman',
        anio: 2005,
        notaGlobal: 6.5,
        usuarios: 63,
        portada: 'https://via.placeholder.com/80x110.png?text=Portada'
    }
]
</script>