<script setup lang="ts">
import { Link, Head, router } from '@inertiajs/vue3';
import {
    Plus, Package, Trash2, Search, FileText,
    Users, Briefcase, X
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps<{
    services: {
        data: Array<{
            id: number;
            name: string;
            baseValue: number;
            description?: string;
        }>;
        links: Array<any>;
    },
    filters: any;
}>();

const params = ref({
    name: props.filters?.name || '',
    min_base_value: props.filters?.min_base_value || '',
    max_base_value: props.filters?.max_base_value || '',
});

const runSearch = debounce(() => {
    const query = Object.fromEntries(
        Object.entries(params.value).filter(([_, v]) => v !== '' && v !== null)
    );
    router.get('/services', query, {
        preserveState: true,
        replace: true,
    });
}, 300);
watch(() => params.value, () => {
    runSearch();
}, { deep: true });

const clearFilters = () => {
    params.value = { name: '', min_base_value: '', max_base_value: '' };
};

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
};

const deleteService = (id: number) => {
    if (confirm('Tem certeza que deseja excluir este serviço?')) {
        router.delete(`/services/${id}`);
    }
};
</script>

<template>

    <Head title="Serviços" />

    <div class="mx-auto max-w-6xl p-8 text-gray-400">

        <div class="mb-8 flex gap-4 border-b border-slate-700 pb-6">
            <Link href="/clients"
                class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-gray-500 transition">
                <Users :size="20" /> Clientes
            </Link>
            <Link href="/contracts"
                class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-gray-500 transition">
                <Briefcase :size="20" /> Contratos
            </Link>
            <div class="flex items-center gap-2 px-4 py-2 border-b-2 border-blue-500 text-blue-400 font-bold">
                <Package :size="20" /> Serviços
            </div>
        </div>

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-500">Serviços</h1>
                <p class="mt-1 text-gray-400">Catálogo de serviços e valores base.</p>
            </div>
            <Link href="/services/create"
                class="flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white transition hover:bg-blue-500 shadow-lg shadow-blue-900/20">
                <Plus :size="20" /> Novo Serviço
            </Link>
        </div>

        <div
            class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-800 p-4 rounded-xl border border-slate-700 items-end">
            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-300 uppercase">Nome do Serviço</label>
                <div class="relative">
                    <Search class="absolute left-3 top-1 text-slate-400" :size="18" />
                    <input v-model="params.name" type="text" placeholder="Buscar..."
                        class="w-full bg-slate-900 border-none rounded-lg pl-10 text-sm focus:ring-2 focus:ring-blue-500 "/>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-300 uppercase">Preço Mínimo</label>
                <input v-model="params.min_base_value" type="number" placeholder="R$ 0,00"
                    class="w-full bg-slate-900 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-300 uppercase">Preço Máximo</label>
                <input v-model="params.max_base_value" type="number" placeholder="R$ 9999,00"
                    class="w-full bg-slate-900 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500" />
            </div>

            <button @click="clearFilters"
                class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:text-white transition bg-slate-700 rounded-lg">
                <X :size="16" /> Limpar Filtros
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-slate-700/50 text-xs uppercase tracking-wider text-gray-300">
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Nome</th>
                        <th class="p-4 font-semibold">Valor Base</th>
                        <th class="p-4 font-semibold text-right pr-8">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <tr v-for="service in services.data" :key="service.id" class="transition hover:bg-slate-700/30">
                        <td class="p-4 font-mono text-sm text-gray-500">#{{ service.id }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3 font-medium text-slate-200">
                                <div class="rounded-lg bg-slate-700 p-2 text-blue-400">
                                    <Package :size="18" />
                                </div>
                                {{ service.name }}
                            </div>
                        </td>
                        <td class="p-4 font-semibold text-green-400">
                            {{ formatCurrency(service.baseValue) }}
                        </td>
                        <td class="p-4 text-right pr-6">
                            <div class="flex justify-end gap-2">
                                <Link :href="`/services/${service.id}/edit`"
                                    class="rounded-lg p-2 text-blue-400 hover:bg-blue-400/10 transition">
                                    <FileText :size="18" />
                                </Link>
                                <button @click="deleteService(service.id)"
                                    class="rounded-lg p-2 text-red-400 hover:bg-red-400/10 transition">
                                    <Trash2 :size="18" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="services.data.length === 0" class="p-12 text-center">
                <Package :size="48" class="mx-auto mb-4 text-slate-600" />
                <p class="text-gray-400">Nenhum serviço encontrado com esses filtros.</p>
            </div>
        </div>

        <div class="mt-6 flex justify-center gap-2">
            <Link v-for="link in services.links" :key="link.label" :href="link.url || '#'" :data="params"
                v-html="link.label" class="rounded border px-4 py-2 text-sm transition-colors" :class="link.active
                    ? 'bg-blue-600 border-blue-600 text-white'
                    : 'border-slate-700 bg-slate-800 text-gray-400 hover:bg-slate-700'" />
        </div>
    </div>
</template>
