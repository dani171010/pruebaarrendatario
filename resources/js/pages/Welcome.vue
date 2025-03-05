<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Appviewpost from '@/components/public/PostList.vue';
import Appviewlist from '@/components/public/PostDetail.vue';

// Variable para mostrar/ocultar el modal
const showModal = ref(false);
// ID del post seleccionado
const selectedId = ref<number | null>(null);

// Función que se ejecuta cuando se selecciona una noticia
function openModal(id: number) {
  selectedId.value = id;
  showModal.value = true;
}

// Función para cerrar el modal
function closeModal() {
  showModal.value = false;
}
</script>

<template>
  <Head title="Bienvenido al Blog">
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
  </Head>

  <div class="min-h-screen flex flex-col bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">Blog de tecnologia</h1>
      </div>
    </header>


    <!-- Contenido principal: Lista de noticias -->
    <main class="flex-1">
      <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Aquí se muestran las noticias. Se asume que Appviewpost emite "newsSelected" con el ID del post -->
        <Appviewpost @newsSelected="openModal" />
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm text-gray-500">&copy; 2025 Mi Blog. Todos los derechos reservados.</p>
      </div>
    </footer>

    <!-- Modal para mostrar el detalle de la noticia -->
    <div
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-lg p-6 max-w-3xl w-full relative">
        <button @click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
          X
        </button>
        <!-- Se muestra el componente de detalle, pasando el ID seleccionado -->
        <Appviewlist :id="selectedId" />
      </div>
    </div>
  </div>
</template>
