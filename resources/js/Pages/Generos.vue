<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

import AdminLayout from '@/Layouts/AdminLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import Generos from '@/Components/MisComponentes/Generos.vue'

// Usuario y datos
const page = usePage()
const user = page.props.auth.user
const generos = page.props.generos

const redirigido = ref(false)

// Redirigir si es admin
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
    <component :is="layout" v-if="!redirigido">
        <Generos :generos="generos" />
    </component>
</template>
