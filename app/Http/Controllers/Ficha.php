<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

// Models
use App\Models\ficha AS FichaFomulario;

class Ficha extends Controller
{
    public function store(Request $request)
    {
        $path = '';
        if ($request->hasFile('foto1')) {
            $path = base64_encode(file_get_contents($request->file('foto1')));
        }

        $path2 = '';
        if ($request->hasFile('foto2')) {
            $path2 =  base64_encode(file_get_contents($request->file('foto2')));
        }

        $path3 = '';
        if ($request->hasFile('foto3')) {
            $path3 = base64_encode(file_get_contents($request->file('foto3')));
        }

        $valorEstimado = (float) $request->valor;

        if ($request->desmontagem == 'Sim') {
            $valorEstimado -= (float)  50.00;
        }

        if ($request->sujo == 'Sim') {
            $valorEstimado -= (float)  30.00;
        }

        if ($request->bairro != null) {
           $freteBairro = [
            "1" => '42.5',
            "2" => '65.0',
            "3" => '60.0',
            "4" => '55.0',
            "5" => '37.5',
            "6" => '37.5',
            "7" => '42.5',
            "8" => '42.5',
            "9" => '50.0',
            "10" => '37.5',
            "11" => '55.0',
            "12" => '50.0',
            "13" => '60.0',
            "14" => '65.0',
            "16" => '65.0',
            "17" => '60.0',
            "18" => '80.0',
            "19" => '75.0',
            "20" => '85.0',
            "21" => '90.0',
            "22" => '90.0',
            "23" => '95.0',
            "24" => '105.0',
            "25" => '100.0',
            "26" => '95.0',
            "27" => '105.0',
            "28" => '110.0',
            "29" => '112.5',
            "30" => '105.0',
            "31" => '100.0',
            "32" => '100.0',
            "33" => '105.0',
            "34" => '100.0',
            "35" => '105.0',
            "36" => '95.0',
            "37" => '100.0',
            "38" => '100.0',
            "39" => '100.0',
            "40" => '120.0',
            "41" => '105.0',
            "42" => '100.0',
            "43" => '90.0',
            "44" => '65.0',
            "45" => '85.0',
            "46" => '90.0',
            "47" => '105.0',
            "48" => '160.0',
            "49" => '180.0',
            "50" => '140.0',
            "51" => '150.0',
            "52" => '90.0',
            "53" => '85.0',
            "54" => '85.0',
            "55" => '95.0',
           ];

           $valorEstimado -= (float)  $freteBairro[$request->bairro];
        }

        if ($request->outroBairro) {
            $valorFreteNovo = explode('-', $request->outroBairro);
            $valorNovoFrete = trim($parts[1]);

            $valorEstimado -= (float) $valorNovoFrete;
        }

        $imageData = $path ? $path : ($path2 ? $path2 : $path3);
        
        $apiResponseCustomSearch = $this->searchImageWithPrices($imageData);

        $prices = [];
        $liks = [];
        $fotos = [];

        dd($apiResponseCustomSearch);
        // Exibir resultados ou processar conforme necessário
        if (!isset($apiResponseCustomSearch['items'])) {
            $apiResponseCustomSearch = $this->searchImageWithPrices($imageData);
        }

        foreach ($apiResponseCustomSearch['items']as $item) {
            if (isset($item['snippet'])) {
                // Regex para identificar valores como R$ 300,00 ou R$ 300
                preg_match('/R\$\s?([\d.,]+)/', $item['snippet'], $matches);

                if (isset($matches[1])) {
                    // Converte para um formato numérico (removendo pontos e vírgulas)
                    $price = floatval(str_replace(',', '.', str_replace('.', '', $matches[1])));
                    $prices[] = $price;
                }
            }
            if (isset($item['pagemap'])) {
                if (isset($item['pagemap']['cse_image'])) {
                    if (strpos($item['pagemap']['cse_image'][0]['src'], 'logo') == false) {
                        $liks[] = $item['link'];
                        $fotos[] = $item['pagemap']['cse_image'][0]['src'];
                    }
                }
            }
        }

        if ($prices) {
            $prices = implode(' / ', $prices);
        }

        if ($liks) {
            $liks = implode(' spaceItem ', $liks);
        }

        if ($fotos) {
            $fotos = implode(' spaceItem ', $fotos);
        }

        $inserir = array(
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'produto' => $request->produto,
            'marca' => $request->marca,
            'dtCompra' => $request->dtCompra,
            'valor' => (float) $request->valor,
            'valorEstimado' => $valorEstimado,
            'marcaUso' => $request->marcaUso,
            'descricao' => $request->descricao,
            'altura' => $request->altura,
            'largura' => $request->largura,
            'profundidade' => $request->profundidade,
            'quantidade' => $request->quantidade,
            'outroBairro' => $request->outroBairro,
            'voltagem' => $request->voltagem,
            'tipoEstado' => $request->tipoEstado,
            'bairro' => $request->bairro ?: '',
            'novo' => $request->novo ? 1 : 0,
            'usado' => $request->usado ? 1 : 0,
            'troca' => $request->troca ? 1 : 0,
            'nf' => $request->nf,
            'sujo' => $request->sujo == 'Sim' ? 1 : 0,
            'mofo' => $request->mofo ? 1 : 0,
            'cupim' => $request->cupim ? 1 : 0,
            'cpf' => $request->cpf,
            'trincado' => $request->trincado ? 1 : 0,
            'desmontagem' => $request->desmontagem == 'Sim' ? 1 : 0,
            'foto1' => $path ?? 0,
            'foto2' => $path2 ?? 0,
            'foto3' => $path3 ?? 0,
            'status' => 1,
            'urgente' => $prices ?: $request->valor * 1.9,
            'linksProduto' => $liks,
            'fotosProduto' => $fotos,
        );

        // Cria uma nova ficha com os dados fornecidos
        FichaFomulario::create($inserir);
        dd($apiResponseCustomSearch);
    }

    // Pesquisa pela serpapi com googleLens
    function searchImageWithPrices($imageData)
    {
        // Faz o upload da imagem para ImgBB
        $imgbbApiUrl = 'https://api.imgbb.com/1/upload';
        $imgbbApiKey = env('IMGBB_API_KEY'); // Chave da API ImgBB

        $uploadResponse = Http::asForm()->post($imgbbApiUrl, [
            'key' => $imgbbApiKey,
            'image' => $imageData,
        ]);

        if (!$uploadResponse->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload da imagem.',
                'error' => $uploadResponse->json(),
            ], 500);
        }

        // Obtém a URL pública da imagem
        $imageUrl = $uploadResponse->json()['data']['url'];

        // Faz a requisição para a SerpApi
        $serpApiUrl = 'https://serpapi.com/search';
        $serpApiKey = env('SERPAPI_KEY');

        $response = Http::get($serpApiUrl, [
            'engine' => 'google_reverse_image',
            'image_url' => $imageUrl,
            'api_key' => $serpApiKey,
        ]);

        if ($response->successful()) {
            $results = $response->json();

            // Extrai informações úteis (ex.: preços, imagens, links)
            $items = collect($results['visual_matches'] ?? [])->map(function ($item) {
                return [
                    'title' => $item['title'] ?? null,
                    'price' => $item['price'] ?? null,
                    'link' => $item['link'] ?? null,
                    'image' => $item['thumbnail'] ?? null,
                ];
            });

            return response()->json([
                'success' => true,
                'items' => $items,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao buscar resultados na SerpApi.',
            'error' => $response->json(),
        ], 500);
    }

    // Pesquisa pela API google vision + API google custom search
    public function pesquisarItem($imageData)
    {
        $apiKey = env('APP_KEY_GOOGLE');
        $url = "https://vision.googleapis.com/v1/images:annotate?key=$apiKey";

        // Expandindo os tipos de detecção
        $payload = [
            'requests' => [
                [
                    'image' => ['content' => $imageData],
                    'features' => [
                        ['type' => 'WEB_DETECTION', 'maxResults' => 100000],
                        ['type' => 'LABEL_DETECTION', 'maxResults' => 15],
                        ['type' => 'OBJECT_LOCALIZATION', 'maxResults' => 15],
                        ['type' => 'TEXT_DETECTION'],
                    ],
                ],
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        curl_close($ch);
        
        $apiResponse = json_decode($response, true);
        $descriptions = [];
        
        if (isset($apiResponse['responses'][0]['webDetection']['webEntities'])) {
            foreach ($apiResponse['responses'][0]['webDetection']['webEntities'] as $entity) {
                if (isset($entity['description']) && strlen($entity['description']) > 2) {
                    $descriptions[] = $entity['description'];
                }
            }
        }

        if (isset($apiResponse['responses'][0]['labelAnnotations'])) {
            foreach ($apiResponse['responses'][0]['labelAnnotations'] as $label) {
                if ($label['score'] > 0.7) { // Apenas labels com alta confiança
                    $descriptions[] = $label['description'];
                }
            }
        }

        if (isset($apiResponse['responses'][0]['localizedObjectAnnotations'])) {
            foreach ($apiResponse['responses'][0]['localizedObjectAnnotations'] as $object) {
                if ($object['score'] > 0.7) {
                    $descriptions[] = $object['name'];
                }
            }
        }

        if (isset($apiResponse['responses'][0]['textAnnotations'][0]['description'])) {
            $texto = $apiResponse['responses'][0]['textAnnotations'][0]['description'];
            $palavrasRelevantes = array_filter(
                explode(' ', $texto),
                function($palavra) {
                    return strlen($palavra) > 3; // Filtra palavras muito curtas
                }
            );
            $descriptions = array_merge($descriptions, $palavrasRelevantes);
        }

        $descriptions = array_unique($descriptions);
        $stopWords = ['com', 'para', 'que', 'the', 'and', 'or']; // Expandir conforme necessário
        $descriptions = array_diff($descriptions, $stopWords);

        $descriptions[] = 'preço';
        $descriptions[] = 'valor';
        $descriptions[] = 'venda';

        $cx = env('CX');
        $searchTerms = implode(' ', array_slice($descriptions, 0, 10)); // Limitando a 10 termos mais relevantes
        $urlCustomSearch = "https://www.googleapis.com/customsearch/v1?key=$apiKey&cx=$cx&q=" . 
            urlencode($searchTerms) . 
            "&sort=date" . // Ordenar por data para resultados mais recentes
            "&num=10"; // Limitando a 10 resultados mais relevantes
        
        $chCustomSearch = curl_init();
        curl_setopt($chCustomSearch, CURLOPT_URL, $urlCustomSearch);
        curl_setopt($chCustomSearch, CURLOPT_RETURNTRANSFER, true);
        
        $responseCustomSearch = curl_exec($chCustomSearch);
        curl_close($chCustomSearch);
        
        $apiResponseCustomSearch = json_decode($responseCustomSearch, true);
        
        return $apiResponseCustomSearch;
    }

    public function list($statusId)
    {
        // Consulta os posts filtrando pelo campo 'nicho'
        $ficha = FichaFomulario::where('status', $statusId)
            ->orderBy('updated_at', 'desc') // Ordena pelos mais recentes
            ->get();

        return response()->json($ficha);
    }

    public function view($idFicha)
    {
        // Consulta os posts filtrando pelo campo 'nicho'
        $ficha = FichaFomulario::where('id', $idFicha)->get();
        return response()->json($ficha);
    }

    public function update (Request $request)
    {
        $ficha = FichaFomulario::findOrFail($request->id);

        // Atribui os novos valores aos atributos
        $ficha->status = $request->status;
    
        // Salva no banco de dados
        $ficha->save();
    }
}
