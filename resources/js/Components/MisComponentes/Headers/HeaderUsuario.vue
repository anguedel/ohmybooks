<script setup>
import { reactive } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const user = usePage().props.auth.user

const dropdowns = reactive({
    explorar: false,
    comunidad: false
})

function showDropdown(menu) {
    dropdowns[menu] = true
}
function hideDropdown(menu) {
    dropdowns[menu] = false
}

function logout() {
    router.post(route('logout'), {}, {
        onSuccess: () => {
            router.visit('/', { replace: true }); // ✅ esto recarga la página desde el backend y aplica layout nuevo
        }
    });
}
</script>

<template>  
    <header class="header shadow-sm border-bottom bg-light position-relative overflow-hidden">
        <!-- Capa difuminada -->
        <div class="header-background"></div>

        <div class="container py-3 position-relative mb-5"> <!-- AÑADIDO MÁS MARGEN ABAJO -->
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-2 d-flex justify-content-start">
                    <img src="/images/LogoOficial.png" alt="Logo" class="img-fluid" style="max-height: 180px; margin-top: -20px;" />
                </div>

                <!-- Menú -->
                <div class="col-8 d-flex justify-content-center">
                    <nav class="d-flex gap-4 align-items-center position-relative">
                        <a class="nav-link" href="/">Inicio</a>

                        <!-- Dropdown: Explorador -->
                        <div class="dropdown position-relative" @mouseenter="showDropdown('explorar')" @mouseleave="hideDropdown('explorar')">
                            <a href="#" class="nav-link dropdown-toggle">Explorador de libros</a>
                            <ul class="dropdown-menu-custom small-dropdown" v-if="dropdowns.explorar">
                                <li>
                                    <Link :href="route('listas.predefinidas')">Listas</Link>
                                </li>
                                <li>
                                    <Link :href="route('generos')">Géneros</Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Dropdown: Comunidad -->
                        <div class="dropdown" @mouseenter="showDropdown('comunidad')" @mouseleave="hideDropdown('comunidad')">
                            <a href="#" class="nav-link dropdown-toggle">Comunidad</a>
                            <ul class="dropdown-menu-custom" v-if="dropdowns.comunidad">
                                <li>
                                    <Link :href="route('rincon')">Rincón del escritor</Link>
                                </li>
                                <li>
                                    <Link :href="route('frases')">Frases célebres</Link>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <!-- Usuario + logout -->
                <div class="col-2 d-flex flex-column align-items-end gap-1">
                    <div v-if="user" class="fw-medium text-info text-end">
                        Feliz lectura,
                        <Link :href="route('perfil.usuario')" class="text-info fw-bold">
                            {{ user.nombre_usuario }}
                        </Link>
                    </div>
                    <form @submit.prevent="logout">
                        <button type="submit" class="btn btn-outline-danger btn-sm custom-btn">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
</template>

<style scoped>
.nav-link {
    color: #0d6efd;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.3s;
}

.nav-link:hover {
    color: #084298;
}

.dropdown-menu-custom {
    position: absolute;
    background-color: white;
    padding: 0.5rem 0;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    top: 100%;
    left: 0;
    min-width: 180px;
    z-index: 2000;
    transition: opacity 0.2s ease-in-out;
}

.dropdown-menu-custom li a {
    display: block;
    padding: 0.5rem 1rem;
    color: #212529;
    text-decoration: none;
    transition: background-color 0.2s;
}

.dropdown-menu-custom li a:hover {
    background-color: #e7f1ff;
    color: #0d6efd;
}

.custom-btn {
    min-width: 130px;
    white-space: nowrap;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
}

.header-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
     background-color: #2b3d52;
    background-size: cover;
    background-position: center;
    filter: blur(1px) brightness(0.7);
    z-index: 0;
}

.container {
    position: relative;
    z-index: 1;
}
</style>
