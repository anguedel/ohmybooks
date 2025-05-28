<script setup>
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InvitadoLayout from '@/Layouts/InvitadoLayout.vue'
import UsuarioLayout from '@/Layouts/UsuarioLayout.vue'
import ListasUsuarios from '@/Components/MisComponentes/ListasUsuarios.vue'

// Obtener el usuario y listas desde los props compartidos
const page = usePage()
const user = page.props.auth.user
const listas = page.props.listas

// Determinar layout según el rol
let layout = InvitadoLayout
if (user?.rol === 'Administrador') layout = AdminLayout
else if (user) layout = UsuarioLayout

console.log('Listas recibidas:', listas)

</script>

<template>
    <component :is="layout">
        <ListasUsuarios :listas="listas" />
    </component>
</template>
