<?php

namespace App\Http\Controllers;

use App\Jobs\TransactionAuthorization;
use App\Jobs\TransactionMessage;
use App\Services\TransactionService;
use App\User;
use Illuminate\Support\Facades\Http;
use App\Transaction;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    public function createTransaction(TransactionRequest $request)
    {
        $arrayReturn = [];

        //1.verifica se os campos são validos
        //2.verifica se o pagador é o mesmo do token
        //3.verifica se o pagador é usuario ou lojista
        //4.verifica se o recebedor é válido;
        //5.verifica se o pagador tem dinheiro;
        //6.Envia a requisição para o mock;
        //7.Retira o dinheiro do pagador;
        //8.Adiciona o dinheiro no recebedor;
        //9.Cria a fila de envio de mensagem pelo mock;
        /*
         * 1. -> pendente;
         * 2. -> finalizado;
         * 3. -> não autorizado;
         *
         */


        $newTransaction = Transaction::create([
            'value' => $request->value,
            'user_payer' =>$request->payer,
            'user_payee' => $request->payee,
            'status' => '1',
        ]);

        $newTransaction->sendValuePayer();

        $transactionValidate = new TransactionService($newTransaction);

        return response()->json($transactionValidate->sendValidate(), 200);

    }

}
