<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

import AdminLayout from '@/Layouts/AdminLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'
import Frases from '@/Components/MisComponentes/Frases.vue'

// Props de la página
const props = defineProps({
    categorias: Array,
    frasesPorCategoria: Object
})

const user = usePage().props.auth.user
const redirigido = ref(false)

// Redirigir si es admin
onMounted(() => {
    if (user?.rol === 'Administrador') {
        redirigido.value = true
        router.visit(route('admin.dashboard'))
    }
})

// Determinar layout
let layout = InvitadoLayout
if (user?.rol === 'Administrador') layout = AdminLayout
else if (user) layout = UsuarioLayout
</script>

<template>
    <component :is="layout" v-if="!redirigido">
        <Frases :categorias="categorias" :frases-por-categoria="frasesPorCategoria" />
    </component>
</template>
