<script setup>

import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'

// Obtener el usuario desde los props compartidos
const user = usePage().props.auth.user

// Determinar layout según el rol
let layout = InvitadoLayout
if (user?.rol === 'Administrador') layout = AdminLayout
else if (user) layout = UsuarioLayout

defineProps({
    listas: Array
})


</script>


<template>
    <component :is="layout">
        <ListasPredefinidas :listas="listas" />
    </component>
</template>
