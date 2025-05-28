<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    listas: Array
})

const mostrarModalCrear = ref(false)
const mostrarModalEditar = ref(false)
const mostrarConfirmacionEliminar = ref(false)
const formLista = useForm({ nombre: '' })
const listaSeleccionada = ref(null)

function crearLista() {
    formLista.post(route('listas.store'), {
        onSuccess: () => {
            location.reload()
            formLista.reset('nombre')
            mostrarModalCrear.value = false
        }
    })
}

function confirmarEliminar(lista) {
    listaSeleccionada.value = lista
    mostrarConfirmacionEliminar.value = true
}

function eliminarListaConfirmada() {
    router.delete(route('listas.destroy', listaSeleccionada.value.id), {
        onSuccess: () => {
            mostrarConfirmacionEliminar.value = false
            listaSeleccionada.value = null
            location.reload()
        }
    })
}

function abrirEdicion(lista) {
    listaSeleccionada.value = lista
    formLista.nombre = lista.nombre
    mostrarModalEditar.value = true
}

function actualizarLista() {
    router.put(route('listas.update', listaSeleccionada.value.id), {
        nombre: formLista.nombre
    }, {
        onSuccess: () => {
            router.reload({ only: ['listas'] })
            mostrarModalEditar.value = false
            formLista.reset('nombre')
            listaSeleccionada.value = null
        }
    })
}
</script>

<template>
  <div class="relative py-5">
    <div class="absolute inset-0 bg-gradient-to-br from-[#cce4f6] to-[#4b9cd3] blur-sm brightness-75 z-0"></div>

    <div class="container relative z-10 text-center">
      <h1 class="text-center fw-bold mb-5 d-inline-block" style="background-color: #4b9cd3; color: white; padding: 0.5rem 1.5rem; border-radius: 0.5rem;">
        Mis listas
      </h1>

      <div class="text-center mb-4">
        <button id="abrirModalListaBtn" @click="mostrarModalCrear = true"
                class="btn fw-semibold shadow-sm"
                style="background-color: #4b9cd3; color: white;">
          ➕ Añadir nueva lista
        </button>
      </div>

      <div class="mx-auto" style="max-width: 600px;">
        <div v-for="(lista, index) in props.listas" :key="index"
            class="libro-card d-flex align-items-center justify-content-between border rounded px-3 py-2 mb-3 bg-white shadow-sm"
            style="border-color: #4b9cd3;">

          <div class="text-secondary fw-bold" style="width: 30px;">{{ index + 1 }}</div>
          <div class="flex-grow-1 text-dark fw-semibold" style="cursor: pointer"
              @click="$inertia.visit(route('listas.show', lista.id))">
            {{ lista.nombre }}
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" @click.stop="abrirEdicion(lista)" title="Editar">
              ✏️
            </button>
            <button class="btn btn-sm btn-outline-danger" @click.stop="confirmarEliminar(lista)" title="Eliminar">
              🗑️
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal nueva lista -->
  <div v-if="mostrarModalCrear" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded shadow-lg">
      <div class="bg-primary text-white p-4 flex justify-between items-center rounded-t">
        <h5 class="fw-bold m-0">Crear nueva lista</h5>
        <button @click="mostrarModalCrear = false" class="text-white text-xl font-bold hover:text-danger">×</button>
      </div>
      <div class="p-4 bg-light rounded-b">
        <form @submit.prevent="crearLista">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nombre</label>
            <input type="text" class="form-control rounded shadow-sm" v-model="formLista.nombre"
                   placeholder="Ej: Libros favoritos, Por leer..." />
            <div v-if="formLista.errors.nombre" class="text-danger mt-1 small">
              {{ formLista.errors.nombre }}
            </div>
          </div>
          <button type="submit" class="btn btn-success w-100 fw-semibold shadow-sm">
            Crear lista
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal editar lista -->
  <div v-if="mostrarModalEditar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded shadow-lg">
      <div class="bg-primary text-white p-4 flex justify-between items-center rounded-t">
        <h5 class="fw-bold m-0">Editar lista</h5>
        <button @click="mostrarModalEditar = false" class="text-white text-xl font-bold hover:text-danger">×</button>
      </div>
      <div class="p-4 bg-light rounded-b">
        <form @submit.prevent="actualizarLista">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nuevo nombre</label>
            <input type="text" class="form-control rounded shadow-sm" v-model="formLista.nombre"
                   placeholder="Ej: Libros favoritos, Por leer..." />
            <div v-if="formLista.errors.nombre" class="text-danger mt-1 small">
              {{ formLista.errors.nombre }}
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm">
            Guardar cambios
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal confirmar eliminación -->
  <div v-if="mostrarConfirmacionEliminar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded shadow-lg p-4">
      <h5 class="fw-bold text-danger mb-3">¿Eliminar lista?</h5>
      <p class="mb-4">Esta acción no se puede deshacer. ¿Seguro que quieres eliminar la lista <strong>{{ listaSeleccionada?.nombre }}</strong>?</p>
      <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" @click="mostrarConfirmacionEliminar = false">Cancelar</button>
        <button class="btn btn-danger" @click="eliminarListaConfirmada">Eliminar</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.libro-card {
  transition: all 0.25s ease-in-out;
}

.libro-card:hover {
  transform: translateY(-4px);
  border-color: #4b9cd3 !important;
  box-shadow: 0 0.5rem 1rem rgba(75, 156, 211, 0.2);
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>