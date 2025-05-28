<script setup>

import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import ContenidoInicio from '@/Components/MisComponentes/ContenidoInicio.vue'
import ListasUsuarios from '@/Components/MisComponentes/ListasUsuarios.vue'
import ListasPredefinidas from '@/Components/MisComponentes/ListasPredefinidas.vue'

// Obtener el usuario desde los props compartidos
const user = usePage().props.auth.user

// Determinar layout según el rol
let layout = InvitadoLayout
if (user?.rol === 'Administrador') layout = AdminLayout
else if (user) layout = UsuarioLayout

</script>

<template>

    <component :is="layout">
        <ListasPredefinidas />
    </component>

</template>