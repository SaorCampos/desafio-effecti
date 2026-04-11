<script setup lang="ts">
import { router, Link, Head } from '@inertiajs/vue3';
import {
    Plus, FileText, Search, Trash2,
    Users, Briefcase, Package, X, CreditCard, Mail
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps<{
    clients: any,
    filters: any
}>();

const params = ref({
    name: props.filters?.name || '',
    document: props.filters?.document || '',
    status: props.filters?.status || '',
    email: props.filters?.email || '',
});

const runSearch = debounce(() => {
    const query = Object.fromEntries(
        Object.entries(params.value).filter(([_, v]) => v !== '' && v !== null)
    );

    router.get('/clients', query, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch(() => params.value, () => {
    runSearch();
}, { deep: true });

const clearFilters = () => {
    params.value = { name: '', document: '', status: '', email: '' };
};

const deleteClient = (id: number) => {
    if (confirm('Deseja excluir este cliente?')) {
        router.delete(`/clients/${id}`);
    }
};

const handleDocumentInput = (e: Event) => {
    const input = e.target as HTMLInputElement;
    params.value.document = input.value.replace(/\D/g, '');
};

</script>

<template>

    <Head title="Clientes" />

    <div class="mx-auto max-w-6xl p-8 text-gray-400">

        <div class="mb-8 flex gap-4 border-b border-slate-700 pb-6">
            <div class="flex items-center gap-2 px-4 py-2 border-b-2 border-blue-500 text-blue-400 font-bold">
                <Users :size="20" /> Clientes
            </div>
            <Link href="/contracts" class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-white transition">
                <Briefcase :size="20" /> Contratos
            </Link>
            <Link href="/services" class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-white transition">
                <Package :size="20" /> Serviços
            </Link>
        </div>

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-500">Gestão de Clientes</h1>
                <p class="mt-1 text-gray-400">Gerencie sua base de clientes e contatos.</p>
            </div>
            <Link href="/clients/create"
                class="flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white transition hover:bg-blue-500 shadow-lg shadow-blue-900/20">
                <Plus :size="20" /> Novo Cliente
            </Link>
        </div>

        <div
            class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-800 p-4 rounded-xl border border-slate-700 items-end">
            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-300 uppercase">Nome / Razão Social</label>
                <div class="relative">
                    <Search class="absolute left-3 top-1 text-slate-500" :size="18" />
                    <input v-model="params.name" type="text" placeholder="Buscar..."
                        class="w-full bg-slate-900 border-none rounded-lg pl-10 text-sm focus:ring-2 focus:ring-blue-500 text-white" />
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-300 uppercase">Documento (CPF/CNPJ)</label>
                <div class="relative">
                    <CreditCard class="absolute left-3 top-1 text-slate-500" :size="18" />
                    <input v-model="params.document" type="text" placeholder="000.000..." maxlength="14" @input="handleDocumentInput"
                        class="w-full bg-slate-900 border-none rounded-lg pl-10 text-sm focus:ring-2 focus:ring-blue-500 text-white" />
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-300 uppercase">Email</label>
                <div class="relative">
                    <Mail class="absolute left-3 top-1 text-slate-500" :size="18" />
                    <input v-model="params.email" type="email" placeholder="exemplo@dominio.com"
                        class="w-full bg-slate-900 border-none rounded-lg pl-10 text-sm focus:ring-2 focus:ring-blue-500 text-white" />
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-300 uppercase">Status</label>
                <select v-model="params.status"
                    class="w-full bg-slate-900 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 text-white py-2">
                    <option value="">Todos os Status</option>
                    <option value="active">Ativo</option>
                    <option value="inactive">Inativo</option>
                </select>
            </div>

            <button @click="clearFilters"
                class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:text-white transition bg-slate-700 rounded-lg hover:bg-slate-600">
                <X :size="16" /> Limpar Filtros
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-slate-700/50 text-xs uppercase tracking-wider text-gray-300">
                        <th class="p-4 font-semibold">Nome</th>
                        <th class="p-4 font-semibold">Documento</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold text-right pr-8">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700 text-slate-300">
                    <tr v-for="client in clients.data" :key="client.id" class="transition hover:bg-slate-700/30">
                        <td class="p-4 font-medium">{{ client.name }}</td>
                        <td class="p-4 font-mono text-sm text-gray-400">{{ client.document }}</td>
                        <td class="p-4">{{ client.email }}</td>
                        <td class="p-4">
                            <span :class="{
                                'bg-green-900/40 text-green-400 border-green-800/50': client.status === 'active',
                                'bg-gray-700/50 text-gray-400 border-gray-600/50': client.status !== 'active'
                            }" class="px-2.5 py-1 rounded-full text-xs font-medium border">
                                {{ client.status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="p-4 text-right pr-6">
                            <div class="flex justify-end gap-2">
                                <Link :href="`/clients/${client.id}/edit`"
                                    class="rounded-lg p-2 text-blue-400 hover:bg-blue-400/10 transition">
                                    <FileText :size="18" />
                                </Link>
                                <button @click="deleteClient(client.id)"
                                    class="rounded-lg p-2 text-red-400 hover:bg-red-400/10 transition">
                                    <Trash2 :size="18" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="clients.data.length === 0">
                        <td colspan="5" class="p-12 text-center text-gray-500">
                            <Users :size="48" class="mx-auto mb-4 opacity-20" />
                            <p>Nenhum cliente encontrado para "{{ params.document || params.name }}".</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center gap-2">
            <Link v-for="link in clients.links" :key="link.label" :href="link.url || '#'" :data="params"
                v-html="link.label" class="rounded border px-4 py-2 text-sm transition-colors" :class="link.active
                    ? 'bg-blue-600 border-blue-600 text-white'
                    : 'border-slate-700 bg-slate-800 text-gray-400 hover:bg-slate-700'" />
        </div>
    </div>
</template>
