<script setup>
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import { ref, computed } from 'vue';
    import { router } from '@inertiajs/vue3';

    // Components
    import InputLabel from '@/Components/InputLabel.vue'
    import TextInput from '@/Components/TextInput.vue';

    const imageUrls = ref([]); // Armazena URLs de pré-visualização
    const imageFiles = ref([]); // Armazena os arquivos reais
    const errorMessage = ref('');
    const showModal = ref(false);

    const openModal = () => {
        showModal.value = true;
    };

    // Adiciona imagem
    const handleFileChange = (event) => {
      const files = Array.from(event.target.files);

      // Adiciona no máximo 3 imagens
      files.forEach((file) => {
        if (imageFiles.value.length < 3) {
          const url = URL.createObjectURL(file);
          imageUrls.value.push(url);
          imageFiles.value.push(file);
        }
      });

      // Reseta o input para permitir adicionar as mesmas imagens novamente, se necessário
      event.target.value = '';
    };

    // Remove imagem
    const removeImage = (index) => {
      // Remove a imagem correspondente do array
      imageUrls.value.splice(index, 1);
      imageFiles.value.splice(index, 1);
    };

    // Estrutura do formulário
    const form = useForm({
        nome: '',
        telefone: '',
        email: '',
        produto: '',
        marca: '',
        dtCompra: '',
        valor: '',
        marcaUso: '',
        descricao: '',
        altura: '',
        largura: '',
        profundidade: '',
        foto1: '',
        foto2: '',
        foto3: '',
        quantidade: '',
        outroBairro: '',
        voltagem: '',
        tipoEstado: '',
        cpf: '',
        desmontagem: '',
        
        // Flags
        bairro: '',
        novo: '',
        usado: '',
        troca: '',
        nf: '',
        sujo: '',
        mofo: '',
        cupim: '',
        trincado: '',
    });

    // Mascara CPF
    const applyCpfMask = () => {
      // Garante que `form.cpf` é uma string
      const onlyNumbers = String(form.cpf || '').replace(/\D/g, '');

      // Aplica a máscara do CPF: xxx.xxx.xxx-xx
      form.cpf = onlyNumbers
        .slice(0, 11) // Limita o CPF a no máximo 11 caracteres
        .replace(/(\d{3})(\d)/, '$1.$2') // Insere o primeiro ponto
        .replace(/(\d{3})(\d)/, '$1.$2') // Insere o segundo ponto
        .replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Insere o traço
    };

    // Mascara de telefone
    const applyPhoneMask = () => {
      // Remove tudo que não for número
      let onlyNumbers = form.telefone.replace(/\D/g, '');

      // Limita o telefone a no máximo 11 dígitos (ex: DDI + celular)
      onlyNumbers = onlyNumbers.slice(0, 11);

      // Aplica a máscara
      if (onlyNumbers.length <= 10) {
        // Formato para telefones fixos e celulares antigos: (xx) xxxx-xxxx
        form.telefone = onlyNumbers
          .replace(/(\d{2})(\d)/, '($1) $2') // DDD
          .replace(/(\d{4})(\d)/, '$1-$2'); // Número
      } else {
        // Formato para celulares atuais: (xx) 9xxxx-xxxx
        form.telefone = onlyNumbers
          .replace(/(\d{2})(\d)/, '($1) $2') // DDD
          .replace(/(\d{5})(\d)/, '$1-$2'); // Número com 9 dígitos
      }
    };

    // Função de envio do formulário
    const submit = async () => {
        try {
            const formEnv = new FormData();
                formEnv.append('nome', form.nome);
                formEnv.append('telefone', form.telefone);
                formEnv.append('email', form.email);
                formEnv.append('produto', form.produto);
                formEnv.append('marca', form.marca);
                formEnv.append('dtCompra', form.dtCompra);
                formEnv.append('valor', form.valor);
                formEnv.append('marcaUso', form.marcaUso);
                formEnv.append('descricao', form.descricao);
                formEnv.append('altura', form.altura);
                formEnv.append('largura', form.largura);
                formEnv.append('profundidade', form.profundidade);
                formEnv.append('foto1', imageFiles.value[0] ? imageFiles.value[0] : ''); // Aqui o arquivo real é anexado
                formEnv.append('foto2', imageFiles.value[1] ? imageFiles.value[1] : ''); // Aqui o arquivo real é anexado
                formEnv.append('foto3', imageFiles.value[2] ? imageFiles.value[2] : ''); // Aqui o arquivo real é anexado
                formEnv.append('quantidade', form.quantidade);
                formEnv.append('outroBairro', form.outroBairro ? form.outroBairro : '');
                formEnv.append('voltagem', form.voltagem);
                formEnv.append('tipoEstado', form.tipoEstado);
                formEnv.append('bairro', form.bairro);
                formEnv.append('novo', form.novo);
                formEnv.append('usado', form.usado);
                formEnv.append('troca', form.troca);
                formEnv.append('nf', form.nf);
                formEnv.append('sujo', form.sujo);
                formEnv.append('mofo', form.mofo);
                formEnv.append('cupim', form.cupim);
                formEnv.append('cpf', form.cpf);
                formEnv.append('trincado', form.trincado);
                formEnv.append('desmontagem', form.desmontagem);
            formEnv.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            errorMessage.value = '';

            // Enviar os dados
            await router.post(route('ficha.store'), formEnv, {
                onSuccess: () => {
                    openModal();

                    setTimeout(() => {
                        window.location.href = '/';
                    }, 15000);
                },
                onError: (errors) => {
                    errorMessage.value = 'Enviar apenas imagens ou vídeos no formato MP4.';
                    console.log('erro', errors);
                },
                headers: {
                    'Content-Type': 'multipart/form-data' // Garante o envio correto do arquivo
                }
            });

        } catch (error) {
            errorMessage.value = 'Ocorreu um erro inesperado. Tente novamente mais tarde.';
            console.log("Erro ao enviar o formulário:", error);
        }
    };

    setTimeout(() => {
        window.location.href = '/';
    }, 8 * 60 * 1000);
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50">
        <div class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:col-start-2 lg:justify-center">
                        <img src="../../../public/image/logo.png" alt="">
                    </div>
                    <!-- <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav> -->
                </header>

                <main class="mt-6">
                    <div class="espacoLogin">
                        <form @submit.prevent="submit" class="text-left formLogin items-center items-center ">
                            <div v-if="errorMessage" class="mb-4">
                                <div class="flex items-center justify-between p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"></path>
                                        </svg>
                                        <span>{{ errorMessage }}</span>
                                    </div>
                                    <button @click="errorMessage = ''" class="text-red-700 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="Nome" value="Nome:" />
                
                                <TextInput id="nome" type="text" class="mt-1 block w-full TextInput" placeholder="Ana" v-model="form.nome" required autofocus autocomplete="name"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="CPF" value="CPF:" />
                
                                <TextInput id="CPF" type="text" class="mt-1 block w-full TextInput" placeholder="xxx.xxx.xxx-xx" v-model="form.cpf" @input="applyCpfMask" required autofocus autocomplete="cpf"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="Telefone" value="telefone" />

                                <TextInput id="telefone" type="text" class="mt-1 block w-full TextInput" placeholder="(55) 99999-9999" v-model="form.telefone" @input="applyPhoneMask" required autocomplete="phone"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="Email" value="Email" />

                                <TextInput id="Email" type="email" class="mt-1 block w-full TextInput" placeholder="teste@gmail.com" v-model="form.email" required autocomplete="email"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="Produto" value="Produto" />

                                <TextInput id="Produto" type="text" class="mt-1 block w-full TextInput" placeholder="Fogão" v-model="form.produto" required autocomplete="produto" />
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="marca" value="Marca" />

                                <TextInput id="marca" type="text" class="mt-1 block w-full TextInput" placeholder="Brastemp" v-model="form.marca" required autocomplete="marca" />
                            </div>

                            <div class="mt-4 mx-auto w-2/4 sm:w-3/4">
                                <InputLabel for="quantidade" value="quantidade" />

                                <TextInput id="quantidade" type="number" class="mt-1 block w-full TextInput" placeholder="3" v-model="form.quantidade" min="1" required autocomplete="quantidade"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="DataCompra" value="Data da compra" />

                                <TextInput id="DataCompra" type="date" class="mt-1 block w-full TextInput" v-model="form.dtCompra" required autocomplete="DataCompra" />
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="Valor" value="Valor unitário" />

                                <TextInput id="valor" type="number" class="mt-1 block w-full TextInput" v-model="form.valor" min="1" placeholder="3000" required autocomplete="DataCompra" step="0.01"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="MarcaUso" value="Possui marcas de uso? Qual?" />

                                <TextInput id="MarcaUso" type="text" class="mt-1 block w-full TextInput" v-model="form.marcaUso" placeholder="Arranhado" required autocomplete="Arranhão na superficie"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="descricao" value="Descricao do item" />

                                <TextInput id="descricao" type="text" class="mt-1 block w-full TextInput" v-model="form.descricao" placeholder="Fogão 4 bocas" required autocomplete="descricao"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="altura" value="Altura do item (em Centimetros)" />

                                <TextInput id="altura" type="number" class="mt-1 block w-full TextInput" v-model="form.altura" placeholder="100" :min="1" step="0.01" required autocomplete="altura"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="largura" value="Largura do item (em Centimetros)" />

                                <TextInput id="largura" type="number" class="mt-1 block w-full TextInput" v-model="form.largura" placeholder="200" :min="1" step="0.01" required autocomplete="largura"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="profundidade" value="Profundidade do item (em Centimetros)" />

                                <TextInput id="profundidade" type="number" class="mt-1 block w-full TextInput" v-model="form.profundidade" placeholder="50" :min="1" step="0.01" required autocomplete="profundidade"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="bairro" value="bairro" />

                                <select id="preferencia" class="mt-1 block w-full TextInput" v-model="form.bairro">
                                    <option value="1">Barra da Tijuca</option>
                                    <option value="2">Recreio dos Bandeirantes</option>
                                    <option value="3">Vargem Grande</option>
                                    <option value="4">Vargem Pequena</option>
                                    <option value="5">Gardênia Azul</option>
                                    <option value="6">Cidade de Deus</option>
                                    <option value="7">Curicica</option>
                                    <option value="8">Taquara</option>
                                    <option value="9">Pechincha</option>
                                    <option value="10">Freguesia (Jacarepaguá)</option>
                                    <option value="11">Camorim</option>
                                    <option value="12">Tanque</option>
                                    <option value="13">Praça Seca</option>
                                    <option value="14">Madureira</option>
                                    <option value="16">Cascadura</option>
                                    <option value="17">Campinho</option>
                                    <option value="18">Méier</option>
                                    <option value="19">Engenho de Dentro</option>
                                    <option value="20">Vila Isabel</option>
                                    <option value="21">Tijuca</option>
                                    <option value="22">Maracanã</option>
                                    <option value="23">São Cristóvão</option>
                                    <option value="24">Centro</option>
                                    <option value="25">Flamengo</option>
                                    <option value="26">Botafogo</option>
                                    <option value="27">Copacabana</option>
                                    <option value="28">Ipanema</option>
                                    <option value="29">Leblon</option>
                                    <option value="30">Jardim Botânico</option>
                                    <option value="31">Laranjeiras</option>
                                    <option value="32">Cosme Velho</option>
                                    <option value="33">Glória</option>
                                    <option value="34">Santa Teresa</option>
                                    <option value="35">Lapa</option>
                                    <option value="36">Penha</option>
                                    <option value="37">Olaria</option>
                                    <option value="38">Ramos</option>
                                    <option value="39">Bonsucesso</option>
                                    <option value="40">Ilha do Governador</option>
                                    <option value="41">Pavuna</option>
                                    <option value="42">Anchieta</option>
                                    <option value="43">Guadalupe</option>
                                    <option value="44">Deodoro</option>
                                    <option value="45">Realengo</option>
                                    <option value="46">Bangu</option>
                                    <option value="47">Campo Grande</option>
                                    <option value="48">Santa Cruz</option>
                                    <option value="49">Sepetiba</option>
                                    <option value="50">Guaratiba</option>
                                    <option value="51">Pedra de Guaratiba</option>
                                    <option value="52">Grajaú</option>
                                    <option value="53">Engenho Novo</option>
                                    <option value="54">Rocha Miranda</option>
                                    <option value="55">Higienópolis</option>
                                </select>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="outroBarrio" value="Outro Bairro (inserir estimativa de frete)" />

                                <TextInput id="bairro" type="text" class="mt-1 block w-full TextInput" placeholder="outro bairro - valor" v-model="form.outroBairro" autocomplete="bairro"/>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="bairro" value="Voltagem" />

                                <select id="preferencia" class="mt-1 block w-full TextInput" v-model="form.voltagem">
                                    <option value="110">110</option>
                                    <option value="220">220</option>
                                    <option value="Nao aplicavel">Não aplicável</option>
                                </select>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="limpeza" value="Precisa de limpeza" />

                                <select id="limpeza" class="mt-1 block w-full TextInput" v-model="form.sujo">
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="desmontagem" value="Precisa de desmontagem" />

                                <select id="desmontagem" class="mt-1 block w-full TextInput" v-model="form.desmontagem">
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="nota" value="Possui nota fiscal?" />

                                <select id="nota mx-auto" class="mt-1 block w-full TextInput" v-model="form.nf">
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="troca" value="Aceita crédito (voucher) para uso na loja?" />

                                <select id="troca" class="mt-1 block w-full TextInput" v-model="form.troca">
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <InputLabel for="Estado" value="Tipo de reparo" />

                                <select id="Estado" class="mt-1 block w-full TextInput" v-model="form.tipoEstado">
                                    <option value="Reparo">Reparo</option>
                                    <option value="Limpeza">Limpeza</option>
                                    <option value="Estofamento">Estofamento</option>
                                    <option value="Não aplicável">Não aplicável</option>
                                </select>
                            </div>

                            <div class="mt-4 w-2/4 sm:w-3/4 mx-auto">
                                <TextInput id="file" type="file" class="mt-1 block w-2/4" @change="handleFileChange" accept="image/*" multiple/>
                                <div class="flex flex-wrap items-center justify-center gap-4 mt-4">
                                    <div v-for="(url, index) in imageUrls" :key="index" class="relative">
                                        <img :src="url" alt="Pré-visualização" class="h-32 w-32 rounded-lg object-cover"/>
                                        <button class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1" @click="removeImage(index)">X</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4  w-3/4 sm:w-3/4 mx-auto">
                                <div class="p-6 max-w-lg mx-auto">
                                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Selecione as opções:</h2>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                        <div class="flex items-center space-x-2 bg-gray-100 rounded-lg shadow-md p-3 hover:bg-indigo-50 transition">
                                            <input
                                            type="checkbox"
                                            id="checkbox-1"
                                            v-model="form.novo"
                                            value="Opção 1"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300"
                                            />
                                            <label for="checkbox-1" class="text-gray-700 cursor-pointer">Novo</label>
                                        </div>

                                        <div class="flex items-center space-x-2 bg-gray-100 rounded-lg shadow-md p-3 hover:bg-indigo-50 transition">
                                            <input
                                            type="checkbox"
                                            id="checkbox-2"
                                            v-model="form.usado"
                                            value="Opção 2"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300"
                                            />
                                            <label for="checkbox-2" class="text-gray-700 cursor-pointer">Usado</label>
                                        </div>

                                        <div class="flex items-center space-x-2 bg-gray-100 rounded-lg shadow-md p-3 hover:bg-indigo-50 transition">
                                            <input
                                            type="checkbox"
                                            id="checkbox-3"
                                            v-model="form.mofo"
                                            value="Opção 3"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300"
                                            />
                                            <label for="checkbox-3" class="text-gray-700 cursor-pointer">Possui mofo</label>
                                        </div>

                                        <div class="flex items-center space-x-2 bg-gray-100 rounded-lg shadow-md p-3 hover:bg-indigo-50 transition">
                                            <input
                                            type="checkbox"
                                            id="checkbox-4"
                                            v-model="form.cupim"
                                            value="Opção 4"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300"
                                            />
                                            <label for="checkbox-4" class="text-gray-700 cursor-pointer">Possui Cupim</label>
                                        </div>

                                        <div class="flex items-center space-x-2 bg-gray-100 rounded-lg shadow-md p-3 hover:bg-indigo-50 transition">
                                            <input
                                            type="checkbox"
                                            id="checkbox-5"
                                            v-model="form.trincado"
                                            value="Opção 5"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300"
                                            />
                                            <label for="checkbox-5" class="text-gray-700 cursor-pointer">Esta Trincado</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 block">
                                <div class="container">
                                    <button class="buttonLogin">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Todos direitos reservados <a href="https://mudeitroquei.com.br/" target="_blank"><b style="color: #96AE8A;" >Mudei & Troquei</b></a> COPYRIGHT © 2024
                </footer>
            </div>
        </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 flex items-center pl-10 sm:justify-center bg-gray-500 bg-opacity-75">
        <div class="bg-white p-6 rounded-lg shadow-lg w-3/5 sm:w-1/5">
            <h2 class="text-xl mb-4 text-green-400">Formulário enviado. Aguarde o contato da nossa equipe 💚</h2>
        </div>
    </div>
</template>

<style scoped>
    /*
        - Cores de fontes da marca
        Verde: #96AE8A
        Marrom: #955837
    */

    .espacoLogin{
        align-items: center;
        justify-content: center;
        display: grid;
    }

    .formLogin {
        width: 30rem !important;
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

    .TextInput{
        color: #000000
    }
</style>