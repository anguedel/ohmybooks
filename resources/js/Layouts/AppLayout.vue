<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import HeaderInvitado from '@/Components/MisComponentes/Headers/HeaderInvitado.vue';
import HeaderUsuario from '@/Components/MisComponentes/Headers/HeaderUsuario.vue';
import HeaderAdmin from '@/Components/MisComponentes/Headers/HeaderAdmin.vue';
import Footer from '@/Components/MisComponentes/Footer.vue'; // 👈 Asegúrate de que exista
// Si no tienes Footer.vue, comenta o elimina esa línea y el componente.

const user = computed(() => usePage().props.auth?.user);
</script>

<template>
    <div class="layout-wrapper d-flex flex-column min-vh-100">
        <div class="header-wrapper">
            <HeaderInvitado v-if="!user" />
            <HeaderUsuario v-else-if="user.rol === 'usuario'" />
            <HeaderAdmin v-else-if="user.rol === 'admin'" />
        </div>

        <main class="container mt-4 flex-grow-1">
            <slot />
        </main>

        <div class="footer-wrapper">
            <Footer />
        </div>
    </div>
</template>
