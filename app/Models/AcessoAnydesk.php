<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcessoAnydesk extends Model
{
    protected $table = 'acessos_anydesk';
    
    protected $fillable = [
        'nome_cliente', 
        'codigo_anydesk', 
        'cidade_orgao', 
        'observacoes'
    ];
}