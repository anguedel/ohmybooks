<script setup>
import { reactive, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import ModalInicioSesion from '../ModalInicioSesion.vue'

const dropdowns = reactive({
  explorar: false,
  comunidad: false
})

function showDropdown(menu) {
  dropdowns[menu] = true
}
function hideDropdown(menu) {
  dropdowns[menu] = false
}

const loginForm = ref({
  email: '',
  password: ''
})

const loginError = ref(null)

function iniciarSesion(e) {
  e.preventDefault()
  loginError.value = null

  router.post('/login', loginForm.value, {
    onError: (errors) => {
      loginError.value = Object.values(errors)[0]
    },
    onSuccess: () => {
      const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'))
      if (modal) modal.hide()
    }
  })
}

const form = ref({
  nombre: '',
  nombre_usuario: '',
  email: '',
  password: '',
  ciudad: '',
  pais: '',
  foto: null,
})

const error = ref(null)
const registroExitoso = ref(false)

function cerrarModalRegistro() {
  const modalElement = document.getElementById('registerModal')
  const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement)
  modalInstance.hide()

  document.body.classList.remove('modal-open')
  document.querySelectorAll('.modal-backdrop').forEach(el => el.remove())
}

function registrarUsuario(e) {
  e.preventDefault()
  error.value = null

  const data = new FormData()
  for (const [key, value] of Object.entries(form.value)) {
    data.append(key, value)
  }

  router.post('/register', data, {
    forceFormData: true,
    onError: (errors) => {
      error.value = Object.values(errors)[0]
    },
    onSuccess: () => {
      cerrarModalRegistro()
      registroExitoso.value = true
      setTimeout(() => registroExitoso.value = false, 4000)
    }
  })
}
</script>

<template>
  <header class="header shadow-sm border-bottom bg-light position-relative overflow-hidden">
    <div class="header-background"></div>

    <div class="container py-3 position-relative mb-5">
      <div v-if="registroExitoso" class="alert alert-success text-center mt-3">
        Usuario creado con éxito
      </div>

      <div class="row align-items-center">
        <div class="col-2 d-flex justify-content-start">
          <img src="/images/LogoOficial.png" alt="Logo" class="img-fluid" style="max-height: 180px; margin-top: -20px;" />
        </div>

        <div class="col-8 d-flex justify-content-center">
          <nav class="d-flex gap-4 align-items-center position-relative">
            <a class="nav-link" href="/">Inicio</a>

            <div class="dropdown position-relative" @mouseenter="showDropdown('explorar')" @mouseleave="hideDropdown('explorar')">
              <a href="#" class="nav-link dropdown-toggle">Explorador de libros</a>
              <ul class="dropdown-menu-custom small-dropdown" v-if="dropdowns.explorar">
                <li><Link :href="route('listas.predefinidas')">Listas</Link></li>
                <li><Link :href="route('generos')">Géneros</Link></li>
              </ul>
            </div>

            <div class="dropdown" @mouseenter="showDropdown('comunidad')" @mouseleave="hideDropdown('comunidad')">
              <a href="#" class="nav-link dropdown-toggle">Comunidad</a>
              <ul class="dropdown-menu-custom" v-if="dropdowns.comunidad">
                <li><Link :href="route('rincon')">El rincón del escritor</Link></li>
                <li><Link :href="route('frases')">Frases célebres</Link></li>
              </ul>
            </div>
          </nav>
        </div>

        <div class="col-2 d-flex justify-content-end gap-2">
          <button class="btn btn-primary btn-sm custom-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
            Iniciar sesión
          </button>
          <button class="btn btn-primary btn-sm custom-btn" data-bs-toggle="modal" data-bs-target="#registerModal">
            Crear usuario
          </button>
        </div>
      </div>
    </div>

    <ModalInicioSesion />

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h5 class="modal-title fw-bold text-primary" id="registerModalLabel">Registro de usuario</h5>
            <button type="button" class="btn-close" @click="cerrarModalRegistro" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="registrarUsuario" enctype="multipart/form-data">
              <div class="mb-2"><label class="form-label">Nombre completo</label><input v-model="form.nombre" type="text" class="form-control" /></div>
              <div class="mb-2"><label class="form-label">Nombre de usuario</label><input v-model="form.nombre_usuario" type="text" class="form-control" /></div>
              <div class="mb-2"><label class="form-label">Email</label><input v-model="form.email" type="email" class="form-control" /></div>
              <div class="mb-2"><label class="form-label">Contraseña</label><input v-model="form.password" type="password" class="form-control" /></div>
              <div class="mb-2"><label class="form-label">Ciudad</label><input v-model="form.ciudad" type="text" class="form-control" /></div>
              <div class="mb-2"><label class="form-label">País</label><input v-model="form.pais" type="text" class="form-control" /></div>
              <div class="mb-3"><label class="form-label">Foto de perfil</label><input type="file" class="form-control" @change="e => form.foto = e.target.files?.[0] ?? null" /></div>
              <div v-if="error" class="alert alert-danger">{{ error }}</div>
              <button type="submit" class="btn btn-primary w-100">Crear usuario</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
.nav-link {
  color: #0d6efd;
  font-weight: 500;
  text-decoration: none;
  transition: color 0.3s;
}

.nav-link:hover {
  color: #084298;
}

.dropdown-menu-custom {
  position: absolute;
  background-color: white;
  padding: 0.5rem 0;
  border-radius: 0.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  top: 100%;
  left: 0;
  min-width: 180px;
  z-index: 2000;
  transition: opacity 0.2s ease-in-out;
}

.dropdown-menu-custom li a {
  display: block;
  padding: 0.5rem 1rem;
  color: #212529;
  text-decoration: none;
  transition: background-color 0.2s;
}

.dropdown-menu-custom li a:hover {
  background-color: #e7f1ff;
  color: #0d6efd;
}

.custom-btn {
  min-width: 130px;
  white-space: nowrap;
  font-weight: 500;
  padding: 0.375rem 0.75rem;
  border-radius: 0.5rem;
}

.header-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #2b3d52;
  background-size: cover;
  background-position: center;
  filter: blur(1px) brightness(0.7);
  z-index: 0;
}

.container {
  position: relative;
  z-index: 1;
}
</style>
