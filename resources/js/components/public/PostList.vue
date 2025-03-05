<script lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import axios from 'axios';

interface Autor {
  id: number;
  Nombre: string;
  Apellido: string;
}

interface Post {
  id: number;
  titulo: string;
  contenido: string;
  imagen?: string;
  autor_id: Autor;
}

export default defineComponent({
  name: 'Posts',
  // Declaramos el evento que se va a emitir
  emits: ['newsSelected'],
  setup(props, { emit }) {
    const posts = ref<Post[]>([]);
    const loading = ref(true);

    onMounted(() => {
      axios.get('/api/post')
        .then(response => {
          posts.value = response.data;
        })
        .catch(error => {
          console.error('Error al obtener posts:', error);
        })
        .finally(() => {
          loading.value = false;
        });
    });

    const viewPost = (id: number) => {
      // Emitimos el evento con el id del post seleccionado
      emit('newsSelected', id);
    };

    return { posts, loading, viewPost };
  }
});
</script>

<template>
    <div class="p-6">
      <div v-if="loading" class="text-center text-gray-700">Cargando...</div>
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          v-for="post in posts"
          :key="post.id"
          class="bg-white rounded-lg shadow-lg overflow-hidden cursor-pointer hover:shadow-xl transition duration-300"
          @click="viewPost(post.id)"
        >
        <img v-if="post.imagen" :src="'/storage/' + post.imagen" alt="Imagen del post" class="w-full h-40 object-cover mb-2 rounded mt-2" />
          <div class="p-4">
            <h2 class="text-gray-900 text-xl font-semibold">{{ post.titulo }}</h2>
          </div>
        </div>
      </div>
    </div>
  </template>

