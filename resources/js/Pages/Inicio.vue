<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import ContenidoInicio from '@/Components/MisComponentes/ContenidoInicio.vue'

defineProps({
    librosNovedades: Array
})

const user = usePage().props.auth.user

// Estado para confirmar si ya se redirigió
const redirigido = ref(false)

onMounted(() => {
    if (user?.rol === 'Administrador') {
        redirigido.value = true
        router.visit(route('admin.dashboard'))
    }
})

// Layout según rol
let layout = InvitadoLayout
if (user?.rol === 'Administrador') layout = AdminLayout
else if (user) layout = UsuarioLayout
</script>

<template>
    <!-- Solo renderiza si no se ha redirigido -->
    <component :is="layout" v-if="!redirigido">
        <ContenidoInicio :libros="librosNovedades" />
    </component>
</template>
