<script setup lang="ts">
import { router, Link, Head} from '@inertiajs/vue3';
import { Plus, FileText, Search, Trash2Icon } from 'lucide-vue-next';
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
    <Head title="Clientes" />

    <div class="p-6 text-gray-400">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Gestão de Clientes</h1>
            <Link href="/clients/create"
                class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition">
            <Plus :size="20" /> Novo Cliente
            </Link>
        </div>

        <div class="bg-slate-800 p-4 rounded shadow mb-6 flex gap-4">
            <div class="relative flex-1">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" :size="18" />
                <input v-model="filters.name" type="text" placeholder="Buscar por nome..."
                    class="w-full bg-slate-900 border-none rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>

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
                    <tr v-for="client in clients.data" :key="client.id"
                        class="border-t border-slate-700 hover:bg-slate-700/50">
                        <td class="p-4">{{ client.name }}</td>
                        <td class="p-4 font-mono">{{ client.document }}</td>
                        <td class="p-4">
                            <span :class="{
                                'bg-green-900/50 text-green-400 border-green-800': client.status === 'active',
                                'bg-gray-700 text-gray-400 border-gray-600': client.status !== 'active'
                            }" class="px-2.5 py-1 rounded-full text-xs font-medium border">
                                {{ client.status }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <button @click="router.visit(`/clients/${client.id}/edit`)"
                                class="text-blue-400 hover:text-blue-300 mr-3">
                                <FileText :size="18" />
                            </button>
                            <button @click="deleteClient(client.id)" class="text-red-400 hover:text-red-300">
                                <Trash2Icon :size="18" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="clients.data.length === 0">
                        <td colspan="6" class="p-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-500">
                                <FileText :size="48" class="opacity-20" />
                                <p>Nenhum cliente encontrado para os filtros aplicados.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center gap-2">
            <Link v-for="link in clients.links" :key="link.label" :href="link.url || '#'" v-html="link.label"
                class="px-4 py-2 rounded-lg border text-sm transition" :class="{
                    'bg-blue-600 border-blue-600 text-white font-bold': link.active,
                    'border-slate-700 hover:bg-slate-700 text-gray-400': !link.active && link.url,
                    'opacity-30 cursor-not-allowed border-slate-800': !link.url
                }" />
        </div>

    </div>
</template>
