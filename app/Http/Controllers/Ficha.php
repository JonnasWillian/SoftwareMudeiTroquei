<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\ficha AS fichaFomulario;

class Ficha extends Controller
{
    //
    public function store(Request $request)
    {

        dd($request->all());

        $path = '';
        
        if ($request->hasFile('foto1')) {
            // Armazena o arquivo na pasta 'uploads/posts'
            $path = $request->file('foto1')->store('itens', 'public');
        }

        $path2 = '';
        
        if ($request->hasFile('foto2')) {
            // Armazena o arquivo na pasta 'uploads/posts'
            $path2 = $request->file('foto2')->store('itens', 'public');
        }

        $path3 = '';
        
        if ($request->hasFile('foto3')) {
            // Armazena o arquivo na pasta 'uploads/posts'
            $path3 = $request->file('foto3')->store('itens', 'public');
        }

        $valorEstimado = $request->valor;

        if ($request->desmontagem == 'Sim') {
            $valorEstimado -= 50;
        }

        if ($request->sujo == 'Sim') {
            $valorEstimado -= 30;
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
            'bairro' => $request->bairro,
            'novo' => $request->novo,
            'usado' => $request->usado,
            'troca' => $request->troca,
            'nf' => $request->nf,
            'sujo' => $request->sujo,
            'mofo' => $request->mofo,
            'cupim' => $request->cupim,
            'cpf' => $request->cpf,
            'trincado' => $request->trincado,
            'desmontagem' => $request->desmontagem,
            'foto1' => $path ?? 0,
            'foto2' => $path2 ?? 0,
            'foto3' => $path3 ?? 0,
            'status' => 1,
        );

        // Cria um novo post com os dados fornecidos
        Post::create($inserir);
    }
}
