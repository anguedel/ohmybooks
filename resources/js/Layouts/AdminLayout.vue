<script setup>
import HeaderAdmin from '@/Components/MisComponentes/Headers/HeaderAdmin.vue'
import Footer from '@/Components/MisComponentes/Footer.vue'
</script>

<template>
    <div class="layout-wrapper d-flex flex-column min-vh-100">
        <!-- Header de administración -->
        <div class="header-wrapper">
            <HeaderAdmin />
        </div>

        <!-- Contenido de cada página -->
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
}

.footer-wrapper {
    margin-top: 2rem;
}

.content-wrapper {
    flex: 1;
}
</style>