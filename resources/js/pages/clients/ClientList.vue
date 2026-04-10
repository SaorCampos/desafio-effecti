<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps<{
    clients: any
}>();

const filters = reactive({
    name: '',
    status: ''
});

const search = debounce(() => {
    router.get('/clients', {
        name: filters.name,
        status: filters.status
    }, {
        preserveState: true,
        replace: true
    });
}, 500);

watch(() => [filters.name, filters.status], search);

const deleteClient = (id: number) => {
    if (confirm('Deseja excluir este cliente?')) {
        router.delete(`/clients/${id}`);
    }
};
</script>

<template>
  <div class="p-6 text-gray-100"> <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Gestão de Clientes</h1>
      <button @click="router.visit('/clients/create')" class="bg-blue-600 px-4 py-2 rounded text-white">
        Novo Cliente
      </button>
    </div>

    <div class="bg-slate-800 p-4 rounded shadow mb-6 flex gap-4">
      <input
        v-model="filters.name"
        placeholder="Buscar por nome..."
        class="bg-slate-700 border-none rounded w-full text-white focus:ring-blue-500"
      />
      <select v-model="filters.status" class="bg-slate-700 border-none rounded text-white">
        <option value="">Todos os Status</option>
        <option value="active">Ativo</option>
        <option value="inactive">Inativo</option>
      </select>
    </div>

    <div class="bg-slate-800 rounded shadow overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-slate-700 text-gray-300">
          <tr>
            <th class="p-4 uppercase text-xs">Nome</th>
            <th class="p-4 uppercase text-xs">Documento</th>
            <th class="p-4 uppercase text-xs">Status</th>
            <th class="p-4 uppercase text-xs text-right">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="client in clients.data" :key="client.id" class="border-t border-slate-700 hover:bg-slate-700/50">
            <td class="p-4">{{ client.name }}</td>
            <td class="p-4 font-mono">{{ client.document }}</td>
            <td class="p-4">
              <span :class="client.status === 'active' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'" class="px-2 py-1 rounded text-xs uppercase">
                {{ client.status }}
              </span>
            </td>
            <td class="p-4 text-right">
              <button @click="router.visit(`/clients/${client.id}/edit`)" class="text-blue-400 hover:text-blue-300 mr-3">Editar</button>
              <button @click="deleteClient(client.id)" class="text-red-400 hover:text-red-300">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-6 flex justify-center gap-2">
       <Link
         v-for="link in clients.links"
         :key="link.label"
         :href="link.url ?? ''"
         class="px-4 py-2 rounded border border-slate-700"
         :class="{ 'bg-blue-600 border-blue-600': link.active, 'text-gray-500': !link.url }"
         v-html="link.label"
       />
    </div>
  </div>
</template>
