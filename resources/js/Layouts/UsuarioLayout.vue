<script setup>
import HeaderInvitado from '@/Components/MisComponentes/Headers/HeaderUsuario.vue'
import Footer from '@/Components/MisComponentes/Footer.vue'
</script>

<template>
    <div class="layout-wrapper d-flex flex-column min-vh-100">
        <!-- Header para usuarios autenticados -->
        <div class="header-wrapper">
            <HeaderInvitado />
        </div>

        <!-- Contenido principal -->
        <main class="flex-fill content-wrapper">
            <slot />
        </main>

        <!-- Footer común -->
        <div class="footer-wrapper">
            <Footer />
        </div>
    </div>
</template>



<style scoped>
.layout-wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.header-wrapper {
    margin-bottom: 2rem;
    /* espacio entre header y contenido */
}

.footer-wrapper {
    margin-top: 2rem;
    /* espacio entre contenido y footer */
}

.content-wrapper {
    flex: 1;
}
</style>