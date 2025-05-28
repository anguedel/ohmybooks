<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

import AdminLayout from '@/Layouts/AdminLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import FichaUsuario from '@/Components/MisComponentes/FichaUsuario.vue'

const user = usePage().props.auth.user
const mostrar = ref(true)

onMounted(() => {
    if (user?.rol === 'Administrador') {
        mostrar.value = false
        router.visit('/admin/panel')
    }
})

let layout = InvitadoLayout
if (user?.rol === 'Administrador') layout = AdminLayout
else if (user) layout = UsuarioLayout
</script>

<template>
    <component :is="layout" v-if="mostrar">
        <FichaUsuario />
    </component>
</template>
