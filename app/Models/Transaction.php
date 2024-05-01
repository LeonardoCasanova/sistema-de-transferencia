<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'type','balance'
    ];

    /**
     * Obtém o usuário associado à transação.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
