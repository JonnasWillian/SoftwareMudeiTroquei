<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ficha extends Model
{
    //
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome',
        'telefone',
        'email',
        'produto',
        'marca',
        'dtCompra',
        'valor',
        'marcaUso',
        'descricao',
        'altura',
        'largura',
        'profundidade',
        'foto1',
        'foto2',
        'foto3',
        'quantidade',
        'outroBairro',
        'voltagem',
        'bairro',
        'tipoEstado',
        'novo',
        'usado',
        'troca',
        'nf',
        'sujo',
        'mofo',
        'cupim',
        'trincado',
        'tipoEstado',
        'desmontagem',
        'valorEstimado',
        'status',
    ];
}
