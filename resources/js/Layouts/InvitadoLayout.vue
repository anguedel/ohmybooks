<template>
    <div class="layout-wrapper d-flex flex-column min-vh-100">
        <!-- Header separado -->
        <div class="header-wrapper">
            <Header />
        </div>

        <!-- Contenido principal -->
        <main class="flex-fill content-wrapper">
            <slot />
        </main>

        <!-- Footer separado -->
        <div class="footer-wrapper">
            <Footer />
        </div>
    </div>
</template>

<script setup>
import Header from '@/Components/MisComponentes/Headers/HeaderInvitado.vue'
import Footer from '@/Components/MisComponentes/Footer.vue' 
</script>

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