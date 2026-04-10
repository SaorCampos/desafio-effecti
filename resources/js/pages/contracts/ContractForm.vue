<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { Plus, Trash2, ArrowLeft } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    clients: any;
    services: any;
    contract?: any;
}>();

const contractData = computed(() => {
    if (!props.contract) return null;
    return props.contract.data ? props.contract.data[0] : props.contract;
});

const isEditing = computed(() => !!contractData.value);
const serviceList = computed(() => props.services?.data || props.services || []);

const formatDate = (dateString: string | null) => {
    if (!dateString) return '';
    return dateString.split('T')[0];
};

const form = useForm({
    client_id: contractData.value?.clientId ?? '',
    start_date: contractData.value ? formatDate(contractData.value.startDate) : new Date().toISOString().split('T')[0],
    end_date: contractData.value ? formatDate(contractData.value.endDate) : new Date().toISOString().split('T')[0],
    status: contractData.value?.status ?? 'active',
    items: contractData.value?.items?.map((item: any) => ({
        service_id: item.serviceId,
        quantity: item.quantity,
        unitValue: item.unitValue
    })) ?? [{ service_id: '', quantity: 1, unitValue: 0 }],
});

const addItem = () => {
    form.items.push({ service_id: '', quantity: 1, unitValue: 0 });
};

const removeItem = (index: number) => {
    form.items.splice(index, 1);
};

const updatePrice = (index: number) => {
    const selectedId = form.items[index].service_id;
    if (!selectedId) return;
    const selected = serviceList.value.find(
        (s: any) => String(s.id) === String(selectedId)
    );
    if (selected) {
        form.items[index].unitValue = selected.baseValue;
    }
};

const submit = () => {
    if (isEditing.value) {
        form.put(`/contracts/${contractData.value.id}`, {
            onSuccess: () => router.visit('/contracts'),
        });
    } else {
        form.post('/contracts', {
            onSuccess: () => router.visit('/contracts'),
        });
    }
};
</script>

<template>
    <div class="mx-auto max-w-5xl p-8 text-gray-500">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">
                {{ isEditing ? 'Editar Contrato #' + props.contract.data[0].id : 'Novo Contrato' }}
            </h1>
            <button type="button" @click="router.visit('/contracts')" class="text-sm text-gray-400 hover:text-black">
                <ArrowLeft class="inline-block mr-1" :size="18" /> Voltar
            </button>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-2 gap-6 rounded-lg bg-slate-800 p-6 shadow">
                <div class="flex flex-col">
                    <label class="mb-2 text-sm text-gray-400">Cliente</label>
                    <select v-model="form.client_id" class="rounded border-none bg-slate-700 p-2">
                        <option value="">Selecione um cliente</option>
                        <option v-for="client in clients?.data || clients || []" :key="client.id" :value="client.id">
                            {{ client.name }}
                        </option>
                    </select>
                    <div v-if="form.errors.client_id" class="mt-1 text-xs text-red-400">
                        {{ form.errors.client_id }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label class="mb-2 text-sm text-gray-400">Data de Início</label>
                        <input type="date" v-model="form.start_date" class="rounded border-none bg-slate-700 p-2" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 text-sm text-gray-400">Data de Término</label>
                        <input type="date" v-model="form.end_date" class="rounded border-none bg-slate-700 p-2" />
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-slate-800 p-6 shadow">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Serviços do Contrato</h2>
                    <button type="button" @click="addItem"
                        class="flex items-center gap-2 rounded bg-blue-600 px-3 py-1 text-sm hover:bg-blue-500">
                        <Plus :size="16" /> Adicionar Item
                    </button>
                </div>

                <div v-for="(item, index) in form.items" :key="index"
                    class="mb-4 grid grid-cols-12 items-end gap-4 border-b border-slate-700 pb-4">
                    <div class="col-span-5 flex flex-col">
                        <label class="mb-1 text-xs text-gray-400">Serviço</label>
                        <select v-model="item.service_id" @change="updatePrice(index)"
                            class="rounded border-none bg-slate-700 p-2">
                            <option value="" disabled>Selecione...</option>
                            <option v-for="service in serviceList" :key="service.id" :value="service.id">
                                {{ service.name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-span-2 flex flex-col">
                        <label class="mb-1 text-xs text-gray-400">Qtd</label>
                        <input type="number" v-model="item.quantity" min="1"
                            class="rounded border-none bg-slate-700 p-2" />
                    </div>

                    <div class="col-span-3 flex flex-col">
                        <label class="mb-1 text-xs text-gray-400">Valor Unit. (R$)</label>
                        <input type="number" v-model="item.unitValue" readonly
                            class="rounded border-none bg-slate-700/50 p-2 text-gray-400" />
                    </div>

                    <div class="col-span-2 flex justify-end">
                        <button type="button" @click="removeItem(index)" class="p-2 text-red-400 hover:text-red-300"
                            v-if="form.items.length > 1">
                            <Trash2 :size="20" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" @click="router.visit('/contracts')" class="px-6 py-2">
                    Cancelar
                </button>
                <button type="submit" :disabled="form.processing"
                    class="rounded bg-green-600 px-8 py-2 font-bold hover:bg-green-500 disabled:opacity-50">
                    {{ isEditing ? 'Atualizar Contrato' : 'Salvar Contrato' }}
                </button>
            </div>
        </form>
    </div>
</template>
