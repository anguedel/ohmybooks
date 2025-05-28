<script setup>
import { usePage, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue'

const user = usePage().props.auth.user;
const mostrarEditarModal = ref(false)
const toastVisible = ref(false)
const toastMensaje = ref('')
const toastColor = ref('bg-success')
const page = usePage()
const votacionesCount = page.props.votacionesCount
const comentariosCount = page.props.comentariosCount
const librosSugeridosCount = page.props.librosSugeridosCount
const valoracionesPorNota = usePage().props.valoracionesPorNota
const valoracionesPorGenero = usePage().props.valoracionesPorGenero
const valoracionesPorAutor = usePage().props.valoracionesPorAutor

const form = useForm({
    nombre: user?.nombre ?? '',
    nombre_usuario: user?.nombre_usuario ?? '',
    email: user?.email ?? '',
    ciudad: user?.ciudad ?? '',
    pais: user?.pais ?? '',
    foto: null
})

const sugerencia = useForm({
    titulo: '',
    descripcion: ''
});

function mostrarToast(mensaje, color = 'bg-success') {
    toastMensaje.value = mensaje
    toastColor.value = color
    toastVisible.value = true
    setTimeout(() => toastVisible.value = false, 3000)
}

function enviarSugerencia() {
    if (!sugerencia.titulo.trim()) {
        mostrarToast('El título es obligatorio', 'bg-danger')
        return
    }

    sugerencia.post(route('libros.sugeridos.store'), {
        onSuccess: () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('sugerirLibroModal'))
            modal?.hide()

            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove())
            document.body.classList.remove('modal-open')
            document.body.style = ''

            sugerencia.reset()
            mostrarToast('Gracias por la sugerencia')
        }
    })
}

function actualizarUsuario() {
    form.put(route('usuario.update'), {
        onSuccess: () => {
            mostrarEditarModal.value = false
            mostrarToast('Datos actualizados correctamente')

            setTimeout(() => {
                location.reload()
            }, 1000)
        }
    })
}
</script>

<template>
  <div class="relative py-5">
    <div class="absolute inset-0 bg-gradient-to-br from-[#cce4f6] to-[#4b9cd3] blur-sm brightness-75 z-0"></div>
    <div class="container relative z-10">
      <div class="bg-light border p-4 mb-4 shadow libro-card">
        <div class="text-center mb-4">
          <h3 class="d-inline-block" style="background-color: #4b9cd3; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">
            Mis datos
          </h3>
        </div>

        <div class="row align-items-center">
          <div class="col-md-4">
            <div class="ps-3 border-start border-3 border-primary">
              <p class="mb-1">
                <span class="fw-semibold text-secondary">👤 Nombre de usuario:</span>
                <span class="fw-bold text-dark">{{ user.nombre_usuario }}</span>
              </p>
              <p class="mb-1">
                <span class="fw-semibold text-secondary">🌍 Ciudad, país:</span>
                <span class="fw-bold text-dark">{{ user.ciudad }}, {{ user.pais }}</span>
              </p>
              <p class="mb-1">
                <span class="fw-semibold text-secondary">📧 Email:</span>
                <span class="fw-bold text-dark">{{ user.email }}</span>
              </p>
              <p class="mb-0">
                <span class="fw-semibold text-secondary">📝 Datos personales:</span>
                <span class="fw-bold text-dark">{{ user.nombre }}</span>
              </p>
            </div>
          </div>

          <div class="col-md-4 text-center">
            <div class="d-inline-block p-1 border border-3 border-primary rounded-circle shadow-sm">
              <img :src="`/images/FotosPerfil/${user.foto}` || '/images/FotosPerfil/pordefecto.png'" alt="Foto de perfil" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" />
            </div>
          </div>

          <div class="col-md-4 d-flex flex-column gap-2">
            <Link class="btn bg-primary border-2 border-primary text-white fw-semibold" :href="route('listas.usuario')">
              📚 Mis listas
            </Link>
            <button class="btn bg-primary border-2 border-primary text-white fw-semibold" @click="mostrarEditarModal = true">
              ✏️ Modificar datos
            </button>
            <button class="btn bg-primary border-2 border-primary text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#sugerirLibroModal">
              💡 Sugerir libro
            </button>
          </div>
        </div>
      </div>

      <div class="bg-success-subtle border p-4">
        <div class="mb-4 text-center">
          <h4 class="fw-bold text-white" style="background-color: #4b9cd3; padding: 0.5rem 1rem; border-radius: 0.5rem; display: inline-block;">
            Estadísticas del usuario
          </h4>
        </div>

        <div class="row text-center mb-4">
          <div class="col-md-4">
            <div class="card border-success shadow-sm libro-card">
              <div class="card-body">
                <h5 class="card-title text-success fw-bold">{{ votacionesCount }}</h5>
                <p class="card-text mb-0">Libros votados</p>
                <i class="bi bi-star-fill text-warning"></i>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card border-primary shadow-sm libro-card">
              <div class="card-body">
                <h5 class="card-title text-primary fw-bold">{{ librosSugeridosCount }}</h5>
                <p class="card-text mb-0">Libros sugeridos</p>
                <i class="bi bi-lightbulb-fill text-info"></i>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card border-secondary shadow-sm libro-card">
              <div class="card-body">
                <h5 class="card-title text-dark fw-bold">{{ comentariosCount }}</h5>
                <p class="card-text mb-0">Comentarios</p>
                <i class="bi bi-chat-left-text-fill text-secondary"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-md-4">
            <div class="border">
              <div class="bg-primary text-white p-2">Votos por nota</div>
              <ul class="list-group list-group-flush text-start">
                <li class="list-group-item d-flex justify-content-between">
                  <span class="fw-bold text-success">Excelente</span>
                  <span class="fw-bold text-success">{{ valoracionesPorNota.excelente }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span class="fw-bold text-primary">Muy bueno</span>
                  <span class="fw-bold text-primary">{{ valoracionesPorNota.muy_bueno }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span class="fw-bold text-warning">Regular</span>
                  <span class="fw-bold text-warning">{{ valoracionesPorNota.regular }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span class="fw-bold" style="color: orange;">Flojo</span>
                  <span class="fw-bold" style="color: orange;">{{ valoracionesPorNota.flojo }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span class="fw-bold text-danger">Malo</span>
                  <span class="fw-bold text-danger">{{ valoracionesPorNota.malo }}</span>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-md-4">
            <div class="border">
              <div class="bg-primary text-white p-2">Votos por género</div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between" v-for="genero in valoracionesPorGenero" :key="genero.genero">
                  <span class="fw-bold">{{ genero.genero }}</span>
                  <span>{{ genero.total }}</span>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-md-4">
            <div class="border">
              <div class="bg-primary text-white p-2">Votos por autores</div>
              <div v-if="valoracionesPorAutor.length">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between" v-for="autor in valoracionesPorAutor" :key="autor.autor">
                    <span class="fw-bold">{{ autor.autor }}</span>
                    <span>{{ autor.total }}</span>
                  </li>
                </ul>
              </div>
              <div v-else class="card border border-secondary text-center text-muted py-3 px-2">
                <span>🕵️‍♂️ Todavía no hay datos disponibles</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="mostrarEditarModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="modal-content bg-light p-4 rounded shadow" style="max-width: 500px; width: 100%;">
        <h5 class="fw-bold text-primary mb-3">Editar mis datos</h5>
        <form @submit.prevent="actualizarUsuario">
          <div class="mb-2">
            <label class="form-label">Nombre y apellidos</label>
            <input type="text" class="form-control" v-model="form.nombre" />
          </div>
          <div class="mb-2">
            <label class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" v-model="form.nombre_usuario" />
          </div>
          <div class="mb-2">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" v-model="form.email" />
          </div>
          <div class="mb-2">
            <label class="form-label">Ciudad</label>
            <input type="text" class="form-control" v-model="form.ciudad" />
          </div>
          <div class="mb-2">
            <label class="form-label">País</label>
            <input type="text" class="form-control" v-model="form.pais" />
          </div>
          <div class="mb-3">
            <label class="form-label">Foto de perfil</label>
            <input type="file" class="form-control" @change="e => form.foto = e.target.files[0]" />
          </div>
          <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-secondary" @click="mostrarEditarModal = false">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade" id="sugerirLibroModal" tabindex="-1" aria-labelledby="sugerirLibroModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h5 class="modal-title fw-bold text-primary" id="sugerirLibroModalLabel">Sugerir nuevo libro</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="enviarSugerencia">
              <div class="mb-3">
                <label class="form-label">Título *</label>
                <input type="text" class="form-control" v-model="sugerencia.titulo" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Otros datos de interés (opcional)</label>
                <textarea class="form-control" rows="4" v-model="sugerencia.descripcion" placeholder="Autor, año, editorial, sinopsis..."></textarea>
              </div>
              <button type="submit" class="btn btn-primary w-100">Enviar sugerencia</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div v-if="toastVisible" class="fixed inset-0 flex items-center justify-center z-50 bg-transparent">
      <div :class="['text-white px-6 py-4 rounded shadow-lg text-lg font-semibold', toastColor]">
        {{ toastMensaje }}
      </div>
    </div>
  </div>
</template>

<style scoped>
.portada-libro {
  width: 100%;
  height: 260px;
  object-fit: cover;
}

.titulo-libro {
  font-size: 0.95rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.libro-card {
  transition: all 0.25s ease-in-out;
}

.libro-card:hover {
  transform: translateY(-4px);
  border-color: #4b9cd3 !important;
  box-shadow: 0 0.5rem 1rem rgba(75, 156, 211, 0.2);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>