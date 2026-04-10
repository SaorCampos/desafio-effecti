<script setup lang="ts">
import { router, Link, Head } from '@inertiajs/vue3';
import { Plus, Eye, FileText, Search, Trash2Icon } from 'lucide-vue-next';
import { reactive, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps<{
    contracts: {
        data: Array<{
            id: number;
            clientName: string;
            totalValue: number;
            startDate: string;
            endDate: string;
            status: string;
        }>;
        links: Array<any>;
    };
    filters: {
        search: string;
    };
}>();

const state = reactive({
    search: props.filters.search || ''
});

// Filtro reativo para busca de contratos
const handleSearch = debounce(() => {
    router.get('/contracts', { search: state.search }, {
        preserveState: true,
        replace: true
    });
}, 500);

watch(() => state.search, handleSearch);

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
};

const formatDate = (dateStr: string) => {
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('pt-BR').format(date).replace(/\//g, '-');
};

const deleteContract = (id: number) => {
    if (confirm('Tem certeza que deseja excluir este contrato? Esta ação não pode ser desfeita.')) {
        router.delete(`/contracts/${id}`, {
            onSuccess: () => {
                console.log('Contrato removido');
            },
        });
    }
};
</script>

<template>

    <Head title="Contratos" />

    <div class="p-8 text-gray-500 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Contratos</h1>
                <p class="text-gray-400 mt-1">Gerencie os acordos e serviços ativos.</p>
            </div>
            <Link href="/contracts/create"
                class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition">
                <Plus :size="20" /> Novo Contrato
            </Link>
        </div>

        <div class="bg-slate-800 p-4 rounded-xl shadow-lg mb-6 flex items-center gap-4">
            <div class="relative flex-1">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" :size="18" />
                <input v-model="state.search" type="text" placeholder="Buscar por cliente ou ID..."
                    class="w-full bg-slate-900 border-none rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl shadow-lg overflow-hidden border border-slate-700">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-700/50 text-gray-300 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Cliente</th>
                        <th class="p-4 font-semibold text-center">Início</th>
                        <th class="p-4 font-semibold text-center">Fim</th>
                        <th class="p-4 font-semibold text-right">Valor Total</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                        <th class="p-4 font-semibold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <tr v-for="contract in contracts.data" :key="contract.id" class="hover:bg-slate-700/30 transition">
                        <td class="p-4 text-gray-400 font-mono text-sm">#{{ contract.id }}</td>
                        <td class="p-4 font-medium">{{ contract.clientName }}</td>
                        <td class="p-4 text-center text-gray-400">{{ formatDate(contract.startDate) }}</td>
                        <td class="p-4 text-center text-gray-400">{{ formatDate(contract.endDate) }}</td>
                        <td class="p-4 text-right font-semibold text-blue-400">
                            {{ formatCurrency(contract.totalValue) }}
                        </td>
                        <td class="p-4 text-center">
                            <span :class="{
                                'bg-green-900/50 text-green-400 border-green-800': contract.status === 'active',
                                'bg-gray-700 text-gray-400 border-gray-600': contract.status !== 'active'
                            }" class="px-2.5 py-1 rounded-full text-xs font-medium border">
                                {{ contract.status === 'active' ? 'Ativo' : 'Encerrado' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 hover:bg-slate-600 rounded-lg text-gray-400 hover:text-white transition">
                                    <Link :href="`/contracts/${contract.id}/edit`"
                                        class="flex items-center ">
                                        <FileText :size="18" />
                                    </Link>
                                </button>
                                <button @click="deleteContract(contract.id)"
                                    class="p-2 hover:bg-slate-600 rounded-lg text-gray-400 hover:text-red-600 transition">
                                    <Trash2Icon :size="18" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="contracts.data.length === 0">
                        <td colspan="6" class="p-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-500">
                                <FileText :size="48" class="opacity-20" />
                                <p>Nenhum contrato encontrado para os filtros aplicados.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center gap-2">
            <Link v-for="link in contracts.links" :key="link.label" :href="link.url || '#'" v-html="link.label"
                class="px-4 py-2 rounded-lg border text-sm transition" :class="{
                    'bg-blue-600 border-blue-600 text-white font-bold': link.active,
                    'border-slate-700 hover:bg-slate-700 text-gray-400': !link.active && link.url,
                    'opacity-30 cursor-not-allowed border-slate-800': !link.url
                }" />
        </div>
    </div>
</template>
