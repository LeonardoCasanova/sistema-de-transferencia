<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class User extends Model
{
    /**
     * Atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome_completo', 'cpf_cnpj', 'email', 'password', 'type',
    ];

    /**
     * Atributos que devem ser ocultados em arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Define a senha do usuário.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Define o email do usuário em minúsculas.
     *
     * @param  string  $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Escopo de consulta para pesquisar por email ou CPF/CNPJ.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $emailOrCpf
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEmailOrCpf($query, $emailOrCpf)
    {
        return $query->where('email', $emailOrCpf)
                     ->orWhere('cpf_cnpj', $emailOrCpf);
    }

    /**
     * Regras de validação para criação de um novo usuário.
     *
     * @return array
     */
    public static function validationRules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf_cnpj' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'string'],
        ];
    }
}
