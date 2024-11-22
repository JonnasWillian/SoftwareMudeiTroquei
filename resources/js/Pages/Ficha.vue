<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import { ref, onMounted, watch } from 'vue';

    const id = JSON.parse(localStorage.getItem('id'));
    const lista = ref([]);

    const fetchPosts= async (id) => {
        try {
            const response = await axios.get(`/fichaView/${id}`);
            lista.value = response.data; // Atualiza os posts com os dados recebidos
            console.log('lista', lista.value)
        } catch (error) {
            console.error("Erro ao consultar os posts:", error);
        }
    };

    const resposta = [];
    resposta["0"]= 'Não';
    resposta["1"]= 'Sim';

    const bairros = [];
    bairros["1"]= 'Barra da Tijuca';
    bairros["2"]= 'Recreio dos Bandeirantes';
    bairros["3"]= 'Vargem Grande';
    bairros["4"]= 'Vargem Pequena';
    bairros["5"]= 'Gardênia Azul';
    bairros["6"]= 'Cidade de Deus';
    bairros["7"]= 'Curicica';
    bairros["8"]= 'Taquara';
    bairros["9"]= 'Pechincha';
    bairros["10"] = 'Freguesia (Jacarepaguá)';
    bairros["11"] = 'Camorim';
    bairros["12"] = 'Tanque';
    bairros["13"] = 'Praça Seca';
    bairros["14"] = 'Madureira';
    bairros["16"] = 'Cascadura';
    bairros["17"] = 'Campinho';
    bairros["18"] = 'Méier';
    bairros["19"] = 'Engenho de Dentro';
    bairros["20"] = 'Vila Isabel';
    bairros["21"] = 'Tijuca';
    bairros["22"] = 'Maracanã';
    bairros["23"] = 'São Cristóvão';
    bairros["24"] = 'Centro';
    bairros["25"] = 'Flamengo';
    bairros["26"] = 'Botafogo';
    bairros["27"] = 'Copacabana';
    bairros["28"] = 'Ipanema';
    bairros["29"] = 'Leblon';
    bairros["30"] = 'Jardim Botânico';
    bairros["31"] = 'Laranjeiras';
    bairros["32"] = 'Cosme Velho';
    bairros["33"] = 'Glória';
    bairros["34"] = 'Santa Teresa';
    bairros["35"] = 'Lapa';
    bairros["36"] = 'Penha';
    bairros["37"] = 'Olaria';
    bairros["38"] = 'Ramos';
    bairros["39"] = 'Bonsucesso';
    bairros["40"] = 'Ilha do Governador';
    bairros["41"] = 'Pavuna';
    bairros["42"] = 'Anchieta';
    bairros["43"] = 'Guadalupe';
    bairros["44"] = 'Deodoro';
    bairros["45"] = 'Realengo';
    bairros["46"] = 'Bangu';
    bairros["47"] = 'Campo Grande';
    bairros["48"] = 'Santa Cruz';
    bairros["49"] = 'Sepetiba';
    bairros["50"] = 'Guaratiba';
    bairros["51"] = 'Pedra de Guaratiba';
    bairros["52"] = 'Grajaú';
    bairros["53"] = 'Engenho Novo';
    bairros["54"] = 'Rocha Miranda';
    bairros["55"] = 'Higienópolis';

    onMounted(() => {
        fetchPosts(id); // Realiza a consulta inicial ao carregar a página
    });

    watch(id, () => {
        fetchPosts(id) // Atualiza a consulta
    }, { deep: true });
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Ficha do item</h2>
            <br>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="text-gray-900">
                        <h2 class="text-xl font-semibold leading-tight text-gray-800 p-5">Dados do cliente</h2>
                        <table class="table-auto w-full">
                            <thead>
                                <tr class="bg-gray-200 ">
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>CPF</th>
                                    <th>Bairro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="list in lista" :key="list.id">
                                    <td>{{list.nome}}</td>
                                    <td>{{list.telefone}}</td>
                                    <td>{{list.email}}</td>
                                    <td>{{list.cpf}}</td>
                                    <td>{{bairros[list.bairro]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-10">
                    </div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 p-5">Dados do produto</h2>
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-200 ">
                                <th>Nome</th>
                                <th>Descricao</th>
                                <th>Marca</th>
                                <th>Data da compra</th>
                                <th>Bairro alternativo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="list in lista" :key="list.id">
                                <td>{{list.produto}}</td>
                                <td>{{list.descricao}}</td>
                                <td>{{list.marca}}</td>
                                <td>{{list.dtCompra}}</td>
                                <td>{{list.outroBairro}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-200 ">
                                <th>Quantidade</th>
                                <th>Valor ofertado</th>
                                <th>Valor estimado (gastos)</th>
                                <th>Valor comercial (google)</th>
                                <th>Marca de uso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="list in lista" :key="list.id">
                                <td>{{list.quantidade}}</td>
                                <td>{{list.valor}}</td>
                                <td>{{list.valorEstimado}}</td>
                                <td>{{}}</td>
                                <td>{{list.marcaUso}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-200 ">
                                <th>Voltagem</th>
                                <th>Altura (cm)</th>
                                <th>Largura (cm)</th>
                                <th>Profundidade (cm)</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="list in lista" :key="list.id">
                                <td>{{list.voltagem}}</td>
                                <td>{{list.altura}}</td>
                                <td>{{list.largura}}</td>
                                <td>{{list.profundidade}}</td>
                                <td>{{list.tipoEstado}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-200 ">
                                <th>Desmontagem</th>
                                <th>Marca de uso</th>
                                <th>Novo</th>
                                <th>Usado</th>
                                <th>Troca (Voucher na loja)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="list in lista" :key="list.id">
                                <td>{{list.desmontagem}}</td>
                                <td>{{list.marcaUso}}</td>
                                <td>{{resposta[list.novo]}}</td>
                                <td>{{resposta[list.usado]}}</td>
                                <td>{{resposta[list.troca]}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-200 ">
                                <th>Possui NF</th>
                                <th>Está sujo</th>
                                <th>Possui mofo</th>
                                <th>Possui cupim</th>
                                <th>Está trincado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="list in lista" :key="list.id">
                                <td>{{list.nf}}</td>
                                <td>{{resposta[list.sujo]}}</td>
                                <td>{{resposta[list.mofo]}}</td>
                                <td>{{resposta[list.cupim]}}</td>
                                <td>{{resposta[list.trincado]}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="p-10">
                    </div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 p-5">Imagens</h2>

                    <div class="border border-gray-300 rounded-lg p-4 w-full max-w-md flex" v-for="list in lista" :key="list.id">
                        <img v-if="list.foto1" class="rounded-lg mb-4 w-full p-10" :src="`/storage/${list.foto1}`">
                        <img v-if="list.foto2" class="rounded-lg mb-4 w-full p-10" :src="`/storage/${list.foto2}`">
                        <img v-if="list.foto3" class="rounded-lg mb-4 w-full p-10" :src="`/storage/${list.foto3}`">
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

    th, td {
        padding: 10px  60px;
    }

    

</style>