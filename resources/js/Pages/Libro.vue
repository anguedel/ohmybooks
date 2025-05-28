<script setup>
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'
import FichaLibro from '@/Components/MisComponentes/FichaLibro.vue'

const page = usePage()
const user = page.props.auth.user
const libro = page.props.libro

let layout = InvitadoLayout
if (user?.rol === 'Administrador') layout = AdminLayout
else if (user) layout = UsuarioLayout
</script>

<template>
    <component :is="layout">
        <FichaLibro :libro="libro" :votoUsuario="page.props.votoUsuario" :mediaVoto="page.props.mediaVoto"
            :totalVotos="page.props.totalVotos" />

    </component>
</template>
