<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

defineProps({
  usuarios: Array
})

const mostrarModalCrear = ref(false)
const mostrarModalEditar = ref(false)
const mostrarModalEliminar = ref(false)
const usuarioSeleccionado = ref(null)
const error = ref(null)
const toastVisible = ref(false)
const toastMensaje = ref('')
const toastColor = ref('bg-success')

const form = useForm({
  nombre: '',
  nombre_usuario: '',
  email: '',
  password: '',
  ciudad: '',
  pais: '',
  foto: null
})

const formEditar = useForm({
  id: null,
  nombre: '',
  nombre_usuario: '',
  email: '',
  ciudad: '',
  pais: '',
  foto: null
})

function registrarUsuario() {
  form.post('/admin/usuarios', {
    onSuccess: () => {
      mostrarModalCrear.value = false
      form.reset()
      mostrarToast('Usuario creado correctamente', 'bg-success')
      setTimeout(() => {
        router.reload({ only: ['usuarios'] })
      }, 300)
    },
    onError: (errors) => {
      error.value = Object.values(errors).flat().join('\n')
      mostrarToast('Error al crear usuario', 'bg-danger')
    }
  })
}


function abrirModalEditar(usuario) {
  usuarioSeleccionado.value = usuario
  formEditar.id = usuario.id
  formEditar.nombre = usuario.nombre
  formEditar.nombre_usuario = usuario.nombre_usuario
  formEditar.email = usuario.email
  formEditar.ciudad = usuario.ciudad
  formEditar.pais = usuario.pais
  formEditar.foto = null
  mostrarModalEditar.value = true
}

function cerrarModalEditar() {
  mostrarModalEditar.value = false
  formEditar.reset()
}

function actualizarUsuario(id) {
  formEditar.put(`/admin/usuarios/${id}`, {
    onSuccess: () => {
      cerrarModalEditar()
      mostrarToast('Usuario actualizado correctamente', 'bg-success')
      setTimeout(() => {
        router.reload({ only: ['usuarios'] })
      }, 300)
    },
    onError: (errors) => {
      error.value = Object.values(errors).flat().join('\n')
      mostrarToast('Error al actualizar usuario', 'bg-danger')
    }
  })
}

function abrirModalEliminar(usuario) {
  usuarioSeleccionado.value = usuario
  mostrarModalEliminar.value = true
}

function confirmarEliminarUsuario() {
  router.delete(`/admin/usuarios/${usuarioSeleccionado.value.id}`, {
    onSuccess: () => {
      mostrarModalEliminar.value = false
      mostrarToast('Usuario eliminado correctamente', 'bg-danger')
      setTimeout(() => {
        router.reload({ only: ['usuarios'] })
      }, 300)
    },
    onError: () => {
      mostrarToast('Error al eliminar usuario', 'bg-danger')
    }
  })
}

function mostrarToast(mensaje, color = 'bg-success') {
  toastMensaje.value = mensaje
  toastColor.value = color
  toastVisible.value = true

  setTimeout(() => {
    toastVisible.value = false
  }, 3000)
}
</script>


<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="fw-bold" style="color: #93c5fd;">Usuarios</h4>
      <button class="btn" style="background-color: #4b9cd3; color: white;" @click="mostrarModalCrear = true">
        Crear nuevo usuario
      </button>
    </div>

    <table class="table table-striped table-bordered">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="usuario in usuarios" :key="usuario.id">
          <td>{{ usuario.id }}</td>
          <td>{{ usuario.nombre }}</td>
          <td>{{ usuario.email }}</td>
          <td>{{ usuario.rol }}</td>
          <td>
            <button class="btn btn-sm btn-warning me-2" @click="abrirModalEditar(usuario)">Editar</button>
            <button class="btn btn-sm btn-danger" @click="abrirModalEliminar(usuario)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal Crear -->
    <div v-if="mostrarModalCrear" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white w-full max-w-md rounded shadow-lg">
        <div class="bg-primary text-white p-4 flex justify-between items-center rounded-t">
          <h5 class="fw-bold m-0">Crear nuevo usuario</h5>
          <button @click="mostrarModalCrear = false" class="text-white text-xl font-bold hover:text-danger">×</button>
        </div>
        <div class="modal-body p-4 bg-light rounded-b">
          <form @submit.prevent="registrarUsuario" enctype="multipart/form-data">
            <div class="mb-2">
              <label class="form-label">Nombre completo</label>
              <input v-model="form.nombre" type="text" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">Nombre de usuario</label>
              <input v-model="form.nombre_usuario" type="text" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">Email</label>
              <input v-model="form.email" type="email" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">Contraseña</label>
              <input v-model="form.password" type="password" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">Ciudad</label>
              <input v-model="form.ciudad" type="text" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">País</label>
              <input v-model="form.pais" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Foto de perfil</label>
              <input type="file" class="form-control" @change="e => form.foto = e.target.files?.[0] ?? null" />
            </div>
            <div v-if="error" class="alert alert-danger">{{ error }}</div>
            <button type="submit" class="btn btn-primary w-100">Crear usuario</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Editar -->
    <div v-if="mostrarModalEditar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white w-full max-w-md rounded shadow-lg">
        <div class="bg-warning text-white p-4 flex justify-between items-center rounded-t">
          <h5 class="fw-bold m-0">Editar usuario</h5>
          <button @click="cerrarModalEditar" class="text-white text-xl font-bold hover:text-danger">×</button>
        </div>
        <div class="modal-body p-4 bg-light rounded-b">
          <form @submit.prevent="actualizarUsuario(formEditar.id)" enctype="multipart/form-data">
            <div class="mb-2">
              <label class="form-label">Nombre completo</label>
              <input v-model="formEditar.nombre" type="text" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">Nombre de usuario</label>
              <input v-model="formEditar.nombre_usuario" type="text" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">Email</label>
              <input v-model="formEditar.email" type="email" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">Ciudad</label>
              <input v-model="formEditar.ciudad" type="text" class="form-control" />
            </div>
            <div class="mb-2">
              <label class="form-label">País</label>
              <input v-model="formEditar.pais" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Foto de perfil</label>
              <input type="file" class="form-control" @change="e => formEditar.foto = e.target.files?.[0] ?? null" />
            </div>
            <button type="submit" class="btn btn-warning w-100">Guardar cambios</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Eliminar -->
    <div v-if="mostrarModalEliminar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white w-full max-w-sm rounded shadow-lg p-4">
        <h5 class="fw-bold text-danger mb-3">¿Estás seguro?</h5>
        <p>¿Deseas eliminar al usuario <strong>{{ usuarioSeleccionado?.nombre }}</strong>?</p>
        <div class="d-flex justify-content-end mt-3">
          <button class="btn btn-secondary me-2" @click="mostrarModalEliminar = false">Cancelar</button>
          <button class="btn btn-danger" @click="confirmarEliminarUsuario">Eliminar</button>
        </div>
      </div>
    </div>

    <!-- Toast centrado -->
    <div v-if="toastVisible" class="toast show fade align-items-center text-white position-fixed p-4"
      :class="toastColor" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;
            min-width: 250px;
            max-width: 90%;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;">
      <strong class="me-2">✔</strong> {{ toastMensaje }}
    </div>


  </div>
</template>
