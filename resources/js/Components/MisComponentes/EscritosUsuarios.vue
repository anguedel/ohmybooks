<script setup>
import { ref } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'

const props = defineProps({
  escritos: Array
})

const user = usePage().props.auth?.user ?? null

const selected = ref({})
const showModal = ref(false)
const mostrarEdicion = ref(false)
const mostrarEliminar = ref(false)
const escritoSeleccionado = ref(null)

const formEdicion = useForm({
  titulo: '',
  contenido: ''
})

function openModal(escrito) {
  selected.value = escrito
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

function preview(texto) {
  return texto.split(' ').slice(0, 50).join(' ') + '...'
}

function getFoto(foto) {
  if (!foto || foto === 'null' || foto.trim() === '') {
    return '/images/FotosPerfil/pordefecto.png'
  }
  if (foto.startsWith('http://') || foto.startsWith('https://')) {
    return foto
  }
  return `/images/FotosPerfil/${foto}`
}

const mostrarModalPublicar = ref(false)
const form = useForm({
  titulo: '',
  contenido: ''
})

function abrirModalPublicar() {
  mostrarModalPublicar.value = true
}
function cerrarModalPublicar() {
  mostrarModalPublicar.value = false
}

function publicarEscrito() {
  form.post(route('escritos.store'), {
    onSuccess: () => {
      form.reset()
      mostrarModalPublicar.value = false
      mostrarToast('✅ Escrito publicado con éxito')
      router.visit(window.location.pathname, { preserveScroll: true })
    }
  })
}

const toastVisible = ref(false)
const toastMensaje = ref('')

function mostrarToast(mensaje) {
  toastMensaje.value = mensaje
  toastVisible.value = true
  setTimeout(() => toastVisible.value = false, 3000)
}

function abrirEdicion(escrito) {
  escritoSeleccionado.value = escrito
  formEdicion.titulo = escrito.titulo
  formEdicion.contenido = escrito.contenido
  mostrarEdicion.value = true
}

function actualizarEscrito() {
  formEdicion.put(route('escritos.update', escritoSeleccionado.value.id), {
    onSuccess: () => {
      mostrarEdicion.value = false
      mostrarToast('✏️ Escrito actualizado correctamente')
      router.visit(window.location.pathname, { preserveScroll: true })
    }
  })
}

function abrirConfirmacion(escrito) {
  escritoSeleccionado.value = escrito
  mostrarEliminar.value = true
}

function eliminarEscrito() {
  router.delete(route('escritos.destroy', escritoSeleccionado.value.id), {
    onSuccess: () => {
      mostrarEliminar.value = false
      mostrarToast('🗑️ Escrito eliminado')
      router.visit(window.location.pathname, { preserveScroll: true })
    }
  })
}
</script>

<template>
  <div class="relative py-5">
    <div class="absolute inset-0 bg-cover bg-center blur-sm brightness-75 z-0"
      style="background-image: url('/images/Fondo2.avif');">
    </div>

    <div class="container relative z-10">
      <div class="text-center mb-3">
        <h1 class="d-inline-block"
          style="background-color: #d38e0d; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">
          El rincón del escritor
        </h1>
      </div>

      <div class="text-end mb-4" v-if="user">
        <button @click="abrirModalPublicar" class="btn fw-semibold shadow-sm"
          style="color: #d38e0d; border: 2px solid #d38e0d;">
          ✍️ Publicar escrito
        </button>
      </div>

      <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4" v-for="escrito in props.escritos" :key="escrito.id">
          <div class="card h-100 border shadow-sm position-relative"
            style="border-color: #d38e0d; background-color: #fffbe6;">

            <div v-if="user && user.id === escrito.usuario_id"
              class="position-absolute top-0 end-0 m-2 d-flex flex-column gap-1 z-10">
              <button class="btn btn-sm btn-outline-warning" @click="abrirEdicion(escrito)"
                title="Editar escrito">✏️</button>
              <button class="btn btn-sm btn-outline-danger" @click="abrirConfirmacion(escrito)"
                title="Eliminar escrito">🗑️</button>
            </div>

            <div class="card-body">
              <h5 class="card-title fw-bold mb-3" style="color: #d38e0d;">{{ escrito.titulo }}</h5>
              <div class="d-flex align-items-start gap-3">
                <img :src="getFoto(escrito.usuario.foto)" alt="Foto del autor" class="rounded-circle"
                  style="width: 48px; height: 48px; object-fit: cover;" />
                <p class="card-text small text-muted mb-0">{{ preview(escrito.contenido) }}</p>
              </div>
            </div>

            <div class="card-footer bg-transparent border-top-0">
              <div class="d-flex justify-content-between align-items-center w-100">
                <small class="fw-semibold" style="color: #d38e0d;">{{ escrito.usuario.nombre }}</small>
                <a href="#" class="btn btn-sm fw-semibold" style="border: 2px solid #d38e0d; color: #d38e0d;"
                  @click.prevent="openModal(escrito)">
                  Seguir leyendo →
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Aquí permanecen todos los modales y el toast sin cambios de estilo, excepto si lo deseas -->

  <!-- Modal para publicar -->
  <div v-if="mostrarModalPublicar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 max-w-lg w-full">
      <h2 class="text-lg fw-bold mb-3">Publicar escrito</h2>
      <form @submit.prevent="publicarEscrito">
        <div class="mb-3">
          <label class="form-label">Título</label>
          <input type="text" v-model="form.titulo" class="form-control" />
        </div>
        <div class="mb-3">
          <label class="form-label">Contenido</label>
          <textarea v-model="form.contenido" class="form-control" rows="5"></textarea>
        </div>
        <div class="d-flex justify-content-end gap-2">
          <button class="btn btn-secondary" @click="cerrarModalPublicar">Cancelar</button>
          <button class="btn btn-primary" type="submit">Publicar</button>
        </div>
      </form>
    </div>
  </div>

  <div v-if="toastVisible" class="toast align-items-center text-white position-fixed bottom-0 end-0 m-4 p-3 bg-success"
    role="alert" aria-live="assertive" aria-atomic="true"
    style="z-index: 9999; min-width: 200px; border-radius: 0.5rem;">
    {{ toastMensaje }}
  </div>


  <!-- Modal para editar -->
  <div v-if="mostrarEdicion" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 max-w-lg w-full">
      <h2 class="text-lg fw-bold mb-3">Editar escrito</h2>
      <form @submit.prevent="actualizarEscrito">
        <div class="mb-3">
          <label class="form-label">Título</label>
          <input type="text" v-model="formEdicion.titulo" class="form-control" />
        </div>
        <div class="mb-3">
          <label class="form-label">Contenido</label>
          <textarea v-model="formEdicion.contenido" class="form-control" rows="5"></textarea>
        </div>
        <div class="d-flex justify-content-end gap-2">
          <button class="btn btn-secondary" @click="mostrarEdicion = false">Cancelar</button>
          <button class="btn btn-warning" type="submit">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>


  <!-- Modal de confirmación para eliminar -->
  <div v-if="mostrarEliminar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 max-w-md w-full text-center">
      <h2 class="text-lg fw-bold text-danger mb-3">¿Eliminar escrito?</h2>
      <p>¿Estás seguro de que quieres eliminar este escrito?</p>
      <div class="d-flex justify-content-center gap-3 mt-4">
        <button class="btn btn-secondary" @click="mostrarEliminar = false">Cancelar</button>
        <button class="btn btn-danger" @click="eliminarEscrito">Eliminar</button>
      </div>
    </div>
  </div>


  <!-- Modal para ver el escrito completo -->
  <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded p-4 max-w-xl w-full position-relative">
      <button @click="closeModal" class="position-absolute top-0 end-0 m-2 btn-close" aria-label="Cerrar"></button>

      <h2 class="fw-bold mb-2" style="color: #d38e0d;">{{ selected.titulo }}</h2>

      <div class="d-flex align-items-center gap-3 mb-3">
        <img :src="getFoto(selected.usuario?.foto)" alt="Foto del autor" class="rounded-circle"
          style="width: 48px; height: 48px; object-fit: cover;" />
        <small class="text-muted">{{ selected.usuario?.nombre }}</small>
      </div>

      <div style="max-height: 400px; overflow-y: auto;">
        <p class="text-dark">{{ selected.contenido }}</p>
      </div>

      <div class="d-flex justify-content-end mt-4">
        <button class="btn btn-secondary" @click="closeModal">Cerrar</button>
      </div>
    </div>
  </div>

</template>

<style scoped>
.modal.show {
  background-color: rgba(0, 0, 0, 0.5);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.autor-img {
  width: 70px;
  height: 70px;
  flex-shrink: 0;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid #ccc;
}

.autor-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
