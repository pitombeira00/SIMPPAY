<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Http\Requests\TransactionRequest;
use App\Services\TransactionAppService;

class TransactionController extends Controller
{
    public function createTransaction(TransactionRequest $request)
    {

        $transactionReturn = (new TransactionAppService())($request);

        return response()->json($transactionReturn, 200);
    }
}
