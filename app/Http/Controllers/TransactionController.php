<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Realiza a transferência de dinheiro entre usuários.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transfer(Request $request)
    {
        // Inicia uma transação de banco de dados
        DB::beginTransaction();

        try {
            // Verifica se o usuário pagador é um lojista
            $payer = User::find($request->payer);
            if ($payer->type === 'lojista') {
                return response()->json(['error' => 'Lojistas não podem fazer transações'], 400);
            }

            // Validação dos dados de entrada para a transferência
            $validator = Validator::make($request->all(), [
                'value' => ['required', 'numeric', 'min:0'],
                'payer' => ['required', 'exists:users,id'],
                'payee' => ['required', 'exists:users,id', 'different:payer'],
            ]);

            // Se a validação falhar, retorne os erros
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // Verifica se o usuário pagador tem saldo suficiente para a transferência
            $payerBalance = Transaction::where('user_id', $request->payer)->sum('balance');
            if ($payerBalance < $request->value) {
                return response()->json(['error' => 'Saldo insuficiente'], 400);
            }

            // Consulta o serviço autorizador externo
            $authorization = $this->authorizeTransfer();

            // Verifica se a transferência foi autorizada
            if (!$authorization) {
                return response()->json(['error' => 'Transferência não autorizada'], 400);
            }

            // Realiza a transferência subtraindo o valor da conta do pagador e adicionando-o à conta do beneficiário
            $transaction = new Transaction();
            $transaction->user_id = $request->payer;
            $transaction->amount = -$request->value;
            $transaction->balance = $payerBalance - $request->value;
            $transaction->type = 'debit';
            $transaction->save();

            $payeeBalance = Transaction::where('user_id', $request->payee)->sum('balance');
            Transaction::create([
                'user_id' => $request->payee,
                'amount' => $request->value,
                'balance' => $payeeBalance + $request->value,
                'type' => 'credit',
            ]);

            // Confirma a transação
            DB::commit();

            // Após a confirmação da transação, envia notificação ao recebedor
            $this->sendNotification($request->payee);

            // Retorna uma resposta de sucesso
            return response()->json(['message' => 'Transferência realizada com sucesso'], 200);
        } catch (\Exception $e) {
            // Em caso de erro, faz rollback da transação
            DB::rollback();

            // Retorna uma resposta de erro
            return response()->json(['error' => 'Ocorreu um erro durante a transferência'], 500);
        }
    }

    /**
     * Consulta o serviço autorizador externo.
     *
     * @return bool
     */
    private function authorizeTransfer()
    {
        // URL do serviço mock
        $url = 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc';

        // Faz a solicitação HTTP GET
        $response = file_get_contents($url);

        // Decodifica a resposta JSON
        $data = json_decode($response, true);

        // Verifica se a transferência foi autorizada
        return isset($data['message']) && $data['message'] === 'Autorizado';
    }

    /**
     * Envia notificação ao recebedor do pagamento.
     *
     * @param int $userId
     * @return void
     */
    private function sendNotification($userId)
    {
        // URL do serviço de envio de notificação
        $url = 'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6';

        // Faz a solicitação HTTP GET
        $response = Http::get($url);

        // Verifica se a notificação foi enviada com sucesso
        if ($response['message'] === true) {
            // Notificação enviada com sucesso
            Log::info('Notificação enviada com sucesso para o usuário ' . $userId);
        } else {
            // Lidar com falha no envio da notificação
            Log::error('Falha ao enviar notificação para o usuário ' . $userId);
        }
    }
}
