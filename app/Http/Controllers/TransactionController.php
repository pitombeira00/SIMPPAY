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

        $newTransaction = Transaction::create([
            'value' => $request->value,
            'user_payer' =>$request->payer,
            'user_payee' => $request->payee,
            'status' => '1',
        ]);

        $newTransaction->removeValuePayer();

        $transactionValidate = new TransactionService($newTransaction);

        return response()->json($transactionValidate->sendValidate(), 200);

    }

}
