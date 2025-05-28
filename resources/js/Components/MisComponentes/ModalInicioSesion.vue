<script setup>
import { reactive, ref, watch, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

// Formulario reactivo
const loginForm = reactive({
    email: '',
    password: '',
});

const loginError = ref(null);
const page = usePage();
const user = computed(() => page.props.auth?.user);

// Cierra el modal manualmente (reutilizable)
function cerrarModalLogin() {
    const modal = document.getElementById('loginModal');
    if (modal) {
        const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
        bsModal.hide();
    }
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    document.body.classList.remove('modal-open');
}

// Método de login
function iniciarSesion() {
    loginError.value = null;

    router.post('/login', loginForm, {
        onSuccess: () => {
            cerrarModalLogin()

            // ✅ Recarga total para obtener el usuario correctamente desde el backend
            window.location.href = route('perfil.usuario') // o route('inicio')
        }

        ,
        onError: (errors) => {
            loginError.value = errors.email || errors.password || 'Error al iniciar sesión.';
        },
    });
}
</script>

<template>
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary" id="loginModalLabel">Iniciar sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="iniciarSesion">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input v-model="loginForm.email" type="email" class="form-control" id="login-email" />
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Contraseña</label>
                            <input v-model="loginForm.password" type="password" class="form-control"
                                id="login-password" />
                        </div>
                        <div v-if="loginError" class="alert alert-danger">{{ loginError }}</div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                    </form>

                    <div class="mt-3 text-center">
                        <small>
                            ¿No tienes cuenta?
                            <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal">
                                Regístrate
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
