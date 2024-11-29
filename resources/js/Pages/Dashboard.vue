<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { ref, onMounted, watch } from 'vue';

    const tipo = ref(1);
    const lista = ref([]);

    const status = [];
    status[1] = "Em analise";
    status[2] = "Aprovados";
    status[3] = "Recusados";

    const fetchPosts= async (tipo) => {
        try {
            const response = await axios.get(`/ficha/${tipo.value}`);
            lista.value = response.data; // Atualiza os posts com os dados recebidos
        } catch (error) {
            console.error("Erro ao consultar os posts:", error);
        }
    };

    onMounted(() => {
        fetchPosts(tipo); // Realiza a consulta inicial ao carregar a página
    });

    const verFicha = (id) => {
        localStorage.setItem('id', JSON.stringify(id));
        window.location.href = '/fichas';
    };

    watch(tipo, () => {
        fetchPosts(tipo) // Atualiza a consulta
    }, { deep: true });

    console.log('lista', lista)
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
            <br>

            <div class="mt-4">
                <InputLabel for="preferencia" value="Preferência" />

                <select id="preferencia" class="mt-1 block w-full p-2 border rounded-lg text-sm sm:w-1/3" v-model="tipo">
                    <option value="1">Em análise</option>
                    <option value="2">Aprovados</option>
                    <option value="3">Recusados</option>
                </select>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="text-gray-900">
                        <!-- Tabela responsiva -->
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="px-2 py-1 text-left">#</th>
                                        <th class="px-2 py-1 text-left">Item</th>
                                        <th class="px-2 py-1 text-left">Descrição</th>
                                        <th class="px-2 py-1 text-left">Valor ofertado</th>
                                        <th class="px-2 py-1 text-left">Valor estimado (gastos)</th>
                                        <th class="px-2 py-1 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr @click="verFicha(list.id)" class="cursor-pointer hover:bg-gray-100" v-for="list in lista" :key="list.id">
                                        <td class="px-2 py-1">
                                            <i class="material-icons">folder_open</i>
                                        </td>
                                        <td class="px-2 py-1">{{ list.produto }}</td>
                                        <td class="px-2 py-1">{{ list.descricao }}</td>
                                        <td class="px-2 py-1">{{ list.valor }}</td>
                                        <td class="px-2 py-1">{{ list.valorEstimado }}</td>
                                        <td class="px-2 py-1">{{ status[list.status] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<style scoped>

    th, td {
        padding: 10px  70px;
    }

    tr:hover {
        background-color: rgba(150, 174, 138, 0.2); 
    }

</style>