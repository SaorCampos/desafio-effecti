<script setup lang="ts">
import { useForm, router, Head } from '@inertiajs/vue3';
import { ArrowLeft, Save, Package } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    service?: any;
}>();

const serviceData = computed(() => {
    if (!props.service) return null;
    return props.service.data ? props.service.data[0] : props.service;
});

const isEditing = computed(() => !!serviceData.value);

const form = useForm({
    name: serviceData.value?.name ?? '',
    base_value: serviceData.value?.baseValue ?? '',
    description: serviceData.value?.description ?? '',
});

const submit = () => {
    if (isEditing.value) {
        form.put(`/services/${serviceData.value.id}`, {
            onSuccess: () => router.visit('/services'),
        });
    } else {
        form.post('/services', {
            onSuccess: () => router.visit('/services'),
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Editar Serviço' : 'Novo Serviço'" />

    <div class="mx-auto max-w-3xl p-8 text-gray-100">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    {{ isEditing ? 'Editar Serviço' : 'Novo Serviço' }}
                </h1>
                <p class="text-sm text-gray-400">Configure os detalhes do serviço oferecido.</p>
            </div>
            <button type="button" @click="router.visit('/services')"
                    class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors">
                <ArrowLeft :size="18" /> Voltar
            </button>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="rounded-xl border border-slate-700 bg-slate-800 p-8 shadow-xl">
                <div class="grid grid-cols-1 gap-6">

                    <div class="flex flex-col">
                        <label class="mb-2 text-sm font-medium text-gray-400">Nome do Serviço</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                                <Package :size="18" />
                            </div>
                            <input
                                type="text"
                                v-model="form.name"
                                placeholder="Ex: Manutenção Preventiva"
                                class="w-full rounded-lg border-none bg-slate-700 py-3 pl-10 pr-4 text-white focus:ring-2 focus:ring-blue-500 transition-all"
                                :class="{ 'ring-1 ring-red-500': form.errors.name }"
                            />
                        </div>
                        <span v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</span>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-2 text-sm font-medium text-gray-400">Valor Base (R$)</label>
                        <input
                            type="number"
                            step="0.01"
                            v-model="form.base_value"
                            placeholder="0,00"
                            class="rounded-lg border-none bg-slate-700 p-3 text-white focus:ring-2 focus:ring-blue-500 transition-all"
                            :class="{ 'ring-1 ring-red-500': form.errors.base_value }"
                        />
                        <span v-if="form.errors.base_value" class="mt-1 text-xs text-red-400">{{ form.errors.base_value }}</span>
                    </div>

                    <!-- <div class="flex flex-col">
                        <label class="mb-2 text-sm font-medium text-gray-400">Descrição (Opcional)</label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            placeholder="Descreva o que este serviço inclui..."
                            class="rounded-lg border-none bg-slate-700 p-3 text-white focus:ring-2 focus:ring-blue-500 transition-all"
                        ></textarea>
                        <span v-if="form.errors.description" class="mt-1 text-xs text-red-400">{{ form.errors.description }}</span>
                    </div> -->

                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" @click="router.visit('/services')" class="px-6 py-2 text-gray-400 hover:text-white transition-colors">
                    Cancelar
                </button>
                <button type="submit" :disabled="form.processing"
                    class="flex items-center gap-2 rounded-lg bg-green-600 px-8 py-3 font-bold text-white hover:bg-green-500 disabled:opacity-50 transition-all shadow-lg shadow-green-900/20">
                    <Save :size="18" />
                    {{ isEditing ? 'Atualizar Serviço' : 'Cadastrar Serviço' }}
                </button>
            </div>
        </form>
    </div>
</template>
