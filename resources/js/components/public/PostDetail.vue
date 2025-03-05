<script lang="ts">
import { defineComponent, ref, onMounted, watch } from 'vue';
import axios from 'axios';

interface autor {
  id: number;
  Nombre: string;
  Apellidos: string;
}

interface posts {
  id: number;
  titulo: string;
  contenido: string;
  imagen?: string;
  autor: autor;
}

export default defineComponent({
  name: 'PostDetail',
  props: {
    id: {
      type: Number,
      required: true,
    }
  },
  setup(props) {
    const post = ref<posts | null>(null);
    const loading = ref(true);
    const error = ref<string | null>(null);

    const fetchPost = (postId: number) => {
      loading.value = true;
      error.value = null;
      axios.get(`/api/post/${postId}`)
        .then(response => {
          post.value = response.data;
        })
        .catch(err => {
          console.error('Error al obtener el post:', err);
          error.value = 'Error al obtener el post';
          post.value = null;
        })
        .finally(() => {
          loading.value = false;
        });
    };

    onMounted(() => {
      fetchPost(props.id);
    });

    // Si la propiedad id cambia, se vuelve a cargar el post
    watch(() => props.id, (newId) => {
      if (newId) {
        fetchPost(newId);
      }
    });

    return { post, loading, error };
  }
});
</script>

<template>
  <div>
    <div v-if="loading" class="text-gray-900">Cargando post...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>
    <div v-else-if="post" class="bg-white rounded-lg shadow-lg w-full max-h-[80vh] overflow-y-auto p-6 relative">
      <h1 class="text-gray-900 text-2xl font-bold">{{ post.titulo }}</h1>
      <img v-if="post.imagen" :src="'/storage/' + post.imagen" alt="Imagen del post" class="rounded shadow-md mt-2 h-48 object-cover w-full" />
     <p class="text-gray-900 mb-4 mt-2 text-lg">
        <strong>Autor:</strong> {{ post.autor.Nombre }} {{ post.autor.Apellidos }}
      </p>
      <p class="text-gray-900 my-4 w-full break-words">{{ post.contenido }}</p>

    </div>
    <div v-else class="text-gray-900">Post no encontrado</div>
  </div>
</template>
