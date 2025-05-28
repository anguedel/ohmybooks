<template>
  <header class="header shadow-sm border-bottom bg-light position-relative overflow-hidden">
    <div class="header-background"></div>

    <div class="container py-3 position-relative mb-5">
      <div class="row align-items-center">
        <div class="col-2 d-flex justify-content-start">
          <img src="/images/LogoOficial.png" alt="Logo" class="img-fluid" style="max-height: 180px; margin-top: -20px;" />
        </div>

        <div class="col-8 d-flex justify-content-center">
          <h1 class="h5 text-info fw-bold mb-0">Panel de administración</h1>
        </div>

        <div class="col-2 d-flex flex-column align-items-end gap-1">
          <div class="fw-medium text-info text-end">
            {{ user?.name }}
          </div>
          <form @submit.prevent="logout">
            <button type="submit" class="btn btn-outline-danger btn-sm custom-btn">
              Cerrar sesión
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3'

const user = usePage().props.auth.user

function logout() {
  router.post(route('logout'), {}, {
    onSuccess: () => {
      router.visit('/', { replace: true })
    }
  })
}
</script>

<style scoped>
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
</style>
