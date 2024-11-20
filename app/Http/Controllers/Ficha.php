<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\ficha AS FichaFomulario;

class Ficha extends Controller
{
    //
    public function store(Request $request)
    {
        $path = '';
        if ($request->hasFile('foto1')) {
            $path = $request->file('foto1')->store('itens', 'public');
        }

        $path2 = '';
        if ($request->hasFile('foto2')) {
            $path2 = $request->file('foto2')->store('itens', 'public');
        }

        $path3 = '';
        if ($request->hasFile('foto3')) {
            $path3 = $request->file('foto3')->store('itens', 'public');
        }

        $valorEstimado = $request->valor;

        if ($request->desmontagem == 'Sim') {
            $valorEstimado -= 50;
        }

        if ($request->sujo == 'Sim') {
            $valorEstimado -= 30;
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

           $valorEstimado -= $freteBairro[$request->bairro];
        }

        if ($request->outroBairro) {
            $valorFreteNovo = explode('-', $request->outroBairro);
            $valorNovoFrete = trim($parts[1]);

            $valorEstimado -= $valorNovoFrete;
        }

        $inserir = array(
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'produto' => $request->produto,
            'marca' => $request->marca,
            'dtCompra' => $request->dtCompra,
            'valor' => $request->valor,
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
            'sujo' => $request->sujo ? 1 : 0,
            'mofo' => $request->mofo ? 1 : 0,
            'cupim' => $request->cupim ? 1 : 0,
            'cpf' => $request->cpf,
            'trincado' => $request->trincado ? 1 : 0,
            'desmontagem' => $request->desmontagem,
            'foto1' => $path ?? 0,
            'foto2' => $path2 ?? 0,
            'foto3' => $path3 ?? 0,
            'status' => 1,
            'urgente' => 0,
        );

        // Cria uma nova ficha com os dados fornecidos
        FichaFomulario::create($inserir);
    }

    public function view(Request $request)
    {}
}
