<script setup lang="ts">
import { useForm, router, Head } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    client?: any;
}>();

const clientData = computed(() => {
    if (!props.client) return null;
    return props.client.data ? props.client.data[0] : props.client;
});

const isEditing = computed(() => !!clientData.value);

const form = useForm({
    name: clientData.value?.name ?? '',
    document: clientData.value?.document ?? '',
    email: clientData.value?.email ?? '',
    status: clientData.value?.status ?? 'active',
});

const submit = () => {
    if (isEditing.value) {
        form.put(`/clients/${clientData.value.id}`, {
            onSuccess: () => router.visit('/clients'),
        });
    } else {
        form.post('/clients', {
            onSuccess: () => router.visit('/clients'),
        });
    }
};

// Função para permitir apenas números no modelo
const handleDocumentInput = (e: Event) => {
    const input = e.target as HTMLInputElement;
    form.document = input.value.replace(/\D/g, '');
};

// Opcional: Máscara visual para CPF/CNPJ
const maskedDocument = computed(() => {
    const val = form.document.replace(/\D/g, '');
    if (val.length <= 11) {
        return val.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "$1.$2.$3-$4");
    }
    return val.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "$1.$2.$3/$4-$5");
});

</script>

<template>

    <Head :title="isEditing ? 'Editar Cliente' : 'Novo Cliente'" />

    <div class="mx-auto max-w-4xl p-8 text-gray-100">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    {{ isEditing ? 'Editar Cliente' : 'Novo Cliente' }}
                </h1>
                <p class="text-sm text-gray-400" v-if="isEditing">
                    Editando: {{ clientData.name }}
                </p>
            </div>
            <button type="button" @click="router.visit('/clients')"
                class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors">
                <ArrowLeft :size="18" /> Voltar para a listagem
            </button>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="rounded-lg bg-slate-800 p-8 shadow-xl border border-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="flex flex-col md:col-span-2">
                        <label class="mb-2 text-sm font-medium text-gray-400">Nome Completo / Razão Social</label>
                        <input type="text" v-model="form.name" placeholder="Ex: João Silva ou Empresa LTDA"
                            class="rounded border-none bg-slate-700 p-3 text-white focus:ring-2 focus:ring-blue-500 transition-all"
                            :class="{ 'ring-1 ring-red-500': form.errors.name }" />
                        <span v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</span>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-2 text-sm font-medium text-gray-400">CPF ou CNPJ (apenas números)</label>
                        <input type="text" :value="form.document" @input="handleDocumentInput" maxlength="14"
                            placeholder="000.000.000-00"
                            class="rounded border-none bg-slate-700 p-3 text-white focus:ring-2 focus:ring-blue-500"
                            :class="{ 'ring-1 ring-red-500': form.errors.document }" />
                        <span v-if="form.errors.document" class="mt-1 text-xs text-red-400">
                            {{ form.errors.document }}
                        </span>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-2 text-sm font-medium text-gray-400">E-mail</label>
                        <input type="email" v-model="form.email" placeholder="contato@exemplo.com"
                            class="rounded border-none bg-slate-700 p-3 text-white focus:ring-2 focus:ring-blue-500 transition-all"
                            :class="{ 'ring-1 ring-red-500': form.errors.email }" />
                        <span v-if="form.errors.email" class="mt-1 text-xs text-red-400">{{ form.errors.email }}</span>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-2 text-sm font-medium text-gray-400">Status da Conta</label>
                        <select v-model="form.status"
                            class="rounded border-none bg-slate-700 p-3 text-white focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                        </select>
                        <span v-if="form.errors.status" class="mt-1 text-xs text-red-400">{{ form.errors.status
                            }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" @click="router.visit('/clients')"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors">
                    Cancelar
                </button>
                <button type="submit" :disabled="form.processing"
                    class="flex items-center gap-2 rounded bg-green-600 px-8 py-3 font-bold text-white hover:bg-green-500 disabled:opacity-50 transition-all shadow-lg">
                    <Save :size="18" />
                    {{ isEditing ? 'Atualizar Cliente' : 'Cadastrar Cliente' }}
                </button>
            </div>
        </form>
    </div>
</template>
