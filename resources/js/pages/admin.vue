
<script lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import axios from 'axios';

interface Autor {
  id: number;
  Nombre: string;
  Apellidos: string;
}

interface Post {
  id: number;
  titulo: string;
  contenido: string;
  imagen: string;
  autor_id: Autor;
}

export default defineComponent({
  name: 'AdminDashboard',
  setup() {
    // Estado de tabs
    const activeTab = ref<'posts' | 'authors'>('posts');
    // Listas de posts y autores
    const posts = ref<Post[]>([]);
    const authors = ref<Autor[]>([]);

    // Modal para Post
    const showPostModal = ref(false);
    const currentPost = ref<Post | null>(null);
    const postForm = ref({
      titulo: '',
      contenido: '',
      imagen: '',
      autor_id: ''
    });

    // Modal para Autor
    const showAuthorModal = ref(false);
    const currentAuthor = ref<Autor | null>(null);
    const authorForm = ref({
      Nombre: '',
      Apellidos: ''
    });

    // Funciones para obtener datos de la API
    const fetchPosts = () => {
      axios.get('/api/post')
        .then(response => {
          posts.value = response.data;
        })
        .catch(error => console.error('Error fetching posts:', error));
    };

    const fetchAuthors = () => {
      axios.get('/api/autor')
        .then(response => {
          authors.value = response.data;
        })
        .catch(error => console.error('Error fetching authors:', error));
    };

    onMounted(() => {
      fetchPosts();
      fetchAuthors();
    });

    // Funciones para el modal de Post
    const openPostModal = (post: Post | null) => {
      if (post) {
        currentPost.value = post;
        postForm.value = {
          titulo: post.titulo,
          contenido: post.contenido,
          imagen: post.imagen || '',
          autor_id: post.autor_id.toString()
        };
      } else {
        currentPost.value = null;
        postForm.value = {
          titulo: '',
          contenido: '',
          imagen: '',
          autor_id: ''
        };
      }
      showPostModal.value = true;
    };

    const closePostModal = () => {
      showPostModal.value = false;
    };

    const selectedImage = ref<File | null>(null);

    const handleImageUpload = (event: Event) => {
    const fileInput = event.target as HTMLInputElement;
    if (fileInput.files && fileInput.files[0]) {
        selectedImage.value = fileInput.files[0]; // Guardamos el archivo en una variable reactiva
    }
    };

    const submitPost = () => {
        const formData = new FormData();
        formData.append('titulo', postForm.value.titulo);
        formData.append('contenido', postForm.value.contenido);
        formData.append('autor_id', postForm.value.autor_id);

        if (selectedImage.value) {
            formData.append('imagen', selectedImage.value); // Agrega la imagen
        }
      if (currentPost.value) {
        // Actualizar post existente
        formData.append('_method', 'PUT'); // Laravel requiere esto si usas multipart/form-data en PUT
        axios.post(`/api/post/${currentPost.value.id}`, formData,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
          .then(() => {
            fetchPosts();
            closePostModal();
          })
          .catch(error => console.error('Error updating post:', error));
      } else {
        // Crear un nuevo post
        axios.post('/api/post', formData,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
          .then(() => {
            fetchPosts();
            closePostModal();
          })
          .catch(error => console.error('Error creating post:', error));
      }
    };

    const deletePost = (id: number) => {
      if (confirm('¿Estás seguro de eliminar este post?')) {
        axios.delete(`/api/post/${id}`)
          .then(() => {
            fetchPosts();
          })
          .catch(error => console.error('Error deleting post:', error));
      }
    };

    // Funciones para el modal de Autor
    const openAuthorModal = (author: Autor | null) => {
      if (author) {
        currentAuthor.value = author;
        authorForm.value = {
          Nombre: author.Nombre,
          Apellidos: author.Apellidos
        };
      } else {
        currentAuthor.value = null;
        authorForm.value = {
          Nombre: '',
          Apellidos: ''
        };
      }
      showAuthorModal.value = true;
    };

    const closeAuthorModal = () => {
      showAuthorModal.value = false;
    };

    const submitAuthor = () => {
      if (currentAuthor.value) {
        // Actualizar autor existente
        axios.put(`/api/autor/${currentAuthor.value.id}`, authorForm.value)
          .then(() => {
            fetchAuthors();
            closeAuthorModal();
          })
          .catch(error => console.error('Error updating author:', error));
      } else {
        // Crear un nuevo autor
        axios.post('/api/autor', authorForm.value)
          .then(() => {
            fetchAuthors();
            closeAuthorModal();
          })
          .catch(error => console.error('Error creating author:', error));
      }
    };

    const deleteAuthor = (id: number) => {
      if (confirm('¿Estás seguro de eliminar este autor?')) {
        axios.delete(`/api/autor/${id}`)
          .then(() => {
            fetchAuthors();
          })
          .catch(error => console.error('Error deleting author:', error));
      }
    };

    return {
      activeTab,
      posts,
      authors,
      showPostModal,
      currentPost,
      postForm,
      showAuthorModal,
      currentAuthor,
      authorForm,
      openPostModal,
      closePostModal,
      submitPost,
      deletePost,
      openAuthorModal,
      closeAuthorModal,
      submitAuthor,
      deleteAuthor,
      handleImageUpload
    };
  }
});
</script>

<template>
    <div class="min-h-screen bg-gray-100">
      <!-- Header -->
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4">
          <h1 class="text-3xl font-bold text-gray-900">Panel Administrativo Blog</h1>
        </div>
      </header>

      <!-- Contenido Principal -->
      <main class="max-w-7xl mx-auto p-4">
        <!-- Tabs para Posts y Autores -->
        <div class="mb-4">
          <button
            @click="activeTab = 'posts'"
            :class="activeTab === 'posts' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'"
            class="px-4 py-2 rounded-l focus:outline-none"
          >
            Posts
          </button>
          <button
            @click="activeTab = 'authors'"
            :class="activeTab === 'authors' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'"
            class="px-4 py-2 rounded-r focus:outline-none"
          >
            Autores
          </button>
        </div>

        <!-- Gestión de Posts -->
        <div v-if="activeTab === 'posts'">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-900">Administrar Posts</h2>
            <button @click="openPostModal(null)" class="bg-green-500 text-white px-4 py-2 rounded">
              Crear Post
            </button>
          </div>
          <!-- Lista de Posts -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="post in posts"
              :key="post.id"
              class="bg-white p-4 rounded shadow"
            >
              <img :src="'/storage/' + post.imagen" alt="Post Image" class="w-full h-40 object-cover mb-2 rounded" />
              <h3 class="text-xl font-semibold text-gray-950">{{ post.titulo }}</h3>
              <p class="text-gray-600 break-words">{{ post.contenido.substring(0, 100) }}...</p>
              <div class="mt-2 flex space-x-2">
                <button @click="openPostModal(post)" class="bg-blue-500 text-white px-3 py-1 rounded">
                  Editar
                </button>
                <button @click="deletePost(post.id)" class="bg-red-500 text-white px-3 py-1 rounded">
                  Eliminar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Gestión de Autores -->
        <div v-else-if="activeTab === 'authors'">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-900">Administrar Autores</h2>
            <button @click="openAuthorModal(null)" class="bg-green-500 text-white px-4 py-2 rounded">
              Crear Autor
            </button>
          </div>
          <!-- Lista de Autores -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="author in authors"
              :key="author.id"
              class="bg-white p-4 rounded shadow"
            >
              <h3 class="text-xl font-semibold text-gray-950">{{ author.Nombre }} {{ author.Apellidos }}</h3>
              <div class="mt-2 flex space-x-2">
                <button @click="openAuthorModal(author)" class="bg-blue-500 text-white px-3 py-1 rounded">
                  Editar
                </button>
                <button @click="deleteAuthor(author.id)" class="bg-red-500 text-white px-3 py-1 rounded">
                  Eliminar
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>

      <!-- Modal para Crear/Editar Post -->
      <div
        v-if="showPostModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        @click.self="closePostModal"
      >
        <div class="bg-white rounded-lg p-6 w-full max-w-lg relative">
          <button @click="closePostModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">X</button>
          <h2 class="text-2xl font-semibold mb-4 text-gray-950">
            {{ currentPost ? 'Editar Post' : 'Crear Post' }}
          </h2>
          <form @submit.prevent="submitPost">
            <div class="mb-4">
              <label class="block text-gray-700">Título</label>
              <input v-model="postForm.titulo" type="text" class="w-full border rounded p-2 text-gray-950" required />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700">Contenido</label>
              <textarea v-model="postForm.contenido" class="w-full border rounded p-2 text-gray-950" required></textarea>
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Imagen</label>
            <input type="file" @change="handleImageUpload" class="w-full border rounded p-2 text-gray-950" />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700">Autor</label>
              <select v-model="postForm.autor_id" class="w-full border rounded p-2 text-gray-950" required>
                <option value="" disabled>Seleccione un autor</option>
                <option v-for="author in authors" :key="author.id" :value="author.id">
                  {{ author.Nombre }} {{ author.Apellidos }}
                </option>
              </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
              {{ currentPost ? 'Actualizar' : 'Crear' }}
            </button>
          </form>
        </div>
      </div>

      <!-- Modal para Crear/Editar Autor -->
      <div
        v-if="showAuthorModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        @click.self="closeAuthorModal"
      >
        <div class="bg-white rounded-lg p-6 w-full max-w-md relative">
          <button @click="closeAuthorModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">X</button>
          <h2 class="text-2xl font-semibold mb-4 text-gray-950">
            {{ currentAuthor ? 'Editar Autor' : 'Crear Autor' }}
          </h2>
          <form @submit.prevent="submitAuthor">
            <div class="mb-4">
              <label class="block text-gray-700">Nombre</label>
              <input v-model="authorForm.Nombre" type="text" class="w-full border rounded p-2 text-gray-950" required />
            </div>
            <div class="mb-4">
              <label class="block text-gray-700">Apellido</label>
              <input v-model="authorForm.Apellidos" type="text" class="w-full border rounded p-2 text-gray-950" required />
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
              {{ currentAuthor ? 'Actualizar' : 'Crear' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </template>
