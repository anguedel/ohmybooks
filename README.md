# Proyecto Laravel + Vue + Inertia - Gestor de Libros

Este proyecto es una aplicación web desarrollada con **Laravel 10**, **Vue 3** e **Inertia.js**. Permite a los usuarios explorar libros, dejar comentarios, crear listas personalizadas y sugerir nuevos títulos. También incluye un panel de administración para gestionar usuarios, libros, frases célebres, etc.

---

## Tecnologías utilizadas

- **Laravel 10** (PHP 8+)
- **Vue 3** con Composition API
- **Inertia.js** para la navegación SPA
- **Vite** como sistema de compilación frontend
- **MySQL** como base de datos
- **Bootstrap 5** para los estilos

---

## Instalación del proyecto

### 1. Clona el repositorio

```bash
git clone https://github.com/tuusuario/mi-proyecto-libros.git
cd mi-proyecto-libros

### 2. Instala las dependencias de Laravel
composer install

### 3. Instala las dependencias de Node
npm install

### 4. Copia el archivo .env y configura
cp .env.example .env

### 5. Genera la clave de la aplicación
php artisan key:generate

### 6. Ejecuta las migraciones y seeders (opcional)
php artisan migrate --seed

### 7. Compila los assets
npm run dev

Autenticación
Este proyecto usa Laravel Breeze con Inertia. Se incluye:

Registro
Login
Panel de usuario

Funcionalidades principales:

Exploración de libros y autores
Comentarios en libros
Listas personalizadas
Frases célebres clasificadas por categoría
Sugerencias de libros
Panel de administración completo

Despliegue:

npm run build
php artisan config:cache
php artisan route:cache

Licencia
Este proyecto está bajo la licencia MIT. Puedes usarlo y modificarlo libremente.

Desarrollado por Ángel Guerrero – angel_ink@me.com


