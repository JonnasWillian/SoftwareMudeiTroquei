<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Link, useForm } from '@inertiajs/vue3';
    import { ref, onMounted, watch, computed } from 'vue';
    import axios from 'axios';

    const id = JSON.parse(localStorage.getItem('id'));
    const lista = ref([]); 
    const demandaMedia = ref('');
    const demandaAlta = ref('');
    const currentIndex = ref({}); // Armazena o índice da imagem para cada item
    const statusAtual = ref('');
    const status = [];
    status[1] = "Em analise";
    status[2] = "Aprovados";
    status[3] = "Recusados";

    // Função para buscar posts
    const fetchPosts = async (id) => {
        try {
            const response = await axios.get(`/fichaView/${id}`);
            lista.value = response.data;
            console.log('lista', lista.value);

            const valor = Number(lista.value[0].valorEstimado); // Converte para número
            demandaMedia.value = (valor + valor * 0.05).toFixed(2); // Calcula e retorna formatado

            const valorAlta = Number(lista.value[0].valorEstimado); // Converte para número
            demandaAlta.value = (valorAlta + valorAlta * 0.10).toFixed(2); // Calcula e retorna formatado

            statusAtual.value = lista.value[0].status
        } catch (error) {
            console.error("Erro ao consultar os posts:", error);
        }
    };

    // Funções de navegação de imagens
    const prevImage = (id) => {
        if (!currentIndex.value[id]) currentIndex.value[id] = 0;
        currentIndex.value[id] = (currentIndex.value[id] - 1 + 2) % 3; 
    };

    const nextImage = (id) => {
        if (!currentIndex.value[id]) currentIndex.value[id] = 0;
        currentIndex.value[id] = (currentIndex.value[id] + 1) % 3;
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
        fetchPosts(id); 
    });

    watch(() => id, () => {
        fetchPosts(id); // Atualiza a consulta
    }, { deep: true });

    // Função de envio do formulário
    const submit = async () => {
        try {
            const formEnv = new FormData();
                formEnv.append('status', statusAtual);
                formEnv.append('id', id);
            formEnv.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Enviar os dados
            await router.post(route('ficha.update'), formEnv, {
                onSuccess: () => {
                        window.location.href = '/fichas';
                },
                onError: (errors) => {
                    console.log('erro', errors);
                },
                headers: {
                    'Content-Type': 'multipart/form-data' // Garante o envio correto do arquivo
                }
            });

        } catch (error) {
            console.log("Erro ao enviar o formulário:", error);
        }
    };
</script>

<template>
    <AuthenticatedLayout>
        <br>
        <div class="py-12">
            <Link href="/dashboard">
                <button class="absolute left-14 top-1/10 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full" @click="prevImage(list.id)">&#8592; Voltar</button>
            </Link>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-6">
                    <!-- Itens em formato de cards -->
                    <div class="w-full   ">
                        <div v-for="list in lista" :key="list.id" class="border border-gray-300 rounded-lg p-4">
                            <div class="flex">
                                <h2 class="text-xl font-semibold leading-tight text-gray-800">Ficha do item</h2>
                            </div>
                            <!-- Imagens -->
                            <div class="relative">
                                <!-- Controles para passar imagens -->
                                <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full" @click="prevImage(list.id)">&#8592;</button>
                                <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full" @click="nextImage(list.id)">&#8594;</button>

                                <div class="flex justify-center">
                                    <img
                                        v-if="list.foto1"
                                        :src="`/storage/${list.foto1}`"
                                        alt="Foto 1"
                                        class="rounded-lg w-1/2 h-100 object-cover transition-all ease-in-out"
                                    />
                                    <img
                                        v-if="list.foto2 && currentIndex[list.id] === 1"
                                        :src="`/storage/${list.foto2}`"
                                        alt="Foto 2"
                                        class="rounded-lg w-1/2 h-100 object-cover transition-all ease-in-out"
                                    />
                                    <!--img
                                        v-if="list.foto3 && currentIndex[list.id] === 2"
                                        :src="`/storage/${list.foto3}`"
                                        alt="Foto 3"
                                        class="rounded-lg w-1/2 h-80 object-cover transition-all ease-in-out"
                                    />-->
                                </div>
                            </div>

                            <!-- Informações do cliente -->
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Dados do Cliente</h3>
                            <ul class="text-sm text-gray-600">
                                <li><strong>Nome:</strong> {{ list.nome }}</li>
                                <li><strong>Telefone:</strong> {{ list.telefone }}</li>
                                <li><strong>Cpf:</strong> {{ list.cpf }}</li>
                                <li><strong>Email:</strong> {{ list.email }}</li>
                                <li><strong>Bairro:</strong> {{ bairros[list.bairro] }}</li>
                            </ul>

                            <!-- Informações do produto -->
                            <h3 class="text-lg font-semibold text-gray-700 mt-4 mb-2">Dados do Produto</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 text-sm text-gray-600">

                            <ul class="text-sm text-gray-600">
                                <li><strong>Produto:</strong> {{ list.produto }}</li>
                                <li><strong>Descrição:</strong> {{ list.descricao }}</li>
                                <li><strong>Marca:</strong> {{ list.marca }}</li>
                                <li><strong>Data da Compra:</strong> {{ list.dtCompra }}</li>
                                <li><strong>Bairro alternativo:</strong> {{ list.outroBairro }}</li>

                            </ul>

                            <ul>
                                <li><strong>Quantidade:</strong> {{ list.quantidade }}</li>
                                <li><strong>Valor ofertado:</strong> {{ list.valor }}</li>
                                <li><strong>Valor estimado (gastos): </strong> {{ list.valorEstimado }}</li>
                                <li><strong>Valor comercial (google): </strong> {{  }}</li>
                            </ul>

                            <ul>
                                <li class="text-green-500"><strong>Demanda Baixa:</strong> {{ list.valorEstimado }}</li>   
                                <li class="text-orange-400"><strong>Demanda Média:</strong> {{ demandaMedia }}</li>
                                <li class="text-red-600"><strong>Demanda Alta:</strong> {{ demandaAlta }}</li>
                            </ul>
                            </div>

                          <!-- Detalhes adicionais -->
                            <h3 class="text-lg font-semibold text-gray-700 mt-4 mb-2">Detalhes Adicionais</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 text-sm text-gray-600">
                                <ul>
                                    <li><strong>Voltagem:</strong> {{ list.voltagem }}</li>
                                    <li><strong>Altura:</strong> {{ list.altura }} cm</li>
                                    <li><strong>Largura:</strong> {{ list.largura }} cm</li>
                                    <li><strong>Profundidade:</strong> {{ list.profundidade }} cm</li>
                                    <li><strong>Estado:</strong> {{ list.tipoEstado }}</li>
                                    <li><strong>Desmontagem:</strong> {{ list.desmontagem }}</li>
                                    <li><strong>Marca de Uso:</strong> {{ list.marcaUso }}</li>
                                    <li><strong>Novo:</strong> {{ resposta[list.novo] }}</li>
                                </ul>

                                <ul>
                                    <li><strong>Usado:</strong> {{ resposta[list.usado] }}</li>
                                    <li><strong>Troca:</strong> {{ resposta[list.troca] }}</li>
                                    <li><strong>Possui NF:</strong> {{ list.nf }}</li>
                                    <li><strong>Está Sujo:</strong> {{ resposta[list.sujo] }}</li>
                                    <li><strong>Possui Mofo:</strong> {{ resposta[list.mofo] }}</li>
                                    <li><strong>Possui Cupim:</strong> {{ resposta[list.cupim] }}</li>
                                    <li><strong>Está Trincado:</strong> {{ resposta[list.trincado] }}</li>
                                </ul>

                                <ul>
                                    <form @submit.prevent="submit">
                                        <li>
                                            <strong>Stataus:</strong>
                                            <select id="preferencia" class="mt-1 block w-full" v-model="statusAtual">
                                                <option value="1">{{status[1]}}</option>
                                                <option value="2">{{status[2]}}</option>
                                                <option value="3">{{status[3]}}</option>
                                            </select>
                                            <div class="mt-4 block">
                                                <div class="container">
                                                    <button class="buttonLogin">Atualizar</button>
                                                </div>
                                            </div>
                                        </li>
                                    </form>
                                </ul>
                            </div>
                        </div>
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

    button {
        background-color: #fff;
        border: none;
        cursor: pointer;
        font-size: 1.25rem;
        padding: 0.5rem;
        transition: background-color 0.2s;
    }

    button:hover {
        background-color: #f0f0f0;
    }

    /* Imagens dentro do slider */
    img {
        transition: all 0.3s ease-in-out;
    }

    .container {
        text-align: center;
    }

    .buttonLogin {
        background: linear-gradient(162.8deg, #FFFFFF -20.14%, #955837 57.49%);
        box-shadow: 0px 5px 25px -10px #000000;
        border-radius: 100px;
        border: none;
        color: white;
        padding: 15px 30px;
        border-radius: 25px;
        font-size: 18px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.1s ease;
    }

    .buttonLogin:hover {
        transform: scale(1.05);
    }
</style>