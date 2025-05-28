<script setup>
import { Link } from '@inertiajs/vue3'

</script>

<template>
  <footer class="footer text-light pt-5 pb-3">
    <div class="container">
      <div class="row">
        <!-- Logo -->
        <div class="col-md-4 mb-4 mb-md-0 d-flex align-items-start">
          <img src="/images/LogoOficial.png" alt="Logo" class="img-fluid" style="max-height: 180px" />
        </div>

        <!-- Menú vertical -->
        <div class="col-md-4 mb-4 mb-md-0">
          <h5 class="mb-3 text-uppercase">Navegación</h5>
          <ul class="list-unstyled">

            <Link :href="route('inicio')" class="footer-link">Inicio</Link>
            <li>
              <Link :href="route('listas.predefinidas')" class="footer-link">Listas</Link>
            </li>
            <li>
              <Link :href="route('generos')" class="footer-link">Géneros</Link>
            </li>
            <li>
              <Link :href="route('rincon')" class="footer-link">El rincón del escritor</Link>
            </li>
            <li>
              <Link :href="route('frases')" class="footer-link">Frases célebres</Link>
            </li>
          </ul>
        </div>

        <!-- Enlaces legales -->
        <div class="col-md-4">
          <h5 class="mb-3 text-uppercase">Información legal</h5>
          <ul class="list-unstyled">
            <li><a href="/politica-privacidad" class="footer-link">Política de privacidad</a></li>
            <li><a href="/condiciones-uso" class="footer-link">Condiciones de uso</a></li>
          </ul>
        </div>
      </div>

      <!-- Línea inferior -->
      <div class="text-center mt-4 small text-white-50">
        &copy; {{ new Date().getFullYear() }} OhMyBooks. Todos los derechos reservados.
      </div>
    </div>
  </footer>
</template>



<style scoped>
.footer {
  background-color: #2b3d52;
}

.footer-link {
  color: #ffffff;
  text-decoration: none;
  display: block;
  padding: 0.25rem 0;
  transition: color 0.3s;
}

.footer-link:hover {
  color: #cce4f6;
  text-decoration: underline;
}
</style>
