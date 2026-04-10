<script setup lang="ts">
import { Link, Head } from '@inertiajs/vue3';
import { Plus, Package, Edit, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    services: {
        data: Array<{
            id: number;
            name: string;
            baseValue: number;
            description?: string;
        }>;
        links: Array<any>;
    }
}>();

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
};
</script>

<template>
    <Head title="Serviços" />

    <div class="p-8 text-gray-100 max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Serviços</h1>
                <p class="text-gray-400 mt-1">Catálogo de serviços e valores base.</p>
            </div>
            <Link href="/services/create" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition">
                <Plus :size="20" /> Novo Serviço
            </Link>
        </div>

        <div class="bg-slate-800 rounded-xl shadow-lg overflow-hidden border border-slate-700">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-700/50 text-gray-300 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Nome</th>
                        <th class="p-4 font-semibold">Valor Base</th>
                        <th class="p-4 font-semibold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <tr v-for="service in services.data" :key="service.id" class="hover:bg-slate-700/30 transition">
                        <td class="p-4 text-gray-400 font-mono text-sm">#{{ service.id }}</td>
                        <td class="p-4 font-medium">
                            <div class="flex items-center gap-3">
                                <Package :size="18" class="text-blue-400" />
                                {{ service.name }}
                            </div>
                        </td>
                        <td class="p-4 font-semibold text-green-400">
                            {{ formatCurrency(service.baseValue) }}
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 hover:bg-slate-600 rounded-lg text-gray-400 hover:text-white transition">
                                    <Edit :size="18" />
                                </button>
                                <button class="p-2 hover:bg-red-900/30 rounded-lg text-gray-500 hover:text-red-400 transition">
                                    <Trash2 :size="18" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center gap-2">
            <Link v-for="link in services.links" :key="link.label" :href="link.url || '#'" v-html="link.label"
                class="px-3 py-1 rounded border text-sm"
                :class="link.active ? 'bg-blue-600 border-blue-600' : 'border-slate-700 text-gray-400'" />
        </div>
    </div>
</template>
