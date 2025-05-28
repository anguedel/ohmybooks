<script setup>

import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import ContenidoListasUsuario from '@/Components/MisComponentes/ContenidoListasUsuario.vue'

// Obtener el usuario desde los props compartidos
const user = usePage().props.auth.user
const page = usePage()
const lista = page.props.lista


// Determinar layout según el rol
let layout = InvitadoLayout
if (user?.rol === 'Administrador') layout = AdminLayout
else if (user) layout = UsuarioLayout

</script>

<template>

    <component :is="layout">
        <ContenidoListasUsuario :lista="lista" />

    </component>
</template>