<script setup lang="ts">
import { router, Link, Head } from '@inertiajs/vue3';
import {
    Plus, FileText, Search, Trash2,
    Users, Briefcase, Package, X, Calendar
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
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
            items: Array<{
                serviceName: string;
            }>;
        }>;
        links: Array<any>;
    };
    filters: any;
}>();

const params = ref({
    contract_id: props.filters?.contract_id || '',
    client_name: props.filters?.client_name || '',
    service_name: props.filters?.service_name || '',
    start_date: props.filters?.start_date || '',
    end_date: props.filters?.end_date || '',
});

const runSearch = debounce(() => {
    const query = Object.fromEntries(
        Object.entries(params.value).filter(([_, v]) => v !== '' && v !== null)
    );

    router.get('/contracts', query, {
        preserveState: true,
        replace: true,
    });
}, 400);

watch(() => params.value, () => {
    runSearch();
}, { deep: true });

const clearFilters = () => {
    params.value = {
        contract_id: '',
        client_name: '',
        service_name: '',
        start_date: '',
        end_date: ''
    };
};

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
};

const formatDate = (dateStr: string) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('pt-BR');
};

const deleteContract = (id: number) => {
    if (confirm('Tem certeza que deseja excluir este contrato?')) {
        router.delete(`/contracts/${id}`);
    }
};
</script>

<template>

    <Head title="Contratos" />

    <div class="mx-auto max-w-7xl p-8 text-gray-400">

        <div class="mb-8 flex gap-4 border-b border-slate-700 pb-6">
            <Link href="/clients" class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-white transition">
                <Users :size="20" /> Clientes
            </Link>
            <div class="flex items-center gap-2 px-4 py-2 border-b-2 border-blue-500 text-blue-400 font-bold">
                <Briefcase :size="20" /> Contratos
            </div>
            <Link href="/services" class="flex items-center gap-2 px-4 py-2 text-gray-400 hover:text-white transition">
                <Package :size="20" /> Serviços
            </Link>
        </div>

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-500">Contratos</h1>
                <p class="mt-1 text-gray-400">Gerencie os acordos e serviços ativos.</p>
            </div>
            <Link href="/contracts/create"
                class="flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white transition hover:bg-blue-500 shadow-lg shadow-blue-900/20">
                <Plus :size="20" /> Novo Contrato
            </Link>
        </div>

        <div class="mb-6 bg-slate-800 p-5 rounded-xl border border-slate-700 shadow-inner">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end">

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-300 uppercase">ID Contrato</label>
                    <input v-model="params.contract_id" type="number" placeholder="#000"
                        class="w-full bg-slate-900 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 text-white" />
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-300 uppercase">Cliente</label>
                    <div class="relative">
                        <Search class="absolute left-3 top-1 text-slate-500" :size="16" />
                        <input v-model="params.client_name" type="text" placeholder="Nome do cliente..."
                            class="w-full bg-slate-900 border-none rounded-lg pl-9 text-sm focus:ring-2 focus:ring-blue-500 text-white" />
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-300 uppercase">Serviço</label>
                    <div class="relative">
                        <Search class="absolute left-3 top-1 text-slate-500" :size="16" />
                        <input v-model="params.service_name" type="text" placeholder="Nome do serviço..."
                            class="w-full bg-slate-900 border-none rounded-lg pl-9 text-sm focus:ring-2 focus:ring-blue-500 text-white" />
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-300 uppercase">Data Início</label>
                    <div class="relative">
                        <Calendar class="absolute left-3 top-1 text-slate-500" :size="16" />
                        <input v-model="params.start_date" type="date"
                            class="w-full bg-slate-900 border-none rounded-lg pl-9 text-sm focus:ring-2 focus:ring-blue-500 text-white" />
                    </div>
                </div>

                <button @click="clearFilters"
                    class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm text-gray-300 hover:text-white transition bg-slate-700 rounded-lg hover:bg-slate-600">
                    <X :size="16" /> Limpar
                </button>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-slate-700/50 text-xs uppercase tracking-wider text-gray-300">
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Cliente</th>
                        <th class="p-4 font-semibold text-center">Vigência</th>
                        <th class="p-4 font-semibold text-right">Valor Total</th>
                        <th class="p-4 font-semibold text-center">Serviços</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                        <th class="p-4 font-semibold text-right pr-8">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700 text-slate-300">
                    <tr v-for="contract in contracts.data" :key="contract.id" class="transition hover:bg-slate-700/30">
                        <td class="p-4 font-mono text-sm text-gray-500">#{{ contract.id }}</td>
                        <td class="p-4">
                            <div class="font-medium text-slate-200">{{ contract.clientName }}</div>
                        </td>
                        <td class="p-4 text-center">
                            <div class="text-sm">
                                <span class="text-gray-400">{{ formatDate(contract.startDate) }}</span>
                                <span class="mx-2 text-slate-600">→</span>
                                <span class="text-gray-400">{{ formatDate(contract.endDate) }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-right font-semibold text-blue-400">
                            {{ formatCurrency(contract.totalValue) }}
                        </td>
                        <td class="p-4 text-center">
                            <span :title="contract.items?.map(i => i.serviceName).join(', ') || 'Nenhum serviço'"
                                class="cursor-help inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-slate-700/50 text-slate-300 border border-slate-600">
                                {{ contract.items?.length || 0 }} Serviços
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <span :class="{
                                'bg-green-900/40 text-green-400 border-green-800/50': contract.status === 'active',
                                'bg-red-900/40 text-red-400 border-red-800/50': contract.status !== 'active'
                            }" class="px-2.5 py-1 rounded-full text-xs font-medium border">
                                {{ contract.status === 'active' ? 'Ativo' : 'Encerrado' }}
                            </span>
                        </td>
                        <td class="p-4 text-right pr-6">
                            <div class="flex justify-end gap-2">
                                <Link :href="`/contracts/${contract.id}/edit`"
                                    class="rounded-lg p-2 text-blue-400 hover:bg-blue-400/10 transition">
                                    <FileText :size="18" />
                                </Link>
                                <button @click="deleteContract(contract.id)"
                                    class="rounded-lg p-2 text-red-400 hover:bg-red-400/10 transition">
                                    <Trash2 :size="18" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="contracts.data.length === 0">
                        <td colspan="7" class="p-12 text-center text-gray-500">
                            <Briefcase :size="48" class="mx-auto mb-4 opacity-20" />
                            <p>Nenhum contrato encontrado para os critérios de busca.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center gap-2">
            <Link v-for="link in contracts.links" :key="link.label" :href="link.url || '#'" :data="params"
                v-html="link.label" class="rounded border px-4 py-2 text-sm transition-colors" :class="link.active
                    ? 'bg-blue-600 border-blue-600 text-white'
                    : 'border-slate-700 bg-slate-800 text-gray-400 hover:bg-slate-700'" />
        </div>
    </div>
</template>
