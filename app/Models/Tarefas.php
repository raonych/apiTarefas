<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarefas extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'concluido',
        'dataLimite',
        'userId'
    ];
}
