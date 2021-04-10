<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Transaction;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    public function createTransaction(TransactionRequest $request)
    {

        //1.verifica se os campos são validos
        //2.verifica se o pagador é o mesmo do token
        //3.verifica se o pagador é usuario ou lojista
        //4.verifica se o recebedor é válido;
        //5.verifica se o pagador tem dinheiro;
        //6.Retira dinheiro do pagador;
        //7.Envia a requisição para o mock;


        return response()->json([
            'status' => 'Success',
            'data' => NULL,
            'message' => 'Transacao executada'
        ], 200);
    }

}
