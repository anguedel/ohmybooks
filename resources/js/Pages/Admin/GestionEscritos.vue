<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ListaEscritos from '@/Components/Admin/ListaEscritos.vue'
import { usePage } from '@inertiajs/vue3'

const escritos = usePage().props.escritos
</script>

<template>
    <AdminLayout>
        <ListaEscritos :escritos="escritos" />
    </AdminLayout>
</template>
