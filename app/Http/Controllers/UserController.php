<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Registra um novo usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUser(Request $request)
    {
        // Validação dos dados de entrada para usuário
        $validator = Validator::make($request->all(), User::validationRules());

        // Se a validação falhar, retorne os erros
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Criação do usuário
        $user = User::create([
            'nome_completo' => $request->input('name'),
            'cpf_cnpj' => $request->input('cpf_cnpj'),
            'email' => $request->input('email'),
            'password' => $request->input('password'), // Já criptografado pelo método setPasswordAttribute no modelo User
            'type' => $request->input('type'),
        ]);

        // Retorna uma resposta de sucesso com o usuário criado
        return response()->json(['user' => $user], 201);
    }
}
