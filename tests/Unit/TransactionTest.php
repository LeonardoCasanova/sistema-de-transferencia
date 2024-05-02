<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use DatabaseTransactions;

    public function testTransferSuccess()
    {
        // Simular dados de usuário e transação
        $payer = new User();
        $payer->id = 1; // Defina o ID conforme necessário
        $payer->name = 'Leonardo Casanova';
        $payer->email = 'leonardo@gmail.com';
        $payer->cpf_cnpj ='42076478808';
        $payer->password = bcrypt('123456678');
        $payer->type = 'cliente'; // ou 'lojista', dependendo do tipo
        $payer->save();

        $payee = new User();
        $payee->id = 2; // Defina o ID conforme necessário
        $payee->name = 'Joao Carlos';
        $payee->email = 'joao@hotmail.com';
        $payer->cpf_cnpj ='42076478428';
        $payee->password = bcrypt('123456678');
        $payee->type = 'lojista'; // ou 'lojista', dependendo do tipo
        $payee->save();

        $amount = 100;

        // Realizar a transferência
        $response = $this->postJson('/api/transfer', [
            'value' => $amount,
            'payer' => $payer->id,
            'payee' => $payee->id,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200);

        // Verificar se os saldos foram atualizados corretamente
        $this->assertEquals($payer->balance - $amount, $payer->fresh()->balance);
        $this->assertEquals($payee->balance + $amount, $payee->fresh()->balance);
    }

    public function testInsufficientBalance()
    {
        // Simular dados de usuário e transação
        $payer = new User();
        $payer->id = 1; // Defina o ID conforme necessário
        $payer->name = 'Joao Carlos';
        $payer->email = 'joao@hotmail.com';
        $payer->cpf_cnpj ='42076478812';
        $payer->password = bcrypt('12345667');
        $payer->type = 'cliente'; // ou 'lojista', dependendo do tipo
        $payer->balance = 0; // Defina o saldo conforme necessário
        $payer->save();

        $payee = new User();
        $payee->id = 2; // Defina o ID conforme necessário
        $payee->name = 'Pedro';
        $payee->email = 'pedro@gmail.com';
        $payer->cpf_cnpj ='42076478813';
        $payee->password = bcrypt('12345678');
        $payee->type = 'cliente'; // ou 'lojista', dependendo do tipo
        $payee->balance = 0; // Defina o saldo conforme necessário
        $payee->save();

        $amount = 1000; // Um valor que excede o saldo do pagador

        // Tentar realizar a transferência
        $response = $this->postJson('/api/transfer', [
            'value' => $amount,
            'payer' => $payer->id,
            'payee' => $payee->id,
        ]);

        // Verificar se a resposta indica saldo insuficiente
        $response->assertStatus(400)->assertJson(['error' => 'Saldo insuficiente']);
    }
}
